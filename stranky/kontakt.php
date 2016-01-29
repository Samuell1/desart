<? $titlew="Kontakt"; require("../inc/settings.php"); require("../inc/header.php"); ?>


      <div class="bigtitlecenter">
        <div class="bigtitle">
            <h5>Kontakt</h5>
            Našli ste na webe chybu? Chcete sa nás niečo opýtať? Máte záujem stať sa súčasťou nášho tímu? Neváhajte a pošlite nám správu na email <strong>info(zavináč)desart.sk</strong> alebo nás kontaktujte súkromnou správou.
        </div>
      </div>

      <div class="bigtitlecenter">
        <div class="bigtitle">
            <h5>Aktuálny tím</h5>
        </div>
      </div>
<div class="row">
<? 


$result = dbquery("SELECT * FROM bg_users WHERE user_perm>='2' ORDER BY user_perm='3' DESC,user_perm='2' DESC,user_perm='4' DESC");

$rows1 = dbrows($result);

        while($data = dbarray($result)){
	 
	 echo '
    <div class="col-md-2 col-xs-6 col-sm-4 text-center">
        <div style="margin-bottom:20px">
        <img src="'.useravatar($data["user_id"]).'" class="img-circle img-responsive" alt="avatar" style="display:inline-block;width:120px;height:120px">
        <h5>'.username($data["user_id"],1).'</h5><small style="color:#888">'.$adminprava[$data["user_perm"]].'</small>
        </div>
    </div>
	 ';
	 
		}
?>
</div>


<?
require("../inc/footer.php"); ?>