<?php
@SESSION_START();
$target_dir = "downloads/";
$conn = mysqli_connect("localhost","root","P@ssw0rd123?","GNHQ");
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);


if($imageFileType==("exe")){$uploadOk=2;}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
}else if($uploadOk == 2){
	echo "Sorry, that file type is prohibited.";
} else {

	// set up basic connection
	$ftp_server = "45.77.113.131";
	$ftp_user_name = "drive";
	$ftp_user_pass = "Q*7zgz[]@!w8@5]P";
	
// set up basic connection
$conn_id = ftp_connect($ftp_server);

// login with username and password
$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);

// upload a file
$file = basename($_FILES["fileToUpload"]["name"]);
$file_title = sha1(md5(microtime().rand()));
if (ftp_put($conn_id, "files/id_".$_SESSION['auth_id']."/".$file_title, $_FILES["fileToUpload"]["tmp_name"], FTP_BINARY)) {
	mysqli_query($conn,"INSERT INTO `drive_files`(`id`, `owner_id`, `file_name`, `file_size`, `file_type`, `file_uid`, `server_location`) VALUES ('','".$_SESSION['auth_id']."','$file','".$_FILES["fileToUpload"]["size"]."','1','$file_title','"."files/id_".$_SESSION['auth_id']."/".$file_title."')");
 echo $file_title;
} else {
 echo "There was a problem while uploading $file\n";
}

// close the connection
ftp_close($conn_id);
}
?>