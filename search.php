<?php require("inc/settings.php"); $titlew="Vyhľadávanie"; require("inc/header.php");

if(isset($_POST["search"])){
redirect("?i=".htmlspecialchars(urlencode($_POST["search"])));
}

echo '
      <div class="bigtitlecenter">
        <div class="bigtitle">
            <h5>Vyhľadávanie</h5>
            Nevieš niečo nájsť? Tak neváhaj použiť naše vyhľadávanie, ktoré ti ušetrí čas.
            <br><br>
<form action="" method="post">
    <div class="input-group">
      <input type="text" class="form-control" name="search" value="'.(isset($_GET["i"]) ? $_GET["i"]:"").'">
      <span class="input-group-btn">
        <button class="btn btn-success" type="submit">Vyhladať</button>
      </span>
    </div>
</form>
        </div>
      </div>
';

if(isset($_GET["i"]) && $_GET["i"] != ""){

		$search = mysqli_real_escape_string($db_connect,htmlspecialchars($_GET["i"]));
    
if(strlen($search) >= 3){

$result333 = dbquery("SELECT * FROM bg_articles WHERE article_suggestion='0' AND (article_txt LIKE '%".$search."%' OR article_name LIKE '%".$search."%' OR article_target LIKE '%".$search."%')");
$rows2 = dbrows($result333);
		if($rows2 >= "1"){

            echo '<div class="list-group">
            <a class="list-group-item active">Nájdené výsledky v článkoch</a>
            ';
			while($data66 = dbarray($result333)){
                echo '
<div class="list-group-item">
  <span class="label label-warning">'.articlecat($data66["article_cat"]).'</span>
<a href="/clanok/'.$data66["article_id"].'/'.bezd($data66["article_name"]).'">'.$data66["article_name"].'</a>
<span class="badge">'.timeago($data66["article_date"]).'</span>
</div>
                ';
			}
            echo '</div>';

		}else{
			echo '<div class="alert alert-info">Žiadne výsledky v článkoch.</div>';
		}
    
$result33 = dbquery("SELECT post_topicid,post_text,post_time FROM bg_forumtopicpost WHERE post_text LIKE '%".$search."%' GROUP BY post_topicid");
$rows2 = dbrows($result33);
		if($rows2 >= "1"){

            echo '<div class="list-group">
            <a class="list-group-item active">Nájdené výsledky vo fóre</a>
            ';
			while($data66 = dbarray($result33)){
                echo '
<div class="list-group-item">
  <span class="label label-warning">'.forumcat(forumcatid($data66["post_topicid"])).'</span>
<a href="/tema/'.$data66["post_topicid"].'/'.bezd(forumtopicname($data66["post_topicid"])).'">'.forumtopicname($data66["post_topicid"]).'</a>
<span class="badge">'.timeago($data66["post_time"]).'</span>
</div>
                ';
			}
            echo '</div>';

		}else{
			echo '<div class="alert alert-info">Žiadne výsledky v fóre.</div>';
		}


}else{
echo '<div class="alert alert-info">Výsledok musí obsahovať viacej než 3 písmená</div>';
}
    
}

require("inc/footer.php"); ?>