<?php require("inc/settings.php");

if($setting["modoffline"] == "0"){redirect("/");}
if(MEMBER && ADMIN){redirect("/");}

?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<center><h2>Mód údržby</h2>
<? if(!MEMBER){ ?>
  <form action="" method="post">
	<input type='email' name='user_email' value=''/>
	<input type='password' name='user_password' value=''/>
	<input type='submit' class='submit' value='Prihlásiť'/>
  </form>
<? } ?>