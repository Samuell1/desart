<? $titlew="Hlavné nastavenia"; require("../inc/settings.php"); require("inc/header.php"); ?>

<? if(!SADMIN){ redirect("/"); } ?>

  <div class='padding'>
    <h3>Hlavné nastavenia</h3>
                                
  
  <?
  if(isset($_GET["saved"])){
	echo '<div class="tip-green border">Nastavenia úspešne aktualizované.</div>';
  }
  
  
  if(isset($_POST["savesettings"])){

	$afk = $_POST["modudrzby"];
	$description = strip_tags($_POST["desc"]);
	$keywords = strip_tags($_POST["keywords"]);
	$name = strip_tags($_POST["title"]);
	$afk = $_POST["modudrzby"];
	
	dbquery("UPDATE bg_settings SET title='".$name."',modoffline='".$afk."',description='".$description."',keywords='".$keywords."'");
			
	redirect("/uzivatel/admin/hlavne?saved");
}


  
  ?>
     
    <form method="post" action="">
    <table align="center" cellpadding="0" cellspacing="1" width="100%">
	
    <tr>
    <td class="tbl1" align="right">Názov webu:</td>
    <td class="tbl1"><input name="title" class="textbox" value="<? echo $setting["title"];?>" style="width: 250px;" type="text"></td>
    </tr>
    
    <tr>
    <td class="tbl1" align="right">Popis webu:</td>
    <td class="tbl1"><textarea name="desc" class="textbox" style="width:350px;" rows="2"><? echo $setting["description"];?></textarea></td>
    </tr>
    
    <tr>
    <td class="tbl1" align="right">Kľúčové slová:</td>
    <td class="tbl1"><textarea name="keywords" class="textbox" style="width:350px;" rows="2"><? echo $setting["keywords"];?></textarea></td>
    </tr>
    
	<tr><td class="tbl1" align="right"> Mód údržby: </td> <td class="tbl1"><select name="modudrzby" class="textbox">
	<?
        if($setting["modoffline"] == 0){
        echo "<option value='0' selected>Vyp.</option>";
        echo "<option value='1'>Zap.</option>";
        }else{
        echo "<option value='1' selected>Zap.</option>";
        echo "<option value='0'>Vyp.</option>";
        }
		?>
</select></td></tr>
        
    <tr>
    <td class="tbl1" align="right"></td>
    <td class="tbl1"><input class="button" id="" name="savesettings" value="Uložit" type="submit"></td>
    </tr>
    
    </table>
    </form>
    </div>

<? 
require("inc/footer.php"); ?>