<? $titlew="Diskusné fórum"; require("../inc/settings.php"); require("../inc/header.php");


if(!isset($_GET["fcat"])){

    echo '
      <div class="bigtitlecenter">
        <div class="bigtitle">
            <h5>Diskusné fórum Desart.sk</h5>
            Ak potrebujete pomôcť s grafikou a programovaním napíšte do nášho fóra. Fórum taktiež môžete použiť na predaj svojich grafických a programátorských prác alebo na ich prezentáciu. Pred pridaním príspevku si prosím prečítajte <a href="/stranky/pravidla">pravidlá</a>.
        </div>
      </div>
    ';
    
        $resultf = dbquery("SELECT * FROM bg_forum WHERE forum_id='3' OR forum_id='4' OR forum_id='8' OR forum_id='9' OR forum_id='11' ORDER BY forum_name");

    echo '<div class="list-group">';
    
		while($dataf = dbarray($resultf)){
            
             echo '<a href="/forum/'.$dataf["forum_id"].'/'.bezd($dataf["forum_name"]).'" class="list-group-item">'.$dataf["forum_name"].' <small>('.$dataf["forum_desc"].')</small><span class="badge"> '.dbcount("(forumt_id)", "bg_forumtopic"," forumt_fid='".$dataf["forum_id"]."'").'</span></a>';
            
		}

    echo '</div>';	

    echo '
      <div class="bigtitlecenter">
        <div class="bigtitle">
            <h5>Design, grafika, webdesign</h5>
        </div>
      </div>
    ';
    
		$resultf = dbquery("SELECT * FROM bg_forum WHERE forum_id='2' OR forum_id='10' OR forum_id='12' ORDER BY forum_name");
    
    echo '<div class="list-group">';
    
		while($dataf = dbarray($resultf)){
            
             echo '<a href="/forum/'.$dataf["forum_id"].'/'.bezd($dataf["forum_name"]).'" class="list-group-item">'.$dataf["forum_name"].' <small>('.$dataf["forum_desc"].')</small><span class="badge"> '.dbcount("(forumt_id)", "bg_forumtopic"," forumt_fid='".$dataf["forum_id"]."'").'</span></a>';
            
		}

    echo '</div>';

    echo '
      <div class="bigtitlecenter">
        <div class="bigtitle">
            <h5>Programovanie, kódovanie, tvorba web stránok</h5>
        </div>
      </div>
    ';
		
		$resultf = dbquery("SELECT * FROM bg_forum WHERE forum_id='5' OR forum_id='6' OR forum_id='1' OR forum_id='7' ORDER BY forum_name");
		
    echo '<div class="list-group">';
    
		while($dataf = dbarray($resultf)){
            
            echo '<a href="/forum/'.$dataf["forum_id"].'/'.bezd($dataf["forum_name"]).'" class="list-group-item">'.$dataf["forum_name"].' <small>('.$dataf["forum_desc"].')</small><span class="badge"> '.dbcount("(forumt_id)", "bg_forumtopic"," forumt_fid='".$dataf["forum_id"]."'").'</span></a>';
            
		}

    echo '</div>';
    
    echo '
      <div class="bigtitlecenter">
        <div class="bigtitle">
            <h5>To najlepšie a najnovšie z fóra</h5>
        </div>
      </div>
    ';
    
    echo '<div class="row">';
    
        echo '<div class="col-md-4">';
    
		$resultf = dbquery("SELECT * FROM bg_forumtopic WHERE forumt_fid<>'8' ORDER BY forumt_reads DESC LIMIT 0,8");
		
    echo '<div class="list-group">';
    echo '<a class="list-group-item active">Najsledovanejšie témy</a>';
    
		while($dataf = dbarray($resultf)){
            
				$postcount = dbcount("(post_id)", "bg_forumtopicpost"," post_topicid='".$dataf3["forumt_id"]."'");
				$limitp = "15";
				$stranatema = ceil($postcount/$limitp);

            echo '<a href="/tema/'.$dataf["forumt_id"]."/".bezd($dataf["forumt_name"]).($stranatema > 1 ? "?strana=".$stranatema:"").'" class="list-group-item">'.trimlink($dataf["forumt_name"],35).' <span class="badge">'.$dataf["forumt_reads"].'</a>';
            
		}

    echo '</div>';
    
        echo '</div>';
    
        echo '<div class="col-md-4">';
    
		$resultf = dbquery("SELECT * FROM bg_forumtopic WHERE forumt_fid<>'8' ORDER BY forumt_newpost DESC LIMIT 0,8");
		
    echo '<div class="list-group">';
    echo '<a class="list-group-item active">Posledné aktívne témy</a>';
    
		while($dataf = dbarray($resultf)){
            
				$postcount = dbcount("(post_id)", "bg_forumtopicpost"," post_topicid='".$dataf3["forumt_id"]."'");
				$limitp = "15";
				$stranatema = ceil($postcount/$limitp);

            echo '<a href="/tema/'.$dataf["forumt_id"]."/".bezd($dataf["forumt_name"]).($stranatema > 1 ? "?strana=".$stranatema:"").'" class="list-group-item">'.trimlink($dataf["forumt_name"],35).' <span class="badge">'.(dbcount("(post_id)", "bg_forumtopicpost"," post_topicid='".$dataf["forumt_id"]."'")-1).'</span></a>';
            
		}

    echo '</div>';
    
        echo '</div>';
    
        echo '<div class="col-md-4">';
    
		$resultf = dbquery("SELECT * FROM bg_forumtopic WHERE forumt_fid<>'8' AND forumt_fav='1' ORDER BY forumt_newpost DESC LIMIT 0,8");
		
    echo '<div class="list-group">';
    echo '<a class="list-group-item active">Obľúbené témy</a>';
    
		while($dataf = dbarray($resultf)){
            
				$postcount = dbcount("(post_id)", "bg_forumtopicpost"," post_topicid='".$dataf3["forumt_id"]."'");
				$limitp = "15";
				$stranatema = ceil($postcount/$limitp);

            echo '<a href="/tema/'.$dataf["forumt_id"]."/".bezd($dataf["forumt_name"]).($stranatema > 1 ? "?strana=".$stranatema:"").'" class="list-group-item">'.trimlink($dataf["forumt_name"],35).' <span class="badge">'.(dbcount("(post_id)", "bg_forumtopicpost"," post_topicid='".$dataf["forumt_id"]."'")-1).'</span></a>';
            
		}

    echo '</div>';
    
        echo '</div>';
    
    echo '</div>';
	  
}else{

	if (!is_numeric($_GET['fcat'])){ redirect("/"); }
	
		$resultf2 = dbquery("SELECT * FROM bg_forum WHERE forum_id='".$_GET['fcat']."'");
		$dataf2 = dbarray($resultf2);
		
				$rowscat2 = dbrows($resultf2);
				if($rowscat2 != "1"){redirect("/");}
		
			if(isset($_GET["nf"]) && $_GET["nf"] != bezd($dataf2["forum_name"])){
			redirect("/forum/".$dataf2["forum_id"]."/".bezd($dataf2["forum_name"]));
			}

    echo '
      <div class="bigtitlecenter">
        <div class="bigtitle">
            <h5>Fórum: '.$dataf2["forum_name"].'</h5>
            '.$dataf2["forum_desc"].' ('.dbcount("(forumt_id)", "bg_forumtopic"," forumt_fid='".$dataf2["forum_id"]."'")." ".temysklonuj(dbcount("(forumt_id)", "bg_forumtopic"," forumt_fid='".$dataf2["forum_id"]."'")).')
            
        </div>
      </div>
      '.((MEMBER AND $dataf2["forum_id"] != 8) ? "<a href='/forum/novatema-".$dataf2["forum_id"]."' class='btn btn-success btn-block' style='margin-bottom:5px'>Vytvoriť novú tému</a>":"").'
    ';

		
				$resultf3 = dbquery("SELECT * FROM bg_forumtopic WHERE forumt_fid='".$dataf2["forum_id"]."'");
		
			if(dbrows($resultf3) >= 1){
			
if (isset($_GET['strana'])){
$strana = (int)$_GET['strana'];

	if (!ctype_digit($_GET['strana'])){ redirect("/"); }

}else{
 $strana = 1;
}


	$limit = "18";
	$celkovy_pocet = dbrows($resultf3);
	$pocet_stran = ceil($celkovy_pocet/$limit);
	$pociatok = ($strana*$limit)-$limit;
	
	$resultf3 = dbquery("SELECT * FROM bg_forumtopic WHERE forumt_fid='".$dataf2["forum_id"]."' ORDER BY forumt_fav DESC,forumt_time DESC LIMIT $pociatok, $limit");
			
        echo '<div class="list-group">';
		while($dataf3 = dbarray($resultf3)){
		
				$postcount = dbcount("(post_id)", "bg_forumtopicpost"," post_topicid='".$dataf3["forumt_id"]."'");
				$limitp = "15";
				$stranatema = ceil($postcount/$limitp);
	
             echo '<a href="/tema/'.$dataf3["forumt_id"]."/".bezd($dataf3["forumt_name"]).($stranatema > 1 ? "?strana=".$stranatema:"").'" class="list-group-item">'.($dataf3["forumt_fav"]==1 ? '<i class="fa fa-star" style="color:#d9534f;"></i> ':'').$dataf3["forumt_name"].' <small>('.timeago($dataf3["forumt_time"]).' od '.username($dataf3["forumt_userid"]).')</small><span class="badge"> '.(dbcount("(post_id)", "bg_forumtopicpost"," post_topicid='".$dataf3["forumt_id"]."'")-1).'</span> '.($dataf3["forumt_locked"]==1?'<span class="label label-warning" style="font-family:Arial;font-size:10px;">Zamknuté</span>':'').'</a>';

		}
        echo '</div>';
		
            pagination($celkovy_pocet,$limit,$pocet_stran,$strana);

			}else{
			echo '<div class="alert alert-warning" role="alert">V tejto kategórií sa zaťiaľ nenachádza žiadna téma. Späť na <a href="/forum">fórum</a></div>';
			}


}


require("../inc/footer.php"); ?>