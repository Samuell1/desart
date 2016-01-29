<? $titlew="Správa komentárov"; require("../inc/settings.php"); require("inc/header.php"); ?>

<? if(!ADMIN){ redirect("/"); } ?>

<div class="panel panel-default">
    <table class="table table-striped table-hover" align="center" cellpadding="0" cellspacing="1" width="100%">
    
    <tr>
    <td width="5%"><strong>ID</strong></td>
    <td width="45%"><strong>Komentované</strong></td>
    <td><strong>Dátum</strong></td>
    <td><strong>Autor</strong></td>
    <td width="25%"></td>
    </tr>
<? 


$result = dbquery("SELECT * FROM bg_comments");
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
	
$result = dbquery("SELECT * FROM bg_comments ORDER BY comment_id DESC LIMIT $pociatok, $limit");


		if($strana >$pocet_stran){redirect("/");} 
	
        while($data = dbarray($result)){
		
		$nameexist = ($data["comment_type"]=="A" ? articlename($data["comment_pageid"]):projektname($data["comment_pageid"]));
		
		$name = ($data["comment_type"]=="A" ? articleurl($data["comment_pageid"],articlename($data["comment_pageid"])):projekturl($data["comment_pageid"],projektname($data["comment_pageid"]),0,0));
		
			
		echo '<tr id="article'.$data["comment_id"].'" class="'.($data["comment_delete"]==1?"danger":($nameexist == ""?"warning":"")).'">
		<td><span class="label label-default">'.$data["comment_id"].'</span></td>
		<td>'.($nameexist == "" ? "Článok alebo projekt neexistuje":$name).'</td>
		<td>'.date("j. n. Y",$data["comment_time"]).'</td>
		<td>'.username($data["comment_userid"],0).'</td>
		<td align="right"><a href="?cdel='.$data["comment_id"].'" class="label label-danger">Kôš</a> <span class="label label-default articleinf" data-toggle="popover" data-placement="right" data-trigger="hover" data-html="true" data-content="<div style=\'width:160px\'>'.str_replace("\"","'",bbcode(badwords(smiley($data["comment_text"])))).'</div>">Obsah</span></td>
		</tr>';
		}
        echo '</table>';
        
        pagination($rows1,$limit,$pocet_stran,$strana);
		
		}else{
		echo "Žiadní užívatelia.<br/>";
		}
?>
</div>
<? require("inc/footer.php"); ?>