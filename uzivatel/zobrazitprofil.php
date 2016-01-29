<?php

if(!isset( $_SERVER['HTTP_X_REQUESTED_WITH']) && !($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' )){die("");}

include("../inc/settings.php");

	$puserr = dbquery("SELECT * FROM bg_users WHERE user_id='".(int)$_GET["uid"]."'");
	$puser = dbarray($puserr);
	
	$validuser = dbrows($puserr);
	if($validuser == 0){die("Error.");}

    


echo '
<div class="userprofilhead" style="background:#81c46b;border-bottom:5px solid #74b35f;padding:25px 30px;color:#fff;">
    <div class="row">
        <div class="col-md-2 hidden-xs">
        '.(useravatar($puser["user_id"]) != "/file/avatars/avatar.png" ? '<img src="'.useravatar($puser["user_id"]).'" alt="avatar" class="img-responsive img-circle"/>':'<img src="'.useravatar($puser["user_id"]).'" alt="avatar" class="img-responsive img-circle"/>').'
        
        </div>
        <div class="col-md-5">
        <h3>'.$puser["user_nick"].'</h3>
        <small>'.$adminprava[$puser["user_perm"]].'</small>
        </div>
        <div class="col-md-5 text-right" style="padding-top:10px;">
';        


if(userrank($puser["user_id"],0,1) <= 5){
$res = (($ranknum[userrank($puser["user_id"],0,1)] - userrank($puser["user_id"])) / $ranknum[userrank($puser["user_id"],0,1)]) * 100;

echo '
<h4>Level: <strong>'.userrank($puser["user_id"],1).'</strong></h4>

<div class="progress" style="background:#6ab455;box-shadow:none;padding:4px;border-radius: 16px;">
  <div class="progress-bar progress-bar-success" style="width: '.(100 - $res).'%;background:#fff;border-radius: 16px;">
  </div>
  <div style="color:#fff;margin-right:10px;margin-top:-1px;font-size:10px;">
  '.userrank($puser["user_id"]).' / '.$ranknum[userrank($puser["user_id"],0,1)].'
  </div>
</div>
<small>Následujúci level: <strong>'.$ranklevel[userrank($puser["user_id"],0,1)+1].'</strong></small>
 
';
}else{
echo '<h4>Level: '.userrank($puser["user_id"],1).'</h4>';
}
echo'
        </div>
    </div>
</div>


<div class="modal-body">

<h4>Informácie o užívateľovi</h4>
';

echo '
<div class="row">
    <div class="col-md-6">
<table class="table" style="font-size:11px;color:#888">
        <tr>
          <td>Posledná aktivita:</td>
          <td>'.timeago($puser["user_lastactivity"]).'</td>
        </tr>
        <tr>
          <td>Dátum registrácie:</td>
          <td>'.date("j.n. Y",$puser["user_datereg"]).'</td>
        </tr>
        <tr>
          <td>E-mail:</td>
          <td>'.(!$puser["user_emailhide"] && MEMBER ? $puser["user_email"] : "Skrytý").'</td>
        </tr>
        <tr>
          <td>Skype:</td>
          <td>'.($puser["user_skype"] ? $puser["user_skype"] : "Nevyplnené").'</td>
        </tr>
        <tr>
          <td>ICQ:</td>
          <td>'.($puser["user_icq"] ? $puser["user_icq"] : "Nevyplnené").'</td>
        </tr>
</table>
    </div>
    <div class="col-md-6">
<table class="table" style="font-size:11px;color:#888">
        <tr>
          <td>Príspevkov vo fóre:</td>
          <td>'.dbcount("(post_id)", "bg_forumtopicpost","post_userid='".$puser["user_id"]."'").'</td>
        </tr>
        <tr>
          <td>Komentárov:</td>
          <td>'.dbcount("(comment_id)", "bg_comments","comment_delete='0' AND comment_userid='".$puser["user_id"]."'").'</td>
        </tr>
        <tr>
          <td>Článkov:</td>
          <td>'.dbcount("(article_id)", "bg_articles","article_suggestion='0' AND article_author='".$puser["user_id"]."'").'</td>
        </tr>
        <tr>
          <td>DeviantArt:</td>
          <td>'.($puser["user_deviantart"] ? urlvalid($puser["user_deviantart"].".deviantart.com",$puser["user_deviantart"]) : "Nevyplnené").'</td>
        </tr>
        <tr>
          <td>Webová stránka:</td>
          <td>'.($puser["user_web"] ? urlvalid($puser["user_web"],"Zobraziť") : "Nevyplnené").'</td>
        </tr>
</table>
    </div>
</div>
';

    $result_article = dbquery("SELECT * FROM bg_articles WHERE article_author='".$puser["user_id"]."' AND article_suggestion='0' ORDER BY article_date DESC LIMIT 0,12");
    $result_comments = dbquery("SELECT * FROM bg_comments WHERE comment_userid='".$puser["user_id"]."' AND comment_delete='0' ORDER BY comment_time DESC LIMIT 0,12");
    $result_forumt = dbquery("SELECT * FROM bg_forumtopic WHERE forumt_userid='".$puser["user_id"]."' ORDER BY forumt_time DESC LIMIT 0,12");

    $rows_article = dbrows($result_article);
    $rows_comments = dbrows($result_comments);
    $rows_forumt = dbrows($result_forumt);

echo '

<ul class="nav nav-tabs" role="tablist" id="myTab">
  '.($rows_article>0?'<li><a href="#article" role="tab" data-toggle="tab">Články</a></li>':'').'
  '.($rows_comments>0?'<li class="active"><a href="#comments" role="tab" data-toggle="tab">Komentáre</a></li>':'').'
  '.($rows_forumt>0?'<li><a href="#forum" role="tab" data-toggle="tab">Témy vo fóre</a></li>':'').'
  
</ul>

<div class="tab-content">
  <div class="tab-pane fade" id="article">

    <div class="list-group" style="margin-top:15px">
';



		while($datap = dbarray($result_article)){

            echo '<a href="/clanok/'.$datap["article_id"].'/'.bezd($datap["article_name"]).'" class="list-group-item">'.$datap["article_name"].' <span class="badge">'.timeago($datap["article_date"]).'</span></a>';
            
		}
echo '
    </div>
    
  </div>
  <div class="tab-pane fade in active" id="comments">
  
    <div class="list-group" style="margin-top:15px;">
';



		while($datap = dbarray($result_comments)){
            
		if($datap["comment_type"] == "A"){
			$type = articleurl($datap["comment_pageid"],trimlink(articlename($datap["comment_pageid"]),22),1,1);
            $name = articlename($datap["comment_pageid"]);
		}else if($datap["comment_type"] == "P"){
			$type = projekturl($datap["comment_pageid"],trimlink(projektname($datap["comment_pageid"]),22),1,1);
            $name = projektname($datap["comment_pageid"]);
		}

            echo '<a href="'.$type.'" class="list-group-item">'.$name.'<br/><small>'.trimlink(bbcoderemove(badwords($datap["comment_text"])),70).'</small> <span class="badge">'.timeago($datap["comment_time"]).'</span></a>';
            
		}
echo '
    </div>
    
  </div>
  <div class="tab-pane fade" id="forum">
  
    <div class="list-group" style="margin-top:15px;">
';


		while($datap = dbarray($result_forumt)){

            echo '<a href="/tema/'.$datap["forumt_id"].'/'.bezd($datap["forumt_name"]).'" class="list-group-item">'.$datap["forumt_name"].' <span class="badge">'.timeago($datap["forumt_time"]).'</span></a>';
            
		}
echo '
    </div>
    
  </div>
</div>
</div>
';