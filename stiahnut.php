<? require("inc/settings.php");
echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>';
		if (!ctype_digit($_GET['id'])){ redirect("/"); }
		
					if(MEMBER){
				if(dbcount("(down_id)", "bg_downloads"," down_id='".(int)$_GET['id']."'") == "1"){

			dbquery("UPDATE bg_downloads SET down_reads=down_reads+1 WHERE down_id='".(int)$_GET['id']."'");
	
				$subor = downloadsubor((int)$_GET['id']);
					sleep(5);
					redirect($subor);
	
				}else{ redirect("/nastiahnutie"); }
				
					}else{ echo "<center>Pre stiahnutie súboru musíš byť prihlásený!<br/> <a href='/registracia'>Zaregistruj</a> sa alebo <a href='/'>prihlás</a>."; }

?>