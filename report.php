<?php
$page="report.php";
include("functions.php");
$dblinkfile = db_connect("documents");

$sqlFile="SELECT * FROM `documents` WHERE `upload_by`='System_Api'";
$result=$dblinkfile->query($sqlFile) or
			die("Something went wrong with $sql<br>".$dblinkfile->error);


$loanArray=array();
$tmpNum=0;
$numLoans=0;
while($data=$result->fetch_array(MYSQLI_ASSOC)) {
	
	$tmp=explode("-",$data['name']);
	$loanArray[]=$tmp[0];
	$tmpNum+=$data['file_size'];
	$numLoans+=1;
}

$loanUnique=array_unique($loanArray);
foreach($loanUnique as $key=>$value) {
	
	$sqlrst="SELECT count(`name`) FROM `documents` WHERE `name` like '%$value%'";
	$rstLoanName=$dblinkfile->query($sqlrst) or
			die("Something went wrong with $sql<br>".$dblinkfile->error);
	$tmp=$rstLoanName->fetch_array(MYSQLI_NUM);
	echo '<div> '.$value.' has '.$tmp[0].'	number of documents</div>';
}
?>