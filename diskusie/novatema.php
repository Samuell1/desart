<? $titlew="Vytvoriť novú tému"; require("../inc/settings.php"); require("../inc/header.php");

	if(!MEMBER){redirect("/");}
	if(dbcount("(forum_id)", "bg_forum","forum_id='".$_GET["forumfid"]."'") == 0){redirect("/");}
	
	if($_GET["forumfid"] == 8){redirect("/");}

echo '
      <div class="bigtitlecenter">
        <div class="bigtitle">
            <h5>Vytvoriť novú tému</h5>
            Pred vytvorením témy si prečítajte <a href="/stranky/pravidla">pravidlá</a>
        </div>
      </div>
';
	
	if(isset($_POST["new"]) && $_POST["name"] != "" && $_POST["forumnew"] != ""){

	
		if(dbcount("(forumt_id)", "bg_forumtopic","forumt_userid='".$userinfo["user_id"]."' AND forumt_time > ".strtotime("-5 minutes")."") == 0) {
	
			$name = mysql_real_escape_string(htmlspecialchars($_POST["name"]));
			$text = trim(htmlspecialchars($_POST["forumnew"], ENT_QUOTES, "UTF-8"));
	
		if(dbcount("(forumt_id)", "bg_forumtopic"," forumt_name='".$name."'") != 1){
	
			dbquery("INSERT INTO bg_forumtopic(forumt_name, forumt_userid, forumt_time, forumt_fid, forumt_newpost)
                               VALUES('".$name."','".$userinfo["user_id"]."','".time()."','".$_GET["forumfid"]."','".time()."')");
			$idforumt = mysqli_insert_id();
			dbquery("INSERT INTO bg_forumtopicpost(post_userid, post_text, post_time, post_topicid, post_ip)
                               VALUES('".$userinfo["user_id"]."','".$text."','".time()."','".$idforumt."','".$_SERVER["REMOTE_ADDR"]."')");
			$idpost = mysqli_insert_id();
			redirect("/tema/".$idforumt."/".bezd($name)."#p".$idpost);
	
		}else{echo '<div class="alert alert-danger" role="alert">Použi iný názov tento názov už existuje.</div>';}
		
		}else{
		echo '<div class="alert alert-danger" role="alert">Pre vytvorenie novej témy musíš počkať 5 minút ...</div>';
		}

	}
	

if(isset($_POST["view"])){

		echo '
<div class="forumpost list-group" id="p">
    
    <div class="list-group-item" style="background:#5cb85c;border-color:#5cb85c;">Téma: '.htmlspecialchars($_POST["name"]).'</div>

	<div class="list-group-item">
        <a href="#p0" class="idf">#0</a><strong>'.username($userinfo["user_id"],1).'</strong> - '.$adminprava[$userinfo["user_perm"]].'<span class="pull-right">'.date("j. n. Y H:i:s",$dataf3["post_time"]).'</span>
    </div>
    
<div class="list-group-item">
    
    <div class="row">
    
        <div class="col-md-2 visible-md visible-lg">
	       '.(useravatar($userinfo["user_id"]) != "/file/avatars/avatar.png" ? '<img src="'.useravatar($userinfo["user_id"]).'" alt="avatar" class="img-responsive"/>':'<img src="'.useravatar($userinfo["user_id"]).'" alt="avatar" class="img-responsive" />').'
        </div>
        <div class="col-md-10">
	       '.userdetect(smiley(bbcode(badwords(nl2br($_POST["forumnew"]))))).'
        </div>
    
    </div>
</div>
    <div class="list-group-item"></div>
</div>
		';
}

echo '<div class="list-group">
<form name="form" action="" method="POST">
    <div class="list-group-item list-group-item-warning">Vytvoriť novú tému v fóre: '.forumcat($_GET["forumfid"]).'</div>
    <div class="list-group-item">
    <input name="name" class="form-control" style="width:100%" value="'.(isset($_POST["name"]) ? $_POST["name"]:"").'" placeholder="zadajte názov témy..." type="text">
    </div>
<textarea name="forumnew" class="list-group-item" rows="15" placeholder="" style="width:100%;padding:10px;font-size:12px;resize:vertical">'.(isset($_POST["forumnew"]) ? $_POST["forumnew"]:"").'</textarea>
<div class="list-group-item">
	<span class="bbcody">
			<a href="javascript:addText(\'forumnew\', \'[b]\', \'[/b]\', \'form\');" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top" title="[b]tučný[/b]"><i class="fa fa-bold"></i></a>
			<a href="javascript:addText(\'forumnew\', \'[i]\', \'[/i]\', \'form\');" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top" title="[i]kurzíva[/i]"><i class="fa fa-italic"></i></a>
			<a href="javascript:addText(\'forumnew\', \'[u]\', \'[/u]\', \'form\');" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top" title="[u]podčiarknuté[/u]"><i class="fa fa-underline"></i></a>
			<a href="javascript:addText(\'forumnew\', \'[url]\', \'[/url]\', \'form\');" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top" title="[url]odkaz[/url]"><i class="fa fa-link"></i></a>
			<a href="javascript:addText(\'forumnew\', \'[code]\', \'[/code]\', \'form\');" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top" title="[code]kód[/code]"><i class="fa fa-code"></i></a>
			<a href="javascript:addText(\'forumnew\', \'[img]\', \'[/img]\', \'form\');" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top" title="[img]obrázok[/img]"><i class="fa fa-file-o"></i></a>
	</span>
    <span class="pull-right">
    <input name="view" class="btn btn-warning btn-sm" value="Náhľad" type="submit"> 
    <input name="new" class="btn btn-success btn-sm" value="Odoslať" type="submit">
    </span>
    <div class="clearfix"></div>
</div>
</form>
</div>
';

require("../inc/footer.php"); ?>