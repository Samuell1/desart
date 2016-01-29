<? $titlew="Série článkov"; require("../inc/settings.php"); require("inc/header.php"); 

if(!ADMIN){ redirect("/"); } 

if(isset($_GET["serieedit"])){
    
    
    if(isset($_POST["saveserie"])){
        
        
        dbquery("UPDATE bg_articleseries SET as_name='".$_POST["sname"]."',as_mtext='".$_POST["stext"]."',as_countarticles='".dbcount("(article_id)", "bg_articles","article_series='".$_GET["serieedit"]."'")."' WHERE as_id='".$_GET["serieedit"]."'");
        	
        $seriaidclanok = $_POST['clanokseria'];
        foreach ($seriaidclanok as $idclanku) {

	           dbquery("UPDATE bg_articles SET article_series='".$_GET["serieedit"]."' WHERE article_id='".$idclanku."'");

        }
        redirect("serieclankov?serieedit=".$_GET["serieedit"]);
        
    }

    if(isset($_POST["delserie"])){


        $seriaidclanok2 = $_POST['clanokseriadel'];
        foreach ($seriaidclanok2 as $idclanku1) {
	               dbquery("UPDATE bg_articles SET article_series='0' WHERE article_id='".$idclanku1."'");
        }
        redirect("serieclankov?serieedit=".$_GET["serieedit"]);
    }
    
echo '
<form method="post" action="" class="form-horizontal">
<div class="panel panel-default">
    <div class="panel-heading">Upraviť sériu #'.seriename($_GET["serieedit"]).'
        <span class="pull-right">
            <input name="saveserie" type="submit" value="Uložiť sériu" class="btn btn-success btn-xs">
            <input name="delserie" type="submit" value="Zmazať označené" class="btn btn-danger btn-xs">
        </span>
    </div>
  <div class="panel-body">

  <div class="form-group">
    <label for="sname" class="col-sm-2 control-label">Názov série</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="sname" name="sname" value="'.seriename($_GET["serieedit"]).'">
      <p class="help-block">Názov série sa musí vyskytovať v názvu článku</p>
    </div>
  </div>
  <div class="form-group">
    <label for="stext" class="col-sm-2 control-label">Popis série</label>
    <div class="col-sm-10">
      <textarea name="stext" class="form-control">'.serietext($_GET["serieedit"]).'</textarea>
    </div>
  </div>

  </div>
   <table class="table table-striped table-hover" align="center" cellpadding="0" cellspacing="1" width="100%">
    
    <tr>
    <td width="1%"><strong>ID</strong></td>
    <td width="40%"><strong>Názov článku</strong></td>
    <td width="1%"><span class="label label-success">Nachádza sa</span></td>
    <td width="1%"><span class="label label-danger">Odstrániť</span></td>
    </tr>
';
$result = dbquery("SELECT * FROM bg_articles WHERE article_name LIKE '%".seriename($_GET["serieedit"])."%' ORDER BY article_date");
    while($data = dbarray($result)){
		echo '<tr '.($data["article_series"]!=$_GET["serieedit"] ? 'class="warning"':'').'>
		<td><span class="label label-default">'.$data["article_id"].'</span></td>
		<td>'.$data["article_name"].'</td>
		<td align="center"><input type="checkbox" name="clanokseria[]" value="'.$data["article_id"].'" '.($data["article_series"]==$_GET["serieedit"] ? "checked":"").'></td>
		<td align="center"><input type="checkbox" name="clanokseriadel[]" value="'.$data["article_id"].'" '.($data["article_series"]!=$_GET["serieedit"] ? "checked":"").'></td>
		</tr>';
    }
echo ' 
   </table>
</div>
</form>
';

}

echo '
<div class="panel panel-default">
    <div class="panel-heading">Série článkov</div>
   <table class="table table-striped table-hover" align="center" cellpadding="0" cellspacing="1" width="100%">
    
    <tr>
    <td width="1%"><strong>ID</strong></td>
    <td width="40%"><strong>Názov série</strong></td>
    <td><strong>Počet článkov</strong></td>
    <td width="18%"></td>
    </tr>
';

if(isset($_GET["serdel"])){
    dbquery("UPDATE bg_articles SET article_series='0' WHERE article_series='".$_GET["serdel"]."'");
	dbquery("DELETE FROM bg_articleseries WHERE as_id='".$_GET["serdel"]."'");
	redirect("serieclankov");
}

$result = dbquery("SELECT * FROM bg_articleseries");
$rows1 = dbrows($result);

	if ($rows1 >= "1") {
	
			$result = dbquery("SELECT * FROM bg_articleseries ORDER BY as_name");
	
        while($data = dbarray($result)){
            
        dbquery("UPDATE bg_articleseries SET as_countarticles='".dbcount("(article_id)", "bg_articles","article_series='".$data["as_id"]."'")."' WHERE as_id='".$data["as_id"]."'");

		echo '<tr>
		<td><span class="label label-default">'.$data["as_id"].'</span></td>
		<td>'.seriename($data["as_id"]).'</td>
		<td>'.dbcount("(article_id)", "bg_articles","article_series='".$data["as_id"]."'").'</td>
		<td align="right"><a href="?serieedit='.$data["as_id"].'" class="label label-primary">Upraviť</a> <a href="?serdel='.$data["as_id"].'" class="label label-danger" onclick="return confirm(\'Zmazať sériu?\');">Kôš</a></td>
		</tr>';
		}
		echo "</table>";
		
		}else{
		echo "Žiadne serie k článkom.";
		}

echo '</div>';

	if(isset($_POST["seriesadd"])){
	   dbquery("INSERT INTO bg_articleseries(as_name)VALUES('".strip_tags($_POST["newserie"])."')");
	   redirect("serieclankov");
    }

echo '
<div class="panel panel-default">
    <div class="panel-heading">Pridať sériu</div>
  <div class="panel-body">
<form class="form-horizontal" method="post" action="">
  <div class="form-group">
    <label for="newserie" class="col-sm-2 control-label">Názov série</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="newserie" name="newserie">
      <p class="help-block">Názov série sa musí vyskytovať v názvu článku</p>
    </div>
  </div>
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" name="seriesadd" class="btn btn-default">Pridať novú sériu</button>
    </div>
  </div>
</form>
  </div>
</div>
';


require("inc/footer.php"); ?>