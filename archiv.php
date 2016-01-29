<?php
require("inc/settings.php"); 

$header = (object)[
  "title" => "Archív: ".(isset($_GET['idm']) ? $mesiac[$_GET['idm']]:""),
  "socialmeta" => 0
];


require("inc/header.php");
?>
      <div class="bigtitlecenter">
        <div class="bigtitle">
            <h5><? echo (!isset($_GET['idm']) ? "Archív": "Archív: ".$mesiac[$_GET['idm']]); ?></h5>
            Vyberte rok a mesiac, pre ktorý chcete zobraziť prehľad článkov.
        </div>
      </div>


<ol class="breadcrumb text-center">
<?

	for ($i = 1; $i <= 12; $i++){
				
        echo ' <li><a href="/archiv/'.$i.'/'.bezd($mesiac[$i]).'" style="color:'.($i == date("n") ? "#74BA45":"none").';margin:2px;">'.$mesiac[$i].'</a></li>';

	}

?>
</ol>

<?php
		// Vypis archivu člankov
		if (!is_numeric($_GET['idm'])){ redirect("/"); }
		
			if(isset($_GET["m"]) && $_GET["m"] != bezd($mesiac[$_GET['idm']])){
			redirect("/archiv/".$_GET['idm']."/".bezd($mesiac[$_GET['idm']]));
			}

$resultarchiv = dbquery("SELECT * FROM bg_articles WHERE MONTH(FROM_UNIXTIME(article_date))='".$_GET['idm']."' AND article_suggestion='0'");

		$rowsar = dbrows($resultarchiv);
		if($rowsar <= 0){redirect("/");}
			
$result2 = dbquery("SELECT * FROM bg_articles WHERE MONTH(FROM_UNIXTIME(article_date))='".$_GET['idm']."' AND article_suggestion='0' ORDER BY article_date");

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
	
$result = dbquery("SELECT * FROM bg_articles WHERE MONTH(FROM_UNIXTIME(article_date))='".$_GET['idm']."' AND article_suggestion='0' ORDER BY article_date DESC LIMIT $pociatok, $limit");

		if($strana > $pocet_stran){redirect("/");} 

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
		
require("inc/footer.php"); ?>