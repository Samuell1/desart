<? $titlew="Vytvoriť / Upraviť článok"; require("../inc/settings.php"); require("inc/header.php"); 

if(!userperm("4") && !userperm("2")){ redirect("/"); }
		
if(isset($_GET["edit"]) == ""){
    
    // article add

	if(isset($_POST["add"]) AND $_POST["nazov"] != "" AND $_POST["text"] != ""){

		$nazov = dbescape(strip_tags($_POST["nazov"]));
		$kat = $_POST["kat"];
		$img = $_POST["img"];
		$target = dbescape(strip_tags($_POST["target"]));
		$text = addslashes($_POST["text"]);
		$mtext = addslashes($_POST["minitext"]);
		$autor = $userinfo["user_id"];

		$navrh = 1;
		
        dbquery("INSERT INTO bg_articles(article_minitxt, article_img,article_name, article_cat, article_author, article_txt, article_date, article_suggestion, article_target)
     			VALUES('".$mtext."','".$img."','".$nazov."','".$kat."','".$autor."','".$text."','".time()."','".$navrh."','".$target."')");
			echo '<div class="alert alert-success">Článok <b>'.$nazov.'</b> bol pridaný.</div>';

	}
echo '
<form  name="form1" method="post" action="?add">
<div class="panel panel-default">
  <div class="panel-heading">Vytvoriť článok <input name="add" value="Vytvoriť článok" class="btn btn-success btn-xs pull-right" type="submit"></div>
  <div class="panel-body">

  <div class="form-group">
    <label for="nazov">Názov článku:</label>
    <input type="text" class="form-control" id="nazov" name="nazov">
  </div>
  <div class="form-group">
    <label for="target">Tagy:</label>
    <input type="text" class="form-control" id="target" name="target" value="tag1, tag2, tag3">
  </div>
  <div class="form-group">
    <label for="kat">Kategória:</label>
';

$result = dbquery("SELECT * FROM bg_articlecats");
        $rows1 = dbrows($result);

	if ($rows1 >= 1) {
		echo '<select name="kat" class="form-control">';
		while($data = dbarray($result)){
			echo '<option value="'.$data["articlec_id"].'">'.$data["articlec_name"].'</option>';
		}
		echo '</select>';
		
	}else {
		echo "Žiadne kategorie k článkom.";
	}
echo '
  </div>
  <div class="form-group">
    <label for="img">Obrázok článku:</label>
      ';

		echo '<select name="img" class="form-control">';
	echo '<option value="">- Výber obrázka článku -</option>';
	echo '<option value="none">- Žiadny obrázok -</option>';
$handle=opendir('../../data.desart.sk/articles'); 
while (false!==($file = readdir($handle))) 
{ 
    if ($file != "." && $file != "..") 
    { 

        echo '<option value="'.$file.'">'.$file.'</option>';
    } 
}
closedir($handle);
		echo '</select>';

echo '
  </div>
  <div class="form-group">
    <label for="minitext">Krátky popis:</label>
      <textarea name="minitext" id="minitext" class="form-control" rows="2"></textarea>
  </div>


<textarea name="text" class="ckeditor" style="width: 97%;" rows="35"></textarea>

 </div>
</div>
</form>
';

}else{
    
    
    // article edit

	if (!ctype_digit($_GET['edit'])){ redirect("/"); }
	if(dbcount("(article_id)", "bg_articles"," article_id='".(int)$_GET['edit']."'") == "0"){ redirect("/"); }

  $editresult = dbquery("SELECT * FROM bg_articles WHERE article_id='".$_GET["edit"]."'");
  $edata = dbarray($editresult);

	if(isset($_POST["savearticle"]) AND $_POST["nazov"] != "" AND $_POST["text"] != ""){

		$nazov = dbescape(strip_tags($_POST["nazov"]));
		$kat = $_POST["kat"];
		$img = $_POST["img"];
		$target = dbescape(strip_tags($_POST["target"]));
		$text = addslashes($_POST["text"]);
		$mtext = addslashes($_POST["minitext"]);

    $navrh = (isset($_POST["suggestion"]) ? $_POST["suggestion"]:$edata["article_suggestion"]);
		$redate = $_POST["redate"];
		
			if($redate){
				dbquery("UPDATE bg_articles SET article_date='".time()."' WHERE article_id='".(int)$_GET["edit"]."'");
			}

     dbquery("UPDATE bg_articles SET article_minitxt='".$mtext."',article_img='".$img."',article_name='".$nazov."',article_cat='".$kat."',article_txt='".$text."',article_suggestion='".$navrh."',article_target='".$target."' WHERE article_id='".(int)$_GET["edit"]."'");

			echo '<div class="alert alert-success">Článok <b><a class="alert-link" href="/clanok/'.$_GET["edit"].'/">'.$nazov.'</a></b> bol Upravený.</div>';
	}


	if($userinfo["user_perm"] == 4){
		$mojclanok = ($edata["article_author"] == $userinfo["user_id"] ? true:false);
	}else{
		$mojclanok = true;
	}
    

	if($mojclanok){

echo '
<form method="post" action="?edit='.$edata["article_id"].'">

<div class="panel panel-primary">
  <div class="panel-heading">Nastavenia článku #'.$edata["article_id"].' <input name="savearticle" value="Uložiť článok" class="btn btn-default btn-xs pull-right" type="submit"></div>
  <ul class="list-group">
    <li class="list-group-item">
    Obrázok článku:
      ';

		echo '<select name="img" class="form-control">';
	echo '<option value="">- Výber obrázka článku -</option>';
	echo '<option value="none">- Žiadny obrázok -</option>';
    echo '<option value="'.$edata["article_img"].'" selected>'.str_replace("/file/articles/", "", $edata["article_img"]).'</option>';
$handle=opendir('../../data.desart.sk/articles'); 
while (false!==($file = readdir($handle))) 
{ 
    if ($file != "." && $file != "..") 
    { 
        $datum = @filemtime("../../data.desart.sk/articles".$file);
        echo '<option '.($datum>strtotime('-1 day') ? "style='color:green' ":"").'value="'.$file.'">'.$file.'</option>';
    } 
}
closedir($handle);
		echo '</select>';

echo '
    </li>
    <li class="list-group-item">Tagy:<input type="text" class="form-control" id="target" name="target" value="'.$edata["article_target"].'"></li>
    <li class="list-group-item">Autor článku: <span class="pull-right">'.username($edata["article_author"]).'</span></li>
    <li class="list-group-item">Aktualizovať dátum: 

<div class="btn-group pull-right" data-toggle="buttons">
  <label class="btn btn-primary btn-xs active">
    <input type="radio" name="redate" value="0" checked> '.date("j. n. Y H:i:s",$edata["article_date"]).'
  </label>
  <label class="btn btn-primary btn-xs">
    <input type="radio" name="redate" value="1"> Aktualizovať
  </label>
</div>
    </li>
    <li class="list-group-item">Návrh:

<div class="btn-group pull-right" data-toggle="buttons">
  <label class="btn btn-primary btn-xs '.($edata["article_suggestion"] == "1" ? "active":"").'">
    <input type="radio" name="suggestion" value="1" '.($edata["article_suggestion"] == "1" ? "checked":"").'> Návrh
  </label>
  <label class="btn btn-primary btn-xs '.($edata["article_suggestion"] == "2" ? "active":"").'">
    <input type="radio" name="suggestion" value="2" '.($edata["article_suggestion"] == "2" ? "checked":"").'> Korekcia
  </label>

';
if(ADMIN){
echo '
  <label class="btn btn-primary btn-xs '.($edata["article_suggestion"] == "0" ? "active":"").'">
    <input type="radio" name="suggestion" value="0" '.($edata["article_suggestion"] == "0" ? "checked":"").'> Zverejniť
  </label>
';
}
echo '
</div>
    </li>
  </ul>
</div>

<div class="panel panel-default">
  <div class="panel-heading">Upraviť článok #'.$edata["article_id"].' <input name="savearticle" value="Uložiť článok" class="btn btn-success btn-xs pull-right" type="submit"></div>
  <div class="panel-body">

  <div class="form-group">
    <label for="nazov">Názov článku:</label>
    <input type="text" class="form-control" id="nazov" name="nazov" value="'.$edata["article_name"].'">
  </div>
  <div class="form-group">
    <label for="kat">Kategória:</label>
';

$result = dbquery("SELECT * FROM bg_articlecats");
        $rows1 = dbrows($result);

	if ($rows1 >= 1) {
		echo '<select name="kat" class="form-control">';
		echo '<option value="'.$edata["article_cat"].'" selected>'.articlecat($edata["article_cat"]).'</option>';
		while($data = dbarray($result)){
			echo '<option value="'.$data["articlec_id"].'">'.$data["articlec_name"].'</option>';
		}
		echo '</select>';
		
	}else {
		echo "Žiadne kategorie k článkom.";
	}
echo '
  </div>
  <div class="form-group">
    <label for="minitext">Krátky popis:</label>
      <textarea name="minitext" id="minitext" class="form-control" rows="2">'.$edata["article_minitxt"].'</textarea>
  </div>

<textarea name="text" class="ckeditor" style="width: 97%;" rows="35">'.htmlspecialchars(stripslashes($edata["article_txt"])).'</textarea>

 </div>
</div>

</form>
';

}else{ echo '<div class="alert alert-warning">Na tento článok nemáš dostatočné práva.</div>';}

}

require("inc/footer.php"); ?>