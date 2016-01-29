<?php $titlew="Súbory"; require("../inc/settings.php"); require("inc/header.php"); ?>  

<?php if(!ADMIN){ redirect("/"); } 

if(isset($_GET["filedit"])){

        $result = dbquery("SELECT * FROM bg_downloads WHERE down_id='".$_GET["filedit"]."'");
        $data = dbarray($result);

        if(isset($_POST["savefile"])){
            
            
            dbquery("UPDATE bg_downloads SET 
            down_name='".$_POST["filename"]."',down_file='".$_POST["fileurl"]."',down_imgview='".$_POST["fileimgview"]."',down_cat='".$_POST["filekat"]."'
            ,down_img='".$_POST["fileimg"]."',down_filesize='".$_POST["filesize"]."',down_author='".$_POST["fileautor"]."'
            ,down_filetype='".$_POST["filetype"]."',down_demo='".$_POST["filedemo"]."',down_txt='".$_POST["filetext"]."'
            WHERE down_id='".(int)$_GET["filedit"]."'");
            
            redirect("na-stiahnutie?filedit=".$_GET["filedit"]);
            
        }

echo '
<form class="form-horizontal" action="" method="post">
<div class="panel panel-default">
  <div class="panel-heading">Upraviť súbor #'.$data["down_name"].'
  <input name="savefile" type="submit" value="Uložiť súbor" class="btn btn-success btn-xs pull-right">
  </div>
  <div class="panel-body">

  <div class="form-group">
    <label for="filename" class="col-sm-3 control-label">Názov súboru:</label>
    <div class="col-sm-9">
      <input type="text" class="form-control" id="filename" name="filename" value="'.$data["down_name"].'">
    </div>
  </div>
  <div class="form-group">
    <label for="fileurl" class="col-sm-3 control-label">Súbor:</label>
    <div class="col-sm-9">
      <input type="text" class="form-control" id="fileurl" name="fileurl" value="'.$data["down_file"].'">
    </div>
  </div>
  <div class="form-group">
    <label for="fileimgview" class="col-sm-3 control-label">Veľký náhlad:</label>
    <div class="col-sm-9">
      <input type="text" class="form-control" id="fileimgview" name="fileimgview" value="'.$data["down_imgview"].'">
    </div>
  </div>
  <div class="form-group">
    <label for="filekat" class="col-sm-3 control-label">Kategória súboru:</label>
    <div class="col-sm-9">';
$result = dbquery("SELECT * FROM bg_downloadcats");
        $rows1 = dbrows($result);

	if ($rows1 >= "1") {
		echo '<select name="filekat" class="form-control">';
            echo '<option value="'.$data["down_cat"].'" selected>'.downloadcat($data["down_cat"]).'</option>';
	        echo '<option value="">- Výber kategórie -</option>';
		while($data1 = dbarray($result)){
			echo '<option value="'.$data1["downc_id"].'">'.$data1["downc_name"].'</option>';
		}
		echo '</select>';
		
	}else {
		echo "Žiadne kategorie k downloadom.";
	}
echo '
    </div>
  </div>
  <div class="form-group">
    <label for="fileimg" class="col-sm-3 control-label">Náhľad (malý):</label>
    <div class="col-sm-9">
';

		echo '<select name="fileimg" class="form-control">';
    echo '<option value="'.$data["down_img"].'" selected>'.$data["down_img"].'</option>';
	echo '<option value="">- Výber náhlad súboru -</option>';
$handle=opendir('../../data.desart.sk/downloadimg'); 
while (false!==($file = readdir($handle))) 
{ 
    if ($file != "." && $file != "..") 
    { 
        echo '<option value="'.$file.'">'.$file.'</option>';
    } 
}
closedir($handle);
		echo '</select>
    </div>
  </div>
  <div class="form-group">
    <label for="filesize" class="col-sm-3 control-label">Veľkosť:</label>
    <div class="col-sm-9">
      <input type="text" class="form-control" id="filesize" name="filesize" value="'.$data["down_filesize"].'">
    </div>
  </div>
  <div class="form-group">
    <label for="fileautor" class="col-sm-3 control-label">Autor:</label>
    <div class="col-sm-9">
      <input type="text" class="form-control" id="fileautor" name="fileautor" value="'.$data["down_author"].'">
    </div>
  </div>
  <div class="form-group">
    <label for="filetype" class="col-sm-3 control-label">Typ súboru:</label>
    <div class="col-sm-9">
      <input type="text" class="form-control" id="filetype" name="filetype" value="'.$data["down_filetype"].'">
    </div>
  </div>
  <div class="form-group">
    <label for="filedemo" class="col-sm-3 control-label">Live demo:</label>
    <div class="col-sm-9">
      <input type="text" class="form-control" id="filedemo" name="filedemo" value="'.$data["down_demo"].'">
    </div>
  </div>
  <div class="form-group">
    <label for="filetext" class="col-sm-3 control-label">Popis:</label>
    <div class="col-sm-9">
      <textarea class="form-control" id="filetext" name="filetext">'.$data["down_txt"].'</textarea>
    </div>
  </div>

  </div>
</div>
</form>
';
}

echo '
<div class="panel panel-default">
  <div class="panel-heading">Súbory</div>
   <table class="table table-striped table-hover" align="center" cellpadding="0" cellspacing="1" width="100%">
    
    <tr>
    <td width="5%"><strong>ID</strong></td>
    <td width="40%"><strong>Názov súboru</strong></td>
    <td><strong>Počet stiahnutí</strong></td>
    <td width="18%"></td>
    </tr>
';


if(isset($_GET["filedel"])){

	dbquery("DELETE FROM bg_downloads WHERE down_id='".$_GET["filedel"]."'");
	redirect("na-stiahnutie");

}


$result = dbquery("SELECT * FROM bg_downloads ORDER BY down_id DESC");
        $rows1 = dbrows($result);

	if ($rows1 >= "1") {

        while($data = dbarray($result)){
            
		echo '<tr>
		<td><span class="label label-default">'.$data["down_id"].'</span></td>
		<td>'.$data["down_name"].'</td>
		<td>'.$data["down_reads"].'</td>
		<td align="right"><a href="?filedit='.$data["down_id"].'" class="label label-primary">Upraviť</a> <a href="?filedel='.$data["down_id"].'" class="label label-danger" onclick="return confirm(\'Zmazať súbor?\');">Kôš</a></td>
		</tr>';

	}

		}else{
			echo "Žiadne downloady.";
		}

echo '</table></div>';

	if(isset($_POST["add"]) AND $_POST["filename"] != "" AND $_POST["filetext"] != ""){

		$nazov = addslashes(strip_tags($_POST["filename"]));
		$text = addslashes($_POST["filetext"]);
		$kat = $_POST["filekat"];
		$img = $_POST["fileimg"];
		$file = $_POST["fileurl"];
		$imgbig = $_POST["fileimgview"];
		$demo = $_POST["filedemo"];
		$filetype = $_POST["filetype"];
		$filesize = $_POST["filesize"];
		$fileau = $_POST["fileautor"];

     dbquery("INSERT INTO bg_downloads(down_file, down_txt, down_img, down_name, down_cat, down_userid, down_date, down_imgview, down_demo, down_filetype, down_filesize, down_author)VALUES('".$file."','".$text."','".$img."','".$nazov."','".$kat."','".$userinfo["user_id"]."','".time()."','".$imgbig."','".$demo."','".$filetype."','".$filesize."','".$fileau."')");
        redirect("na-stiahnutie");
	}

echo '
<form class="form-horizontal" action="" method="post">
<div class="panel panel-default">
  <div class="panel-heading">Nový súbor
  <input name="add" type="submit" value="Pridať súbor" class="btn btn-success btn-xs pull-right">
  </div>


  <div class="panel-body">

  <div class="form-group">
    <label for="filename" class="col-sm-3 control-label">Názov súboru:</label>
    <div class="col-sm-9">
      <input type="text" class="form-control" id="filename" name="filename">
    </div>
  </div>
  <div class="form-group">
    <label for="fileurl" class="col-sm-3 control-label">Súbor:</label>
    <div class="col-sm-9">
      <input type="text" class="form-control" id="fileurl" name="fileurl">
    </div>
  </div>
  <div class="form-group">
    <label for="fileimgview" class="col-sm-3 control-label">Veľký náhlad:</label>
    <div class="col-sm-9">
      <input type="text" class="form-control" id="fileimgview" name="fileimgview">
    </div>
  </div>
  <div class="form-group">
    <label for="filekat" class="col-sm-3 control-label">Kategória súboru:</label>
    <div class="col-sm-9">';
$result = dbquery("SELECT * FROM bg_downloadcats");
        $rows1 = dbrows($result);

	if ($rows1 >= "1") {
		echo '<select name="filekat" class="form-control">';
	        echo '<option value="">- Výber kategórie -</option>';
		while($data1 = dbarray($result)){
			echo '<option value="'.$data1["downc_id"].'">'.$data1["downc_name"].'</option>';
		}
		echo '</select>';
		
	}else {
		echo "Žiadne kategorie k downloadom.";
	}
echo '
    </div>
  </div>
  <div class="form-group">
    <label for="fileimg" class="col-sm-3 control-label">Náhľad (malý):</label>
    <div class="col-sm-9">
';

		echo '<select name="fileimg" class="form-control">';
	echo '<option value="">- Výber náhlad súboru -</option>';
$handle=opendir('../../data.desart.sk/downloadimg'); 
while (false!==($file = readdir($handle))) 
{ 
    if ($file != "." && $file != "..") 
    { 
        echo '<option value="'.$file.'">'.$file.'</option>';
    } 
}
closedir($handle);
		echo '</select>
    </div>
  </div>
  <div class="form-group">
    <label for="filesize" class="col-sm-3 control-label">Veľkosť:</label>
    <div class="col-sm-9">
      <input type="text" class="form-control" id="filesize" name="filesize">
    </div>
  </div>
  <div class="form-group">
    <label for="fileautor" class="col-sm-3 control-label">Autor:</label>
    <div class="col-sm-9">
      <input type="text" class="form-control" id="fileautor" name="fileautor">
    </div>
  </div>
  <div class="form-group">
    <label for="filetype" class="col-sm-3 control-label">Typ súboru:</label>
    <div class="col-sm-9">
      <input type="text" class="form-control" id="filetype" name="filetype">
    </div>
  </div>
  <div class="form-group">
    <label for="filedemo" class="col-sm-3 control-label">Live demo:</label>
    <div class="col-sm-9">
      <input type="text" class="form-control" id="filedemo" name="filedemo">
    </div>
  </div>
  <div class="form-group">
    <label for="filetext" class="col-sm-3 control-label">Popis:</label>
    <div class="col-sm-9">
      <textarea class="form-control" id="filetext" name="filetext"></textarea>
    </div>
  </div>

  </div>

</div>
</form>
';

require("inc/footer.php"); ?>