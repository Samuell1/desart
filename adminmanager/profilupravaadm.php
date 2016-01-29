<?php include("../inc/settings.php");

	$puserr = dbquery("SELECT * FROM bg_users WHERE user_id='".(int)$_GET["uid"]."'");
	$puser = dbarray($puserr);
	
	$validuser = dbrows($puserr);
	if($validuser == 0){die("Error.");}
	if(!ADMIN){die("Error.");}
	

echo '
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Profil užívateľa: '.$puser["user_nick"].'</h4>
      </div>
	  <div class="modal-body">
	  
<div id="messageprofedit"></div>
      
<form class="form-horizontal" action="" method="post" id="saveprofileform">

  <div class="form-group">
    <label class="col-sm-2 control-label">Uživateľké id</label>
    <div class="col-sm-10">
      <input type="text" class="form-control input-md" name="id" value="'.$puser["user_id"].'" readonly>
    </div>
  </div>

  <div class="form-group">
    <label for="nick" class="col-sm-2 control-label">Uživateľké meno</label>
    <div class="col-sm-10">
      <input type="text" class="form-control input-md" id="nick" name="nick" value="'.$puser["user_nick"].'">
    </div>
  </div>
  
  <div class="form-group">
    <label for="email" class="col-sm-2 control-label">Email</label>
    <div class="col-sm-10">
      <input type="email" class="form-control input-md" id="email" name="email" value="'.$puser["user_email"].'">
    </div>
  </div>

  <div class="form-group">
    <label for="perm" class="col-sm-2 control-label">Hodnosť</label>
    <div class="col-sm-10">
		<select class="form-control input-md" id="perm" name="perm">
';
			echo '<option value="'.$puser["user_perm"].'" selected>'.$adminprava[$puser["user_perm"]].'</option>';
			echo '<option value="">- Vyber hodnosť -</option>';
		for($i=1;$i<=count($adminprava);$i++){
			echo '<option value="'.$i.'">'.$adminprava[$i].' ('.$i.')</option>';
		}
echo '
		</select>
    </div>
  </div>
';

echo '
    </div>
      <div class="modal-header">
        <h4 class="modal-title">Zoznam banov</h4>
      </div>
    <div class="modal-body">
';


  $userbans = dbquery("SELECT * FROM da_bans WHERE ban_userid='".$puser["user_id"]."'");


echo '
  <div class="form-group">
    <div class="col-sm-12">
<ul class="list-group">
';

while ($data = dbarray($userbans)) {
 echo '
  <li class="list-group-item">'.$data["ban_reason"].'
    <span class="pull-right">
      <span class="label label-info">Od: '.date("d.m. Y H:i:s",$data["ban_time"]).'</span>
      <span class="label label-danger">Do: '.date("d.m. Y H:i:s",$data["ban_durationtime"]).'</span>
    </span>
  </li>
 ';
}

echo '
</ul>
    </div>
  </div>
';


echo '
    </div>
      <div class="modal-header">
        <h4 class="modal-title">Podobné ip:</h4>
      </div>
    <div class="modal-body">
';

  $detecti = dbquery("SELECT * FROM bg_users WHERE user_ip='".$puser["user_ip"]."' OR user_ip='".$puser["user_oldip"]."' OR user_oldip='".$puser["user_ip"]."' OR user_oldip='".$puser["user_oldip"]."'");


echo '
  <div class="form-group">
    <div class="col-sm-12">
<ul class="list-group">
';

while ($data = dbarray($detecti)) {
 echo '
  <li class="list-group-item">
    <span class="pull-right">
      <span class="label label-danger">IP: '.$data["user_ip"].'</span>
      <span class="label label-danger">OLDIP: '.($data["user_oldip"] ? $data["user_oldip"]:"none").'</span>
      <span class="label label-info">OS: '.($data["user_os"] ? $data["user_os"]:"none").'</span>
      <span class="label label-info">BROWSER: '.($data["user_browser"] ? $data["user_browser"]:"none").'</span>
    </span>
    '.$data["user_nick"].'
  </li>
 ';
}

echo '
</ul>
    </div>
  </div>
';
  
?>

	  </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">Zatvoriť</button>
        <input type="submit" class="btn btn-sm btn-success" id="saveprofile" name="saveprofile" value="Uložiť zmeny">
      </div>
</form>

<div class="clearfix"></div>

<script>
	$("#saveprofile").click(function(e){
	  e.preventDefault();
      $.ajax({
        type : 'post',
        url : '/adminmanager/profilupravasave.php', 
        data: $('#saveprofileform').serialize(),
		success : function()
           { 
			  $("#messageprofedit").html("<div class='alert alert-success'>Uživateľ upravený.</div>");
           }
		});
	});
</script>