<?php if (!defined('PERM')) { die(); } ?>

<div class="list-group">
    <a class="list-group-item active">Najčítanejšie články</a>
<?php

  $result_panel1 = dbquery("SELECT article_name,article_id,article_reads FROM bg_articles WHERE article_suggestion='0' ORDER BY article_reads DESC LIMIT 0,8");


    while($data = dbarray($result_panel1)){
                 
    echo '<a href="/clanok/'.$data["article_id"].'/'.bezd($data["article_name"]).'" class="list-group-item">'.$data["article_name"].'</a>';
                 
    }

?>
</div>

<div class="list-group">
    <a class="list-group-item active">Podobné články</a>
<?php

		$target1 = trim($tags[0]);
		$target2 = trim($tags[1]);
	
$result33 = dbquery("SELECT * FROM bg_articles WHERE article_suggestion='0' AND article_target LIKE '%".StrToLower($target1)."%' AND article_id<>'".$datacla["article_id"]."' OR article_target LIKE '%".StrToLower($target2)."%' AND article_id<>'".$datacla["article_id"]."' AND article_suggestion='0' ORDER BY article_date DESC LIMIT 0,8");

						$rows2 = dbrows($result33);
						
		if($rows2 >= "1"){

			while($data66 = dbarray($result33)){
			
                 echo '<a href="/clanok/'.$data66["article_id"].'/'.bezd($data66["article_name"]).'" class="list-group-item">'.$data66["article_name"].'</a>';
			}
			echo '<div class="clearfix"></div>';
		}

?>
</div>
