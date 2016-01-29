<? require("inc/settings.php"); $titlew=(isset($_GET['seria']) ? "Séria: ".seriename($_GET['seria']):"Série článkov"); require("inc/header.php"); ?>

      <div class="bigtitlecenter">
        <div class="bigtitle">
            <h5><? echo(isset($_GET['seria']) ? "Séria: ".seriename($_GET['seria']):"Série článkov"); ?></h5>
           <? echo(isset($_GET['seria']) ? serietext($_GET['seria']):""); ?>
        </div>
      </div>

<?php

			if(isset($_GET['seria'])){
                
        // vypis clankov v serii
		if (!is_numeric($_GET['seria'])){ redirect("/"); }

            $resultcat = dbquery("SELECT * FROM bg_articleseries WHERE as_id='".$_GET["seria"]."'");

			$datac = dbarray($resultcat);
			if(isset($_GET["n"]) && $_GET["n"] != bezd($datac["as_name"])){
			redirect("/seria/".$datac["as_id"]."/".bezd($datac["as_name"]));
			}


		$rowscat = dbrows($resultcat);
		if($rowscat != "1"){redirect("/");}
	if($_GET["seria"]){
			$cid = (int) $_GET["seria"];

	$cat = "WHERE article_series='".$cid."' AND article_suggestion='0'";
	}

$result2 = dbquery("SELECT * FROM bg_articles ".$cat." ORDER BY article_date");

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

$result = dbquery("SELECT article_img,article_id,article_name,article_author,article_cat,article_date,article_minitxt,article_reads FROM bg_articles ".$cat." ORDER BY article_date DESC LIMIT $pociatok, $limit");

		if($strana >$pocet_stran){redirect("/");} 

echo '<div class="row">';
        while($data = dbarray($result)){
		
		$ckom = dbcount("(comment_id)", "bg_comments","comment_delete='0' AND comment_pageid='".$data["article_id"]."' AND comment_type='A'");
            
echo '
    <div class="col-sm-6 col-md-4">
        <div class="articleview-box">
            <div class="img-responsive">    
                <img src="http://data.desart.sk/articles/'.($data["article_img"]!="none" ? $data["article_img"]:"noneimage.jpg").'" alt="'.$data["article_name"].'">
                <div class="rate">'.stargenerate(priemervypocet($data["article_id"],"A")).'</div>
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

        pagination($rows1,$limit,$pocet_stran,$strana);

		}else{
			echo "<p style='text-align:center;'>Žiadne články.</p>";
		}

			}else{

        // vypis serii
$result2 = dbquery("SELECT * FROM bg_articleseries WHERE as_countarticles>='2'");

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
	
    $result = dbquery("SELECT * FROM bg_articleseries WHERE as_countarticles>='2' ORDER BY as_id DESC LIMIT $pociatok, $limit");

		if($strana >$pocet_stran){redirect("/");} 

echo '<div class="row">';



    while($data = dbarray($result)){
        
        
    $clanokprvy_result = dbquery("SELECT * FROM bg_articles WHERE article_suggestion='0' AND article_series='".$data["as_id"]."' ORDER BY article_date DESC");  
    $clanokp = dbarray($clanokprvy_result);

echo '
    <div class="col-sm-6 col-md-4">
        <div class="articleview-box">
            <div class="img-responsive">    
                <img src="http://data.desart.sk/articles/'.($clanokp["article_img"]!="none" ? $clanokp["article_img"]:"noneimage.jpg").'" alt="'.$data["as_name"].'">
                <div class="date">'.date("j",$clanokp["article_date"]).'<small>'.$mesiac[date("n",$clanokp["article_date"])].', '.date("Y",$clanokp["article_date"]).'</small></div>
                 
            </div>
            <div class="info">
                <small>'.articlecat($clanokp["article_cat"],1,1).'</small>
                <h4><a href="/seria/'.$data["as_id"].'/'.bezd($data["as_name"]).'">'.$data["as_name"].'</a></h4>
                '.strip_tags(trimlink($data["as_mtext"],160)).'
                <div class="infin">
                    <span><i class="fa fa-comments"></i> Počet článkov: <a>'.dbrows($clanokprvy_result).'</a></span>
                </div>
                
            </div>
        </div>
    </div>
';
                

	} 

echo '</div>';
        
        pagination($rows1,$limit,$pocet_stran,$strana);

		}else{
			echo "<p style='text-align:center;'>Žiadne série.</p>";
		}
		
			}

require("inc/footer.php"); ?>