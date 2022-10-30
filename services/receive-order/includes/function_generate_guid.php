<?php
function generate_guid() {
	mt_srand((double)microtime() * 1000000);
	$characters = strtolower(md5(uniqid(rand(), true)));
	$guid = substr($characters, 0, 8) . '-' . substr($characters, 8, 4) . '-' . substr($characters, 12, 4) . '-' . substr($characters, 16, 4) . '-' . substr($characters, 20, 12);
	
	return $guid;
}
?>