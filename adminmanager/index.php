<?php $titlew="Úvod"; require("../inc/settings.php"); require("inc/header.php"); ?>


<div class="panel panel-default">
  <div class="panel-heading">Štatistika registrovaných užívateľov, pridaných článkov a komentárov (mesačné)</div>
    <div id="chart_div3"></div>
</div>
<div class="panel panel-default">
  <div class="panel-heading">Štatistika fóra (za posledných 7 dní)</div>
    <div id="chart_div4"></div>
</div>

<div class="row">
    <div class="col-md-6">
<div class="panel panel-default">
  <div class="panel-heading">Prehliadače používané užívateľmi</div>
    <div id="chart_div"></div>
</div>
<div class="panel panel-default">
  <div class="panel-heading">Moje štatistiky článkov</div>
    <?
    $clcountinguser = dbarray(dbquery("SELECT SUM(article_reads) AS totalreads FROM bg_articles WHERE article_author='".$userinfo["user_id"]."'"));
    $clcountinguser2 = dbrows(dbquery("SELECT bg_articles.article_author,bg_articles.article_id,bg_comments.comment_pageid,bg_comments.comment_delete FROM bg_articles LEFT JOIN bg_comments ON bg_articles.article_id=bg_comments.comment_pageid WHERE bg_articles.article_author='".$userinfo["user_id"]."' AND bg_comments.comment_delete='0'"));
    ?>
  <ul class="list-group">
    <li class="list-group-item">Počet článkov <span class="badge"><? echo dbcount("(article_id)", "bg_articles","article_author='".$userinfo["user_id"]."'"); ?></span></li>
    <li class="list-group-item">Počet pozretí článkov <span class="badge"><? echo $clcountinguser["totalreads"]; ?></span></li>
    <li class="list-group-item">Počet komentárov k článkom <span class="badge"><? echo $clcountinguser2; ?></span></li>
  </ul>
</div>

<div class="panel panel-default">
  <div class="panel-heading">Najnovší užívateľia</div>
	<table class="table table-striped">
<?php
$result = dbquery("SELECT * FROM bg_users ORDER BY user_id DESC LIMIT 0,10");

$rows1 = dbrows($result);

	if ($rows1 >= "1") {
        while($data = dbarray($result)){

		echo '<tr>
		<td>'.(useravatar($data["user_id"]) != "/file/avatars/avatar.png" ? '<img src="'.useravatar($data["user_id"]).'" alt="" class="img-circle" width="20">':'<img src="'.useravatar($data["user_id"]).'" alt="" class="img-circle" width="20">').'</td>
		<td>'.username($data["user_id"],1).' ('.$data["user_id"].')</td>
		<td>'.date("j.n. Y H:i:s",$data["user_datereg"]).'</td>
		</tr>';
		}
		
		}else{
		echo "Žiadní užívatelia.<br/>";
		}
?>
	</table>
</div>

    </div>
    <div class="col-md-6">

<div class="panel panel-default">
  <div class="panel-heading">OS používané užívateľmi</div>
    <div id="chart_div2"></div>
</div>

<div class="panel panel-default">

  <div class="panel-heading">Posledné komentáre</div>
  <ul class="list-group">
<?
$result = dbquery("SELECT * FROM bg_comments WHERE comment_delete='0' ORDER BY comment_id DESC LIMIT 0,8");

$rows1 = dbrows($result);

	if ($rows1 >= "1") {
        while($data = dbarray($result)){

			$type = articleurl($data["comment_pageid"],trimlink(articlename($data["comment_pageid"]),22),1,1);
            $tname = articlename($data["comment_pageid"]);
	 
		echo '<li class="list-group-item">
'.(useravatar($data["comment_userid"]) != "/file/avatars/avatar.png" ? '<img src="'.useravatar($data["comment_userid"]).'" alt="" class="img-circle pull-left" style="width:40px;margin-right:5px">':'<img src="'.useravatar($data["comment_userid"]).'" alt="" class="img-circle pull-left" style="width:40px;margin-right:5px">').'
        <span class="badge">'.timeago($data["comment_time"]).'</span>
'.username($data["comment_userid"],1).' > <a href="'.$type.'" style="font-size:9px">'.trimlink($tname,32).'</a><br>
<small>'.trimlink(bbcoderemove(badwords($data["comment_text"])),100).'</small><br>
		</li>';
	 
		}
		
		}else{
		echo "Žiadne komentáre k článkom.";
		}

?>
	</ul>
</div>

    </div>
</div>

<div class="panel panel-default">

  <div class="panel-heading">Najnovšie témy (nezamknuté)</div>
  <ul class="list-group">
<?
$result = dbquery("SELECT * FROM bg_forumtopic WHERE forumt_locked='0' ORDER BY forumt_time DESC LIMIT 0,8");

$rows1 = dbrows($result);

	if ($rows1 >= "1") {
        while($data = dbarray($result)){
	 
		echo '<li class="list-group-item">
'.(useravatar($data["forumt_userid"]) != "/file/avatars/avatar.png" ? '<img src="'.useravatar($data["forumt_userid"]).'" alt="" class="img-circle pull-left" style="width:20px;margin-right:5px">':'<img src="'.useravatar($data["forumt_userid"]).'" alt="" class="img-circle pull-left" style="width:20px;margin-right:5px">').'
        <span class="badge">'.timeago($data["forumt_newpost"]).'</span>
<a href="/tema/'.$data["forumt_id"].'/'.bezd($data["forumt_name"]).'" title="'.$data["forumt_name"].'">'.$data["forumt_name"].'</a>
		</li>';
	 
		}
		
		}else{
		echo "Žiadne témy.";
		}

?>
	</ul>
</div>
<?php require("inc/footer.php"); ?>