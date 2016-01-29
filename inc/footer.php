<?php if (!defined('PERM')) { die(); }

$panel = (isset($panelchange) ? $panelchange : "");

?>
        </div>
<?php if($panel != ""){ ?>
        <div class="col-md-4">
     <?php include($panel); ?>
        </div>
<?php } ?>
    </div>
    </div>
  </div>

<?php if(odkazactiveself("/index.php")){ ?>
<div class="previewwebsitebg">
    <div class="container">

      <div class="bigtitlecenter">
        <div class="bigtitle">
            <h5>Posledné prezentácie webstránok</h5>
            V tejto sekcii "Prezentácie webstránok" môžete pridávať Vaše webové stránky na hodnotenie. Projekty môžu hodnotiť všetci užívatelia našej stránky. Pre pridanie projektu musíte byť prihlásený alebo registrovaný.
        </div>
      </div>

      <div class="row">


<?php


            $result = dbquery("SELECT * FROM bg_projects ORDER BY pr_id DESC LIMIT 0,3");


             while($data = dbarray($result)){
                 echo '

            <div class="col-md-4">

                <div class="panel previewwebsite">
                    <div class="row">
                            <div class="col-sm-2 col-xs-3 col-md-3">
                                <img src="http://fimg-resp.seznam.cz/?spec=ft280x130&amp;url=http://'.urlvalid($data["pr_web"],0,0).'" alt="'.$data["pr_name"].'" class="img-responsive">
                            </div>
                            <div class="col-sm-10 col-xs-8 col-md-9">
                                <div class="infoweb"><span>'.$data["pr_name"].'</span><small>'.trimlink($data["pr_type"],30).'</small></div>
                            </div>
                        <a href="/prezentacia/'.$data["pr_id"].'/'.bezd($data["pr_name"]).'" class="webarrow"><i class="fa fa-chevron-right"></i></a>
                    </div>
                </div>

            </div>

                 ';

            }


?>

      </div>
    </div>
</div>
<?php } ?>

<div class="colorline"></div>
<div class="subfooter">
    <div class="container">
    <div class="row">
        <div class="col-md-4 komentarefooter">
            <h4>Posledné <strong>komentáre</strong></h4>
<?
$result = dbquery("SELECT * FROM bg_comments WHERE comment_delete='0' AND comment_type='A' ORDER BY comment_id DESC LIMIT 0,4");

$rows1 = dbrows($result);

	if ($rows1 >= "1") {
        while($data = dbarray($result)){

			$type = articleurl($data["comment_pageid"],trimlink(articlename($data["comment_pageid"]),22),1,1);

	 echo '<div class="link">
        <span class="user">'.username($data["comment_userid"],1).'</span>
        '.trimlink(bbcoderemove(badwords($data["comment_text"])),100).'
        <a class="time" href="'.$type.'">'.timeago($data["comment_time"]).' <i class="fa fa-angle-double-right"></i></a></div>';

		}

		}else{
		echo "Žiadne komentáre k článkom.<br/>";
		}

?>
        </div>
        <div class="col-md-4 sfpadd">
            <h4>Komunita na <strong>Facebooku</strong></h4>
            <iframe src="//www.facebook.com/plugins/likebox.php?href=https%3A%2F%2Fwww.facebook.com%2FDesart.sk&amp;width&amp;height=258&amp;colorscheme=dark&amp;show_faces=true&amp;header=false&amp;stream=false&amp;show_border=false"  style="border:none; overflow:hidden; height:200px;width:100%"></iframe>
        </div>
        <div class="col-md-4 sfpadd">
            <h4><strong>Vyhľadávanie</strong> na desart.sk</h4>
            Nevieš niečo nájsť? Tak neváhaj použiť naše vyhľadávanie, ktoré ti ušetrí čas.
			<div class="search">
				<form action="/vyhladavanie" method="post">
				<input placeholder="Zadajte hľadaný výraz..." class="textboxs" name="search" type="text">
				<button type="submit" class="sbutton"><i class="fa fa-search"></i></button>
				</form>
			</div>
        </div>
    </div>

    <div class="partners">
        <a href="http://vikitron.com" target="_blank" class="btn btn-default btn-sm">vikitron.com</a>
        <a href="http://awagency.eu" target="_blank" class="btn btn-default btn-sm">awagency.eu</a>
        <a href="http://gamestreets.eu" target="_blank" class="btn btn-default btn-sm">gamestreets.eu</a>
        <a href="http://grapz.cz" target="_blank" class="btn btn-default btn-sm">grapz.cz</a>
    </div>
    </div>
</div>




<div class="footer">
    <div class="container">
        <div class="row">
            <div class="col-xs-5 col-md-5">2012 - <?php echo date("Y");?> © Desart.sk<sup>v3</sup></div>
            <div class="col-md-7 text-right">
                <a href="/archiv/<?php echo date("n");?>/">Archív</a>
                <a href="/stranky/kontakt">Kontakt & tím</a>
                <a href="/stranky/pravidla">Pravidlá</a>
                <a href="/stranky/o-nas">O nás</a>
                <a href="/stranky/podportenas">Podporte nás</a>
                <a href="https://www.facebook.com/Desartsk" title="Facebook"><i class="fa fa-facebook-square"></i></a>
                <a href="https://twitter.com/desartsk/" title="Twitter"><i class="fa fa-twitter"></i></a>
                <a href="http://youtube.com/desartsk" title="Youtube"><i class="fa fa-youtube"></i></a>
            </div>
        </div>
    </div>
</div>



<div class="modal fade" id="profilmodal"><div class="modal-dialog modal-lg"><div class="modal-content"><div class="userprofile"></div></div></div></div>

<?php


if(isset($badpassword)){notification("Zadané heslo je neplatné.","warning");}
if(isset($bademail)){notification("Zadaný email je neplatný.","warning");}
if(isset($notiflogin)){notification("Úspešne prihlásený.");}

if(isset($notifdeactive)){notification("Účet je deaktivovaný.","error");}
if(isset($notifban)){

  $bandata = dbarray(dbquery("SELECT * FROM da_bans WHERE ban_userid='".$notifban."' AND ban_durationtime >'".time()."' "));

  notification("Váš účet je zabanový do ".date("d.m. Y H:i:s",$bandata["ban_durationtime"]).". (".$bandata["ban_reason"].")","warning","20000");
}



if(!MEMBER){ ?>

<div class="modal fade" id="login" tabindex="-1" role="dialog">
  <div class="modal-dialog">
	<div class="modal-content">
  	  <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×    </button>
    	<h4 class="modal-title">Prihlásenie</h4>
  	  </div>
	  <div class="modal-body text-center">
        <form class="form-horizontal" role="form" action="#login" method="post">
          <div class="form-group">
            <div class="col-xs-10 col-sm-5 col-sm-offset-3">
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-envelope-o fa-fw"></i></span>
                    <input placeholder="Email" required="" class="form-control" type="email" name="user_email">
                </div>
            </div>
          </div>
          <div class="form-group">
            <div class="col-xs-10 col-sm-5 col-sm-offset-3">
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-key fa-fw"></i></span>
                    <input placeholder="Heslo" required="" class="form-control" type="password" name="user_password">
                </div>
            </div>
          </div>
          <input class="btn btn-success col-xs-10 col-sm-5 col-sm-offset-3" value="Prihlásiť sa" type="submit">
            <div class="clearfix"></div>
        </form>
      </div>
      <div class="modal-footer">
        <a class="btn btn-danger" href="/registracia">Vytvoriť účet</a>
        <a class="btn btn-warning" href="/noveheslo">Zabudnuté heslo</a>
      </div>
    </div>
  </div>
</div>

<?php }else{ ?>

<div class="modal fade" id="userchatmodal"><div class="modal-dialog modal-lg"><div class="modal-content">
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
  <h4 class="modal-title">Súkromné správy</h4>
  </div>
  <div class="panel-body">
<div class="userchatbox row">
 <div class="col-md-3">
	<div class="chatboxusers">
	<div class="searchuser">
	<div id="searchuserlist" style="margin-bottom:5px">
      <input class="form-control" type="text" name="searchchatuser" id="searchchatuser" placeholder="vyhľadaj užívateľa..." autocomplete="off">
	</div>
	</div>
		<div class="list-group">
		<div class="list-group-item active">Posledné správy</div>
        <div class="userslist">
<?php

$resultchati = dbquery("SELECT DISTINCT(mes_userid) FROM bg_messages WHERE mes_touserid='".$userinfo["user_id"]."' ORDER BY mes_time");

$rows1 = dbrows($resultchati);

	if ($rows1 >= "1") {

        while($data = dbarray($resultchati)){

		$unreadmessel = dbcount("(mes_id)", "bg_messages","mes_touserid='".$userinfo["user_id"]."' AND mes_userid='".$data["mes_userid"]."' AND mes_read='0'");

		$unreadmes = '<span class="badge" data-target="'.$data["mes_userid"].'">'.$unreadmessel.'</span>';

		echo '<a class="list-group-item userlistid" title="Online '.timeago(useractivity($data["mes_userid"])).'" id="'.$data["mes_userid"].'">'.username($data["mes_userid"],0).'<span class="pull-right">'.($unreadmessel >= 1 ? $unreadmes:"").'</span></a>';
		}
		}
?>
        </div>
		</div>
	</div>
 </div>
 <div class="col-md-9">
	<div class="chatboxspace">
		<div class="chatboxmessagesbor"><div class="chatboxmessages"></div></div>
	<div class="formchatbox" style="display:none;margin-top:5px">
	<form action="" method="post" id="chatform">
    <div class="input-group">
      <input type="text" name="chatboxtext" class="chattext form-control" autocomplete="off">
      <span class="input-group-btn">
        <input type="hidden" name="touserid" id="touserid" value=""/>
        <input class="btn btn-default chatbutton" type="submit" value="Odoslať">
      </span>
    </div>
    </form>
	</div>
	</div>
 </div>
</div>
</div></div></div></div>
<?php } ?>


	<script type="text/javascript" src="/css/js.js"></script>
	<?php if(odkazactive("/pridatclanok")){ ?>
	<script type="text/javascript" src="/css/ckeditor.js"></script>
	<?php } ?>
  <script src='//cdnjs.cloudflare.com/ajax/libs/highlight.js/8.4/highlight.min.js'></script>
  <link rel='stylesheet' href='/css/highlight/railscasts.css'>
  <link rel='stylesheet' href='/css/scrollbar/perfect-scrollbar.min.css'>
	<script src='/css/scrollbar/perfect-scrollbar.min.js'></script>
  <link rel='stylesheet' href='/css/goNotification/goNotification.css'>
  <script src='/css/goNotification/goNotification.min.js'></script>
	<script type="text/javascript">
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-42053807-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
	</script>

</body>
</html>
