<? $titlew="Súbory na stiahnutie"; require("inc/settings.php"); require("inc/header.php");

?>
      <div class="bigtitlecenter">
        <div class="bigtitle">
            <h5>Súbory na stiahnutie</h5>
            V tejto sekcií nájdete bezplatne na stiahnutie grafické a programátorské šablóny, témy a webové doplnky.
        </div>
      </div>
<?

$result = dbquery("SELECT * FROM bg_downloadcats ORDER BY downc_name");

$rows1 = dbrows($result);

	if ($rows1 >= "1") {
	
		 echo '<ol class="breadcrumb" style="text-align:center;">';
        while($data = dbarray($result)){
		
		if(dbcount("(down_id)", "bg_downloads"," down_cat='".$data["downc_id"]."'") >= "0" ){
		
			echo "<li><a href='/subory/".$data["downc_id"]."/".bezd($data["downc_name"])."' title='".$data["downc_name"]."'>".$data["downc_name"]."</a></li>";
		}

		}
		echo '</ol><div class="clearfix"></div>';
		
		}else{
		echo "Žiadne kategórie k downloadom, ktoré majú pridaný súbor.<br/>";
		}

		// Vypis downloadov
			if(isset($_GET['cat'])){
		if (!is_numeric($_GET['cat'])){ redirect("/"); }

$resultcat = dbquery("SELECT * FROM bg_downloadcats WHERE downc_id='".$_GET["cat"]."'");
		$rowscat = dbrows($resultcat);
		if($rowscat != "1"){redirect("/");}
	if($_GET["cat"]){
			$cid = (int) $_GET["cat"];

	$cat = "WHERE down_cat='".$cid."'";
	}
	
			}else{
			$cat = "";
			}
			
$result2 = dbquery("SELECT * FROM bg_downloads ".$cat." ORDER BY down_id");

        $rows1 = dbrows($result2);

	if ($rows1 >= "1") {

if (isset($_GET['strana'])){
 $strana = (int)$_GET['strana'];

	if (!ctype_digit($_GET['strana'])){ redirect("/"); }

}else{
 $strana = 1;
}


	$limit = "12";
	$celkovy_pocet = $rows1;
	$pocet_stran = ceil($celkovy_pocet/$limit);
	$pociatok = ($strana*$limit)-$limit;

$result = dbquery("SELECT * FROM bg_downloads ".$cat." ORDER BY down_id DESC LIMIT $pociatok, $limit");


		if($strana>$pocet_stran){redirect("/");} 

echo '<div class="row">';
        while($data = dbarray($result)){
		
echo '<div class="col-sm-6 col-md-3"><div class="subor">
		<a href="/subory/subor/'.$data["down_id"].'/'.bezd($data["down_name"]).'" data-lightbox="image" title=""><img src="http://data.desart.sk/downloadimg/'.$data["down_img"].'"/></a>
			<div class="subortxt"> 
            <div class="stitle"><a href="/subory/subor/'.$data["down_id"].'/'.bezd($data["down_name"]).'">'.$data["down_name"].'</a></div>
            '.downloadcat($data["down_cat"]).'
			</div>
    </div></div>';

	}
echo '</div>';
        
        pagination($rows1,$limit,$pocet_stran,$strana);


		}else{
			echo "<p style='text-align:center;'>Žiadne súbory.</p>";
		}


if(isset($_GET["suborid"])){
    
    if (!ctype_digit($_GET['suborid'])){ redirect("/"); }
    
    
    $resultsubor = dbquery("SELECT * FROM bg_downloads WHERE down_id='".$_GET['suborid']."' LIMIT 0,1");
    $datas = dbarray($resultsubor);
    
    $hodnotilox = dbcount("(rate_pageid)", "bg_ratestar","rate_pageid='".$datas["down_id"]."' AND rate_type='S'");
    
            if(isset($_GET["sn"]) && $_GET["sn"] != bezd($datas["down_name"])){
                redirect("/subory/subor/".$datas["down_id"]."/".bezd($datas["down_name"]));
			}
    
echo '
<div class="modal fade bs-example-modal-lg" id="subormodal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title">'.$datas["down_name"].'</h4>
      </div>
      <div class="modal-body">
            <a href="'.$datas["down_imgview"].'"><img src="'.$datas["down_imgview"].'" alt="nahlad" class="img-thumbnail" style="width:100%;"/></a>
            
<table class="table table-bordered" style="margin-top:10px;">
  <tr><td>Popis:</td><td>'.$datas["down_txt"].'</td></tr>
  <tr><td>Typ súboru:</td><td>'.$datas["down_filetype"].'</td></tr>
  <tr><td>Veľkosť:</td><td>'.$datas["down_filesize"].'</td></tr>
  <tr><td>Dátum pridania:</td><td>'.date("j. n. Y",$datas["down_date"]).'</td></tr>
  <tr><td>Autor:</td><td>'.$datas["down_author"].'</td></tr>
  <tr><td>Počet stiahnutí:</td><td>'.$datas["down_reads"].'x</td></tr>
</table>
      </div>
      <div class="modal-footer">
        <span class="pull-left hodnotesubor"><span class="pull-left">'.rate($datas["down_id"],"S").'</span> <span class="pull-left hodnotenietext">Hodnotili '. $hodnotilox.' '.uzivateliasklonuj($hodnotilox).'</span></span>
        '.($datas["down_demo"] ? '<a href="'.$datas["down_demo"].'" class="btn btn-info">Zobraziť demo</a>':'').'
        '.(MEMBER ? '<a href="/stiahnut/'.$datas["down_id"].'" class="btn btn-success" id="loading-example-btn" data-loading-text="Načítavam súbor...">Stiahnuť súbor</a>':'').'
      </div>
    </div>
  </div>
</div>
';
    
}
	
require("inc/footer.php"); ?>