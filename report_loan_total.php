<?php
$time_start = microtime(true);
$page="report_loan_total.php";
include("functions.php");
$dblinkfile = db_connect("documents");

$sqlFile="SELECT * FROM `documents` WHERE `upload_by`='System_Api'";
$result=$dblinkfile->query($sqlFile) or
			die("Something went wrong with $sql<br>".$dblinkfile->error);

$loanArray=array();
$numLoans=0;
while($data=$result->fetch_array(MYSQLI_ASSOC)) {
	
	$tmp=explode("-",$data['name']);
	$loanArray[]=$tmp[0];
}

$loanUnique=array_unique($loanArray);

echo '<div> Total Loan Number Generated: '.count($loanUnique).'</div><br/>';
echo '<div>List of all Loans:</div>';
foreach($loanUnique as $key=>$value) {
	
	echo '<div> '.$value.'</div>';
}
$time_end = microtime(true);
$execution_time = ($time_end - $time_start)/60;

echo '<br/><div>Time: '.$execution_time.'s</div>';
?>