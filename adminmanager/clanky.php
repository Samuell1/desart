<? $titlew="Zoznam článkov"; require("../inc/settings.php"); require("inc/header.php"); ?>

<? if(!userperm("4") && !userperm("2")){ redirect("/"); } ?>

<div class="panel panel-default">
    <div class="panel-heading">Zoznam článkov <span class="pull-right"><a href="?order=mojeclanky" class="btn btn-default btn-xs">Moje články</a></span></div>
    <table class="table table-striped table-hover" align="center" cellpadding="0" cellspacing="1" width="100%">
    
    <tr>
    <td width="5%"><strong>ID</strong></td>
    <td width="45%"><strong>Názov článku</strong></td>
    <td><strong>Dátum</strong></td>
    <td><strong>Autor</strong></td>
    <td width="25%"></td>
    </tr>
<?


if(ADMIN){
	if(isset($_GET["articledel"])){
		dbquery("DELETE FROM bg_articles WHERE article_id='".$_GET["articledel"]."'");
		dbquery("UPDATE bg_comments SET comment_delete='1' WHERE comment_pageid='".$_GET["articledel"]."' AND comment_type='A'");
		redirect("clanky");
	}
	if(isset($_GET["articlepub"])){
		dbquery("UPDATE bg_articles SET article_suggestion='0' WHERE article_id='".$_GET["articlepub"]."'");
		redirect("clanky");
	}
}

if(isset($_GET["order"]) && $_GET["order"] == "mojeclanky"){
	$order = "WHERE article_author='".$userinfo["user_id"]."'";
}else{$order = "";}	

$result = dbquery("SELECT * FROM bg_articles $order");
$rows1 = dbrows($result);

	if ($rows1 >= "1") {
	
if (isset($_GET['strana'])){
 $strana = (int)$_GET['strana'];

	if (!ctype_digit($_GET['strana'])){ redirect("/"); }

}else{
 $strana = 1;
}


	$limit = "40";
	$celkovy_pocet = $rows1;
	$pocet_stran = ceil($celkovy_pocet/$limit);
	$pociatok = ($strana*$limit)-$limit;
	
$result = dbquery("SELECT * FROM bg_articles $order ORDER BY article_suggestion='0',article_suggestion='2' DESC, article_id DESC LIMIT $pociatok, $limit");


		if($strana >$pocet_stran){redirect("/");} 
	
        while($data = dbarray($result)){
		
			$fav = dbcount("(fav_pageid)", "bg_favorite","fav_pageid='".$data["article_id"]."' AND fav_type='A'");
            if($data["article_suggestion"]==1){
                $navrh = "<span class='label label-warning'>Návrh</span> ";
            }else if($data["article_suggestion"]==2){
                $navrh = "<span class='label label-warning'>Korekcia</span> ";
            }else{
                $navrh = "";
            }
			
		echo '<tr id="article'.$data["article_id"].'" class="'.($data["article_author"]==$userinfo["user_id"]?"info":"").'">
		<td><span class="label label-default">'.$data["article_id"].'</span></td>
		<td>'.$navrh.''.($data["article_suggestion"]==1 ? trimlink($data["article_name"],45):'<a href="/clanok/'.$data["article_id"].'/" target="_blank">'.trimlink($data["article_name"],50).'</a>').'</td>
		<td>'.date("j. n. Y",$data["article_date"]).'</td>
		<td>'.username($data["article_author"],0).'</td>
		<td align="right">'.(ADMIN && $data["article_suggestion"] == 2 ? '<a href="?articlepub='.$data["article_id"].'" class="label label-primary">Pub</a>':'').' <a href="clanokuprava?edit='.$data["article_id"].'" class="label label-primary">Upraviť</a> <a href="?articledel='.$data["article_id"].'" onclick="return confirm(\'Zmazať článok?\');" class="label label-danger">Kôš</a> <span class="label label-default articleinf" data-toggle="popover" data-placement="right" data-trigger="hover" data-html="true" data-content="<div style=\'width:140px\'>Obľúbené: '.$fav.'x<br/>Prečítané: '.$data["article_reads"].'x</div>">Info</span></td>
		</tr>';
		}
		echo "</table>";
		
        pagination($rows1,$limit,$pocet_stran,$strana);
		
		
		}else{
		echo "Žiadni užívatelia.<br/>";
		}
?>
</div>
<? require("inc/footer.php"); ?>