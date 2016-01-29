<? $titlew="Kategórie článkov"; require("../inc/settings.php"); require("inc/header.php"); ?>

<? if(!ADMIN){ redirect("/"); } ?>

<div class="panel panel-default">
    <div class="panel-heading">Kategórie článkov</div>
   <table class="table table-striped table-hover" align="center" cellpadding="0" cellspacing="1" width="100%">
    
    <tr>
    <td width="5%"><strong>ID</strong></td>
    <td width="40%"><strong>Názov kategórie</strong></td>
    <td><strong>Farba</strong></td>
    <td><strong>Počet článkov</strong></td>
    <td width="18%"></td>
    </tr>
<?

if(isset($_GET["catdel"])){

	dbquery("DELETE FROM bg_articlecats WHERE articlec_id='".$_GET["catdel"]."'");
	redirect("kategorie");
}

$result = dbquery("SELECT * FROM bg_comments");
$rows1 = dbrows($result);

	if ($rows1 >= "1") {
	
			$result = dbquery("SELECT * FROM bg_articlecats ORDER BY articlec_name");
	
        while($data = dbarray($result)){
			
		echo '<tr>
		<td><span class="label label-default">'.$data["articlec_id"].'</span></td>
		<td>'.articlecat($data["articlec_id"],1).'</td>
		<td>'.$data["articlec_color"].'</td>
		<td>'.dbcount("(article_id)", "bg_articles","article_cat='".$data["articlec_id"]."'").'</td>
		<td align="right"><a href="?catedit='.$data["articlec_id"].'" class="label label-primary">Upraviť</a> <a href="?catdel='.$data["articlec_id"].'" class="label label-danger" onclick="return confirm(\'Zmazať kategóriu?\');">Kôš</a></td>
		</tr>';
		}
		echo "</table>";
		
		}else{
		echo "Žiadne kategorie k článkom.";
		}
		echo '</div>';
		
	if(isset($_POST["catadd"])){
	dbquery("INSERT INTO bg_articlecats(articlec_name,articlec_seoname)VALUES('".strip_tags($_POST["newcat"])."','".bezd(strip_tags($_POST["newcat"]))."')");
	redirect("kategorie");
    }


echo '
<div class="panel panel-default">
    <div class="panel-heading">Pridať kategóriu</div>
    <div class="panel-body">
<form class="form-horizontal" method="post" action="">
  <div class="form-group">
    <label for="newcat" class="col-sm-3 control-label">Názov kategórie</label>
    <div class="col-sm-9">
      <input type="text" class="form-control" id="newcat" name="newcat">
    </div>
  </div>
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" name="seriesadd" class="btn btn-default">Pridať kategóriu</button>
    </div>
  </div>
</form>
    </div>
</div>
';


		



require("inc/footer.php"); ?>