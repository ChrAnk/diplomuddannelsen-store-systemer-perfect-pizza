<?php
function subscribe(PDO $pdo, $data) {
	$return_array = [
		'rows' => 0,
		'guid' => null
	];

	if($data['method'] == 'get' || $data['method'] == 'post') {
		$guid = generate_guid();

		$query = 'INSERT INTO `order_subscribers` (`guid`, `method`, `address`) VALUES (:guid, :method, :address)';
		
		$stmt = $pdo->prepare($query);
		$stmt->bindValue(':guid', $guid);
		$stmt->bindValue(':method', $data['method']);
		$stmt->bindValue(':address', $data['address']);
		$stmt->execute();
	
		$return_array['rows'] = $stmt->rowcount();
		$return_array['guid'] = $guid;

		return $return_array;
	}
	else {
		return 0;
	}
}
?>