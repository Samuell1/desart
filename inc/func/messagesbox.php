<?php

if(!isset( $_SERVER['HTTP_X_REQUESTED_WITH']) && !($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' )){die("bad request");}

require("../settings.php");

			if(!MEMBER){redirect("/");}
			
			$touserid = (int)$_GET["userid"];
			
	$puserr = dbquery("SELECT * FROM bg_users WHERE user_id='".$touserid."'");
	$puser = dbarray($puserr);
	
	$validuser = dbrows($puserr);
	if($validuser == 0){die("Error.");}
			
			if(!isset($touserid)){redirect("/");}
			if($touserid == $userinfo["user_id"]){redirect("/");}
	
	$selmes = dbquery("SELECT * FROM bg_messages WHERE (mes_userid='".$userinfo["user_id"]."' AND mes_touserid='".$touserid."') OR (mes_userid='".$touserid."' AND mes_touserid='".$userinfo["user_id"]."') ORDER BY mes_id ASC");

	$pocet = dbrows($selmes);
	
	if($pocet >= 1){
	
	while ($data = dbarray($selmes)){

    if($data["mes_userid"] == $userinfo["user_id"]){
        $changepull = "pull-left";
    }else{
        $changepull = "pull-left";
    }

echo '
<div class="media komentar">
  <a class="'.$changepull.'">
    <img class="media-object img-circle" src="'.(useravatar($data["mes_userid"]) != "/file/avatars/avatar.png" ? useravatar($data["mes_userid"]):useravatar($data["mes_userid"])).'" alt="avatar">
  </a>
  <div class="media-body">
    <h4 class="media-heading"><a class="profillink" data-target="65">'.username($data["mes_userid"]).'</a> <span class="time pull-right">'.timeago($data["mes_time"]).'</span></h4>
    '.bbcode(badwords(smiley($data["mes_text"]))).'
  </div>
</div>
';
	
	}
	
	}else{
	echo "<div style='padding:15px'>Žiadne správy s užívateľom <b>".username($touserid,0)."</b></div>";
	}
	
	dbquery("UPDATE bg_messages SET mes_read='1' WHERE mes_touserid='".$userinfo["user_id"]."' AND mes_userid='".$touserid."'");

?>
