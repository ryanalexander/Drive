<?php require("./lib/__autoload.php"); $drive_usage=0;$drive_max=$_SESSION['auth_drive_max'];?>
<head>
	<title>My Drive - GNHQ</title>
	<link rel="stylesheet" href="./bin/css/style.css">
	<script>var g_c_i=0;var drive_max=<?php echo $drive_max; ?>;var drive_usage=<?php echo $drive_usage; ?></script>
</head>
<?php
	$folders = array();
	$files = array();
	$drive = (array)json_decode(file_get_contents("https://drive.stelch.com/api/getFiles2.php?id=".authy::$user['id']));
	$f_id=0;
	foreach($drive as $file){
			$data = (array)$file;
			$drive_usage=$drive_usage+$data['file_size'];
			if($data['file_type']=="2"){array_push($folders,$data);}
	   else if($data['file_type']=="1"){array_push($files  ,$data);}
	   else                            {array_push($files  ,$data);}
	}
?>
<div id="droppable">
<span>Drop files here to upload</span>
</div>
<div id="alert" onclick="alert('');">
<span class="text">An error has occured</span>
<span class="close">X</span>
</div>
<div class="model">
	<div class="box newfile">
		<?php echo authy::$user['permissions']; ?>
	</div>
</div>
<iframe id="exec_download" style="display:none;"></iframe>
<div class="overlay music" id="musicplayer">
	<audio controls autoplay>
		<source src="" type="audio/mpeg">
	</audio>
	<span class="close" onclick="playSong('stop');">X</span>
</div>
<div class="overlay photo" id="photoviewer">
	<span class="exit" onclick="showPhoto('close');">X</span>
	<span class="controls"><img src="bin/img/download.icon" height="3%" /></span>
	<div class="content">
		<img src=""></img>
	</div>
</div>
<div class="overlay showfile">
	<span class="exit"><</span>
	<span class="controls"><img src="bin/img/download.icon" height="3%" /></span>
	<div class="content">
	
	</div>
</div>
<div id="upload_mgr">
<div class="head" onclick="uploadbox.classList.toggle('active');">
	<span class="title">Upload Progress</span>
	<span class="actions">X</span>
</div>
<div class="container">
<center><h3>Waiting for upload</h3></center>
</div>
</div>

<div class="head">
	<span class="title"><img src="bin/img/logo.png" width="70%" /></span>
	<span class="search"><input type="search" placeholder="Search Drive" /></span>
	<span class="align"></span>
	
	<span class="settings"></span>
	<span class="user">
		<img src="bin/img/user.icon" onclick="alert_t('I am still working on that feature');"></img>
	</span>
</div>
<div class="subheader">
	<span class="new">
		<button class="new <?php echo ($drive_usage>=$drive_max) ? 'disabled' : 'active'; ?>" onclick="<?php echo ($drive_usage>=$drive_max) ? 'alert(\'Your drive is full, please delete some files to continue.\');' : 'showDropdown(\'new_btn_dropdown\')'; ?>">NEW</button>
		<div id="new_btn_dropdown" class="dropdown-content">
			<a href="#upload" onclick="initiateUpload();">Upload</a>
			<hr />
			<a href="#folder">New Folder</a>
			<a href="#file" onclick="toggleModel();">New File</a>
		</div>
	</span>
	<span class="drive"><button id="subheaderpg" class="drive">My Drive</button></span>
	<span></span>
	<span></span>
</div>


<div class="container" ondragover="dragover_handler(event);" ondragend="alert('Drag over');">
	<div class="sidebar">
		<br />
		<span class="link active" id="gdrive" onclick="setTab('gdrive','My Drive');"><img src="./bin/img/drive.active" />My Drive</span>
		<span class="link" id="gshare" onclick="setTab('gshare','Shared with me');"><img src="./bin/img/shared.inactive" />Shared with me</span>
		<span class="link" id="grecent" onclick="setTab('grecent','Recent');"><img src="./bin/img/recent.inactive" />Recent</span>
		<span class="link" id="gbin" onclick="setTab('gbin','Trash');"><img src="./bin/img/bin.inactive" />Trash</span>
		<br />
		<hr />
		<span class="link" id="gbackup" onclick="setTab('gbackup','Backups');"><img src="./bin/img/backup.inactive" />Backups</span>
		<hr />
		<br />
		<span class="datausage"><span id="usageMAXINT"><?php echo formatBytes($drive_usage,2)."</span> of ".formatBytes($drive_max,2); ?> used</span>
	</div>
	<div class="dpage">
	<?php
	$empty = 1;
	foreach($folders as $folder){
		$f_id=$f_id+1;
		if($f_id==1){
?>
	<br />
		<p>Folders</p>
<?php		
		}
		$empty = 0;
		?>
			<span id="folder_id_<?php echo $f_id; ?>" class="folder" accessKey="<?php echo $folder['file_uid'] ?>" onclick="selectFolder('<?php echo $f_id; ?>');">
				<div class="icon folder"></div>
				<div class="filename"><?php echo $folder['file_name']; ?></div>
			</span>
		<?php
	}
?>
<div id='files_ctnr'>
<?php
	$f_i_id = $f_id;
	foreach($files as $file){
	if($f_i_id==$f_id){
		echo "<br /><br /><p>Files</p>";
	}
		$f_id=$f_id+1;
		$ext = pathinfo($file['file_name'], PATHINFO_EXTENSION);
		$empty = 0;
		?>
			<span id="file_id_<?php echo $f_id; ?>" class="file" accessKey="<?php echo $file['file_uid'] ?>" onclick="selectFile('<?php echo $f_id; ?>');">
				<div class="icon file <?php echo $ext; ?>"></div>
				<div class="filename"><?php echo $file['file_name']; ?></div>
			</span>
		<script>g_c_i=g_c_i+1;</script>
		<?php
	}
	if($empty==1){
		echo "<center><h2>Your drive is empty.</h2></center>";
	}
	function formatBytes($bytes, $precision = 2) { 
    $units = array('B', 'KB', 'MB', 'GB', 'TB'); 

    $bytes = max($bytes, 0); 
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024)); 
    $pow = min($pow, count($units) - 1); 

    // Uncomment one of the following alternatives
    // $bytes /= pow(1024, $pow);
     $bytes /= (1 << (10 * $pow)); 

    return round($bytes, $precision) . ' ' . $units[$pow]; 
} 
	?>
	</div>
	</div>
</div>

<script src="./bin/js/global.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>