<?php $titlew="Nastavenia profilu";  include("../inc/settings.php"); include("../inc/header.php"); ?>

      <div class="bigtitlecenter">
        <div class="bigtitle" id="profilmes">
            <h5>Nastavenia profilu: <? echo $userinfo["user_nick"]; ?></h5>
            V tejto sekcií si môžeš upraviť heslo, avatar, ale aj kontaktné údaje, či vybrať aké máš skúsenosti s grafikou a programovaním.
        </div>
      </div>

<?
if(!MEMBER){ redirect("/");}

if(isset($_GET["vys"]) && $_GET["vys"] == "ok"){
echo '<div class="alert alert-success">Profil úspešne aktualizovaný.</div>';
}else if(isset($_GET["vys"]) && $_GET["vys"]  == "no"){
echo '<div class="alert alert-danger">Zadaný email nieje správny!</div>';
}else if(isset($_GET["vys"]) && $_GET["vys"] == "avatar"){
echo '<div class="alert alert-danger">Neplatný typ souboru. Povolené typy: .PNG, .JPG, .GIF.</div>';
}else if(isset($_GET["vys"]) && $_GET["vys"]  == "avatare"){
echo '<div class="alert alert-danger">Nastala chyba pri uploade súboru.</div>';
}else if(isset($_GET["vys"]) && $_GET["vys"]  == "pass1"){
echo '<div class="alert alert-danger">Chyba heslá sa nezhodujú.</div>';
}else if(isset($_GET["vys"]) && $_GET["vys"]  == "pass2"){
echo '<divclass="alert alert-danger">Chyba. Nesprávne platné heslo.</div>';
}else if(isset($_GET["vys"]) && $_GET["vys"]  == "pass3"){
echo '<div class="alert alert-warning">Minimálna dĺžka hesla je 6 znakov!</div>';
}else if(isset($_GET["vys"]) && $_GET["vys"]  == "size"){
echo '<div class="alert alert-warning">Maximálna dĺžka obrázka je 150x150 pixelov a veľkosť 1MB.</div>';
}else if(isset($_GET["vys"]) && $_GET["vys"]  == "emailf"){
echo '<div class="alert alert-danger">Tento email už niekto používa.</div>';
}

$result = dbquery("SELECT * FROM bg_users WHERE user_id='".$userinfo["user_id"]."'");

if(isset($_GET["reavatar"])){
	dbquery("UPDATE bg_users SET user_avatar='' WHERE user_id='".$userinfo["user_id"]."'");
	unlink($userinfo["user_avatar"]);
	redirect("/uzivatel/profil?ok#profilmes");
}

if(isset($_POST["update_profil"])){

			if(isset($_POST["email"]) && $_POST["email"] != $userinfo["user_email"]){
				if(preg_match("/^[^@]*@[^@]*\.[^@]*$/",strip_tags($_POST["email"]))){
					dbquery("UPDATE bg_users SET user_active='1' WHERE user_id='".$userinfo["user_id"]."'");
				}else{
					redirect("?vys=no#profilmes");
				}
			}

$skype = mysql_real_escape_string(strip_tags(trim($_POST["skype"])));
$web = mysql_real_escape_string(strip_tags(trim($_POST["web"])));
$icq = mysql_real_escape_string(strip_tags(trim($_POST["icq"])));
$location = mysql_real_escape_string(strip_tags(trim($_POST["location"])));
$email = mysql_real_escape_string(strip_tags(trim($_POST["email"])));
$da = mysql_real_escape_string(strip_tags(trim($_POST["da"])));

$pass = mysql_real_escape_string(strip_tags($_POST["password"]));
$passnew = mysql_real_escape_string(strip_tags($_POST["new_password"]));
$passnew2 = mysql_real_escape_string(strip_tags($_POST["new_password2"]));

$emailhide = $_POST["emailhide"];
$profiletype = "1";
		
		
		
		$day = $_POST["day"];
		$month = $_POST["month"];
		$year = $_POST["year"];
		
		if($day != "" AND $month != "" AND $year != ""){
		$dateb = strtotime($day."-".$month."-".$year);
		}else{
		$dateb = "";
		}
		
		$result55 = dbquery("SELECT * FROM bg_users WHERE user_id<>'".$userinfo["user_id"]."' AND user_email='".$email."'");
		
		$rows55e = dbrows($result55);
		
			if($rows55e >= 1){
					redirect("?vys=emailf#profilmes");
			}

$info = mysql_real_escape_string(strip_tags($_POST["info"]));

		if($_FILES["avatar"]["tmp_name"] != ""){
   if (($_FILES["avatar"]["type"] == "image/gif") || ($_FILES["avatar"]["type"] == "image/jpeg") || ($_FILES["avatar"]["type"] == "image/png" )) {

	$avatarurl = $userinfo["user_id"].upload_koncovka($_FILES["avatar"]["type"]);
	
	if($_FILES["avatar"]["size"] < 1048576){
	
list($width, $height, $type, $attr) = getimagesize($_FILES["avatar"]["tmp_name"]);

			if($width == 100 AND $height == 100) {

    if(move_uploaded_file($_FILES["avatar"]["tmp_name"],"../../../data.desart.sk/avatars/".$avatarurl)){
	dbquery("UPDATE bg_users SET user_avatar='".$avatarurl."', user_info='".$info."',user_email='".$email."',user_deviantart='".$da."', 
user_web='".$web."',user_icq='".$icq."',user_skype='".$skype."',user_location='".$location."',user_emailhide='".$emailhide."',user_profiletype='".$profiletype."',user_birthday='".$dateb."' WHERE user_id='".$userinfo["user_id"]."'");
		redirect("?vys=ok");
	}else{
			redirect("?vys=avatare#profilmes");
	}
			}else{
			redirect("?vys=size#profilmes");
			}
	}else{
	redirect("?vys=size#profilmes");
	}

   }else{ redirect("?vys=avatar#profilmes"); }
		}else{
		
		dbquery("UPDATE bg_users SET user_info='".$info."',user_email='".$email."',user_deviantart='".$da."', 
user_web='".$web."',user_icq='".$icq."',user_skype='".$skype."',user_location='".$location."',user_emailhide='".$emailhide."',user_profiletype='".$profiletype."',user_birthday='".$dateb."' WHERE user_id='".$userinfo["user_id"]."'");

  if($pass == ""){ redirect("?vys=ok#profilmes"); }
  if($pass != ""){

         $result = dbquery("SELECT * FROM bg_users WHERE user_id='".$userinfo["user_id"]."'");

           $data2 = dbarray($result);
           if(md5(md5(md5($pass))) == $data2["user_password"]){

			if(strlen($passnew) >= 6){
		   
		if($passnew == $passnew2){
      dbquery("UPDATE bg_users SET user_password='".md5(md5(md5($passnew)))."' WHERE user_id='".$userinfo["user_id"]."'");
      redirect("?logout");
		}else{
           redirect("?vys=pass2#profilmes");
		}
			}else { redirect("?vys=pass3#profilmes"); }

           }else{
           redirect("?vys=pass2#profilmes");
           }
  }
  
  
		}
		
}

while($data = dbarray($result)) {
    
echo '

    <div class="page-header"><h4>Zmeniť heslo</h4></div>
<form role="form" name="editprof" method="post" action="" enctype="multipart/form-data" class="form-horizontal">
<div class="form-group">
    <label for="password" class="col-sm-2 control-label">Platné heslo:</label>
    <div class="col-sm-10"><input id="password" name="password" class="form-control" type="password"></div>
</div>

<div class="form-group">
    <label for="new_password" class="col-sm-2 control-label">Nové heslo:</label>
    <div class="col-sm-10"><input id="new_password" name="new_password" class="form-control" type="password"></div>
</div>

<div class="form-group">
    <label for="new_password2" class="col-sm-2 control-label">Zopakujte nové heslo:</label>
    <div class="col-sm-10"><input id="new_password2" name="new_password2" class="form-control" type="password"></div>
</div>

    <div class="page-header"><h4>Obrázok profilu</h4></div>
<div class="form-group">
<label for="avatar" class="col-sm-2 control-label"><img src="'.useravatar($userinfo["user_id"]).'" class="img-thumbnail"/></label>
    <div class="col-sm-10"><input type="file" name="avatar" id="avatar">
    <p class="help-block">Maximálna veľkosť obrázku 1MB<br/> Povolené typy: .PNG, .JPG, .GIF.<br/> Presná veľkosť 100x100 pixelov</p></div>
</div>

    <div class="page-header"><h4>Kontaktné údaje</h4></div>
    
<div class="form-group">
    <label for="email" class="col-sm-2 control-label">E-mail:</label>
     <div class="col-sm-10"><input id="email" name="email" value="'.$data["user_email"].'" class="form-control" type="text"></div>
</div>
<div class="form-group">
    <label for="skype" class="col-sm-2 control-label">Skype:</label>
     <div class="col-sm-10"><input id="skype" name="skype" value="'.$data["user_skype"].'" class="form-control" type="text"></div>
</div>
<div class="form-group">
    <label for="icq" class="col-sm-2 control-label">ICQ:</label>
     <div class="col-sm-10"><input id="icq" name="icq" value="'.$data["user_icq"].'" class="form-control" type="text"></div>
</div>
<div class="form-group">
    <label for="web" class="col-sm-2 control-label">Webová stránka:</label>
     <div class="col-sm-10"><input id="web" name="web" value="'.$data["user_web"].'" class="form-control" type="text"></div>
</div>
<div class="form-group">
    <label for="da" class="col-sm-2 control-label">DeviantArt:</label>
     <div class="col-sm-10"><div class="input-group">
        <input id="da" name="da" value="'.$data["user_deviantart"].'" class="form-control" type="text"><span class="input-group-addon">.deviantart.com</span>
    </div></div>
</div>

    <div class="page-header"><h4>Ostatné</h4></div>
<div class="form-group">
    <label for="emailhide" class="col-sm-2 control-label">Skryť Email v profile?</label>
    <div class="col-sm-2"><select id="emailhide" name="emailhide" class="form-control">';
	if($data["user_emailhide"]){
		echo '<option value="1" selected>Áno</option>';
		echo '<option value="0">Nie</option>';
	}else{
		echo '<option value="1">Áno</option>';
		echo '<option value="0" selected>Nie</option>';
	}
echo '</select></div>
</div>
<div class="form-group">
    <label class="col-sm-2 control-label">Dátum narodenia:</label>
<div class="col-sm-10 row">
  <div class="col-xs-3">
<select name="day" class="form-control">';
	if($data["user_birthday"] != "" AND date("j",$data["user_birthday"]) != ""){
	echo '<option selected>'.date("j",$data["user_birthday"]).'</option>';
	}
   echo '<option>- Deň -</option>';
for ($i = 1; $i <= 31; $i++) {
   echo '<option value="'.$i.'">'.$i.'</option>';
}	
echo '</select>
  </div>
  <div class="col-xs-3">
    <select name="month" class="form-control">';
	if($data["user_birthday"] != "" AND date("m",$data["user_birthday"]) != ""){
	echo '<option selected>'.date("m",$data["user_birthday"]).'</option>';
	}
   echo '<option>- Mesiac -</option>';
   
for ($i = 1; $i <= 12; $i++) {
   echo '<option value="'.$i.'">'.$i.'</option>';
}	
echo '</select>
  </div>
  <div class="col-xs-3">
<select name="year" class="form-control">';
	if($data["user_birthday"] != "" AND date("Y",$data["user_birthday"]) != ""){
	echo '<option selected>'.date("Y",$data["user_birthday"]).'</option>';
	}
   echo '<option>- Rok -</option>';
   
for ($i = 1950; $i <= 2013; $i++) {
   echo '<option value="'.$i.'">'.$i.'</option>';
}	
echo '</select>
  </div>
</div>
</div>
<div class="form-group">
    <label for="location" class="col-sm-2 control-label">Bydlisko:</label>
    <div class="col-sm-10"><input id="location" name="location" value="'.$data["user_location"].'" class="form-control" type="text"></div>
</div>
<div class="form-group">
    <label for="info" class="col-sm-2 control-label">O mne:</label>
    <div class="col-sm-10"><textarea id="info" name="info" class="form-control" rows="3" maxLength="500">'.$data["user_info"].'</textarea></div>
</div>
<div class="form-group">
    <label for="da" class="col-sm-2 control-label"></label>
    <div class="input-group">
        <div class="col-sm-10"><input name="update_profil" value="Aktualizovať profil" class="btn btn-success" type="submit"></div>
    </div>
</div>

</form>
';

}

include("../inc/footer.php"); ?>