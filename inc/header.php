<?php if (!defined('PERM')) { die(); } 
	
	if($setting["modoffline"] == "1" && !ADMIN && !userperm("4")){redirect("/modudrzby");}
    $panel = (isset($panelchange) ? $panelchange : "");

?>
<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title><?php echo $header->title; ?></title>
    <meta name="author" content="Desart.sk"/>
    <meta name="description" content="<?php echo $header->desc ?>"/>
    <meta name="keywords" content="<?php echo $header->atags ?>"/>
    <meta name="robots" content="index, follow">
    <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php switch ($header->socialmeta) {
    case 1:
  ?>
    <link rel="image_src" href="<?php echo $header->metaimage ?>" />

    <meta property="og:type" content="article"/>
    <meta property="og:image" content="<?php echo $header->metaimage ?>"/>
    <meta property="og:title" content="<?php echo $header->title ?>"/>
    <meta property="og:url" content="<?php echo $header->url ?>"/>
    <meta property="og:description" content="<?php echo $header->desc ?>"/>

    <meta name="twitter:card" content="summary">
    <meta name="twitter:url" content="<?php echo $header->url ?>">
    <meta name="twitter:description" content="<?php echo $header->desc ?>">
    <meta name="twitter:image" content="<?php echo $header->metaimage ?>">
  <?php
    break;
    default:
    break;
  }
  ?>
	<script type="text/javascript" src="/css/jquery-latest.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
    <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" type="text/css" rel="stylesheet">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
	<link rel="stylesheet" href="/css/default.min.css" type="text/css" media="screen">
	<link rel="shortcut icon" href="/css/img/favicon.ico" type="image/x-icon">
</head>
<body>
<div class="header">
	<div class="container">
    <div class="navbar-header">
        <a href="/" class="navbar-brand"><img alt="desart" src="/css/img/logo.png"></a>
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar">
            <span class="fa fa-bars"></span>
        </button>
    </div>
    <div id="navbar" class="collapse navbar-collapse">
        <ul class="nav navbar-nav" style="margin-left:20px">
			<li class="active"><a href="/">Úvod</a></li>
			<li><a href="/forum">Diskusné fórum</a></li>
			<li><a href="/clanky">Články</a></li>
			<li><a href="/serieclankov">Série článkov</a></li>
			<li><a href="/subory">Súbory</a></li>
        </ul>
        <ul class="nav navbar-nav pull-right">
<? 
if(MEMBER){

$unreadmessages = dbcount("(mes_id)", "bg_messages","mes_touserid='".$userinfo["user_id"]."' AND mes_read='0'");

                echo '
<li class="dropdown">
  <a href="" class="dropdown-toggle" data-toggle="dropdown"><img src="'.useravatar($userinfo["user_id"]).'" class="img-circle" alt="avatar" width="20" height="20"/> '.$userinfo["user_nick"].' <span class="caret"></span></a>
    <ul class="dropdown-menu">
    <li><a role="menuitem" tabindex="-1" class="profillink" data-target="'.$userinfo["user_id"].'"><i class="fa fa-eye"></i> Zobraziť profil</a></li>
    <li><a role="menuitem" tabindex="-1" href="/uzivatel/profil"><i class="fa fa-wrench"></i> Nastavenia profilu</a></li>
    <li><a role="menuitem" tabindex="-1" href="#chat" id="showchat"><i class="fa fa-comments-o"></i> Správy <span class="badge">'.$unreadmessages.'</span></a></li>';
    if(userperm("4") OR userperm("2") or userperm("3")){echo ' <li role="presentation"><a role="menuitem" tabindex="-1" href="/manager"><i class="fa fa-gears"></i> Administrácia</a></li>';}
    echo '<li class="divider"></li>
    <li role="presentation"><a role="menuitem" tabindex="-1" href="/uzivatel/pridatclanok"><i class="fa fa-font"></i> Napísať článok</a></li>';
    echo '<li class="divider"></li>
    <li><a role="menuitem" tabindex="-1" href="?logout"><i class="fa fa-power-off"></i> Odhlásiť</a></li>
    </ul>
</li>
                ';

}else{ 
echo '<li><button type="button" data-toggle="modal" data-target="#login" class="btn btn-default navbar-btn btn-sm">Prihlásiť sa</button></li>';
}
?>
        </ul>
    </div>
	</div>
</div>


<div class="subheader">
<div class="container">

<?php if(odkazactiveself("/clanok") OR odkazactiveself("/tema")){$hidesubhead = " hidediv"; }else{$hidesubhead = "";} ?>

<div class="subheaderhide<?php echo $hidesubhead; ?>">
  <div class="row">
        <div class="col-md-12">
            
            <h4>Diskusné <strong>fórum</strong></h4>
            
            <div class="boxforum">
                <div class="boxforumscroll">
            
                <div class="boxpadding">
                <div class="boxforumchangeajax"></div>
                <div class="boxforumchangeajaxhide">
<?php

	$result_subheader4 = dbquery("SELECT forumt_name,forumt_newpost,forumt_reads,forumt_id,forumt_fid,forumt_fav,forumt_userid,forumt_locked,forumt_time FROM bg_forumtopic WHERE forumt_fid<>'8' ORDER BY forumt_newpost DESC LIMIT 0,20");
			
			
		while($dataf3 = dbarray($result_subheader4)){
		
				$postcount = dbcount("(post_id)", "bg_forumtopicpost"," post_topicid='".$dataf3["forumt_id"]."'");
				$limitp = "15";
				$stranatema = ceil($postcount/$limitp);
				
				$odpovedi = dbcount("(post_id)", "bg_forumtopicpost"," post_topicid='".$dataf3["forumt_id"]."'")-1;
				if(MEMBER){
                    if($dataf3["forumt_locked"] == 0){
					   if (forumtopicread($userinfo["user_id"],$dataf3["forumt_id"],$postcount)) {
					   $newp = "";
					   }else{
					   $newp = "activityotvr";
					   }
                    }else{
                     $newp = ""; 
                    }
					
				}else{
				$newp = "";
				}

                    echo '
                    <div class="forumtema">
                        <div class="row">
                            <div class="col-sm-7 col-xs-7 col-md-7 temainfo">
                                <div class="cat">'.forumcat($dataf3["forumt_fid"]).'</div>
                                <a href="/tema/'.$dataf3["forumt_id"].'/'.bezd($dataf3["forumt_name"]).($stranatema > 1 ? "?strana=".$stranatema:"").'" title="'.$dataf3["forumt_name"].'">'.trimlink($dataf3["forumt_name"],60).'</a>
                            </div>
                            <div class="col-sm-2 col-md-2">
                                <span class="infotema pull-right">
                                Autor: '.username($dataf3["forumt_userid"],1).'<br/>
                                Príspevkov: '.$odpovedi.'x
                                </span>
                            </div>
                           <div class="col-sm-2 col-md-2">
                                <span class="infotema pull-right">
                                Autor: '.username($dataf3["forumt_userid"],1).'<br/>
                                Príspevkov: '.$odpovedi.'x
                                </span>
                            </div>
                            <div class="col-lg-1 col-sm-2 col-md-1 hidden-xs"><a href="/tema/'.$dataf3["forumt_id"].'/'.bezd($dataf3["forumt_name"]).($stranatema > 1 ? "?strana=".$stranatema:"").'" class="otvr '.($newp=="activityotvr" ? $newp:($dataf3["forumt_fav"]==1 ? "favotvr":"")).'" title="posledná aktivita '.(forumtopic1post($dataf3["forumt_id"]) != ": " ? forumtopic1post($dataf3["forumt_id"]):"").'">Zobraziť</a></div>
                        </div>
                    </div>
                    ';

		}

?>
                </div>
                </div>
            
            </div>
            </div>
            <?php if(MEMBER){ ?>
            <div class="forumlinks"><a href="/forum">Založiť novú tému</a><a href="#" id="oblubenetemy">Obľúbené témy</a><a href="#" id="mojetemyshow">Moje témy</a><a href="#" id="aktivnetemyshow">Posledná aktivita</a><a href="#" id="aktivnetemymyshow">Moja posledná aktivita</a></div>
            <?php }else{ ?>
            <div class="forumlinks">Pred prispievaním do fóra sa musíš <a href="/registracia">zaregistrovať</a> alebo <a href="#login" data-toggle="modal" data-target="#login">prihlásiť</a>.</div>
            <?php } ?>

        </div>
  </div>

</div>
<?php if(odkazactiveself("/tema")){

$subheadertitle_forum = dbquery("SELECT forumt_name,forumt_userid,forumt_time,forumt_id FROM bg_forumtopic WHERE forumt_id='".(int)$_GET["idt"]."'");
$forumtitle = dbarray($subheadertitle_forum);

?>

<div class="subheadtitle">
<div class="row">
    <div class="col-xs-2 col-sm-2 col-md-1 col-lg-1 visible-md visible-lg">
    <img src="<? echo useravatar($forumtitle["forumt_userid"]); ?>" class="img-circle" style="max-width:70px;">
    </div>
    <div class="col-xs-6 col-sm-7 col-md-9">
        <div style="font-size:16px;color:#dae2ef;line-height:35px"><? echo $forumtitle["forumt_name"]; ?></div>
        <div style="font-size:12px;color:#666b76;"><span class="label label-warning"><? echo (dbcount("(post_id)", "bg_forumtopicpost"," post_topicid='".(int)$_GET["idt"]."'")-1); ?> odpovedí</span> Pridané <? echo timeago($forumtitle["forumt_time"]); ?></div>
    </div>
    <div class="col-md-2">
        <div class="forumlinks"><a href="#" id="hidesubheader" class="buttonin">Najnovšia aktivita</a></div>
    </div>
</div>
</div>

<?php } ?>
<?php if(odkazactiveself("/clanok")){

$subheadertitle_forum2 = dbquery("SELECT * FROM bg_articles WHERE article_id='".(int)$_GET["id"]."'");
$forumtitle = dbarray($subheadertitle_forum2);

?>

<div class="subheadtitle">
<div class="row">
    <div class="col-xs-2 col-sm-2 col-md-1 col-lg-1">
    <img src="http://data.desart.sk/articles/<? echo $forumtitle["article_img"]; ?>" class="img-circle img-responsive">
    </div>
    <div class="col-xs-6 col-sm-7 col-md-9">
        <div style="font-size:16px;color:#dae2ef;line-height:35px"><? echo $forumtitle["article_name"]; ?></div>
        <div style="font-size:12px;color:#666b76;"><a href="11" class="label label-warning"><? echo articlecat($forumtitle["article_cat"]); ?></a> Pridané <? echo timeago($forumtitle["article_date"]); ?></div>
    </div>
    <div class="col-md-2">
        <a href="#favorite" class="btn btn-default btn-border favadd" id="favadd" style="border-color:#2ecc71" data-idserial="22" data-sfaved="0">Pridať do obľúbených</a>
    </div>
</div>
</div>

<?php } ?>
</div>
</div>


<div class="colorline"></div>

    
<div class="container">
  <div class="midpadding">
      
<? if(MEMBER && $unreadmessages>=1){ notification('Máte novú/é sukromné správy! <a href="#chat" id="showchat2">Otvoriť súkromné správy</a>',"warning","20000"); } ?>

      <div class="row">
          
<?php if($panel != ""){ ?>
        <div class="col-md-8">
<?php }else{ ?>
        <div class="col-md-12">
<?php } ?>