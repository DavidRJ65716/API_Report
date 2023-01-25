<?php
include("functions.php");
$dblinklog = db_connect("docsystem");
$dblinkfile = db_connect("documents");
$userName="kck716";
$password="JwQmWg#T2H7zt8rM";
$sid="d8d6f1e5279a6e9ac8d5b6e7a1e8ca8c90c0bccc";

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
$sqlLog="INSERT INTO `system_access_log`(`session_id`, `action`) 
			VALUES ('$sid','Files Reclaimed - Time: $execution_time')";
			$dblinklog->query($sqlLog) or
				die("Something went wrong with $sql<br>".$dblinklog->error);
echo $cinfo[1];
if ($cinfo[0]=="Status: OK"){
	
	if (preg_match('/\bNone/i', $cinfo[1])){//Looking for Action: None
		
		$action=$cinfo[1];
		$sqlLog="INSERT INTO `system_access_log`(`session_id`, `action`) 
			VALUES ('$sid','$action - Time: $execution_time')";
			$dblinklog->query($sqlLog) or
				die("Something went wrong with $sql<br>".$dblink->error);
	} else {
		
		$tmp=explode(":",$cinfo[1]);
		$files=explode(",",$tmp[1]);
		$numFiles=count($files);
		$sqlLog="INSERT INTO `system_access_log`(`session_id`, `action`) 
			VALUES ('$sid','Files to Import: $numFiles - Time: $execution_time')";
			$dblinklog->query($sqlLog) or
				die("Something went wrong with $sql<br>".$dblinklog->error);
		
		foreach($files as $key=>$value){

			$tmp=explode("/",$value);
			if (sizeof($tmp) > 4) {
				$file=$tmp[4];
				$data="sid=$sid&uid=$userName&fid=$file";
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
				//curl_close($ch);
				$content=$result;
				$path="/var/www/html/receive/$file";
				$fp=fopen("/var/www/html/receive/$file","wb");
				$fileType="PDF";
				$fileSize=fwrite($fp,$content);
				fclose($fp);

				$sqlFile="INSERT INTO `documents`(`name`, `path`, `upload_by`, `status`, `file_type`, `file_size`) 
					VALUES ('$file','$path','System_Api','active','$fileType','$fileSize')";
					$dblinkfile->query($sqlFile) or
						die("Something went wrong with $sql<br>".$dblinkfile->error);
			} else {
				$sqlLog="INSERT INTO `system_access_log`(`session_id`, `action`) 
					VALUES ('$sid','Failed to Find File - Time: $execution_time')";
					$dblinklog->query($sqlLog) or
						die("Something went wrong with $sql<br>".$dblinklog->error);
			}
		}
	}
}

?>