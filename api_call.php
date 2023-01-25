<?php
include("functions.php");
$userName="kck716";
$password="JwQmWg#T2H7zt8rM";
$dblinklog = db_connect("docsystem");
$dblinkfile = db_connect("documents");

include("api_create.php");

if ($cinfo[0]=="Status: OK" && $cinfo[1]=="MSG: Session Created"){
	
	include("api_query_receive.php");
	
	include("api_close.php");
} else {
	
	$action=$cinfo[1];
	$sqlLog="INSERT INTO `system_access_log`(`session_id`, `action`) 
		VALUES ('$sid','$action - Time: Failed')";
		$dblinklog->query($sqlLog) or
			die("Something went wrong with $sql<br>".$dblinklog->error);
	include("api_clear.php");
}
?>
