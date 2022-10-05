<?php
function get_orders(PDO $pdo, $data) {
	$result_array = [
		'rows' => 0,
		'orders' => []
	];

	// Get the ID for the GUID to verify that the user is a subscriber.
	{
		$query_user = 'SELECT `order_subscribers`.`id` FROM `order_subscribers` WHERE `order_subscribers`.`guid` = :guid';
		
		$stmt_user = $pdo->prepare($query_user);
		$stmt_user->bindValue(':guid', $data['guid']);
		$stmt_user->execute();

		$user_count = $stmt_user->rowcount();
	}

	if($user_count) {		
		{
			$query_orders = 'SELECT `orders`.`area`, `orders`.`item`, `orders`.`timestamp` FROM `orders`';

			if($data['area'] || $data['from_time']) {
				$query_orders .= ' WHERE ';
				
				if($data['area']) {
					$query_orders .= '`orders`.`area` = :area';
				}
				if($data['from_time']) {
					if($data['area']) {
						$query_orders .= ' AND ';
					}
					
					$query_orders .= '`orders`.`timestamp` > :from_time';
				}
			}
		}
	
		$stmt_orders = $pdo->prepare($query_orders);

		{
			if($data['area']) {
				$stmt_orders->bindValue(':area', $data['area']);
			}
			if($data['from_time']) {
				$stmt_orders->bindValue(':from_time', $data['from_time']);
			}
		}

		$stmt_orders->execute();

		$result = $stmt_orders->fetchAll(PDO::FETCH_ASSOC);

		$result_array['rows'] = $stmt_orders->rowcount();
		$result_array['orders'] = $result;
	}

	return $result_array;
}
?>