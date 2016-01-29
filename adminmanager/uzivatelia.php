<? $titlew="Správa užívateľov"; require("../inc/settings.php"); require("inc/header.php"); ?>  

<? if(!ADMIN){ redirect("/"); } ?>

<div class="panel panel-default">
    <div class="panel-heading">Vyhľadávanie užívateľa</div>
    <div class="panel-body">
        <form action="" method="post">
        <div class="col-md-10">
            <input type="text" class="form-control" name="search" value="<? echo (isset($_GET["search"]) ? $_GET["search"]:""); ?>">
        </div>
        <div class="col-md-2">
            <input type="submit" class="btn btn-warning" value="Vyhľadať">
        </div>
        </form>
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">Správa užívateľov</div>
<? 

	if(isset($_GET["ban"])){
		dbquery("UPDATE bg_users SET user_ban='1' WHERE user_id='".$_GET["ban"]."'");
		redirect("uzivatelia".(isset($_GET["strana"])?"?strana=".$_GET["strana"]:"")."#user".$_GET["ban"]);
	}
	if(isset($_GET["unban"])){
		dbquery("UPDATE bg_users SET user_ban='0' WHERE user_id='".$_GET["unban"]."'");
		redirect("uzivatelia".(isset($_GET["strana"])?"?strana=".$_GET["strana"]:"")."#user".$_GET["unban"]);
	}


if(isset($_POST["search"])){
    redirect("?search=".htmlspecialchars($_POST["search"]));
}

if(isset($_GET["search"])){
    $searchuser = "WHERE user_nick LIKE '%".$_GET["search"]."%' OR user_ip LIKE '%".$_GET["search"]."%' OR user_browser LIKE '%".$_GET["search"]."%' OR user_os LIKE '%".$_GET["search"]."%' OR user_oldip LIKE '%".$_GET["search"]."%' OR user_email LIKE '%".$_GET["search"]."%'";
}else{$searchuser="";}


$result = dbquery("SELECT * FROM bg_users ".$searchuser);
$rows1 = dbrows($result);

	if ($rows1 >= "1") {
echo '
    <table class="table table-striped table-hover" align="center" cellpadding="0" cellspacing="1" width="100%">
    
    <tr>
    <td width="5%"><strong>ID</strong></td>
    <td><strong>Meno</strong></td>
    <td><strong>Email</strong></td>
    <td><strong>Hodnosť</strong></td>
    <td width="30%"></td>
    </tr>
';
        
	
if (isset($_GET['strana'])){
 $strana = (int)$_GET['strana'];

	if (!ctype_digit($_GET['strana'])){ redirect("/"); }

}else{
 $strana = 1;
}


	$limit = "50";
	$celkovy_pocet = $rows1;
	$pocet_stran = ceil($celkovy_pocet/$limit);
	$pociatok = ($strana*$limit)-$limit;
	
$result1 = dbquery("SELECT * FROM bg_users ".$searchuser." ORDER BY user_datereg DESC LIMIT $pociatok, $limit");


		if($strana >$pocet_stran){redirect("/");} 
	
        while($data = dbarray($result1)){

		echo '<tr id="user'.$data["user_id"].'">
		<td><span class="label label-default tttoggle" data-toggle="tooltip" data-placement="right" title="Preliadač: '.$data["user_browser"].', OS: '.$data["user_os"].'">'.$data["user_id"].'</span></td>
		<td>'.username($data["user_id"],1).'</td>
		<td>'.$data["user_email"].'</td>
		<td>'.$adminprava[$data["user_perm"]].'</td>
		<td align="right"><a href="#eprof" class="editprofiladm label label-success" data-target="'.$data["user_id"].'">Upraviť</a> '.($data["user_ban"]==1 ? "<a href='?unban=".$data["user_id"]."' class='label label-danger'>Odblokovať</a>":"<a href='".(isset($_GET["strana"])?"?strana=".$_GET["strana"]."&":"?")."ban=".$data["user_id"]."' class='label label-info'>Zablokovať</a>").'</td>
		</tr>';
		}
        echo "</table>";
        pagination($rows1,$limit,$pocet_stran,$strana);
		
		
		}else{
		echo "Žiadní užívatelia.";
		}

?>
</div>
<? require("inc/footer.php"); ?>