<?php
function create_order(PDO $pdo, $data) {
	$query = 'INSERT INTO `orders` (`area`, `item`) VALUES (:area, :item)';
	
	$stmt = $pdo->prepare($query);
	$stmt->bindValue(':area', $data['area']);
	$stmt->bindValue(':item', $data['item']);
	$stmt->execute();

	// Enable to send POST to all addresses who have subscribed with POST method.
	// Do not enable until refactored with curl_multi_init to prevent long execution time in case of many subscribers.
	{
		$allow_post = false;

		if($allow_post) {
			$query2 = 'SELECT `order_subscribers`.`address` FROM `order_subscribers` WHERE `order_subscribers`.`method` = "post"';

			$stmt2 = $pdo->prepare($query2);
			$stmt2->execute();
		
			$result = $stmt2->fetchAll(PDO::FETCH_ASSOC);
		
			foreach($result as $value) {
				$headers = [
					'Content-Type: application/json',
					'Accept: application/json'
				];
				
				$parameters = [
					'area' => $data['area'],
					'item' => $data['item']
				];
				
				$parameters_json = json_encode($parameters);
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $value['address']);
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $parameters_json);
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				
				curl_exec($ch);
			}
		}
	}

	return $stmt->rowcount();
}
?>