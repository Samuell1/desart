<?php
require("inc/settings.php"); 

$header = (object)[
  "title" => "Tvorba webstránok, programovanie, grafika, it novinky, recenzie",
  "socialmeta" => 0
];


require("inc/header.php");


echo '
      <div class="bigtitlecenter">
        <div class="bigtitle">
            <h5>Posledné články</h5>
            Posledné aktuálne články z kategórií.
        </div>
      </div>
';

echo '<div class="row">';


    $result = dbquery("SELECT article_cat,article_name,article_minitxt,article_author,article_img,article_id,article_date,article_reads FROM bg_articles WHERE article_suggestion='0' ORDER BY article_date DESC LIMIT 0,9");

    while($data = dbarray($result)){
        
        $ckom = dbcount("(comment_id)", "bg_comments","comment_delete='0' AND comment_pageid='".$data["article_id"]."' AND comment_type='A'");

echo '
    <div class="col-sm-6 col-md-4">
        <div class="articleview-box">
            <div class="img-responsive">    
                <img src="http://data.desart.sk/articles/'.($data["article_img"]!="none" ? $data["article_img"]:"noneimage.jpg").'" alt="'.$data["article_name"].'">
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

    $rows1 = dbrows(dbquery("SELECT * FROM bg_articles WHERE article_suggestion='0'"));
    $limit = "9";
	$celkovy_pocet = $rows1;
	$pocet_stran = ceil($celkovy_pocet/$limit);

    pagination($rows1,$limit,$pocet_stran,"1","/clanky");

?>
<div class="text-center">
<script type="text/javascript">
 _adsys_id = 13071;
 _adsys_size = 2;
 _adsys_protocol = document.URL.substring(0,5).toLowerCase()=="https" ? "https://" : "http://";
 document.write(unescape('%3Cscript type="text/javascript" src="'
  +_adsys_protocol+'as.wedos.com/advert.js"%3E%3C/script%3E'));
</script>
<noscript>
 <a href="http://hosting.wedos.com/d/64014"
title="Nejprodávanější hosting v ČR!">WEBHOSTING - DOMÉNY - SERVERY - WEDOS.cz</a>
</noscript>
</div>
<?


echo '

      <div class="bigtitlecenter">
        <div class="bigtitle">
            <h5>Posledné série článkov</h5>
           V tejto časti webu si môžete pozrieť série článkov
        </div>
      </div>
';

echo '<div class="row">';


    $result1 = dbquery("SELECT * FROM bg_articleseries WHERE as_countarticles>='2' ORDER BY as_id DESC LIMIT 0,3");

    while($data = dbarray($result1)){
        
        
    $clanokprvy_result = dbquery("SELECT * FROM bg_articles WHERE article_suggestion='0' AND article_series='".$data["as_id"]."' ORDER BY article_date DESC");  
    $clanokp = dbarray($clanokprvy_result);
        
            if(dbrows($clanokprvy_result) >= 2){

echo '
    <div class="col-sm-6 col-md-4">
        <div class="articleview-box">
            <div class="img-responsive">    
                <img src="http://data.desart.sk/articles/'.($clanokp["article_img"]!="none" ? $clanokp["article_img"]:"noneimage.jpg").'" alt="'.$data["as_name"].'">
                <div class="date">'.dbrows($clanokprvy_result).'<small>'.sklonuj(dbrows($clanokprvy_result), 'článok', 'články', 'článkov').'</small></div>
                 
            </div>
            <div class="info">
                <small>'.articlecat($clanokp["article_cat"],1,1).'</small>
                <h4><a href="/seria/'.$data["as_id"].'/'.bezd($data["as_name"]).'">'.$data["as_name"].'</a></h4>
                ';
                
                $clanok3zoznam_result = dbquery("SELECT * FROM bg_articles WHERE article_suggestion='0' AND article_series='".$data["as_id"]."' ORDER BY article_date DESC LIMIT 0,3"); 
                echo '<div class="list-group">';
                while($data1 = dbarray($clanok3zoznam_result)){
                    
                    $nazovc = str_replace($data["as_name"], "", $data1["article_name"]);
                
                    echo '<a href="/clanok/'.$data1["article_id"].'/'.bezd($data1["article_name"]).'" class="list-group-item">'.$nazovc.'</a>';
                }
                echo '</div>';
                echo '
            </div>
        </div>
    </div>
';
                
           }
	} 

echo '</div>';

require("inc/footer.php"); ?>