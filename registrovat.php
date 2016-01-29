<? $titlew="Registrácia"; require("inc/settings.php"); require("inc/header.php"); ?>    

      <div class="bigtitlecenter">
        <div class="bigtitle">
            <h5>Vytvorenie nového účtu</h5>
            Prosím vyplňte <b>všetky</b> údaje - viac údajov si môžete doplniť po prihlásení.
        </div>
      </div>

<?
if(MEMBER){ redirect("/"); }

if(isset($_POST["vytvorit"])){

				if(isset($_POST['sbs']) && $_POST['sbs'] == ""){ // kontrola pred spam botmi
			
    if(isset($_POST['cislo']) && isset($_SESSION['spamkiller']) &&  $_POST['cislo'] == $_SESSION['spamkiller']) {

    	if(isset($_POST['podmienky']) == "1") {
     $user = dbescape(StrTr(strip_tags($_POST["meno"]), "ÁÄČÇĎÉĚËÍŇÓÖŘŠŤÚŮÜÝŽáäčçďéěëíňóöřšťúůüýž ", "AACCDEEEINOORSTUUUYZaaccdeeeinoorstuuuyz-"));
     $email = dbescape(strip_tags($_POST["email"]));

	$pass = md5(md5(md5($_POST["heslo"])));
	$pass2 = md5(md5(md5($_POST["heslo2"])));

	if($pass == $pass2){

	$result5 = dbquery("SELECT * FROM bg_users WHERE user_nick='".$user."'");
	
	$result55 = dbquery("SELECT * FROM bg_users WHERE user_email='".$email."'");

     $rows5 = dbrows($result5);
		 $rows55e = dbrows($result55);
		 
            if($rows5 == 0 && $rows55e == 0 && $user != ""){

			if(preg_match("/^[^@]*@[^@]*\.[^@]*$/",$email)){
		 
			if(strlen($user) >= 4 AND strlen($email) >= 4){
			
			if(strlen($pass) >= 6){

     dbquery("INSERT INTO bg_users(user_nick, user_password,user_email,user_active,user_datereg,user_lastactivity,user_ip,user_browser,user_os)
     VALUES('".$user."','".$pass."','".$email."','1','".time()."','".time()."','".$_SERVER["REMOTE_ADDR"]."','".getBrowser()."','".getOS()."')");

		echo '<div class="alert alert-success">Registrácia prebehla úspešne. Teraz sa môžete prihlásiť.</div>';

            }else{  echo '<div class="alert alert-warning">Minimálna dĺžka hesla je 6 znakov.</div>'; }
			
            }else{  echo '<div class="alert alert-warning">Minimálna dĺžka mena a emailu sú 4 znaky.</div>'; }
			
			}else{  echo '<div class="alert alert-warning">Prosím zadajte správny email.</div>'; }
			
            }else{  echo '<div class="alert alert-danger">Zadané meno alebo email už existuje.</div>'; }
			
			}else{  echo '<div class="alert alert-warning">Heslá sa nezhodujú.</div>'; }

            }else{  echo '<div class="alert alert-danger">Musíte súhlasiť s podmienkami!</div>'; }

            }else{  echo '<div class="alert alert-warning">Zlé opísané čísla.</div>'; }
			
				}

}


    

echo '
<form action="" method="post" class="form-horizontal" role="form">
  <div class="form-group">
    <label for="meno" class="col-sm-4 control-label">Uživateľské meno:</label>
    <div class="col-sm-5">
      <input id="meno" name="meno" type="text" value="'.(isset($_POST["meno"]) ?$_POST["meno"]:"").'" class="form-control">
    </div>
  </div>
  <div class="form-group">
    <label for="heslo" class="col-sm-4 control-label">Uživateľské heslo:</label>
    <div class="col-sm-5">
      <input id="heslo" name="heslo" type="password" class="form-control">
    </div>
  </div>
  <div class="form-group">
    <label for="heslo2" class="col-sm-4 control-label">Heslo znova:</label>
    <div class="col-sm-5">
      <input id="heslo2" name="heslo2" type="password" class="form-control">
    </div>
  </div>
  <div class="form-group">
    <label for="email" class="col-sm-4 control-label">E-mail:</label>
    <div class="col-sm-5">
      <input id="email" name="email" type="text" value="'.(isset($_POST["email"]) ?$_POST["email"]:"@").'" class="form-control">
    </div>
  </div>
  <div class="form-group">
    <label for="cislo" class="col-sm-4 control-label">Antispam:</label>
    <div class="col-sm-5">
        <div class="input-group">
        <span class="input-group-addon"><img src="http://desart.sk/inc/spamkiller.php"></span>
        <input id="cislo" name="cislo" type="text" class="form-control" placeholder="opíšte čísla a písmená">
        </div>
    </div>
  </div>
  <div class="form-group">
    <div class="col-sm-offset-4 col-sm-10">
      <div class="checkbox">
        <label>
          <input name="podmienky" value="1" type="checkbox"> Súhlasím so všetkými <a target="_blank" href="/stranky/pravidla">pravidlami a podmienkami</a>.
        </label>
      </div>
    </div>
  </div>
  <div class="form-group">
    <div class="col-sm-offset-4 col-sm-10">
      <div class="checkbox">
        <label>
          <input type="hidden" name="sbs" id="sbs"/>
          <input class="btn btn-success" name="vytvorit" value="Vytvorit účet" type="submit">
        </label>
      </div>
    </div>
  </div>
</form>
';
	?>

    
<? require("inc/footer.php"); ?>