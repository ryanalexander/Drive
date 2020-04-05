var g_tab = "gdrive";
var g_file = "";
var input = null;
var g_type = "";
var g_uploading = 0;
var alert_vis = 0;
var alert_dis_id = 0;
function checkNetwork(){if(navigator.onLine!=true){log("Network Offline",2);alert_t("You are currently offline");}}
function setTab(tabID,title) {
	checkNetwork();
	var current = document.getElementById(g_tab);
	var req     = document.getElementById(tabID);
	var btn     = document.getElementById("subheaderpg");
	if(navigator.onLine==true){}else{alert("")}
	if(current==req){}else {
		g_tab=tabID;
		btn.innerHTML=title;
		current.getElementsByTagName('img')[0].src = current.getElementsByTagName('img')[0].src.replace("active","inactive");
		req.getElementsByTagName('img')[0].src = req.getElementsByTagName('img')[0].src.replace("inactive","active");
		current.classList.toggle("active");
		req.classList.toggle("active");
	}
}
function dragover_handler(e) {
	if(document.getElementById("droppable").style.display=="none"){
		document.getElementById("droppable").style.display="block";
	}else {
		document.getElementById("droppable").style.display="none";
	}
}
function downloadFile(key) {
	document.getElementById("exec_download").src = ("https://dev.gnhq.studio/drive_cdn/file/"+key);
	alert("Initiating download.");
	document.getElementById("exec_download").src = ("");
}
function selectFile(file_id){
	checkNetwork();
	var req = document.getElementById("file_id_"+file_id);
	var old = null;
	if(req==null){return false;}
	if(g_type=="file"){old=document.getElementById("file_id_"+g_file)}else{old=document.getElementById("folder_id_"+g_file)}
	if(req==old){
		if(req.getElementsByClassName('mp3').length==1){playSong(req.accessKey);log("Now playing "+req.getElementsByClassName('filename')[0].innerHTML, 1);
		}else if(req.getElementsByClassName('wav').length==1){playSong(req.accessKey);log("Now playing "+req.getElementsByClassName('filename')[0].innerHTML, 1);
		}else if(req.getElementsByClassName('ogg').length==1){playSong(req.accessKey);log("Now playing "+req.getElementsByClassName('filename')[0].innerHTML, 1);
		}else if(req.getElementsByClassName('png').length==1){showPhoto(req.accessKey);log("Now showing "+req.getElementsByClassName('filename')[0].innerHTML, 1);
		}else if(req.getElementsByClassName('jpg').length==1){showPhoto(req.accessKey);log("Now showing "+req.getElementsByClassName('filename')[0].innerHTML, 1);
		}else if(req.getElementsByClassName('jpeg').length==1){showPhoto(req.accessKey);log("Now showing "+req.getElementsByClassName('filename')[0].innerHTML, 1);
		}else if(req.getElementsByClassName('mpeg').length==1){showPhoto(req.accessKey);log("Now showing "+req.getElementsByClassName('filename')[0].innerHTML, 1);
		}else {
			log("Now downloading "+req.getElementsByClassName('filename')[0].innerHTML, 1);
			downloadFile(req.accessKey);
		}
	}
		g_file=file_id;
		g_type="file";
		if(req!=null){
			req.classList.toggle("active");
		}
		g_file = file_id;
	if(old!=null){old.classList.toggle("active");}
}
function selectFolder(file_id){
	checkNetwork();
	var req = document.getElementById("folder_id_"+file_id);
	var old = null;
	if(g_type=="file"){old=document.getElementById("file_id_"+g_file)}else{old=document.getElementById("folder_id_"+g_file)}
	if(req==old){
		alert_t("File not found");
	}
		g_file=file_id;
		g_type="folder";
		req.classList.toggle("active");
		g_file = file_id;
	if(old!=null){old.classList.toggle("active");}
}

alert = function(e) {
    var elem = document.getElementById("alert");
    var textb = elem.getElementsByClassName('text')[0];
    textb.innerHTML = e;
    if(e==""&&alert_vis==1){elem.classList.toggle("active");alert_vis=0;}
    if(e!=""&&alert_vis==0){elem.classList.toggle("active");alert_vis=1;}
}
alert_t = function(e) {
    var elem = document.getElementById("alert");
    var textb = elem.getElementsByClassName('text')[0];

    textb.innerHTML = e;
    if(e==""&&alert_vis==1){elem.classList.toggle("active");alert_vis=0;}
    if(e!=""&&alert_vis==0){
        elem.classList.toggle("active");
        alert_vis=1;
        alert_dis_id = setTimeout(function(){
            alert("");
        },4000);
    }
}
	
function log(message,level){
	if(level==0){setTimeout(console.info("GNHQ OS : "+message));}
	if(level==1){console.warn("GNHQ OS : "+message);}
	if(level==2){console.error("GNHQ OS : "+message);}
}


function showDropdown(id) {
    document.getElementById(id).classList.toggle("show");
}
window.onclick = function(event) {
  if (!event.target.matches('.new')) {

    var dropdowns = document.getElementsByClassName("dropdown-content");
    var i;
    for (i = 0; i < dropdowns.length; i++) {
      var openDropdown = dropdowns[i];
      if (openDropdown.classList.contains('show')) {
        openDropdown.classList.remove('show');
      }
    }
  }
  if(!event.target.matches('folder')&&!event.target.matches('file')&&old!=null){
	var old = null;
	if(g_type=="file"){old=document.getElementById("file_id_"+g_file)}else{old=document.getElementById("folder_id_"+g_file)}
	old.classList.toggle("active");
  }
}
window.onkeydown = function() {
    var key = event.keyCode || event.charCode;
    if( key == 8 || key == 46 ){
		if(g_file!=""){
			if(g_type=="file"){document.getElementById("file_id_"+g_file).style.display="none";selectFile(parseInt(g_file)+1);}else{document.getElementById("folder_id_"+g_file).style.display="none";selectFolder(parseInt(g_file)+1);}
			$.ajax({
				url : 'https://dev.gnhq.studio/drive_cdn/removeFile.php?fid='+document.getElementById("file_id_"+g_file).accessKey,
				type : 'GET',
				processData: false,
				contentType: false,
				success : function(data) {
					var empty = 1;
					for(i=0;i<document.getElementsByClassName('file').length;i++) {
						log("ID: "+document.getElementsByClassName('file')+id,0);
						if(document.getElementsByClassName('file')[i].style.display!="none"){empty=0;}
					}
					if(empty==1){
						document.getElementById("files_ctnr").innerHTML="<center><h2>Your drive is empty.</h2></center>";
					}
				}
			});	
			alert_t("File Deleted");
		}else {
		}
}};


function initiateUpload() {
	g_uploading++;
	g_id = g_uploading;
    input = $(document.createElement('input')); 
	uploadbox = document.getElementById('upload_mgr');
    input.attr("type", "file");
    input.attr("id","file_upload");
    input.trigger('click');
    input.change(function(){
	if(input[0].files[0]["size"]>(drive_max-drive_usage)){alert("File to large");return false;}
	if(uploadbox.classList.contains("active")){}else{uploadbox.classList.toggle("active");}
	if(g_uploading==1){uploadbox.getElementsByClassName("container")[0].innerHTML="";}
	uploadbox.getElementsByClassName("container")[0].innerHTML+='<p id="upload_id_'+g_uploading+'" class="item"><span class="title">'+input[0].files[0]['name']+'</span><span class="progress">Waiting...</span></p>';
	var formData = new FormData();
	formData.append('fileToUpload', input[0].files[0]);
	$.ajax({
       url : 'https://dev.gnhq.studio/drive_cdn/upload.php',
       type : 'POST',
       data : formData,
       processData: false,  // tell jQuery not to process the data
       contentType: false,  // tell jQuery not to set contentType
       success : function(data) {
			if(data.length==40){
				alert_t("File uploaded.");
				newEntity(input[0].files[0]['name'],'file',data);
				document.getElementById("upload_id_"+g_id).innerHTML='<span class="title">'+input[0].files[0]['name']+'</span><span class="progress done">100%</span>';
				log(data, 0);
			}else if(data.length<39){
				alert_t(data);
				document.getElementById("upload_id_"+g_id).innerHTML='<span class="title">'+input[0].files[0]['name']+'<span class="progress failed">Prohibited</span></p>';
			}else {
				alert("An error has occured.");
				log(data,2);
				document.getElementById("upload_id_"+g_id).innerHTML='<span class="title">'+input[0].files[0]['name']+'</span><span class="progress failed">0%</span></p>';
			}
       }
	});	
	});
    return false;
}
function toggleModel() {
	document.getElementsByClassName("model")[0].classList.toggle("active");
}

function playSong(file_id) {
	var elem = document.getElementById("musicplayer");
	var audio = elem.getElementsByTagName("audio")[0];
	var tag = elem.getElementsByTagName("audio")[0].getElementsByTagName("source")[0];
	if(file_id=="stop"){
		if((elem.classList.contains("active"))){elem.classList.toggle("active");}
		audio.pause();
	}else {
	if(!(elem.classList.contains("active"))){elem.classList.toggle("active");}
	alert("Fetching song.");
	tag.src = "https://dev.gnhq.studio/drive_cdn/file/"+file_id;
	audio.addEventListener('ended', function() { 
		if(!(elem.classList.contains("active"))){elem.classList.toggle("active");}
	}, false);
	audio.load();
	log(audio.play(),1);
	}
}
function showPhoto(file_id) {
	log("Loading Image",0);
	var elem = document.getElementById("photoviewer");
	var downico = document.getElementsByClassName("controls")[0].getElementsByTagName("img")[0];
	var img = elem.getElementsByClassName("content")[0].getElementsByTagName("img")[0];
	if(file_id=="close"){
		if((elem.classList.contains("active"))){elem.classList.toggle("active");}
	}else {
	if(!(elem.classList.contains("active"))){elem.classList.toggle("active");}
	img.src = "https://dev.gnhq.studio/drive_cdn/file/"+file_id;
	downico.onclick=function(){downloadFile(file_id);};
	}
}

function newEntity(title,type,uid){
	if(type=="file"){
		var ctnr = document.getElementById("files_ctnr");
		var re = /(?:\.([^.]+))?$/;
		var ext = re.exec(title)[1]; 
		g_c_i=g_c_i+2;
		if(document.getElementsByClassName("file").length<1){ctnr.innerHTML="<br /><br /><p>Files</p>";}
		ctnr.innerHTML+='<span id="file_id_'+g_c_i+'" class="file" accessKey="'+uid+'" onclick="selectFile(\''+g_c_i+'\');"><div class="icon file '+ext+'"></div><div class="filename">'+title+'</div></span>';
	}else {
		return "An error occured";
	}
}