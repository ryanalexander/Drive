<?php
if(isset($_GET['id'])&&$_GET['id']!=""){
$conn = mysqli_connect("localhost","root","P@ssw0rd123?","GNHQ");
$query = mysqli_query($conn,"SELECT * FROM `drive_files` WHERE `owner_id`='".mysqli_real_escape_string($conn,$_GET['id'])."';");
$file_list = array();
while($rows=mysqli_fetch_assoc($query)){
	$file_name = $rows['file_name'];
	$file_size = $rows['file_size'];
	$file_uid = $rows['file_uid'];
	$file_location = $rows['server_location'];
	$file_type = $rows['file_type'];
	$data = array(
		"file_name"=>"$file_name",
		"file_size"=>"$file_size",
		"file_type"=>"$file_type",
		"file_uid"=>"$file_uid",
		"file_location"=>"$file_location"
	);
	array_push($file_list,$data);
}
echo json_encode($file_list);
}
?>