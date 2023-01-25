<?php
include("functions.php");
$userName="kck716";
$password="JwQmWg#T2H7zt8rM";
$dblinklog = db_connect("docsystem");
//$sid=generateRandomString();
$data="username=$userName&password=$password";
$ch=curl_init('Website');
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
$uploadDate=date("Y-m-d H:i:s");
$cinfo=json_decode($result,true);
$sid=$cinfo[2];
$action=$cinfo[1];

$sqlLog="INSERT INTO `system_access_log`(`session_id`, `action`) 
	VALUES ('$sid','$action time: $execution_time')";
$dblinklog->query($sqlLog) or
	die("Something went wrong with $sql<br>".$dblinklog->error);
?>