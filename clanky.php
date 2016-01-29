<?php
require("inc/settings.php"); 


$header = (object)[
  "title" => (isset($_GET['cat']) ? "Články: ".articlecat($_GET['cat']):"Články"),
  "socialmeta" => 0
];


require("inc/header.php"); ?>

      <div class="bigtitlecenter">
        <div class="bigtitle">
            <h5><? echo (!isset($_GET['cat']) ? "Články":articlecat($_GET['cat'])); ?></h5>
<div class="btn-group">
  <a href="?order=top" class="btn btn-default"><i class="fa fa-bullhorn"></i> Najsledovanejšie</a>
  <a href="?order=new" class="btn btn-default"><i class="fa fa-calendar"></i> Najnovšie</a>

  <div class="btn-group">
    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
      Kategórie
      <span class="caret"></span>
    </button>
    <ul class="dropdown-menu" role="menu">
            <?php
$result_subheader1 = dbquery("SELECT * FROM bg_articlecats ORDER BY articlec_name");

$rows1 = dbrows($result_subheader1);

	if ($rows1 >= "1") {
        while($data = dbarray($result_subheader1)){
		
		if(dbcount("(article_id)", "bg_articles"," article_cat='".$data["articlec_id"]."'") >= "0" ){
            
            echo '<li><a href="/clanky/kategoria/'.$data["articlec_id"].'/'.bezd($data["articlec_name"]).'" title="'.$data["articlec_name"].'" style="color:#'.$data["articlec_color"].';">'.$data["articlec_name"].'</a></li>';
		
		}

		}
		
	}
            ?>
    </ul>
  </div>
</div>
        </div>
      </div>


<?php

		// Vypis člankov
			if(isset($_GET['cat'])){
		if (!is_numeric($_GET['cat'])){ redirect("/"); }

$resultcat = dbquery("SELECT articlec_id,articlec_name FROM bg_articlecats WHERE articlec_id='".$_GET["cat"]."'");

			$datac = dbarray($resultcat);
			if(isset($_GET["n"]) && $_GET["n"] != bezd($datac["articlec_name"])){
			redirect("/clanky/kategoria/".$datac["articlec_id"]."/".bezd($datac["articlec_name"]));
			}


		$rowscat = dbrows($resultcat);
		if($rowscat != "1"){redirect("/");}
	if($_GET["cat"]){
			$cid = (int) $_GET["cat"];

	$cat = "WHERE article_cat='".$cid."' AND article_suggestion='0'";
	}
	
			}else{
			$cat = " WHERE article_suggestion='0'";
			}
if(isset($_GET["order"]) && $_GET["order"] == "top"){
$result2 = dbquery("SELECT * FROM bg_articles ".$cat." ORDER BY article_reads");
}else{
$result2 = dbquery("SELECT * FROM bg_articles ".$cat." ORDER BY article_date");
}
        $rows1 = dbrows($result2);

	if ($rows1 >= "1") {

if (isset($_GET['strana'])){
 $strana = (int)$_GET['strana'];

	if (!ctype_digit($_GET['strana'])){ redirect("/"); }

}else{
 $strana = 1;
}


	$limit = "9";
	$celkovy_pocet = $rows1;
	$pocet_stran = ceil($celkovy_pocet/$limit);
	$pociatok = ($strana*$limit)-$limit;
	
if(isset($_GET["order"]) && $_GET["order"] == "top"){
$result = dbquery("SELECT article_img,article_id,article_name,article_author,article_cat,article_date,article_minitxt,article_reads FROM bg_articles ".$cat." ORDER BY article_reads DESC LIMIT $pociatok, $limit");
}else{
$result = dbquery("SELECT article_img,article_id,article_name,article_author,article_cat,article_date,article_minitxt,article_reads FROM bg_articles ".$cat." ORDER BY article_date DESC LIMIT $pociatok, $limit");
}

		if($strana >$pocet_stran){redirect("/");} 

echo '<div class="row">';
        while($data = dbarray($result)){
		
		$ckom = dbcount("(comment_id)", "bg_comments","comment_delete='0' AND comment_pageid='".$data["article_id"]."' AND comment_type='A'");
            
echo '
    <div class="col-sm-6 col-md-4">
        <div class="articleview-box">
            <div class="img-responsive">    
                <img src="http://data.desart.sk/articles/'.($data["article_img"]!="none" ? $data["article_img"]:"noneimage.jpg").'" alt="'.$data["article_name"].'">
                <div class="rate">RATE</div>
                <div class="date">'.date("j",$data["article_date"]).'<small>'.$mesiac[date("n",$data["article_date"])].', '.date("Y",$data["article_date"]).'</small></div>
                 
            </div>
            <div class="info">
                <small>'.articlecat($data["article_cat"],1,1).'</small>
                <h4><a href="/clanok/'.$data["article_id"].'/'.bezd($data["article_name"]).'">'.$data["article_name"].'</a></h4>
                '.strip_tags(trimlink($data["article_minitxt"],160)).'
                <div class="infin">
                    <span><i class="fa fa-user"></i> Autor: '.username($data["article_author"],1).'</span>
                    <span><i class="fa fa-comments"></i> Komentáre: <a>'.$ckom.'</a></span>
                </div>
                
            </div>
        </div>
    </div>
';

	}
echo '</div>';
        
        if(isset($_GET["order"]) && $_GET["order"] == "top" OR isset($_GET["order"]) && $_GET["order"] == "new"){$orderlink = "&order=".$_GET["order"];}else{ $orderlink = "";}

        pagination($rows1,$limit,$pocet_stran,$strana,"",$orderlink);

		}else{
			echo "<p style='text-align:center;'>Žiadne články.</p>";
		}
		
require("inc/footer.php"); ?>