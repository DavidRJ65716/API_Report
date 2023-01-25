<?php
$time_start = microtime(true);
$page="report_average_file.php";
include("functions.php");
$dblinkfile = db_connect("documents");

$sqlFile="SELECT * FROM `documents` WHERE `upload_by`='System_Api'";
$result=$dblinkfile->query($sqlFile) or
			die("Something went wrong with $sql<br>".$dblinkfile->error);

$loanArray=array();
$tmpNum=0;
$numLoans=0;
while($data=$result->fetch_array(MYSQLI_ASSOC)) {
	
	$tmpNum+=$data['file_size'];
	$numLoans+=1;
}

$average = $tmpNum/$numLoans;
$tmpMB=$tmpNum/pow(1024, 2);
$averageKB=$average/1024;

echo '<div>Total Size of documents: '.round($tmpNum).' Bytes - '.round($tmpMB).' MB</div><br/>';
echo '<div>Average siz of document: '.round($average).' Bytes - '.round($averageKB).' KB</div>';

$time_end = microtime(true);
$execution_time = ($time_end - $time_start)/60;

echo '<br/><div>Time: '.$execution_time.'s</div>';
?>