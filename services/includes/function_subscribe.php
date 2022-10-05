<?php
function subscribe(PDO $pdo, $data) {
	$return_array = [
		'rows' => 0,
		'guid' => null
	];

	if($data['method'] == 'get' || $data['method'] == 'post') {
		// Create GUID
		mt_srand((double)microtime() * 1000000);
		$characters = strtolower(md5(uniqid(rand(), true)));
		$guid = substr($characters, 0, 8) . '-' . substr($characters, 8, 4) . '-' . substr($characters, 12, 4) . '-' . substr($characters, 16, 4) . '-' . substr($characters, 20, 12);


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