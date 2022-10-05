<?php
error_reporting(1);

header('Content-Type: application/json; charset=utf-8');

require_once('config/db_settings.php');
require_once('includes/function_subscribe.php');
require_once('includes/function_unsubscribe.php');
require_once('includes/function_create_order.php');
require_once('includes/function_get_orders.php');

$dsn = 'mysql:host=' . $host . ';dbname=' . $database . ';charset=' . $charset;
$pdo = new PDO($dsn, $username, $password);

$data = json_decode($_POST['data'], true);
$action = $data['action'];

$return_array = [
	'action' => $action,
	'rows' => 0,
	'data' => null
];

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
?>