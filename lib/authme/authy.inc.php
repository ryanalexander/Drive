<?php
class authy {
    public static $login = false;
    public static $config = array(
        "main_domain"=>"drive.stelch.com",
        "authenticated_page"=>"/",
        "login_code"=>"cMX6k2Bztdxu",
        "login_page"=>"https://accounts.stelch.com/",
        "login_required"=>true,
        "session_storage_mode"=>"session",
        "page_view_permission"=>""
    );
    public static $user = array(
        "id"=>"0",
        "name"=>"testing",
        "email"=>"test@stelch.com",
        "password"=>"example1"
    );
    public static function execute() {
        session_start();
        if(authy::$config["login_required"]==true&&authy::login_state()==false){header("Location: ".authy::$config["login_page"].authy::$config["login_code"]);}
        if(authy::login_state()==true){
            if($_SESSION['auth_name']!=""&&$_SESSION['auth_position']){
                authy::$user['name']=$_SESSION['auth_name'];
                authy::$user['email']=$_SESSION['auth_email'];
                authy::$user['position']=$_SESSION['auth_position'];
            }else {
                header("Referer: https://dev.gnhq.studio");
                print_r($_SESSION);
                //header("Location: ./lib/authme/verify.php?t=".$_COOKIE['auth_session']);
            }
        }
    }
    public static function login_state() {
        if(isset($_COOKIE['auth_token'])&&$_COOKIE['auth_token']!=""){
            if(isset($_COOKIE['auth_session'])){
                return true;
            }
        }
        return false;
    }
}
?>