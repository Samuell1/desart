<?php if (!defined('PERM')) { die(); } ?>


<div class="list-group">
<?
$result = dbquery("SELECT * FROM bg_forumtopicpost GROUP BY post_topicid ORDER BY post_time DESC LIMIT 0,5");

$rows1 = dbrows($result);

	if ($rows1 >= "1") {
        while($data = dbarray($result)){
		
			$type = "/tema/".$data["post_topicid"]."/".bezd(forumtopicname($data["post_topicid"]));
            
        echo '<a href="'.$type.'" class="list-group-item">'.forumtopicname($data["post_topicid"]).'<br/><small>'.timeago($data["post_time"]).' od '.username($data["post_userid"],0).'</small></a>';
	 
		}
		
		}

?>
</div>

<?php
if(userperm("5")){

	if(isset($_POST["editmod"])){
		if($_POST["modset"] == 1){
            dbquery("DELETE FROM bg_forumtopicread WHERE forumr_tid='".$dataf2["forumt_id"]."'");
			dbquery("UPDATE bg_forumtopic SET forumt_locked='1',forumt_lockuserid='".$userinfo["user_id"]."' WHERE forumt_id='".$dataf2["forumt_id"]."'");
			redirect("/tema/".$dataf2["forumt_id"]."/".bezd($dataf2["forumt_name"]));
		}
		if($_POST["modset"] == 2){
			dbquery("UPDATE bg_forumtopic SET forumt_locked='0' WHERE forumt_id='".$dataf2["forumt_id"]."'");
			redirect("/tema/".$dataf2["forumt_id"]."/".bezd($dataf2["forumt_name"]));
		}
		if($_POST["modset"] == 3){
			dbquery("DELETE FROM bg_forumtopic WHERE forumt_id='".$dataf2["forumt_id"]."'");
			dbquery("DELETE FROM bg_forumtopicpost WHERE post_topicid='".$dataf2["forumt_id"]."'");
			dbquery("DELETE FROM bg_forumtopicread WHERE forumr_tid='".$dataf2["forumt_id"]."'");
			redirect("/forum");
		}
		if($_POST["modset"] == 4){
			dbquery("UPDATE bg_forumtopic SET forumt_fav='1' WHERE forumt_id='".$dataf2["forumt_id"]."'");
			redirect("/forum");
		}
		if($_POST["modset"] == 5){
			dbquery("UPDATE bg_forumtopic SET forumt_fav='0' WHERE forumt_id='".$dataf2["forumt_id"]."'");
			redirect("/forum");
		}
		if($_POST["modset"] == 6){
			dbquery("UPDATE bg_forumtopic SET forumt_fid='".$_POST["changeforum"]."' WHERE forumt_id='".$dataf2["forumt_id"]."'");
			redirect("/tema/".$dataf2["forumt_id"]."/".bezd($dataf2["forumt_name"]));
		}
	}
echo '<div class="list-group"><a class="list-group-item active">Moderátorské menu</a><div class="list-group-item">';

echo '<form action="" method="POST">
<select name="modset" id="select1" class="form-control">
<option value="1">Zamknúť tému</option>
<option value="2">Odomknúť tému</option>
<option value="3">Odstrániť tému</option>
<option value="4">Obľúbiť tému</option>
<option value="5">Neobľúbiť tému</option>
<option value="6">Presunúť tému</option>
</select>
</div>
<div class="list-group-item list-group-item-warning" id="hide1modf" style="display:none;">
<select name="changeforum" class="form-control">';

		$resultf = dbquery("SELECT * FROM bg_forum ORDER BY forum_name");
		
		while($dataf = dbarray($resultf)){
		echo '<option value="'.$dataf["forum_id"].'">'.$dataf["forum_name"].'</option>';
		}
		
echo '</select>
</div>
<div class="list-group-item"><input name="editmod" class="btn btn-success" value="Vykonať" type="submit"></div>
</form>';
echo '</div>';
}
?>