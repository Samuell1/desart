<?php

if(!isset( $_SERVER['HTTP_X_REQUESTED_WITH']) && !($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' )){die("bad request");}

require("../settings.php");

		if(!MEMBER){redirect("/");}

		$searchuser = htmlspecialchars(dbescape($_GET["s"]));
		
		if(!isset($searchuser)){redirect("/");}
		
		if($searchuser != ""){

$result = dbquery("SELECT * FROM bg_users WHERE user_id<>'".$userinfo["user_id"]."' AND user_nick LIKE '".$searchuser."%' ORDER BY user_nick ASC");

$rows1 = dbrows($result);

	if ($rows1 >= "1") {
        while($data = dbarray($result)){
		
		$unreadmessel = dbcount("(mes_id)", "bg_messages","mes_touserid='".$userinfo["user_id"]."' AND mes_userid='".$data["user_id"]."' AND mes_read='0'");
		
		$unreadmes = '<span class="gtipred" data-target="'.$data["user_id"].'">'.$unreadmessel.'</span>';
		
		echo '<a class="list-group-item userlistid" title="Online '.timeago($data["user_lastactivity"]).'" id="'.$data["user_id"].'">'.$data["user_nick"].'<span class="badge">'.$adminprava[$data["user_perm"]].'</span></a>';
						
		}
		
		}else{
		echo "Žiadni užívatelia.";
		}
		
		}else{
		
$resultchati = dbquery("SELECT DISTINCT(mes_userid) FROM bg_messages WHERE mes_touserid='".$userinfo["user_id"]."' AND mes_time>'".strtotime('-1 month')."' ORDER BY mes_time");

$rows1 = dbrows($resultchati);

	if ($rows1 >= "1") {
	
        while($data = dbarray($resultchati)){
		
		$unreadmessel = dbcount("(mes_id)", "bg_messages","mes_touserid='".$userinfo["user_id"]."' AND mes_userid='".$data["mes_userid"]."' AND mes_read='0'");
		
		$unreadmes = '<span class="badge" data-target="'.$data["mes_userid"].'">'.$unreadmessel.'</span>';

		echo '<a class="list-group-item userlistid" title="Online '.timeago(useractivity($data["mes_userid"])).'" id="'.$data["mes_userid"].'">'.username($data["mes_userid"],0).'<span class="fright">'.($unreadmessel >= 1 ? $unreadmes:"").'</span></a>';
		}
		}
        }
?>