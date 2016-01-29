<?php  include("../inc/settings.php");

if(!ADMIN){die();}

if(isset($_POST)){

	$id = $_POST["id"];
	$nick = $_POST["nick"];
	$email = $_POST["email"];
	$perm = $_POST["perm"];
	
	dbquery("UPDATE bg_users SET user_nick='".$nick."',user_email='".$email."',user_perm='".$perm."' WHERE user_id='".$id."'");

}