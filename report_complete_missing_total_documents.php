<?php
$time_start = microtime(true);
$page="report_complete_missing_total_documents.php";
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
}

echo '<div>Loans Missing Documents:</div>';

$loanUnique=array_unique($loanArray);
$compleatedLoans=array();
$totalCredit=0;
$totalClosing=0;
$totalTitle=0;
$totalFinancial=0;
$totalPersonal=0;
$totalInternal=0;
$totalLegal=0;
$totalOther=0;

foreach($loanUnique as $key=>$value) {
	
	$missing="";
	
	$sqlrst="SELECT count(`name`) FROM `documents` WHERE `name` like '%$value%' AND `name` like '%credit%'";
	$rstLoanName=$dblinkfile->query($sqlrst) or
			die("Something went wrong with $sql<br>".$dblinkfile->error);
	$credit=$rstLoanName->fetch_array(MYSQLI_NUM);
		
	$totalCredit+=$credit[0];
	if($credit[0] == 0){
		$missing .= " Credit";
	}
	
	$sqlrst="SELECT count(`name`) FROM `documents` WHERE `name` like '%$value%' AND `name` like '%closing%'";
	$rstLoanName=$dblinkfile->query($sqlrst) or
			die("Something went wrong with $sql<br>".$dblinkfile->error);
	$closing=$rstLoanName->fetch_array(MYSQLI_NUM);
	
	$totalClosing+=$closing[0];
	if($closing[0] == 0){
		$missing .= " Closing";
	}
	
	$sqlrst="SELECT count(`name`) FROM `documents` WHERE `name` like '%$value%' AND `name` like '%title%'";
	$rstLoanName=$dblinkfile->query($sqlrst) or
			die("Something went wrong with $sql<br>".$dblinkfile->error);
	$title=$rstLoanName->fetch_array(MYSQLI_NUM);
	
	$totalTitle+=$title[0];
	if($title[0] == 0){
		$missing .= " Title";
	}
	
	$sqlrst="SELECT count(`name`) FROM `documents` WHERE `name` like '%$value%' AND `name` like '%financial%'";
	$rstLoanName=$dblinkfile->query($sqlrst) or
			die("Something went wrong with $sql<br>".$dblinkfile->error);
	$financial=$rstLoanName->fetch_array(MYSQLI_NUM);
	
	$totalFinancial+=$financial[0];
	if($financial[0] == 0){
		$missing .= " Financial";
	}
	
	$sqlrst="SELECT count(`name`) FROM `documents` WHERE `name` like '%$value%' AND `name` like '%personal%'";
	$rstLoanName=$dblinkfile->query($sqlrst) or
			die("Something went wrong with $sql<br>".$dblinkfile->error);
	$personal=$rstLoanName->fetch_array(MYSQLI_NUM);
	
	$totalPersonal+=$personal[0];
	if($personal[0] == 0){
		$missing .= " Personal";
	}
	
	$sqlrst="SELECT count(`name`) FROM `documents` WHERE `name` like '%$value%' AND `name` like '%internal%'";
	$rstLoanName=$dblinkfile->query($sqlrst) or
			die("Something went wrong with $sql<br>".$dblinkfile->error);
	$internal=$rstLoanName->fetch_array(MYSQLI_NUM);
	
	$totalInternal+=$internal[0];
	if($internal[0] == 0){
		$missing .= " Internal";
	}
	
	$sqlrst="SELECT count(`name`) FROM `documents` WHERE `name` like '%$value%' AND `name` like '%legal%'";
	$rstLoanName=$dblinkfile->query($sqlrst) or
			die("Something went wrong with $sql<br>".$dblinkfile->error);
	$legal=$rstLoanName->fetch_array(MYSQLI_NUM);
	
	$totalLegal+=$legal[0];
	if($legal[0] == 0){
		$missing .= " Legal";
	}
	
	$sqlrst="SELECT count(`name`) FROM `documents` WHERE `name` like '%$value%' AND `name` like '%other%'";
	$rstLoanName=$dblinkfile->query($sqlrst) or
			die("Something went wrong with $sql<br>".$dblinkfile->error);
	$other=$rstLoanName->fetch_array(MYSQLI_NUM);
	
	$totalOther+=$other[0];
	if($other[0] == 0){
		$missing .= " Other";
	}
	
	if($missing == ""){
		array_push($compleatedLoans, $value);
	} else {
		echo '<div> '.$value.' is Missing:'.$missing.'</div>';
	}
}

echo '<br/><div>Completed List:</div>';
foreach($compleatedLoans as $key=>$value) {

	echo '<div>'.$value.'</div>';
}

//credit, closing, title, financial, personal, internal, legal, other
echo '<br/>Total Number of Documents</div>';
echo '<div>Total Credits: '.$totalCredit.'</div>';
echo '<div>Total Closings: '.$totalClosing.'</div>';
echo '<div>Total Titles: '.$totalTitle.'</div>';
echo '<div>Total Finacials: '.$totalFinancial.'</div>';
echo '<div>Total Personals: '.$totalPersonal.'</div>';
echo '<div>Total Internal: '.$totalInternal.'</div>';
echo '<div>Total Legal: '.$totalLegal.'</div>';
echo '<div>Total Other: '.$totalOther.'</div>';

$time_end = microtime(true);
$execution_time = ($time_end - $time_start)/60;

echo '<br/><div>Time: '.$execution_time.'s</div>';
?>