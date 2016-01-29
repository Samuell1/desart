<? require("inc/settings.php"); 

	if (!ctype_digit($_GET['id'])){ redirect("/"); }


    if(userperm("4") OR userperm("2") OR userperm("5")){
        $resultc = dbquery("SELECT * FROM bg_articles WHERE article_id='".strip_tags((int)$_GET["id"])."'");
        $deaktivate = 0;
    }else{
        $resultc = dbquery("SELECT * FROM bg_articles WHERE article_id='".strip_tags((int)$_GET["id"])."' AND article_suggestion='0'");
        $deaktivate = 1;
    }

        $datacla = dbarray($resultc);

    $deaktivate = ($datacla["article_suggestion"] == 0 ? 1:0);
		
$header = (object)[
  "title" => $datacla["article_name"],
  "metaimage" => (isset($datacla["article_img"]) ? "http://data.desart.sk/articles/".$datacla["article_img"] : ""),
  "desc" => (isset($datacla["article_minitxt"]) ? $datacla["article_minitxt"] : $setting["description"]),
  "atags" => (isset($datacla["article_target"]) ? $setting["keywords"].", ".$datacla["article_target"] : $setting["keywords"]) ,
  "id" => $datacla["article_id"],
  "url" => "http://desart.sk/clanok/".$datacla["article_id"]."/".bezd($datacla["article_name"]),
  "socialmeta" => 1
];

    $panelchange = "panel.php";
	
require("inc/header.php");

        $rows1 = dbrows($resultc);
		
	if ($rows1 == "1") {
		
			if(isset($_GET["n"]) && $_GET["n"] != bezd($datacla["article_name"])){
			redirect("/clanok/".$datacla["article_id"]."/".bezd($datacla["article_name"]));
			}

if($datacla["article_series"] != 0){
    
    if(dbcount("(article_id)","bg_articles"," article_series='".$datacla["article_series"]."'") >= 2){
    
    $clanokprvy_result = dbquery("SELECT * FROM bg_articles WHERE article_suggestion='0' AND article_series='".$datacla["article_series"]."' ORDER BY article_date ASC");

    $datacc = array();
    while($row = dbarray($clanokprvy_result)){ $datacc[] = $row["article_id"]; }
    
    $najdiclanok = array_search($datacla["article_id"],$datacc);
    
    $najdiclanok0 = (isset($datacc[($najdiclanok-1)])? $datacc[($najdiclanok-1)]:"hide");
    $najdiclanok1 =  (isset($datacc[($najdiclanok+1)])? $datacc[($najdiclanok+1)]:"hide");
    
echo '
<ul class="pager">
  '.($najdiclanok0 != "hide" ? '<li class="previous"><a href="/clanok/'.$najdiclanok0.'/'.bezd(articlename($najdiclanok0)).'">&larr; '.($najdiclanok1 == "hide" ? articlename($najdiclanok0):'Predchadzajúci článok').'</a></li>':'').'
  '.($najdiclanok1 != "hide" ? '<li class="next"><a href="/clanok/'.$najdiclanok1.'/'.bezd(articlename($najdiclanok1)).'">'.articlename($najdiclanok1).' &rarr;</a></li>':'').'
</ul>
';

    }

}

echo '
        <div class="articleview-inside">
            <div class="info">
                <h4><a href="/clanok/'.$datacla["article_id"].'/'.bezd($datacla["article_name"]).'">'.$datacla["article_name"].'</a></h4>
                <div class="infin">
                    <span><i class="fa fa-user"></i> Autor: '.username($datacla["article_author"],1).'</span>
                    <span><i class="fa fa-time"></i> Dátum: <a>'.date("j. n. Y",$datacla["article_date"]).'</a></span>
                    <span><i class="fa fa-th-list"></i> Kategória: '.articlecat($datacla["article_cat"],1).'</span>
                    <span><i class="fa fa-eye"></i> Prečítané: <a>'.$datacla["article_reads"].'</a>×</span>
                    
                </div>
                
            </div>
        </div>
';
      
echo '
      <div class="articleview">   
      '.$datacla["article_txt"];
echo '</div>';


?>
<br><div class="heureka-163376503"> <div class="heureka3-content"> <div style="float: left; border: 1px solid #E2E2E2; margin: 0 15px 0 0; padding: 0; width: 50px; height: 50px;"><a href="http://grafika-a-design.heureka.sk/adobe-creative-suite-6-photoshop-extended-win-cz-dvd-pack/#c971:3" class="heureka-image-163376503"> </a></div> <div style="margin: 0; padding: 0; line-height: 1.2em;color:#888;"> <a href="http://grafika-a-design.heureka.sk/adobe-creative-suite-6-photoshop-extended-win-cz-dvd-pack/#c971:3" target="_blank">Adobe Creative Suite 6 Photoshop Extended WIN CZ DVD Pack</a> môžete kúpiť v <span class="heureka-shops-163376503"> </span> e-shopoch za cenu od <span class="heureka-price-163376503"> </span> <small>(Zdroj: Heureka.sk)</small><br /> <a href="http://grafika-a-design.heureka.sk/adobe-creative-suite-6-photoshop-extended-win-cz-dvd-pack/#c971:3" target="_blank">Porovnať ceny >></a> </div> </div> <div style="clear: both;"></div> </div> <script type="text/javascript" src="http://www.heureka.sk/direct/bannery/?id=16337650:3:971"></script><br><br>
<?


			 $tags = explode(",", $datacla["article_target"]);

echo '
<div class="row">
    <div class="col-sm-7">
';
		echo '<div class="tagy">';
			for($i = 0; $i < count($tags); $i++){
				echo " <a href='/vyhladavanie?i=".urlencode(trim($tags[$i]))."' class='btn btn-default btn-sm'>".trim($tags[$i])."</a>";
			}
		echo '</div>';

echo '
    </div>
    <div class="col-sm-5 text-right">
    <div>'.fav($datacla["article_id"]).'</div>
    </div>
</div>
';
        if($deaktivate){
		dbquery("UPDATE bg_articles SET article_reads=article_reads+1 WHERE article_id='".$datacla["article_id"]."'");
        }
		
		}else{
		redirect("/");
		}
        if($deaktivate){
            komentare($datacla["article_id"]);
        }


require("inc/footer.php"); ?>