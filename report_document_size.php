<?php
$time_start = microtime(true);
$page="report_document_size.php";
include("functions.php");
$dblinkfile = db_connect("documents");

$sqlFile="SELECT * FROM `documents` WHERE `upload_by`='System_Api'";
$result=$dblinkfile->query($sqlFile) or
			die("Something went wrong with $sql<br>".$dblinkfile->error);

$loanArray=array();
while($data=$result->fetch_array(MYSQLI_ASSOC)) {
	
	$tmp=explode("-",$data['name']);
	$loanArray[]=$tmp[0];
}

$loanUnique=array_unique($loanArray);
$fileTotal=0;
foreach($loanUnique as $key=>$value) {
	
	$sqlrst="SELECT count(`name`) FROM `documents` WHERE `name` like '%$value%'";
	$rstLoanName=$dblinkfile->query($sqlrst) or
			die("Something went wrong with $sql<br>".$dblinkfile->error);
	$tmp=$rstLoanName->fetch_array(MYSQLI_NUM);
	$fileTotal+=$tmp[0];
}

$average=$fileTotal/count($loanUnique);
echo '<div>Total Number of Documents Recieved: '.$fileTotal.'</div><br/>';
echo '<div>Average Documents Recieved: '.round($average, 2).'</div><br/>';
echo '<div>Above/Below Average List: </div>';

foreach($loanUnique as $key=>$value) {
	
	$sqlrst="SELECT count(`name`) FROM `documents` WHERE `name` like '%$value%'";
	$rstLoanName=$dblinkfile->query($sqlrst) or
			die("Something went wrong with $sql<br>".$dblinkfile->error);
	$tmp=$rstLoanName->fetch_array(MYSQLI_NUM);
	if($tmp[0]>$average){
		echo '<div> '.$value.' has '.$tmp[0].'	number of documents - Above Average</div>';
	} else {
		echo '<div> '.$value.' has '.$tmp[0].'	number of documents - Below Average</div>';
	}
}

$time_end = microtime(true);
$execution_time = ($time_end - $time_start)/60;

echo '<br/><div>Time: '.$execution_time.'s</div>';
?>