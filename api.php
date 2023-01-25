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
$sqlLog="INSERT INTO `file_access_log`(`time_stamp`, `employ_id`, `file_id`, `session_id`, `action`) 
			VALUES ('$uploadDate','System_Api','No_files', '$sid','Action: Session_Start time: $execution_time')";
		$dblinklog->query($sqlLog) or
			die("Something went wrong with $sql<br>".$dblink->error);
if ($cinfo[0]=="Status: OK" && $cinfo[1]=="MSG: Session Created"){
	
	$data="sid=$sid&uid=$userName";
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
	$cinfo=json_decode($result,true);
	$action=$cinfo[2];
	$uploadDate=date("Y-m-d H:i:s");
	$sqlLog="INSERT INTO `file_access_log`(`time_stamp`, `employ_id`, `file_id`, `session_id`, `action`) 
			VALUES ('$uploadDate','System_Api','No_files', '$sid','$action time: $execution_time')";
		$dblinklog->query($sqlLog) or
			die("Something went wrong with $sql<br>".$dblink->error);
	
} else {
	
	$uploadDate=date("Y-m-d H:i:s");
	$sqlLog="INSERT INTO `file_access_log`(`time_stamp`, `employ_id`, `file_id`, `session_id`, `action`) 
			VALUES ('$uploadDate','System_Api','No_files', 'No_Session', '$sid')";
		$dblinklog->query($sqlLog) or
			die("Something went wrong with $sql<br>".$dblink->error);
}
?>