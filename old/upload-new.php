<!DOCTYPE html>
<link href="assets/css/bootstrap.css" rel="stylesheet" />
<link href="assets/css/bootstrap-fileupload.min.css" rel="stylesheet" />
<!-- JQUERY SCRIPTS -->
<script src="assets/js/jquery-1.12.4.js"></script>
<!-- BOOTSTRAP SCRIPTS -->
<script src="assets/js/bootstrap.js"></script>
<script src="assets/js/bootstrap-fileupload.js"></script>
<?php
include("functions.php");
echo '<div id="page-inner">';
if (isset($_REQUEST['msg']) && ($_REQUEST['msg']=="success")){
	echo '<div class="alert alert-success alert-dismissable">';
	echo '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>';
	echo 'Document successfully uploaded!</div>';
}
echo '<h1 class="page-head-line">Upload a New File to DocStorage</h1>';
echo '<div class="panel-body">';
echo '<form method="post" enctype="multipart/form-data" action="">';
echo '<input type="hidden" name="uploadedby" value="user@test.mail">';
echo '<input type="hidden" name="MAX_FILE_SIZE" value="100000000">';
echo '<div class="form-group">';
echo '<label class="control-label col-lg-4">File Upload</label>';
echo '<div class="">';
echo '<div class="fileupload fileupload-new" data-provides="fileupload">';
echo '<div class="fileupload-preview thumbnail" style="width: 200px; height: 150px;"></div>';
echo '<div class="row">';//buttons
echo '<div class="col-md-2">';
echo '<span class="btn btn-file btn-primary">';
echo '<span class="fileupload-new">Select File</span>';
echo '<span class="fileupload-exists">Change</span>';
echo '<input name="userfile" type="file"></span></div>';
echo '<div class="col-md-2"><a href="#" class="btn btn-danger fileupload-exists" data-dismiss="fileupload">Remove</a></div>';
echo '</div>';//end buttons
echo '</div>';//end fileupload fileupload-new
echo '</div>';//end ""
echo '</div>';//end form-group
echo '<hr>';
echo '<button type="submit" name="submit" value="submit" class="btn btn-lg btn-block btn-success">Upload File</button>';
echo '</form>';
echo '</div>';//end panel-body
echo '</div>';//end page-inner
if (isset($_POST['submit'])){
	
	$dblink=db_connect("documents");
	$dblinklog=db_connect("docsystem");
    if (mysqli_connect_errno()){
        die("Error connecting to database: ".mysqli_connect_error());   
    }
	$uploadDate=date("Y-m-d H:i:s");
	$uploadBy="user@test.mail";
	$fileName=$_FILES['userfile']['name'];
	$docType="pdf";
	$tmpName=$_FILES['userfile']['tmp_name'];
	$fileSize=$_FILES['userfile']['size'];
	$fileType=$_FILES['userfile']['type'];
    $path="/var/www/html/uploads/";
	$fp=fopen($tmpName, 'r');
	$content=fread($fp, filesize($tmpName));
	fclose($fp);
	$sql="INSERT INTO `documents`(`name`, `path`, `upload_by`, `upload_date`, `status`, `file_type`, `file_size`) 
		VALUES ('$fileName','$path','$uploadBy','$uploadDate','active','$docType','$fileSize')";
	$dblink->query($sql) or
		die("Something went wrong with $sql<br>".$dblink->error);
	$fp=fopen($path.$fileName,"wb") or
		die("Could not open $path$fileName for writing");
	fwrite($fp,$content);
	fclose($fp);
	
	$sql="SELECT `auto_id` FROM `documents` WHERE upload_date LIKE '%$uploadDate%'";
	$result=$dblink->query($sql) or
		die("Something went wrong with $sql<br>".$dblink->error);
	while ($data=$result->fetch_array(MYSQLI_ASSOC)){
		$fileId=$data['auto_id'];
		$sqlLog="INSERT INTO `file_access_log`(`time_stamp`, `employ_id`, `file_id`, `action`) 
			VALUES ('$uploadDate','$uploadBy','$fileId','Uploaded')";
		$dblinklog->query($sqlLog) or
			die("Something went wrong with $sql<br>".$dblink->error);
	}
	
	header("Location: https://192.168.56.102/upload-new.php?msg=success");
}
?>