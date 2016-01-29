<? require("../inc/settings.php"); $titlew=forumtopicname((int)$_GET['idt']);  require("../inc/header.php");

	if (!is_numeric($_GET['idt'])){ redirect("/"); }
	
		$resultf2 = dbquery("SELECT * FROM bg_forumtopic WHERE forumt_id='".$_GET['idt']."'");
		$dataf2 = dbarray($resultf2);
		
				$rowscat2 = dbrows($resultf2);
				if($rowscat2 != "1"){redirect("/");}
		
			if(isset($_GET["nt"]) && $_GET["nt"] != bezd($dataf2["forumt_name"])){
			redirect("/tema/".$dataf2["forumt_id"]."/".bezd($dataf2["forumt_name"]));
			}
			
			$countposts = dbcount("(post_id)", "bg_forumtopicpost"," post_topicid='".$dataf2["forumt_id"]."'");
			
			if(MEMBER){
                if($dataf2["forumt_locked"] == 0){
				if(forumtopicread($userinfo["user_id"],$dataf2["forumt_id"],$countposts) == false) {
					dbquery("INSERT INTO bg_forumtopicread(forumr_userid, forumr_tid, forumr_cpost) VALUES('".$userinfo["user_id"]."','".$dataf2["forumt_id"]."',".$countposts.")");
				}
                }
			}
			
			dbquery("UPDATE bg_forumtopic SET forumt_reads=forumt_reads+1 WHERE forumt_id='".$dataf2["forumt_id"]."'");

			$resultf3 = dbquery("SELECT * FROM bg_forumtopicpost WHERE post_topicid='".$_GET['idt']."'");
					
if (isset($_GET['strana'])){
$strana = (int)$_GET['strana'];

	if (!ctype_digit($_GET['strana'])){ redirect("/"); }

}else{
 $strana = 1;
}


	$limit = "15";
	$celkovy_pocet = dbrows($resultf3);
	$pocet_stran = ceil($celkovy_pocet/$limit);
	$pociatok = ($strana*$limit)-$limit;
	
	if(isset($_GET["poslednastrana"])){
		redirect("?strana=".$pocet_stran);
	}
	
		if($strana >$pocet_stran){redirect("?strana=1");} 

echo '

<ol class="breadcrumb forumpages">
  <li><a href="/forum">Fórum</a></li>
  <li>'.forumcat($dataf2["forumt_fid"],1).'</li>
  <li class="active">'.$dataf2["forumt_name"].'</li>

  <span class="pull-right"><li>'.(isset($_GET['strana']) && $_GET['strana']>=2 ? "Aktuálna strana: ".$_GET['strana']." <a href='?strana=1'>Prejsť na prvú stranu</a>":"").'</li></span>
</ol>
';

	$poslednyprispevok = dbarray(dbquery("SELECT * FROM bg_forumtopicpost WHERE post_topicid='".$_GET['idt']."' ORDER BY post_time DESC"));

	
	$resultf3 = dbquery("SELECT * FROM bg_forumtopicpost WHERE post_topicid='".$_GET['idt']."' ORDER BY post_time ASC LIMIT $pociatok, $limit");
	$resultf5 = dbquery("SELECT * FROM bg_forumtopicpost WHERE post_topicid='".$_GET['idt']."' ORDER BY post_time ASC LIMIT 0,1");
	$resultf5 = dbarray($resultf5);

		$doublepostuserid = "";
		$i = $pociatok-1;
		while($dataf3 = dbarray($resultf3)){
		$i++;
		$permuser = userpermis($dataf3["post_userid"]);

	if($doublepostuserid==$dataf3["post_userid"]){

echo '
<div class="row forumdoublepost" id="p'.$dataf3["post_id"].'">

	<div class="col-md-1 visible-md visible-lg"></div>

	<div class="col-md-11">
		<div class="forumpostbody">
			<div class="forumposthead">
       			<span class="pull-right time">'.timeago($dataf3["post_time"]).'</span>
    		</div>

			'.userdetect(smiley(bbcode(badwords(nl2br($dataf3["post_text"])),1))).'

			<div class="forumpostfooter">
				'.($dataf3["post_edittime"] ? 'Upravil/a '.username($dataf3["post_edituser"],1).' dňa '.date("j. n. Y H:i:s",$dataf3["post_edittime"]).'':'').''.(SADMIN ? " IP: ".$dataf3["post_ip"]:"").'
			</div>
		';
		if($dataf2["forumt_locked"] == 0){
			if(MEMBER && ($userinfo["user_id"] == $dataf3["post_userid"]) || userperm("5")){

			echo '<span class="postbuttons">';
			echo '[<a href="?upravit='.$dataf3["post_id"].'#upravit">Upraviť</a>] 
			'.($resultf5["post_id"] == $dataf3["post_id"] ? '':'[<a href="?zmazatf='.$dataf3["post_id"].'" onclick="return confirm(\'Príspevok už nebude možné vrátiť späť. Prajete ho vymazať?\');">Vymazať</a>]');
			echo '</span>';

			}
		}
		echo '
		</div>
	</div>


</div>
';
	$doublepostuserid = 0;
	}else{ 
echo '
<div class="row forumpost" id="p'.$dataf3["post_id"].'">

	<div class="col-md-1 visible-md visible-lg">
	'.(useravatar($dataf3["post_userid"]) != "avatar.png" ? '<img src="'.useravatar($dataf3["post_userid"]).'" alt="avatar" class="img-responsive img-circle" style="max-width:60px;"/>':'<img src="'.useravatar($dataf3["post_userid"]).'" alt="avatar" class="img-responsive img-circle" style="max-width:60px;" />').'
	</div>

	<div class="col-md-11">
		<div class="forumpostbody">
			<div class="forumposthead '.($resultf5["post_id"] == $dataf3["post_id"] ? "":"OSTATNEPOSTY").'">
       			<a href="#p'.$dataf3["post_id"].'" class="idf">#'.$i.'</a> <strong>'.username($dataf3["post_userid"],1).'</strong> — '.($permuser >= 2 ? $adminprava[$permuser]:userrank($dataf3["post_userid"],1)).'<span class="pull-right time">'.timeago($dataf3["post_time"]).'</span>
    		</div>

			'.userdetect(smiley(bbcode(badwords(nl2br($dataf3["post_text"])),1))).'

			<div class="forumpostfooter">
				'.($dataf3["post_edittime"] ? 'Upravil/a '.username($dataf3["post_edituser"],1).' dňa '.date("j. n. Y H:i:s",$dataf3["post_edittime"]).'':'').''.(SADMIN ? " IP: ".$dataf3["post_ip"]:"").'
			</div>
		';
		if($dataf2["forumt_locked"] == 0){
			echo '<span class="postbuttons">';
			if(MEMBER && ($userinfo["user_id"] == $dataf3["post_userid"]) || userperm("5")){

			echo '[<a href="?upravit='.$dataf3["post_id"].'#upravit">Upraviť</a>] 
			'.($resultf5["post_id"] == $dataf3["post_id"] ? '':'[<a href="?zmazatf='.$dataf3["post_id"].'" onclick="return confirm(\'Príspevok už nebude možné vrátiť späť. Prajete ho vymazať?\');">Vymazať</a>]');
			}
			echo '</span>';
		}
		echo '
		</div>
	</div>


</div>

	'.($resultf5["post_id"] == $dataf3["post_id"] ? '
	<div class="forumpostcrosslinebox"><div class="forumpostcrossline"></div>
	<a class="btn btn-default">'.(dbcount("(post_id)", "bg_forumtopicpost"," post_topicid='".(int)$_GET["idt"]."'")-1).' odpovedí</a>
	'.($celkovy_pocet > "1" ? '<a href="'.($pocet_stran > 1 ? "?strana=".$pocet_stran:"").'#p'.$poslednyprispevok["post_id"].'" class="btn btn-success"><i class="fa fa-chevron-down"></i> posledný príspevok</a>':'').'

	'.(userperm("5") ? '<a class="btn btn-info pull-right" data-toggle="modal" data-target="#modforummodal">Moderatorské menu</a>':'').'
	</div>':"").'

';
	$doublepostuserid = $dataf3["post_userid"];
	}
		
		}
		
    pagination($celkovy_pocet,$limit,$pocet_stran,$strana);

	if(MEMBER && $dataf2["forumt_locked"] == 0){
	
			if(isset($_GET["zmazatf"])){
			
						if (!ctype_digit($_GET['zmazatf'])){ redirect("/"); }

				if(!userperm("5")){
					if(dbcount("(post_id)", "bg_forumtopicpost","post_id='".$_GET['zmazatf']."' AND post_userid='".$userinfo["user_id"]."'") != 1){redirect("/");}
				}
				
				if($resultf5["post_id"] != $dataf3["post_id"]){
					dbquery("DELETE FROM bg_forumtopicpost WHERE post_id='".$_GET['zmazatf']."'");
					redirect("/tema/".$dataf2["forumt_id"]."/".bezd($dataf2["forumt_name"]));
				}
				
			}
	
		if(isset($_GET["upravit"])){
	
			if (!ctype_digit($_GET['upravit'])){ redirect("/"); }
				
	$resultf8 = dbquery("SELECT * FROM bg_forumtopicpost WHERE post_id='".$_GET['upravit']."'");
			$dataf8 = dbarray($resultf8);
			
			if(dbrows($resultf8) != 1){redirect("/");}
			
			if(!userperm("5")){
				if($dataf8["post_userid"] != $userinfo["user_id"]){
					redirect("/tema/".$dataf2["forumt_id"]."/".bezd($dataf2["forumt_name"]));
				}
			}
			
		if(isset($_POST["editreply"]) && $_POST["forumedit"] != ""){
		
				$text = trim(htmlspecialchars($_POST["forumedit"], ENT_QUOTES, "UTF-8"));
				$topicname = htmlspecialchars($_POST["topicname"]);
				if($resultf5["post_id"] == $dataf8["post_id"]){
				dbquery("UPDATE bg_forumtopic SET forumt_newpost='".time()."' WHERE forumt_id='".$dataf2["forumt_id"]."'");
				}
			dbquery("UPDATE bg_forumtopicpost SET post_text='".$text."',post_edittime='".time()."',post_edituser='".$userinfo["user_id"]."' WHERE post_id='".$dataf8["post_id"]."'");
			
			if($resultf5["post_id"] == $dataf8["post_id"]){
				if(isset($_POST["topicname"]) && $_POST["topicname"] != ""){
					dbquery("UPDATE bg_forumtopic SET forumt_name='".$topicname."' WHERE forumt_id='".$dataf2["forumt_id"]."'");
				}
			}
				redirect("/tema/".$dataf2["forumt_id"]."/".bezd($dataf2["forumt_name"])."#p".$dataf8["post_id"]);
		}
			
echo '<div class="list-group" id="upravit">
<form name="form" action="" method="POST">
<div class="list-group-item list-group-item-warning">Upraviť '.($resultf5["post_id"] == $dataf8["post_id"] ? "hlavný":"").' príspevok #'.$_GET['upravit'].'</div>
'.($resultf5["post_id"] == $dataf8["post_id"] ? '<div class="list-group-item"><input name="topicname" class="form-control input-sm" value="'.$dataf2["forumt_name"].'" type="text"></div>':'').'
<textarea name="forumedit" class="list-group-item" rows="7" placeholder="" style="width:100%;padding:10px;font-size:12px;resize:vertical">'.$dataf8["post_text"].'</textarea>
<div class="list-group-item">
	<span class="bbcody">
			<a href="javascript:addText(\'forumedit\', \'[b]\', \'[/b]\', \'form\');" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top" title="[b]tučný[/b]"><i class="fa fa-bold"></i></a>
			<a href="javascript:addText(\'forumedit\', \'[i]\', \'[/i]\', \'form\');" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top" title="[i]kurzíva[/i]"><i class="fa fa-italic"></i></a>
			<a href="javascript:addText(\'forumedit\', \'[u]\', \'[/u]\', \'form\');" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top" title="[u]podčiarknuté[/u]"><i class="fa fa-underline"></i></a>
			<a href="javascript:addText(\'forumedit\', \'[url]\', \'[/url]\', \'form\');" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top" title="[url]odkaz[/url]"><i class="fa fa-link"></i></a>
			<a href="javascript:addText(\'forumedit\', \'[code]\', \'[/code]\', \'form\');" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top" title="[code]kód[/code]"><i class="fa fa-code"></i></a>
			<a href="javascript:addText(\'forumedit\', \'[img]\', \'[/img]\', \'form\');" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top" title="[img]obrázok[/img]"><i class="fa fa-file-o"></i></a>
	</span>
<input name="editreply" class="btn btn-success btn-sm pull-right" value="Odoslať" type="submit">
<div class="clearfix"></div>
</div>
</form>
</div>
';
			
	
		}else{

	if(isset($_POST["addreply"]) && $_POST["forumreply"] != ""){
		if(dbcount("(post_id)", "bg_forumtopicpost","post_userid='".$userinfo["user_id"]."' AND post_time > ".strtotime("-10 seconds")."") == 0) {
		
			$text = trim(htmlspecialchars($_POST["forumreply"], ENT_QUOTES, "UTF-8"));
			
			dbquery("DELETE FROM bg_forumtopicread WHERE forumr_tid='".$dataf2["forumt_id"]."'");
			dbquery("UPDATE bg_forumtopic SET forumt_newpost='".time()."' WHERE forumt_id='".$dataf2["forumt_id"]."'");
			dbquery("INSERT INTO bg_forumtopicpost(post_userid, post_text, post_time, post_topicid, post_ip)
                               VALUES('".$userinfo["user_id"]."','".$text."','".time()."','".$dataf2["forumt_id"]."','".$_SERVER["REMOTE_ADDR"]."')");
			$idpost = mysqli_insert_id($db_connect);
			
			$countposts2 = dbcount("(post_id)", "bg_forumtopicpost"," post_topicid='".$dataf2["forumt_id"]."'");
			$pocetstrann = ceil($countposts2/$limit);
			
			redirect("/tema/".$dataf2["forumt_id"]."/".bezd($dataf2["forumt_name"])."?strana=".$pocetstrann."#p".$idpost);

		}
	}
	
if(dbcount("(post_id)", "bg_forumtopicpost","post_userid='".$userinfo["user_id"]."' AND post_time > ".strtotime("-10 seconds")."")) {
	notification("Tvoja odpoveď bola pridaná");
	notification("O 10 sekúnd môžeš poslať daľšiu odpoveď.","info");
}

echo '<div class="list-group">
<form name="form" action="" method="POST">
<div class="list-group-item list-group-item-warning">Odpovedať</div>
<textarea name="forumreply" class="list-group-item" rows="7" placeholder="" style="width:100%;padding:10px;font-size:12px;resize:vertical"></textarea>
<div class="list-group-item">
	<span class="bbcody">
			<a href="javascript:addText(\'forumreply\', \'[b]\', \'[/b]\', \'form\');" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top" title="[b]tučný[/b]"><i class="fa fa-bold"></i></a>
			<a href="javascript:addText(\'forumreply\', \'[i]\', \'[/i]\', \'form\');" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top" title="[i]kurzíva[/i]"><i class="fa fa-italic"></i></a>
			<a href="javascript:addText(\'forumreply\', \'[u]\', \'[/u]\', \'form\');" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top" title="[u]podčiarknuté[/u]"><i class="fa fa-underline"></i></a>
			<a href="javascript:addText(\'forumreply\', \'[url]\', \'[/url]\', \'form\');" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top" title="[url]odkaz[/url]"><i class="fa fa-link"></i></a>
			<a href="javascript:addText(\'forumreply\', \'[code]\', \'[/code]\', \'form\');" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top" title="[code]kód[/code]"><i class="fa fa-code"></i></a>
			<a href="javascript:addText(\'forumreply\', \'[img]\', \'[/img]\', \'form\');" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top" title="[img]obrázok[/img]"><i class="fa fa-file-o"></i></a>
	</span>
    <input name="addreply" class="btn btn-success btn-sm pull-right" value="Odoslať" type="submit">
    <div class="clearfix"></div>
</div>
</form>
</div>
';
		}
	} else {

		if(!MEMBER || $dataf2["forumt_locked"] == 0){
		echo '<div class="alert alert-info" role="alert">Ak chceš odpovedať v tejto téme tak sa musíš najprv prihlásiť alebo zaregistrovať.</div>';
		}else{
		echo '<div class="alert alert-info" role="alert">Táto téma je zamknutá. Pre jej odomknutie kontaktuj moderátora. -'.username($dataf2["forumt_lockuserid"],1).'</div>';
		}

	}


if(userperm("5")){

	if(isset($_POST["editmod"])){
		if($_POST["modset"] == 1){
            dbquery("DELETE FROM bg_forumtopicread WHERE forumr_tid='".$dataf2["forumt_id"]."'");
			dbquery("UPDATE bg_forumtopic SET forumt_locked='1',forumt_lockuserid='".$userinfo["user_id"]."' WHERE forumt_id='".$dataf2["forumt_id"]."'");
			redirect("/tema/".$dataf2["forumt_id"]."/".bezd($dataf2["forumt_name"]));
		}
		if($_POST["modset"] == 2){
			dbquery("UPDATE bg_forumtopic SET forumt_locked='0' WHERE forumt_id='".$dataf2["forumt_id"]."'");
			redirect("/tema/".$dataf2["forumt_id"]."/".bezd($dataf2["forumt_name"]));
		}
		if($_POST["modset"] == 3){
			dbquery("DELETE FROM bg_forumtopicpost WHERE post_topicid='".$dataf2["forumt_id"]."'");
			dbquery("DELETE FROM bg_forumtopicread WHERE forumr_tid='".$dataf2["forumt_id"]."'");
			dbquery("DELETE FROM bg_forumtopic WHERE forumt_id='".$dataf2["forumt_id"]."'");
			redirect("/forum");
		}
		if($_POST["modset"] == 4){
			dbquery("UPDATE bg_forumtopic SET forumt_fav='1' WHERE forumt_id='".$dataf2["forumt_id"]."'");
			redirect("/forum");
		}
		if($_POST["modset"] == 5){
			dbquery("UPDATE bg_forumtopic SET forumt_fav='0' WHERE forumt_id='".$dataf2["forumt_id"]."'");
			redirect("/forum");
		}
		if($_POST["modset"] == 6){
			dbquery("UPDATE bg_forumtopic SET forumt_fid='".$_POST["changeforum"]."' WHERE forumt_id='".$dataf2["forumt_id"]."'");
			redirect("/tema/".$dataf2["forumt_id"]."/".bezd($dataf2["forumt_name"]));
		}
	}
echo '

<div class="modal fade" id="modforummodal" tabindex="-1" role="dialog" aria-hidden="true" aria-labelledby="modforummodal">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
    	<form action="" method="POST">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="modforummodal">Moderátorské menu</h4>
      </div>
      <div class="modal-body">

<select name="modset" id="select1" class="form-control">
<option value="1">Zamknúť tému</option>
<option value="2">Odomknúť tému</option>
<option value="3">Odstrániť tému</option>
<option value="4">Obľúbiť tému</option>
<option value="5">Neobľúbiť tému</option>
<option value="6">Presunúť tému</option>
</select>
</div>
<div class="list-group-item list-group-item-warning" id="hide1modf" style="display:none;">
<select name="changeforum" class="form-control">';

		$resultf = dbquery("SELECT * FROM bg_forum ORDER BY forum_name");
		
		while($dataf = dbarray($resultf)){
		echo '<option value="'.$dataf["forum_id"].'">'.$dataf["forum_name"].'</option>';
		}
		
echo '</select>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Zavrieť</button>
        <input name="editmod" class="btn btn-success" value="Vykonať" type="submit">
      </div>
      	</form>
    </div>
  </div>
</div>
';

}

		
require("../inc/footer.php"); ?>