<?php
function unsubscribe(PDO $pdo, $data) {
	$query = 'DELETE FROM `order_subscribers` WHERE `order_subscribers`.`guid` = :guid';
	
	$stmt = $pdo->prepare($query);

	$stmt->bindValue(':guid', $data['guid']);

	$stmt->execute();

	return $stmt->rowcount();
}
?>