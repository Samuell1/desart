<? $titlew="Upload"; require("../inc/settings.php"); require("inc/header.php");


    $suborkoncovky = array("image/jpg","image/png","image/gif","application/zip","application/x-rar-compressed","application/x-rar","image/jpeg");
    $suborkoncovkycheck = array("image/jpeg" => ".jpg","image/jpg" => ".jpg","image/png" => ".png","image/gif" => ".gif","application/zip" => ".zip","application/x-rar-compressed" => ".rar","application/x-rar" => ".rar");

  function random($length = 8){

    $password = "";
    $possible = "2346789bcdfghjkmnpqrtvwxyzBCDFGHJKLMNPQRTVWXYZ";
    $maxlength = strlen($possible);
    if ($length > $maxlength) {
      $length = $maxlength;
    }
    $i = 0; 
    while ($i < $length) { 
      $char = substr($possible, mt_rand(0, $maxlength-1), 1);
      if (!strstr($password, $char)) { 
        $password .= $char;
        $i++;
      }

    }
    return $password;

  }



if(isset($_POST["up"])){

echo '
<div class="panel panel-default">
  <div class="panel-heading">Nahrané obrázky</div>
  <ul class="list-group">
';
    
    
    
    

for($i=0; $i<count($_FILES['file']['name']); $i++) {

  $tmpFilePath = $_FILES['file']['tmp_name'][$i];


  if ($tmpFilePath != ""){
        if(in_array($_FILES['file']['type'][$i],$suborkoncovky)){

    $rand = rand(100,999);
    $koncovka = $suborkoncovkycheck[$_FILES['file']['type'][$i]];
            
        if(isset($_POST["randomizename"]) && $_POST["randomizename"] == "1"){
            $name1 = random();
        }else if(isset($_POST["randomizename"]) && $_POST["randomizename"] == "2"){
            $name1 = basename($_FILES['file']['name'][$i],$koncovka);
        }else{
            $name1 = $rand.bezd(trim(basename($_FILES['file']['name'][$i],$koncovka)));
        }
       
            
    $name = $name1.$koncovka;
    $saveurl = $_POST["filedir"].$name;
            
    $podmienka_exist = ($_POST["randomizename"]=="2" ? 1:(!file_exists($saveurl)? 1:0));
            
            if ($podmienka_exist) {

    if($_POST["filedir"] == "../../data.desart.sk/articles/"){
        
        $img = @file_get_contents($weburl."inc/func/resize.php?i=".$_FILES["file"]["tmp_name"][$i]."&w=340&h=200");
        if($img){
            file_put_contents($saveurl, $img);
            echo '
    <li class="list-group-item list-group-item-success">
    <div class="input-group">
      <input type="text" class="form-control"  value="'.$weburl.'file/articles/'.$name.'">
      <span class="input-group-btn">
        <a href="http://data.desart.sk/file/articles/'.$name.'" target="_blank" class="btn btn-success" type="button">Zobraziť</a>
      </span>
    </div>
    </li>
            ';
        }else{
            echo '<li class="list-group-item list-group-item-danger">'.$_FILES['file']['name'][$i].' : error</li>';
        }

    }else if($_POST["filedir"] == "../../data.desart.sk/downloadimg/"){
        
        $img = @file_get_contents($weburl."inc/func/resize.php?i=".$_FILES["file"]["tmp_name"][$i]."&w=200&h=220");
        if($img){
            file_put_contents($saveurl, $img);
            echo '
    <li class="list-group-item list-group-item-success">
    <div class="input-group">
      <input type="text" class="form-control"  value="'.$weburl.'file/downloadimg/'.$name.'">
      <span class="input-group-btn">
        <a href="http://data.desart.sk/file/downloadimg/'.$name.'" target="_blank" class="btn btn-success" type="button">Zobraziť</a>
      </span>
    </div>
    </li>
            ';
        }else{
            echo '<li class="list-group-item list-group-item-danger">'.$_FILES['file']['name'][$i].' : error</li>';
        }

    }else if($_POST["filedir"] == "../../data.desart.sk/f/"){
        
        if(move_uploaded_file($tmpFilePath, $saveurl)) {
            echo '
    <li class="list-group-item list-group-item-success">
    <div class="input-group">
      <input type="text" class="form-control"  value="http://data.desart.sk/f/'.$name.'">
      <span class="input-group-btn">
        <a href="http://data.desart.sk/f/'.$name.'" target="_blank" class="btn btn-success" type="button">Zobraziť</a>
      </span>
    </div>
    </li>
            ';
        }

    }else{
        
        if(move_uploaded_file($tmpFilePath, $saveurl)) {
            echo '
    <li class="list-group-item list-group-item-success">
    <div class="input-group">
      <input type="text" class="form-control"  value="http://data.desart.sk/i/'.$name.'">
      <span class="input-group-btn">
        <a href="http://data.desart.sk/i/'.$name.'" target="_blank" class="btn btn-success" type="button">Zobraziť</a>
      </span>
    </div>
    </li>
            ';
        }

    }
            }else{
                echo '<li class="list-group-item list-group-item-danger">'.$_FILES['file']['name'][$i].' : error > file exists :-(</li>';
            }
        }else{
            echo '<li class="list-group-item list-group-item-danger">'.$_FILES['file']['name'][$i].' : error > ('.$_FILES['file']['type'][$i].')bad type :-(</li>';
        }
  }
}
echo '
  </ul>
</div>
';
}
  ?>


<form action="" method="post" enctype="multipart/form-data">
<div class="panel panel-default">
  <div class="panel-heading">Uploader <input type="button" id="newfile" value="Nový súbor" class="btn btn-default btn-xs pull-right"></div>
  <div class="panel-body">Multiuploader na obrázky, súbory. Povolené typy: <?php foreach ($suborkoncovky as $k => $v) {echo $suborkoncovkycheck[$v]." ";} ?></div>
  <ul class="list-group fileinputs">
    <li class="list-group-item filed"><input type="file" name="file[]" multiple></li>
    <li class="list-group-item filed"><input type="file" name="file[]" multiple></li>
  </ul>

  <div class="panel-footer">
      <span class="pull-right">
<div class="btn-group" data-toggle="buttons">
  <label class="btn btn-primary btn-sm active">
    <input type="radio" name="randomizename" value="0" checked> Aktuálny názov + 3 čísla
  </label>
  <label class="btn btn-primary btn-sm">
    <input type="radio" name="randomizename" value="2"> Aktuálny názov
  </label>
  <label class="btn btn-primary btn-sm">
    <input type="radio" name="randomizename" value="1"> Náhodný názov
  </label>
</div>
	       <input type="submit" name="up" value="Nahrať súbory" class="btn btn-success btn-sm">
      </span> 
    <select name="filedir" class="form-control" style="width:25%">
		<option value="../../data.desart.sk/i/">Obrázok</option>
		<option value="../../data.desart.sk/f/">Súbor</option>
        <option value="../../data.desart.sk/downloadimg/">Náhľad súboru (auto resize 200x220px)</option>
		<option value="../../data.desart.sk/articles/">Náhľad článku (auto resize 340x200px)</option>
	</select>
  </div>
</div>
</form>

<? require("inc/footer.php"); ?>