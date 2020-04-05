<?php
require("./authy.inc.php");
if(isset($_GET['t'])&&$_GET['t']!=""&&$_GET['t']!="null"&&(isset($_SERVER['HTTP_REFERER'])&&($_SERVER['HTTP_REFERER']==authy::$config["login_page"].authy::$config["login_code"])||true==true)){
    //echo "https://stelch.com/projects/auth0/validate_req.php?s_token=".authy::$config['login_code']."&ip_addr=".$_SERVER['REMOTE_ADDR']."&token_id=".$_GET['t'];
    $validate = (array)json_decode(file_get_contents("https://stelch.com/projects/auth0/validate_req.php?s_token=".authy::$config['login_code']."&ip_addr=".$_SERVER['REMOTE_ADDR']."&token_id=".$_GET['t']));
    if($validate['error']=="none"){
        setcookie("auth_token",base64_encode($_GET['t']),time()+65535, "/");
        setcookie("auth_session",$_GET['t'],time()+65535, "/");
        session_start();
        $_SESSION['auth_name']=$validate['name'];
        $_SESSION['auth_email']=$validate['emails'];
        $_SESSION['auth_position']=$validate['position'];
        $_SESSION['auth_mailboxes']=$validate['mailboxes'];
        $_SESSION['auth_drive_max']="5000";
        print_r($_SESSION);
        echo "Login success";
        header("Location: ".authy::$config['authenticated_page']);
    }else {
        if($validate['error']=="Invalid Auth"){header("Location: ".authy::$config['login_page'].authy::$config["login_code"]);}
        echo file_get_contents("error.html");
    }
}else {
    echo file_get_contents("error.html");
    header("Location: ".authy::$config['login_page'].authy::$config["login_code"]);
}
?>
