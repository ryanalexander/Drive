<?php
error_reporting(0);
if(isset($_GET['id'])&&$_GET['id']!=""){
	// set up basic connection
	$ftp_server = "45.77.113.131";
	$ftp_user_name = "drive";
	$ftp_user_pass = "Q*7zgz[]@!w8@5]P";
	
// set up basic connection
$conn_id = ftp_connect($ftp_server);

// login with username and password
$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);

// get contents of the current directory
$contents = ftp_rawlist($conn_id, '/files/id_'.$_GET['id']);

$file_list = array();

foreach($contents as $file){
	$i = explode(" ", $file);
	$filetype = 0;
	$file_name = null;
	$file_size = null;
	if($i[18]==""&&$i[4]=="1"){$filetype=1;$file_size=0;$file_name=$i[26];}else{$file_size=$i[18];$file_name=$i[22];}
	if($i[4]=="2"){$filetype=2;$file_size=0;$file_name=$i[23];}
	$data = array(
		"file_name"=>"$file_name",
		"file_size"=>"$file_size",
		"file_type"=>"$filetype"
	);
	array_push($file_list,$data);
}
// output $contents
echo json_encode($file_list);

}
?>