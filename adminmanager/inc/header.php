<?php
    if (!defined('PERM')) { die(); } 
	if(!($userinfo["user_perm"] >= 2)){ redirect("/"); }
?>
<!DOCTYPE html>
<html>
 <head>
   	 <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
   	 <title><? echo (isset($titlew) ? $titlew." - ":"");?>Desart Manager</title>
     <meta name="author" content="Web created by Samuell" />
     <link href='http://fonts.googleapis.com/css?family=Open+Sans&amp;subset=latin,cyrillic,latin-ext' rel='stylesheet' type='text/css'>
   	 <link rel="shortcut icon" href="css/favicon.png" type="image/x-icon"/>
	   <script language="javascript" src="/css/jquery-latest.min.js"></script>
     <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
     <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
     <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
   	 <link href="../css/bscallout.css" rel="stylesheet" type="text/css"/>
     <script type="text/javascript" src="../css/ckeditor.js"></script>
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">

      google.load('visualization', '1.0', {'packages':['corechart']});

      google.setOnLoadCallback(drawChart);
      google.setOnLoadCallback(drawChart2);
      google.setOnLoadCallback(drawChart3);
      google.setOnLoadCallback(drawChart4);

      function drawChart4() {
        var data = google.visualization.arrayToDataTable([
          ['Deň','Témy', 'Príspevky vo fóre'],

<?

        $denarray = array(date("Y-m-d",strtotime("-7 day")),date("Y-m-d",strtotime("-6 day")),date("Y-m-d",strtotime("-5 day")),date("Y-m-d",strtotime("-4 day")),date("Y-m-d",strtotime("-3 day")),date("Y-m-d",strtotime("-2 day")),date("Y-m-d",strtotime("-1 day")),date("Y-m-d"));

  for ($i = 0; $i <count($denarray); $i++){
        $ciarka = ($i < count($denarray) ? ",":"");
        echo "['".$denarray[$i]."',".dbcount("(forumt_id)", "bg_forumtopic","DATE(FROM_UNIXTIME(forumt_time))='".$denarray[$i]."'").", ".dbcount("(post_id)", "bg_forumtopicpost","DATE(FROM_UNIXTIME(post_time))='".$denarray[$i]."'")."]".$ciarka;

  }

?>
        ]);

        var options = {
          vAxis: {minValue: 0}
        };

        var chart = new google.visualization.LineChart(document.getElementById('chart_div4'));
        chart.draw(data, options);
      }

      function drawChart3() {
        var data = google.visualization.arrayToDataTable([
          ['Mesiac', 'Články', 'Užívatelia', 'Komentáre'],

<?

  for ($i = 1; $i <= 12; $i++){
        $ciarka = ($i < 12 ? ",":"");
        echo "['".$mesiac[$i]."', ".dbcount("(article_id)", "bg_articles","MONTH(FROM_UNIXTIME(article_date))='".$i."' AND article_suggestion='0'").", ".dbcount("(user_id)", "bg_users","MONTH(FROM_UNIXTIME(user_datereg))='".$i."'").", ".dbcount("(comment_id)", "bg_comments","MONTH(FROM_UNIXTIME(comment_time))='".$i."'")."]".$ciarka;

  }

?>
        ]);

        var options = {
          vAxis: {minValue: 0}
        };

        var chart = new google.visualization.LineChart(document.getElementById('chart_div3'));
        chart.draw(data, options);
      }


      function drawChart() {

        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Topping');
        data.addColumn('number', 'Slices');
        data.addRows([
          ['Chrome', <? echo dbcount("(user_id)", "bg_users","user_browser='Chrome'"); ?>],
          ['Firefox', <? echo dbcount("(user_id)", "bg_users","user_browser='Firefox'"); ?>],
          ['Opera', <? echo dbcount("(user_id)", "bg_users","user_browser='Opera'"); ?>],
          ['Safari', <? echo dbcount("(user_id)", "bg_users","user_browser='Safari'"); ?>],
          ['IE', <? echo dbcount("(user_id)", "bg_users","user_browser='Internet Explorer'"); ?>],
          ['Ostatné prehliadače', <? echo dbcount("(user_id)", "bg_users","user_browser='Unknown'"); ?>]
        ]);

        var options = {
                       'width':'100%',
                       'height':200};

        var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
        chart.draw(data, options);
      }
      function drawChart2() {

        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Topping');
        data.addColumn('number', 'Slices');
        data.addRows([
          ['Windows 7', <? echo dbcount("(user_id)", "bg_users","user_os='Windows 7'"); ?>],
          ['Windows 8.1', <? echo dbcount("(user_id)", "bg_users","user_os='Windows 8.1'"); ?>],
          ['Windows 8', <? echo dbcount("(user_id)", "bg_users","user_os='Windows 8'"); ?>],
          ['Windows XP', <? echo dbcount("(user_id)", "bg_users","user_os='Windows XP'"); ?>],
          ['Ubuntu', <? echo dbcount("(user_id)", "bg_users","user_os='Ubuntu'"); ?>],
          ['Linux', <? echo dbcount("(user_id)", "bg_users","user_os='Linux'"); ?>],
          ['Android', <? echo dbcount("(user_id)", "bg_users","user_os='Android'"); ?>],
          ['Ostatné os', <? echo dbcount("(user_id)", "bg_users","user_os='Unknown'"); ?>]
        ]);

        var options = {
                       'width':'100%',
                       'height':200};

        var chart = new google.visualization.PieChart(document.getElementById('chart_div2'));
        chart.draw(data, options);
      }
    </script>
     <style>
.progress-adjust {height: 10px;margin-bottom: 0px}
.skill-name {padding-bottom:5px}
.navbar {border-radius:0;}
      </style>
     <script>
        $(function(){
	$('.articleinf').popover({html: true});
	$('.tttoggle').tooltip();
    
	
	$('.editprofiladm').click(function(){
      var eid = $(this).attr('data-target');
		$('#profilmodal').modal("show");
		$(".userprofile").show().load("/adminmanager/profilupravaadm.php?uid="+eid);
	});
	$('#newfile').click(function(){
        $(".fileinputs").append('<li class="list-group-item filed"><a class="btn btn-danger btn-xs pull-right removefile">Odstrániť</a><input type="file" name="file[]" multiple></li>');
	});   
    $(document).on('click','.filed .removefile',function(e) { 
    e.preventDefault(); 
    $(this).parent().remove(); 
    });
	$('select[name=img]').change(function(event) {
		var textvimg = $('select[name=img]').find(":selected").text();
		if (textvimg != "") {
		$("#imgviewarticle").attr( "src", "/file/articles/"+textvimg);
		}
	});
        });
     </script>
 </head>
<body>

      <div class="navbar navbar-inverse" role="navigation">
        <div class="container">
          <div class='col-md-12'> 
            <div class="navbar-header">
              <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>
              <a class="navbar-brand" href="/manager"><span style="color:#5cb85c">DESART</span> MANAGER</a>
            </div>
            <div class="collapse navbar-collapse">
              <ul class="nav navbar-nav">
                <li><a href="/manager">Úvod</a></li>
                <li class="dropdown">
                  <a href="" class="dropdown-toggle" data-toggle="dropdown">Nastavenia <b class="caret"></b></a>
                    <ul class='dropdown-menu'>
                      <li><a href="/manager/komentare">Správa komentárov</a></li>
                      <li><a href="/manager/faq">FAQ pre redaktorov</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                  <a href="" class="dropdown-toggle" data-toggle="dropdown">Články <b class="caret"></b></a>
                    <ul class='dropdown-menu'>
                      <li><a href="/manager/clanky">Zoznam článkov</a></li>
                      <li><a href="/manager/clanokuprava">Vytvoriť nový článok</a></li>
                      <li><a href="/manager/serieclankov"> Série článkov</a></li>
                      <li><a href="/manager/kategorie">Kategórie článkov</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                  <a href="" class="dropdown-toggle" data-toggle="dropdown">Súbory<b class="caret"></b></a>
                    <ul class='dropdown-menu'>
                      <li><a href="/manager/na-stiahnutie">Zoznam súborov</a></li>
                      <li><a href="">Kategórie súborov</a></li>
                    </ul>
                </li>
                <li><a href="/manager/uzivatelia">Správa užívateľov</a></li>
                <li><a href="/manager/uploader">Uploader</a></li>
              </ul>
              <ul class="nav navbar-nav navbar-right">
                <li class="dropdown ">
                  <a href="" class="dropdown-toggle" data-toggle="dropdown"><?php echo $userinfo["user_nick"]; ?> <b class="caret"></b></a>
                    <ul class='dropdown-menu'>
                      <li><a href="/">Späť na desart.sk</a></li>
                      <li><a href="?logout">Odhlásiť</a></li>
                    </ul>
                </li>
              </ul> 
            </div>
          </div>
        </div>
      </div>

<div class="container">
    
    <div class="alert alert-info" role="alert">
    
        <span class="pull-right">
            Prehliadač: <? echo getBrowser(); ?>, OS: <? echo getOS(); ?><br>
            Vaša ip: <? echo $userinfo["user_ip"]; ?>, Predchádzajúca ip: <? echo $userinfo["user_oldip"]; ?>
        </span>
        
        Vitajte v administrácií stránky desart.sk<br>
        Pred písaním článku si prečítajte <a href="/manager/faq" class="alert-link">FAQ pre redaktorov</a>.

    </div>
<div class="row">
    <div class="col-md-3">
        
<div class="panel panel-info">
  <div class="panel-heading">Aktivita dnešný deň</div>
  <ul class="list-group">
    <li class="list-group-item">Prihlásený užívatelia <span class="badge"><?php $timestamp = strtotime(date('d-m-Y')); echo dbrows(dbquery("SELECT * FROM bg_users WHERE user_lastactivity > ".$timestamp)); ?></span></li>
    <li class="list-group-item">Registrovaný užívatelia <span class="badge"><?php $timestamp = strtotime(date('d-m-Y')); echo dbrows(dbquery("SELECT * FROM bg_users WHERE user_datereg > ".$timestamp)); ?></span></li>
    <li class="list-group-item">Komentáre <span class="badge"><?php echo dbrows(dbquery("SELECT * FROM bg_comments WHERE comment_time > ".$timestamp)); ?></span></li>
    <li class="list-group-item">Prispevky vo fóre <span class="badge"><?php echo dbrows(dbquery("SELECT * FROM bg_forumtopicpost WHERE post_time > ".$timestamp)); ?></span></li>
  </ul>
</div>
<div class="panel panel-info">
  <div class="panel-heading">Štatistiky</div>
    <?php
    
    $clcounting = dbarray(dbquery("SELECT SUM(article_reads) AS totalreads FROM bg_articles"));
    
    ?>
  <ul class="list-group">
    <li class="list-group-item">Užívateľov <span class="badge"><? echo dbcount("(user_id)", "bg_users"); ?> </span></li>
    <li class="list-group-item">Zabanovaných užívateľov <span class="badge"><? echo dbcount("(user_id)", "bg_users","user_ban='1'"); ?></span></li>
    <li class="list-group-item">Komentárov <span class="badge"><? echo dbcount("(comment_id)", "bg_comments"); ?> (<? echo dbcount("(comment_id)", "bg_comments","comment_delete='1'"); ?>)</span></li>
    <li class="list-group-item">Článkov <span class="badge"><? echo dbcount("(article_id)", "bg_articles"); ?></span></li>
    <li class="list-group-item">Články v sériách <span class="badge"><? echo dbcount("(article_id)", "bg_articles","article_series<>'0'"); ?></span></li>
    <li class="list-group-item">Súbory <span class="badge"><? echo dbcount("(down_id)", "bg_downloads"); ?></span></li>
    <li class="list-group-item">Pozretí článkov <span class="badge"><? echo $clcounting["totalreads"]; ?></span></li>
    <li class="list-group-item">Zamknuté témy <span class="badge"><? echo dbcount("(forumt_id)", "bg_forumtopic","forumt_locked='1'"); ?></span></li>
    <li class="list-group-item">Témy vo fóre <span class="badge"><? echo dbcount("(forumt_id)", "bg_forumtopic"); ?></span></li>
    <li class="list-group-item">Odpovede v témach <span class="badge"><? echo dbcount("(post_topicid)", "bg_forumtopicpost"); ?></span></li>
  </ul>
</div>

<div class="panel panel-success">
  <div class="panel-heading">Online užívatelia</div>
  <ul class="list-group">
<?php
		$onlinec = dbquery("SELECT user_id,user_nick,user_lastactivity,user_perm FROM bg_users WHERE user_lastactivity > " . (time() + (-5 * 60)));
		
		$onlineusers = dbrows($onlinec);
		
			while($data58 = dbarray($onlinec)){

				echo '<li class="list-group-item" title="Online '.timeago($data58["user_lastactivity"]).'">'.$data58["user_nick"].'<span class="badge">'.($data58["user_perm"]>=2 ? $adminprava[$data58["user_perm"]]:userrank($data58["user_id"],1)).'</span></li>';
			}
?>
  </ul>
</div>

    </div>
    <div class="col-md-9">
