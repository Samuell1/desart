<?

if(!isset( $_SERVER['HTTP_X_REQUESTED_WITH']) && !($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' )){die("bad request");}

require("../settings.php");

	if(!MEMBER){redirect("/");}

	$touserid = (int)$_POST["touserid"];
	$text = dbescape(htmlspecialchars($_POST["chatboxtext"], ENT_QUOTES));
	
	$puserr = dbquery("SELECT * FROM bg_users WHERE user_id='".$touserid."'");
	$puser = dbarray($puserr);
	
	$validuser = dbrows($puserr);
	if($validuser == 0){echo "Error.";}
	
	if($touserid == ""){redirect("/");}
	
	if($text != ""){
	
	dbquery("INSERT INTO bg_messages (mes_userid, mes_touserid, mes_text, mes_time) VALUES('".$userinfo["user_id"]."', '".$touserid."', '".$text."', '".time()."') ");
	
	}

?>
