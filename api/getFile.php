<?php
if(isset($_GET['uid'])&&$_GET['uid']!=""){
	// set up basic connection
	$conn=mysqli_connect("localhost","root","P@ssw0rd123?","GNHQ");
	$query=mysqli_query($conn,"SELECT * FROM `drive_files` WHERE `file_uid`='".mysqli_real_escape_string($conn,$_GET['uid'])."';");
	$ftp_server = "45.77.113.131";
	$ftp_user_name = "drive";
	$ftp_user_pass = "Q*7zgz[]@!w8@5]P";
	$local_file = "";
	$server_file = null;
	while($rows=mysqli_fetch_assoc($query)){$server_file=$rows['server_location'];$local_file="./downloads/".$rows['file_name'];}
	
	$conn_id = ftp_connect($ftp_server); 
	
	// login with username and password
	$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass); 

	// check connection
	if ((!$conn_id) || (!$login_result)) { 
		exit; 
	} else {
	}
	
	// try to download $server_file and save to $local_file
	if (ftp_get($conn_id, $local_file, $server_file, FTP_BINARY)) {
		$quoted = sprintf('"%s"', addcslashes(basename("downloads/".$local_file), '"\\'));
		$file_url = $local_file;
		if(!isset($_GET['live'])){
		header('Content-Type: application/octet-stream');
		header("Content-Transfer-Encoding: Binary"); 
		header("Content-disposition: attachment; filename=\"" . basename($file_url) . "\""); 	
		}
		readfile($file_url); // do the double-download-dance (dirty but worky)
		if(isset($_GET['keep_cache'])){unlink($file_url);}
	} else {
	}
	
	// close the FTP stream 
	ftp_close($conn_id); 
}
?>