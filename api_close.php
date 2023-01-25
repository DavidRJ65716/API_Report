<?php
$data="sid=$sid&uid=$userName";
$ch=curl_init('Webiste');
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	'content-type: application/x-www-form-urlencoded',
	'content-length: ' . strlen($data))
);
$time_start = microtime(true);
$result = curl_exec($ch);
$time_end = microtime(true);
$execution_time = ($time_end - $time_start)/60;
curl_close($ch);

$cinfo=json_decode($result,true);
$action=$cinfo[1];
$sqlLog="INSERT INTO `system_access_log`(`session_id`, `action`) 
	VALUES ('$sid','$action - Time: $execution_time')";
	$dblinklog->query($sqlLog) or
		die("Something went wrong with $sql<br>".$dblinklog->error);
?>
