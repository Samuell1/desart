<? $titlew="Napísať článok"; require("../inc/settings.php"); require("../inc/header.php"); ?>

<? if(!MEMBER){ redirect("/"); } ?>

      <div class="bigtitlecenter">
        <div class="bigtitle">
            <h5>Napísať článok</h5>
            Napíš svoj vlastný článok a pridaj ho na schválenie. Pred odoslaním článku skontrolujte či Váš článok splňuje <a href="/stranky/pravidla">pravidlá</a> stránky.
        </div>
      </div>
<?

if(isset($_POST["add"]) AND $_POST["nazov"] != "" AND $_POST["text"] != ""){

		$nazov = mysql_real_escape_string(htmlspecialchars($_POST["nazov"]));
		$kat = $_POST["kat"];
		$target = htmlspecialchars($_POST["target"]);
		$text = addslashes($_POST["text"]);
		$mtext = htmlspecialchars($_POST["minitext"]);
		$autor = $userinfo["user_id"];

		$navrh = 1;

	if(dbcount("(article_id)", "bg_articles","article_author='".$userinfo["user_id"]."' AND article_date > ".strtotime("-12 minutes")."")) {
		echo '<div class="tip-red border">Ďalší článok môžeš napísať až o 12 minút. (ochrana proti spamu)</div>';
	}else{
	
   if (($_FILES["articlefile"]["type"] == "image/jpeg") || ($_FILES["articlefile"]["type"] == "image/png" )) {

	$saveurl = rand(1000,9999).rand(10,99).$userinfo["user_id"].upload_koncovka($_FILES["articlefile"]["type"]);
	
	if($_FILES["articlefile"]["size"] < 4194304){

				$imgf = file_get_contents($weburl."/inc/func/resize.php?i=".$_FILES["articlefile"]["tmp_name"]."&w=340&h=200");
				file_put_contents("../../data.desart.sk/articles/".$saveurl, $imgf);
				
     dbquery("INSERT INTO bg_articles(article_minitxt, article_img,article_name, article_cat, article_author, article_txt, article_date, article_suggestion, article_target)
     			VALUES('".$mtext."','".$saveurl."','".$nazov."','".$kat."','".$autor."','".$text."','".time()."','".$navrh."','".$target."')");

			echo '<div class="alert alert-success">Článok <b>'.$nazov.'</b> bol pridaný na schválenie.</div>';
	
	}else{
	echo '<div class="alert alert-danger">Maximálna veľkosť obrázku 2MB..</div>';
	}

   }else{ echo '<div class="alert alert-danger">Neplatný typ souboru. Povolené typy: .PNG, .JPG</div>'; }

   }
   
}
	

echo '
<form method="post" action="" enctype="multipart/form-data" class="form-horizontal">

<div class="form-group">
    <label for="nazov" class="col-sm-2 control-label">Názov článku:</label>
     <div class="col-sm-10"><input id="nazov" name="nazov" value="'.(isset($nazov) ? $nazov:"").'" class="form-control" type="text"></div>
</div>
<div class="form-group">
    <label for="target" class="col-sm-2 control-label">Tagy:</label>
     <div class="col-sm-10"><input id="target" name="target" value="'.(isset($nazov) ? $target:"tag1, tag2, tag3").'" class="form-control" type="text"></div>
</div>
<div class="form-group">
    <label for="kat" class="col-sm-2 control-label">Kategória:</label>
     <div class="col-sm-10">';
$result = dbquery("SELECT * FROM bg_articlecats");
        $rows1 = dbrows($result);

	if ($rows1 >= "1") {
		echo '<select name="kat" class="form-control">';
		while($data = dbarray($result)){
			echo '<option value="'.$data["articlec_id"].'">'.$data["articlec_name"].'</option>';
		}
		echo '</select>';
	}
echo '
     </div>
</div>
<div class="form-group">
    <label for="articlefile" class="col-sm-2 control-label">Obrázok článku:</label>
     <div class="col-sm-10"><input id="articlefile" name="articlefile" type="file">        <p class="help-block">Pokúste sa nahrať obrázok vo veľkosťi 340x200px.</p></div>
</div>
<div class="form-group">
    <label for="minitext" class="col-sm-2 control-label">Kratší popis:</label>
     <div class="col-sm-10">
        <textarea id="minitext" name="minitext" rows="3" class="form-control">'.(isset($mtext) ? $mtext:"").'</textarea>
    </div>
</div>


<div class="form-group">
    <label for="text" class="col-sm-2 control-label">Obsah článku:</label>
     <div class="col-sm-10">
        <textarea name="text" class="ckeditor" rows="35">'.(isset($text) ? $text:"").'</textarea>
    </div>
</div>

<div class="form-group">
    <label for="da" class="col-sm-2 control-label"></label>
    <div class="input-group">
        <div class="col-sm-10"><input name="add" value="Odoslať článok" class="btn btn-success" type="submit"></div>
    </div>
</div>

</form>
';

require("../inc/footer.php"); ?>