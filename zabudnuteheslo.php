<? $titlew="Nové heslo"; require("inc/settings.php"); require("inc/header.php"); ?>
      <div class="bigtitlecenter">
        <div class="bigtitle">
            <h5>Zabudnuté heslo</h5>
            Pre reset hesla zadajte prosím, nasledujúce informácie.
        </div>
      </div>
<?php

if(MEMBER){ redirect("/"); }

if(isset($_GET["error"]) && $_GET["error"] == "ok"){
echo '<div class="alert alert-success">Vaše heslo bolo zmenené. Teraz sa môžete prihlásiť.</div>';
}else if(isset($_GET["error"]) && $_GET["error"] == "exist"){
echo '<div class="alert alert-danger">Vaše konto neexistuje alebo ste zadali zlé informácie.</div>';
}else if(isset($_GET["error"]) && $_GET["error"] == "send"){
echo '<div class="alert alert-success">Na email Vám bol odoslaný odkaz na obnovenie hesla.</div>';
}else if(isset($_GET["error"]) && $_GET["error"] == "email"){
echo '<div class="alert alert-danger">Prosím zadajte správny email.</div>';
}else if(isset($_GET["error"]) && $_GET["error"] == "antispam"){
echo '<div class="alert alert-danger">Zlé ščítané čísla.</div>';
}else if(isset($_GET["error"]) && $_GET["error"] == "pass"){
echo '<div class="alert alert-danger">Minimálna dĺžka hesla je 6 znakov!</div>';
}else if(isset($_GET["error"]) && $_GET["error"] == "passmath"){
echo '<div class="alert alert-danger">Heslá sa nezhodujú.</div>';
}



if(isset($_GET["resetpass"])){
        if (!preg_match("/^[0-9a-z]{32}$/", $_GET['resetpass'])) { redirect("/"); }
     $result = dbquery("SELECT * FROM bg_users WHERE user_usercode='".mysql_real_escape_string(strip_tags(trim($_GET["resetpass"])))."' AND user_active='0'");
     $active = dbarray($result);
     $rows = dbrows($result);
            if($rows == 1){
			
               if(isset($_POST["lostpw2"])){
			   
			   $passr = trim($_POST["newpass"]);
			   $passr2 = trim($_POST["newpass2"]);

            if($passr != $passr2){redirect("?resetpass=".strip_tags(trim($_GET["resetpass"]))."&error=passmath");}
                   
			$newpass = md5(md5(md5($passr)));
			
			         if(strlen($passr) >= 6){

		dbquery("UPDATE bg_users SET user_active='1' WHERE user_id='".$active["user_id"]."'");
		dbquery("UPDATE bg_users SET user_password='".$newpass."' WHERE user_usercode='".$_GET["resetpass"]."'");
		
			         }else{ redirect("?resetpass=".strip_tags(trim($_GET["resetpass"]))."&error=pass"); }
		
                redirect("?error=ok");
                     
                   
               }

echo '
<form action="" method="post" class="form-horizontal" role="form">
  <div class="form-group">
    <label class="col-sm-4 control-label">E-mail:</label>
    <div class="col-sm-5">
      <p class="form-control-static">'.$active["user_email"].'</p>
    </div>
  </div>
  <div class="form-group">
    <label for="newpass" class="col-sm-4 control-label">Nové heslo:</label>
    <div class="col-sm-5">
      <input id="newpass" name="newpass" type="password" class="form-control">
    </div>
  </div>
  <div class="form-group">
    <label for="newpass" class="col-sm-4 control-label">Zopakujte nové heslo:</label>
    <div class="col-sm-5">
      <input id="newpass" name="newpass2" type="password" class="form-control">
    </div>
  </div>
  <div class="form-group">
    <div class="col-sm-offset-4 col-sm-5">
      <div class="checkbox">
        <label>
          <input class="btn btn-success" name="lostpw2" value="Resetovať heslo" type="submit">
        </label>
      </div>
    </div>
  </div>
</form>
';

            }else{
				redirect("?error=exist");
            }

}else{

if(isset($_POST["reset"])){

if(isset($_POST['cislo']) &&  $_POST['cislo']== $_SESSION['control2']) {

	if(preg_match("/^[^@]*@[^@]*\.[^@]*$/",strip_tags($_POST["email"]))){

		$result8 = dbquery("SELECT * FROM bg_users WHERE user_email='".strip_tags(dbescape($_POST["email"]))."' AND user_nick='".strip_tags(dbescape($_POST["meno"]))."' AND user_active='1'");

		        $rows6 = dbrows($result8);

            if($rows6 == 1 && strip_tags($_POST["meno"]) != ""){
     		$active =  dbarray($result8);
			
			$mdcode = md5($active["user_nick"].rand(10,99));

			$url = "http://desart.sk/noveheslo?resetpass=".$mdcode;
			
                    $to      = strip_tags($_POST["email"]);
                    $subject = 'Desart - nové heslo';
                    $message = "<html>Pre nové hesla kliknite sem: ".nl2br($url)."</html>";

	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
	$headers .= 'From: Desart <noreply@desart.sk>' . "\r\n";

	mail($to, $subject, $message, $headers);
		dbquery("UPDATE bg_users SET user_active='0' WHERE user_id='".$active["user_id"]."'");
		dbquery("UPDATE bg_users SET user_usercode='".$mdcode."' WHERE user_id='".$active["user_id"]."'");
		
						redirect("?error=send");

            }else{  redirect("?error=exist"); }
			
			}else{ redirect("?error=email"); }

            }else{ redirect("?error=antispam"); }
}

$num1 = rand(1,9);
$num2 = rand(1,9);
$_SESSION['control2'] = $num1 + $num2;
echo '
<form action="" method="post" class="form-horizontal" role="form">
  <div class="form-group">
    <label for="meno" class="col-sm-4 control-label">Uživateľské meno:</label>
    <div class="col-sm-5">
      <input id="meno" name="meno" type="text" class="form-control">
    </div>
  </div>
  <div class="form-group">
    <label for="email" class="col-sm-4 control-label">E-mail:</label>
    <div class="col-sm-5">
      <input id="email" name="email" type="text" value="@" class="form-control">
    </div>
  </div>
  <div class="form-group">
    <label for="cislo" class="col-sm-4 control-label">Antispam:</label>
    <div class="col-sm-5">
        <div class="input-group">
        <span class="input-group-addon">'.$num1.' + '.$num2.' = </span>
        <input id="cislo" name="cislo" type="text" class="form-control" placeholder="zadaj výsledok">
        </div>
    </div>
  </div>
  <div class="form-group">
    <div class="col-sm-offset-4 col-sm-5">
      <div class="checkbox">
        <label>
          <input class="btn btn-success" name="reset" value="Resetovať heslo" type="submit">
        </label>
      </div>
    </div>
  </div>
</form>
';
}

require("inc/footer.php"); ?>