<?php
@SESSION_START();
$id = (isset($_SESSION['auth_id'])&&$_SESSION['auth_id']!="")? $_SESSION['auth_id'] : "0";
$conn = mysqli_connect("localhost","root","P@ssw0rd123?","GNHQ");
$fid = (isset($_GET['fid'])&&$_GET['fid']!="")? mysqli_real_escape_string($conn,$_GET['fid']) : '0';
$access_query = mysqli_query($conn,"SELECT `owner_id` FROM `drive_files` WHERE `file_uid`='".$fid."';");
$owner_id = mysqli_fetch_row($access_query);
$access = ($owner_id[0]==$id)? 1 : 0;
if($access==0){echo "Access Denied";return false;}
else {
	$conn = mysqli_connect("localhost","root","P@ssw0rd123?","GNHQ");
	if(mysqli_query($conn,"DELETE FROM `drive_files` WHERE `file_uid`='".$fid."';")==1){
		echo "File deleted.";
	}else {
		echo "An error has occured";
	}
}
?>