<?php
error_reporting(1);


// TEST CODE
{
	// /*
	// Get test parameter
	if($_GET['is_testing'] == 1) {
		$is_testing = true;
	}
	
	if($is_testing) {
		// Enable block to activate test mode.
		assert_options(ASSERT_ACTIVE, true);
		assert_options(ASSERT_BAIL, true);
		assert_options(ASSERT_WARNING, false);
		assert_options(ASSERT_CALLBACK, 'assert_output');

		// Third parameter is always null, but still required.
		function assert_output(string $file, int $line, string $code = null, string $desc = null) {
			printf('<table border="1">');
			printf('<thead>');
			printf('<tr><th colspan="2" bgcolor="ff0000"><font color="ffffff">Assertion failed</th></tr>');
			printf('</thead>');
			printf('<tbody>');
			printf('<tr><td>File</td><td>%s</td></tr>', $file);
			printf('<tr><td>Line</td><td>%s</td></tr>', $line);
			printf('<tr><td>Description</td><td>%s</td></tr>', $desc);
			printf('</tbody>');
			printf('</table>');
		}
	}
	// */
}


header('Content-Type: application/json; charset=utf-8');

require_once('config/db_settings.php');
require_once('includes/function_subscribe.php');
require_once('includes/function_unsubscribe.php');
require_once('includes/function_create_order.php');
require_once('includes/function_get_orders.php');
require_once('includes/function_generate_guid.php');

$dsn = 'mysql:host=' . $host . ';dbname=' . $database . ';charset=' . $charset;
$pdo = new PDO($dsn, $username, $password);

$data = json_decode($_POST['data'], true);
$action = $data['action'];

$return_array = [
	'action' => $action,
	'rows' => 0,
	'data' => null
];


// TEST CODE
{
	// /*
	if($is_testing) {
		function create_random_string(
			$min_length = 0,
			$max_length = 40
		) {
			$characters = '0123456789abcdefghijklmnopqrstuvwxyzæøåABCDEFGHIJKLMNOPQRSTUVWXYZÆØÅ';
			$character_count = strlen($characters) - 1;
			
			$random_string = '';
			
			for($i = $min_length; $i < $max_length; $i++) {
				$random_string .= $characters[rand(0, $character_count)];
			}
			
			return $random_string;
		}
		
		function create_random_action($action) {
			return '{"action": "' . $action . '", "' . create_random_string() . '": "' . create_random_string() . '"}';
		}
		
		function run_malformed_test(PDO $pdo, $action, $test_count = 100) {
			$row_count = 0;
			
			$output = '';
			
			$output .= '   Attempting malformed ' . $action . '()... ';
			
			for($i = 0; $i < $test_count; $i++) {
				$data = json_decode(create_random_action($action), true);
				$result = subscribe($pdo, $data);
				$row_count += abs($result['rows']);
			}
			
			$output .= $row_count . ' of ' . $test_count . ' malformed queries succeeded. ';
			
			if(!$row_count) {
				$output .= 'Passed!';
			}
			else {
				$output .= 'Failed!';
			}
			
			$output .= "\n";
			
			return $output;
		}
		
		
		// Test for malformed data
		echo 'Testing malformed data' . "\n";
		echo run_malformed_test($pdo, 'nonsense', 100);
		echo run_malformed_test($pdo, 'subscribe', 100);
		echo run_malformed_test($pdo, 'unsubscribe', 100);
		echo run_malformed_test($pdo, 'create_order', 100);
		echo run_malformed_test($pdo, 'get_orders', 100);
		
		
		// subscribe()
		echo 'Testing subscribe()' . "\n";
		
		$data = json_decode(
			'{"action": "subscribe", "method": "get"}',
			true
		);
		$result = subscribe($pdo, $data);

		echo '   Verifying subscription count... ';
		assert($result['rows'] == 1, 'Incorrect row count:' . $result['rows'] . ', expected 1.');
		echo 'Passed!' . "\n";

		echo '   Verifying GUID length... ';
		assert(strlen($result['guid']) == 36, 'Incorrect GUID length:' . strlen($result['guid']) . ', expected 36.');
		echo 'Passed!' . "\n";


		// unsubscribe()
		echo 'Testing unsubscribe()' . "\n";
		
		$data = json_decode(
			'{"action": "unsubscribe", "guid": "' . $result['guid'] . '"}',
			true
		);
		$result = unsubscribe($pdo, $data);

		echo '   Verifying unsubscription count... ';
		assert($result['rows'] == 1, 'Incorrect row count:' . $result['rows'] . ', expected 1.');
		echo 'Passed!' . "\n";


		// create_order()
		echo 'Testing create_order()' . "\n";
		
		$data = json_decode(
			'{"action": "create_order", "area": 1, "item": 1}',
			true
		);
		$result = create_order($pdo, $data);

		echo '   Verifying create order count... ';
		assert($result['rows'] == 1, 'Incorrect row count:' . $result['rows'] . ', expected 1.');
		echo 'Passed!' . "\n";


		// get_orders()
		echo 'Testing get_orders()' . "\n";
		
		$data = json_decode(
			'{
				"0": {
					"data": {"action": "get_orders", "guid": "aa3ba1a7-088c-d053-0421-1e2643dc7f25", "area": 2, "from_time": "2022-10-04 14:00:00"},
					"expected": 2
				},
				"1": {
					"data": {"action": "get_orders", "guid": "notaguid"},
					"expected": 0
				},
				"2": {
					"data": {"action": "get_orders", "guid": "aa3ba1a7-088c-d053-0421-1e2643dc7f25", "area": 4},
					"expected": 1
				},
				"3": {
					"data": {"action": "get_orders", "guid": "aa3ba1a7-088c-d053-0421-1e2643dc7f25", "area": 4, "from_time": "2023-01-01 00:00:01"},
					"expected": 0
				}
			}',
			true
		);
		$success_counter = 0;
		
		foreach($data as $value) {
			$result = get_orders($pdo, $value['data']);

			echo '   Received ' . $result['rows'] . ' rows, expected ' . $value['expected'] . ' rows. ';

			if($result['rows'] == $value['expected']) {
				echo 'Passed!' . "\n";
				$success_counter++;
			}
			else {
				echo 'Failed!' . "\n";
			}
		}
		
		if($success_counter == count($data)) {
			echo '   Passed all ' . $success_counter . ' queries.' . "\n";
		}
	}
	// */
}


// PRODUCTION CODE
{
	// /*
	if(!$is_testing) {
		switch($action) {
			case 'subscribe':
				$result = subscribe($pdo, $data);

				$result_array['rows'] = $result['rows'];
				$result_array['data'] = $result['guid'];
				
				break;

			case 'unsubscribe':
				$result_array['rows'] = unsubscribe($pdo, $data);

				break;

			case 'create_order':
				$result_array['rows'] = create_order($pdo, $data);

				break;

			case 'get_orders':
				$result = get_orders($pdo, $data);

				$result_array['rows'] = $result['rows'];
				$result_array['data'] = $result['orders'];

				break;

			default:
				$result_array['rows'] = 0;
				$result_array['data'] = 'Action not recognized';
		}

		echo json_encode($result_array, JSON_PRETTY_PRINT);
	}
	// */
}
?>