<? ob_start(); session_start(); error_reporting(0);

	define("PERM",TRUE); // zakaz otvorenia suboru ...
	$weburl = "http://desart.sk/";

	// DB connect
	$db_connect = mysqli_connect("XXXX","XXX","XXX","XXX");
	if (mysqli_connect_errno()){ die("Failed to connect: " . mysqli_connect_error()); }
	mysqli_query($db_connect,"set names utf8");

	$ab2 = dbquery("SELECT * FROM bg_settings");
    $setting = dbarray($ab2);

	// Web title
	$webtitle = $setting["title"];

	// Arrays
	$mesiac = Array(1 => "Január",2 => "Február",3 => "Marec",4 => "Apríl",5 => "Máj",6 => "Jún",7 => "Júl",8 => "August",9 => "September",10 => "Október",11 => "November",12 => "December");
	$adminprava = Array(1 => "Užívateľ",2 => "Hlavný Redaktor",3 => "Hlavný administrátor",4 => "Redaktor",5 => "Moderátor");

	$ranklevel = Array(1 => "Nováčik",2 => "Začiatočník",3 => "Vyzná sa",4 => "Užívateľ",5 => "Aktívny užívateľ",6 => "Spammer");
    $ranknum = Array(1=> "1",2=> "20",3=> "50",4=> "150",5=> "250",6=> "X");

    $bw = array(
        "piča", "pica", "kokot",
        "jeb", "kurva", "kurve",
        "zeman", "mrd", "pičk",
        "piči", "pici", "pičo",
        "pico"
    );

    // Antispam
    $md5_hash = md5(rand(0,999));
    $spamkiller = substr($md5_hash, 15, 5);


function checkstring($string){

	$string = htmlspecialchars(trim($string),ENT_QUOTES);

	return $string;
}

	// Functions
function notification($message,$type="success",$timeout="4000"){
	 // types= success | warning | error | info | loading
	$notif = "
	<script>
	$(function(){
		$.goNotification('".$message."', {type: '".$type."',position: 'bottom right',timeout: ".$timeout.",animation: 'fade'});
	});
	</script>
	";
	echo $notif;
}
function getOS() {

$user_agent = $_SERVER['HTTP_USER_AGENT'];

$os_platform    =   "Unknown";

$os_array       =   array(
                        '/windows nt 6.3/i'     =>  'Windows 8.1',
                        '/windows nt 6.2/i'     =>  'Windows 8',
                        '/windows nt 6.1/i'     =>  'Windows 7',
                        '/windows nt 6.0/i'     =>  'Windows Vista',
                        '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
                        '/windows nt 5.1/i'     =>  'Windows XP',
                        '/windows xp/i'         =>  'Windows XP',
                        '/windows nt 5.0/i'     =>  'Windows 2000',
                        '/windows me/i'         =>  'Windows ME',
                        '/win98/i'              =>  'Windows 98',
                        '/win95/i'              =>  'Windows 95',
                        '/win16/i'              =>  'Windows 3.11',
                        '/macintosh|mac os x/i' =>  'Mac OS X',
                        '/mac_powerpc/i'        =>  'Mac OS 9',
                        '/linux/i'              =>  'Linux',
                        '/ubuntu/i'             =>  'Ubuntu',
                        '/iphone/i'             =>  'iPhone',
                        '/ipod/i'               =>  'iPod',
                        '/ipad/i'               =>  'iPad',
                        '/android/i'            =>  'Android',
                        '/blackberry/i'         =>  'BlackBerry',
                        '/webos/i'              =>  'Mobile'
                     );

foreach ($os_array as $regex => $value) {

 if (preg_match($regex, $user_agent)) {
    $os_platform    =   $value;
 }

}

return $os_platform;

}

function getBrowser() {

$user_agent = $_SERVER['HTTP_USER_AGENT'];

$browser        =   "Unknown";

$browser_array  =   array(
                         '/msie/i'       =>  'Internet Explorer',
                         '/firefox/i'    =>  'Firefox',
                         '/safari/i'     =>  'Safari',
                         '/chrome/i'     =>  'Chrome',
                         '/OPR/i'      =>  'Opera',
                         '/Opera/i'      =>  'Opera',
                         '/netscape/i'   =>  'Netscape',
                         '/maxthon/i'    =>  'Maxthon',
                         '/konqueror/i'  =>  'Konqueror',
                         '/mobile/i'     =>  'Handheld Browser'
                   );

foreach ($browser_array as $regex => $value) {

  if (preg_match($regex, $user_agent)) {
     $browser    =   $value;
  }

}

return $browser;

}


function pagination($rows1,$limit,$pocet_stran,$strana,$golink="",$orderlink=""){

		if($rows1 > $limit){

            $aktualnastrana = (isset($_GET['strana']) ? $_GET['strana']:"1");

                if($pocet_stran < 20){

                // strany pod 10

        echo '<div class="clearfix"></div>';
		echo '<div class="text-center">';
        echo '<ul class="pagination"><li><a href="'.$golink.'?strana='.($aktualnastrana != 1 ? $aktualnastrana-1 : $aktualnastrana).$orderlink.'">&laquo;</a></li></ul>';
        echo ' <ul class="pagination">';
for ($i=1; $i<=$pocet_stran; $i++)
{
	if ($i<>$strana) {
        echo '<li><a href="'.$golink.'?strana='.$i.$orderlink.'">'.$i.'</a></li>';
	} else {
        echo '<li class="active"><a href="'.$golink.'?strana='.$i.$orderlink.'">'.$i.'</a></li>';
	}
}
        echo '</ul> ';
        echo '<ul class="pagination"><li><a href="'.$golink.'?strana='.($aktualnastrana != $pocet_stran ? $aktualnastrana+1 : $aktualnastrana).$orderlink.'">&raquo;</a></li></ul>';
		echo '</div>';


				}else{

				//strany nad 10

				$strana1p = $aktualnastrana - 1;
				$strana2p = $aktualnastrana - 2;

				$strana1m = $aktualnastrana + 1;
				$strana2m = $aktualnastrana + 2;

        echo '<div class="clearfix"></div>';
        echo '<div class="text-center">';
        echo '<ul class="pagination"><li><a href="'.$golink.'?strana='.($aktualnastrana != 1 ? $aktualnastrana-1 : $aktualnastrana).$orderlink.'">&laquo;</a></li></ul>';

				 echo ' <ul class="pagination">';
				if($aktualnastrana >= 4){

					echo "<li><a href='".$golink."?strana=".$strana2p.$orderlink."' title='".$strana2p."'>".$strana2p."</a></li>";
					echo "<li><a href='".$golink."?strana=".$strana1p.$orderlink."' title='".$strana1p."'>".$strana1p."</a></li>";

				}

				echo '<li class="active"><a href="'.$golink.'?strana='.$aktualnastrana.$orderlink.'">'.$aktualnastrana.'</a></li>';

				if($aktualnastrana <= ($pocet_stran - 3)){

					echo "<li><a href='".$golink."?strana=".$strana1m.$orderlink."' title='".$strana1m."'>".$strana1m."</a></li>";
					echo "<li><a href='".$golink."?strana=".$strana2m.$orderlink."' title='".$strana2m."'>".$strana2m."</a></li>";

				}
                echo '</ul> ';


        echo '<ul class="pagination"><li><a href="'.$golink.'?strana='.($aktualnastrana != $pocet_stran ? $aktualnastrana+1 : $aktualnastrana).$orderlink.'">&raquo;</a></li></ul>';
        echo '</div>';

                }
        }
}

function userdetect($string){
if (preg_match_all('/(^|\s)(@\w+)/', $string, $zavinace) > 0) {
  foreach ($zavinace[2] as $name) {
    if (preg_match('/@\d*/', $name)) {

		$namefix = str_replace("@", "", $name);

		$validus = dbcount("(user_id)", "bg_users","user_nick='".htmlspecialchars($namefix)."'");

	if($validus >= 1){
		$string = str_replace($name, "<a class='profillink' data-target='".usernametoid($namefix)."' style='color:#e74c3c'>@".$namefix."</a>", $string);
	}else{
		$string = str_replace($name, '<span style="color:#e74c3c">@'.$namefix.'</span>', $string);
	}
    }
  }
}

return $string;
}

function urlvalid($url,$name=0,$link=1){
	$url = Str_Replace(Array("www.","http://", "http://www.","/news.php","/index.php"), "", $url);
	if($link==1){
	return "<a href='http://".$url."' target='_blank'>".$name."</a>";
	}else{
	return $url;
	}
}
function trimlink($text, $length) {
	if (strlen($text) > $length) $text = mb_substr($text, 0, ($length-3), "utf-8")."...";
	return $text;
}
function bezd($str){

$wordstable = Array(
  'ä'=>'a','Ä'=>'A','á'=>'a','Á'=>'A',
  'à'=>'a','À'=>'A','ã'=>'a','Ã'=>'A',
  'â'=>'a','Â'=>'A','č'=>'c','Č'=>'C',
  'ć'=>'c','Ć'=>'C','ď'=>'d','Ď'=>'D',
  'ě'=>'e','Ě'=>'E','é'=>'e','É'=>'E',
  'ë'=>'e','Ë'=>'E','è'=>'e','È'=>'E',
  'ê'=>'e','Ê'=>'E','í'=>'i','Í'=>'I',
  'ï'=>'i','Ï'=>'I','ì'=>'i','Ì'=>'I',
  'î'=>'i','Î'=>'I','ľ'=>'l','Ľ'=>'L',
  'ĺ'=>'l','Ĺ'=>'L','ń'=>'n','Ń'=>'N',
  'ň'=>'n','Ň'=>'N','ñ'=>'n','Ñ'=>'N',
  'ó'=>'o','Ó'=>'O','ö'=>'o','Ö'=>'O',
  'ô'=>'o','Ô'=>'O','ò'=>'o','Ò'=>'O',
  'õ'=>'o','Õ'=>'O','ő'=>'o','Ő'=>'O',
  'ř'=>'r','Ř'=>'R','ŕ'=>'r','Ŕ'=>'R',
  'š'=>'s','Š'=>'S','ś'=>'s','Ś'=>'S',
  'ť'=>'t','Ť'=>'T','ú'=>'u','Ú'=>'U',
  'ů'=>'u','Ů'=>'U','ü'=>'u','Ü'=>'U',
  'ù'=>'u','Ù'=>'U','ũ'=>'u','Ũ'=>'U',
  'û'=>'u','Û'=>'U','ý'=>'y','Ý'=>'Y',
  'ž'=>'z','Ž'=>'Z','ź'=>'z','Ź'=>'Z'
);

	$str = strtr($str, $wordstable);
    $str = htmlentities($str, ENT_COMPAT, "UTF-8", false);
    $str = preg_replace('/&([a-z]{1,2})(?:acute|lig|grave|ring|tilde|uml|cedil|caron);/i','\1',$str);
    $str = html_entity_decode($str,ENT_COMPAT, "UTF-8");
    $str = preg_replace('/[^a-z0-9-]+/i', '-', $str);
    $str = preg_replace('/-+/', '-', $str);
    $str = trim($str, '-');
    $str = strtolower($str);
    return $str;
}
function smiley($text) {
	$return = str_replace(":)", "<i class='fa fa-smile-o'></i>", $text);
	$return = str_replace(":(", "<i class='fa fa-frown-o'></i>", $return);
	$return = str_replace(":|", "<i class='fa fa-meh-o'></i>", $return);
	$return = str_replace(":like:", "<i class='fa fa-thumbs-up'></i>", $return);
	return $return;
}
function badwords($str) {
    global $bw;

    $a = substr(str_shuffle("#?!*@&%$"), 0, 4);
    $str = str_replace($bw, "<small>".$a."</small>", $str);

    return $str;
}

function odkazactive($name){
		$web = $_SERVER["REQUEST_URI"];
	if(strstr($web, $name)){ return true; }else{ return false; }
}
function odkazactiveself($name){
		$web = $_SERVER["PHP_SELF"];
	if(strstr($web, $name)){ return true; }else{ return false; }
}
function upload_koncovka($t){
  if($t == "image/gif"){ $pripona = ".gif"; }
  if($t == "image/png"){ $pripona = ".png"; }
  if($t == "image/jpeg"){ $pripona = ".jpg"; }
  return $pripona;
}
function temysklonuj($count) {
	$txt = sklonuj($count, 'téma', 'témy', 'tém');
	return $txt;
}
function userrank($id,$rankshow=0,$showtype=0){
    global $ranklevel;

    $forum = dbcount("(post_id)", "bg_forumtopicpost","post_userid='".$id."'");
    $com = dbcount("(comment_id)", "bg_comments","comment_delete='0' AND comment_userid='".$id."'");
    $article = dbcount("(article_id)", "bg_articles","article_suggestion='0' AND article_author='".$id."'");

    $body = ($article * 4) + ($com * 0.5) + ($forum * 2);

    if($body <= 0){
        $ranklevels = $ranklevel[1];
        $ranktype = 1;
    }elseif($body >= 0.5 AND $body <= 20){
        $ranklevels = $ranklevel[2];
        $ranktype = 2;
    }elseif($body >= 21 AND $body <= 50){
        $ranklevels = $ranklevel[3];
        $ranktype = 3;
    }elseif($body >= 51 AND $body <= 150){
        $ranklevels = $ranklevel[4];
        $ranktype = 4;
    }elseif($body >= 151 AND $body <= 250){
        $ranklevels = $ranklevel[5];
        $ranktype = 5;
    }else{
        $ranklevels = $ranklevel[6];
        $ranktype = 6;
    }
    if($rankshow == 1){
        return $ranklevels;
    }else{
        if($showtype == 1){
        return $ranktype;
        }else{
        return $body;
        }

    }

}
function uzivateliasklonuj($id) {
	if($id == 0){
	$txt = "užívatelov";
	}else if($id == 1){
	$txt = "užívateľ";
	}else if($id >= 2){
	$txt = "užívatelia";
	}else if($id >= 5){
	$txt = "užívatelov";
	}
	return $txt;
}
function useravatar($n){

	$pp = dbquery("SELECT * FROM bg_users WHERE user_id='".$n."'");
	$id = dbarray($pp);
		if(!file_exists($id["user_avatar"])){
	return "http://data.desart.sk/avatars/".($id["user_avatar"] ? $id["user_avatar"]:"avatar.png");
		}else{
	return "http://data.desart.sk/avatars/avatar.png";
		}
}
function useractivity($id){

	$pp = dbquery("SELECT * FROM bg_users WHERE user_id='".$id."'");
	$id = dbarray($pp);
		return $id["user_lastactivity"];
}
function username($id,$link = 0,$tag = ""){

	$pp = dbquery("SELECT * FROM bg_users WHERE user_id='".$id."'");
	$id = dbarray($pp);
	if($link){
		return "<a class='profillink' data-target='".$id["user_id"]."'>".$tag.$id["user_nick"]."</a>";
	}else{
		return $id["user_nick"];
	}
}
function usernametoid($id){

	$pp = dbquery("SELECT * FROM bg_users WHERE user_nick='".htmlspecialchars($id)."'");
	$id = dbarray($pp);
	return $id["user_id"];
}
function userpermis($id){

	$pp = dbquery("SELECT * FROM bg_users WHERE user_id='".$id."'");
	$id = dbarray($pp);
		return $id["user_perm"];
}
function downloadsubor($id){
	$pp = dbquery("SELECT * FROM bg_downloads WHERE down_id='".$id."'");
	$id = dbarray($pp);
	return $id["down_file"];
}
function downloadcat($id){

	$pp = dbquery("SELECT * FROM bg_downloadcats WHERE downc_id='".$id."'");
	$id = dbarray($pp);
		return $id["downc_name"];
}
function forumtopic1post($id){
	$pp = dbquery("SELECT post_topicid,post_userid,post_time FROM bg_forumtopicpost WHERE post_topicid='".$id."' ORDER BY post_time DESC LIMIT 0,1");
	$id = dbarray($pp);
	return "".timeago($id["post_time"])." od ".username($id["post_userid"],0);
}
function forumcat($id,$link = 0){

	$pp = dbquery("SELECT * FROM bg_forum WHERE forum_id='".$id."'");
	$id = dbarray($pp);
	if($link){
		return "<a href='/forum/".$id["forum_id"]."/".bezd($id["forum_name"])."' title='".$id["forum_name"]."'>".$id["forum_name"]."</a>";
	}else{
		return $id["forum_name"];
	}
}
function forumtopicname($id){
	$pp = dbquery("SELECT * FROM bg_forumtopic WHERE forumt_id='".$id."'");
	$id = dbarray($pp);
	return $id["forumt_name"];
}
function forumcatid($id){
	$pp = dbquery("SELECT * FROM bg_forumtopic WHERE forumt_id='".$id."'");
	$id = dbarray($pp);
	return $id["forumt_fid"];
}
function forumtopiclock($id){
	$pp = dbquery("SELECT * FROM bg_forumtopic WHERE forumt_id='".$id."'");
	$id = dbarray($pp);
	return $id["forumt_locked"];
}
function forumtopicread($user,$topic,$topiccount){
	$pp = dbquery("SELECT * FROM bg_forumtopicread WHERE forumr_userid='".$user."' AND forumr_tid='".$topic."' AND forumr_cpost='".$topiccount."'");
		if(dbrows($pp) == 1){
		return true;
		}else{
		return false;
		}
}
    // serie funkcie
function seriename($id){

	$pp = dbquery("SELECT * FROM bg_articleseries WHERE as_id='".$id."'");
	$id = dbarray($pp);
		return $id["as_name"];
}
function serietext($id){

	$pp = dbquery("SELECT * FROM bg_articleseries WHERE as_id='".$id."'");
	$id = dbarray($pp);
		return $id["as_mtext"];
}

	// clanky funkcie
function articlecat($id,$link=0,$color=0){

	$pp = dbquery("SELECT * FROM bg_articlecats WHERE articlec_id='".$id."'");
	$id = dbarray($pp);
	if($link){
		return "<a href='/clanky/kategoria/".$id["articlec_id"]."/".bezd($id["articlec_name"])."' ".($color == 1 ? "style='color:#".$id["articlec_color"]."'":"")." title='".$id["articlec_name"]."'>".$id["articlec_name"]."</a>";
	}else{
		return $id["articlec_name"];
	}
}
function articlecatid($id){

	$pp = dbquery("SELECT * FROM bg_articles WHERE article_id='".$id."'");
	$id = dbarray($pp);
		return $id["article_cat"];
}
function articlename($id){
	$pp = dbquery("SELECT * FROM bg_articles WHERE article_id='".$id."'");
	$id = dbarray($pp);
	return $id["article_name"];
}
function articleimg($id){
	$pp = dbquery("SELECT * FROM bg_articles WHERE article_id='".$id."'");
	$id = dbarray($pp);
	return $id["article_img"];
}
function articleurl($id,$text,$com=0,$li=0){
	$pp = dbquery("SELECT * FROM bg_articles WHERE article_id='".$id."'");
	$id = dbarray($pp);
	if($li == 0){
		return "<a href='/clanok/".$id["article_id"]."/".bezd($id["article_name"])."".($com ? "#komentare":"")."' title='".$id["article_name"]."' class='linkcbox'>".$text."</a>";
	}else{
		return "/clanok/".$id["article_id"]."/".bezd($id["article_name"])."".($com ? "#komentare":"");
	}
}
function articlemtxt($id){
	$pp = dbquery("SELECT * FROM bg_articles WHERE article_id='".$id."'");
	$id = dbarray($pp);
	return $id["article_minitxt"];
}

	// redirect
function redirect($location) {
		header("Location: ".str_replace("&amp;", "&", $location));
		exit;
}
	// DB strings
function mysqli_result($res,$row=0,$col=0){
    $numrows = mysqli_num_rows($res);
    if ($numrows && $row <= ($numrows-1) && $row >=0){
        mysqli_data_seek($res,$row);
        $resrow = (is_numeric($col)) ? mysqli_fetch_row($res) : mysqli_fetch_assoc($res);
        if (isset($resrow[$col])){
            return $resrow[$col];
        }
    }
    return false;
}
function dbescape($query) {
    global $db_connect;
    return mysqli_real_escape_string($db_connect, $query);
}

function dbquery($query) {
	global $db_connect;
	$result = @mysqli_query($db_connect,$query);
	if (!$result) {
		echo mysqli_error($db_connect);
		return false;
	} else {
		return $result;
	}
}

function dbcount($field, $table, $conditions = "") {
	global $db_connect;
	$cond = ($conditions ? " WHERE ".$conditions : "");
	$result = @mysqli_query($db_connect,"SELECT Count".$field." FROM ".$table.$cond);
	if (!$result) {
		echo mysqli_error($db_connect);
		return false;
	} else {
		$rows = mysqli_result($result,0);
		return $rows;
	}
}

function dbrows($query) {
	global $db_connect;
	$result = @mysqli_num_rows($query);
	return $result;
}

function dbarray($query) {
	global $db_connect;
	$result = @mysqli_fetch_assoc($query);
	if (!$result) {
		echo mysqli_error($db_connect);
		return false;
	} else {
		return $result;
	}
}

function bbcode($text,$advanced=0) {

		$basic_bbcode = array(
								'[b]', '[/b]',
								'[i]', '[/i]',
								'[u]', '[/u]',
								'[s]','[/s]',
								'[ul]','[/ul]',
								'[li]', '[/li]',
								'[ol]', '[/ol]',
								'[center]', '[/center]',
								'[left]', '[/left]',
								'[right]', '[/right]',
		);

		$basic_html = array(
								'<b>', '</b>',
								'<i>', '</i>',
								'<u>', '</u>',
								'<s>', '</s>',
								'<ul>','</ul>',
								'<li>','</li>',
								'<ol>','</ol>',
								'<div style="text-align: center;">', '</div>',
								'<div style="text-align: left;">',   '</div>',
								'<div style="text-align: right;">',  '</div>',
		);

		$text = str_replace($basic_bbcode, $basic_html, $text);

		if ($advanced){

			$advanced_bbcode = array(
									 '#\[color=([a-zA-Z]*|\#?[0-9a-fA-F]{6})](.+)\[/color\]#Usi',
									 '#\[size=([0-9][0-9]?)](.+)\[/size\]#Usi',
									 '#\[quote](\r\n)?(.+?)\[/quote]#si',
									 '#\[quote=(.*?)](\r\n)?(.+?)\[/quote]#si',
									 '#\[url](.+)\[/url]#Usi',
									 '#\[url=(.+)](.+)\[/url\]#Usi',
									 '#\[img](.+)\[/img]#Usi',
									 '#\[img=(.+)](.+)\[/img]#Usi',
									 '#\[code](\r\n)?(.+?)(\r\n)?\[/code]#si'
			);

			$advanced_html = array(
									 '<span style="color: $1">$2</span>',
									 '<span style="font-size: $1px">$2</span>',
									 "<div class=\"quote\"><span class=\"quoteby\">Odpoved:</span>\r\n$2</div>",
									 "<div class=\"quote\"><span class=\"quoteby\">Odpoved <b>$1</b>:</span>\r\n$3</div>",
									 '<a target="_blank" href="$1">$1</a>',
									 '<a target="_blank" href="$1">$2</a>',
									 '<a href="$1" target="_blank"><img class="bbimg" src="$1" alt="$1" /></a>',
									 '<a href="$1" target="_blank"><img class="bbimg" src="$1" alt="$2" /></a>',
									 '<div class="prettyprint">$2</div>'
			);

			$text = preg_replace($advanced_bbcode, $advanced_html,$text);
		}

		return $text;

}
function bbcoderemove($text){
		return strip_tags(str_replace(array('[',']'), array('<','>'), $text));
}

// Sklonovanie casu
function sklonuj($intCount, $w1, $w2, $w3) {
  switch($intCount) {
    case 1:
      return $w1;
    case 2:
    case 3:
    case 4:
      return $w2;
    default:
      return $w3;
  }
}

function timeago($time, $threshold = 1) {
    $current_time = time();

	$hodmin = date("H:m",$time);

    $diff = $current_time - $time;

    if($diff < $threshold)
        return 'teraz';

    if($diff < 60) {
        $diff = round($diff);
        return 'pred ' . sklonuj($diff, 'sekundou', $diff.' sekundami', $diff.' sekundami');
    }

    if($diff < 3600) {
        $diff = round($diff / 60);
        return 'pred ' . sklonuj($diff, 'minútou', $diff.' minútami', $diff.' minútami');
    }

    if($diff < 3600*24) {
        $diff = round($diff / 3600);
        return 'pred ' . sklonuj($diff, 'hodinou', $diff.' hodinami', $diff.' hodinami');
    }

    if($diff < 3600*24*7) {
        $diff = round($diff / (3600 * 24));
        if($diff == 1)
            return "včera o ".$hodmin;
        return 'pred ' . sklonuj($diff, 'dňom', $diff.' dňami', $diff.' dňami');
    }

    $diff = round($diff / (3600 * 24 * 7));
    return date("j. n. Y H:i",$time);
}

// Komentáre (add,del,all)
function komentare($clanok,$type ="A",$link = ""){

	global $userinfo;

echo '<div class="komentare" id="komentare">';


if(MEMBER){

	if(isset($_GET["komentar"]) && isset($_GET["zmazat"]) && $_GET["zmazat"] != ""){

	if (!ctype_digit($_GET['zmazat'])){ redirect("/"); }

	$result66 = dbquery("SELECT * FROM bg_comments WHERE comment_id='".strip_tags((int)$_GET["zmazat"])."' AND comment_type='".$type."'");
        $rows55 = dbrows($result66);
	$data8 = dbarray($result66);

				if($rows55 == 1) {

			if(!userperm("5")){

		if($data8["comment_userid"] == $userinfo["user_id"]) {
			dbquery("UPDATE bg_comments SET comment_delete='1' WHERE comment_id='".strip_tags((int)$_GET["zmazat"])."' AND comment_type='".$type."'");

				if($link == ""){
			redirect("/clanok/".$_GET["id"]."/".strip_tags($_GET["n"])."#komentare");
				}else{
			redirect($link);
				}
		}else{
		redirect("/");
		}

			}else{

		dbquery("UPDATE bg_comments SET comment_delete='1' WHERE comment_id='".strip_tags((int)$_GET["zmazat"])."' AND comment_type='".$type."'");
		dbquery("UPDATE bg_comments SET comment_delete='1' WHERE comment_type='".$type."' AND comment_reply='".strip_tags((int)$_GET["zmazat"])."'");

				if($link == ""){
			redirect("/clanok/".$_GET["id"]."/".strip_tags($_GET["n"])."#komentare");
				}else{
			redirect($link);
				}

			}

				}else{
					redirect("/");
				}
	}

	$antispamnum = rand(1,99);

if(isset($_POST["addcomment"]) && $_POST["textarea"] != ""){

	$text = trim(htmlspecialchars($_POST["textarea"], ENT_QUOTES, "UTF-8"));
	$reply = (isset($_GET["reply"]) ? strip_tags((int)$_GET["reply"]) : "0");

	if($reply != 0){

		$resultcom = dbquery("SELECT * FROM bg_comments WHERE comment_id='".strip_tags((int)$_GET["reply"])."' AND comment_type='".$type."'");
		$rows5com = dbrows($resultcom);

		if($rows5com != 1){
				if($link == ""){
				redirect("/clanok/".$_GET["id"]."/".strip_tags($_GET["n"])."#komentare");
				}else{
				redirect($link);
				}
		}
	}



	if(!dbcount("(comment_id)", "bg_comments","comment_userid='".$userinfo["user_id"]."' AND comment_time > ".strtotime("-30 seconds")."")) {
		if($text != "" AND strlen($text) >= 8){
	dbquery("INSERT INTO bg_comments(comment_userid, comment_text, comment_time, comment_pageid, comment_type, comment_reply)
                               VALUES('".$userinfo["user_id"]."','".$text."','".time()."','".$clanok."','".$type."','".$reply."')");
		}

	}

				if($link == ""){
				redirect("/clanok/".$_GET["id"]."/".strip_tags($_GET["n"])."#komentare");
				}else{
				redirect($link);
				}

}
		if(isset($_GET['reply'])){if (!ctype_digit($_GET['reply'])){ redirect("/"); }}

if(dbcount("(comment_id)", "bg_comments","comment_userid='".$userinfo["user_id"]."' AND comment_time > ".strtotime("-30 seconds")."")) {
	echo '<div class="alert alert-danger">O 30 sekúnd môžeš znova komentovať.</div>';
}

echo '<div class="list-group komentboxarea '.(isset($_GET['reply']) ? "showdiv":"hidediv").'" id="komreply">
<form name="form" action="#komentare" method="POST">
<div class="list-group-item list-group-item-info">Pridaj komentár</div>
<textarea name="textarea" class="list-group-item" maxLength="800" rows="1" placeholder="text komentáru..." style="width:100%;padding:10px;font-size:12px;resize:vertical"></textarea>
<div class="list-group-item">
	<span class="bbcody">
			<a href="javascript:addText(\'textarea\', \'[b]\', \'[/b]\', \'form\');" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top" title="[b]tučný[/b]"><i class="fa fa-bold"></i></a>
			<a href="javascript:addText(\'textarea\', \'[i]\', \'[/i]\', \'form\');" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top" title="[i]kurzíva[/i]"><i class="fa fa-italic"></i></a>
			<a href="javascript:addText(\'textarea\', \'[u]\', \'[/u]\', \'form\');" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top" title="[u]podčiarknuté[/u]"><i class="fa fa-underline"></i></a>
			<a href="javascript:addText(\'textarea\', \'[url]\', \'[/url]\', \'form\');" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top" title="[url]odkaz[/url]"><i class="fa fa-link"></i></a>
	</span>
    <span class="pull-right">
    '.(isset($_GET["reply"]) ? ' <a href="'.($link == "" ? "/clanok/".$_GET["id"]."/".strip_tags($_GET["n"])."#komentare":$link).'" class="btn btn-warning btn-sm">Zrušiť odpoveď</a>':"").'
    <input name="addcomment" class="btn btn-success btn-sm" value="'.(isset($_GET["reply"]) ? 'Odpovedať na komentár ID #'.strip_tags((int)$_GET["reply"]).'':"Pridať komentár").'" type="submit">
    </span>
    <div class="clearfix"></div>
</div>
</form>
</div>
';

}else{
	echo '<div class="alert alert-info">Pred pridaním komentára sa musíš <a href="/registracia">zaregistrovať</a> alebo prihlásiť.</div>';
}

echo '
<div class="page-header">
  <h5>Komentáre
   '.(!isset($_GET['reply']) ? '<a id="showcommentarea" class="btn btn-success btn-xs pull-right">Pridať komentár</a>':'').'
  </h5>
</div>
';


if(isset($_GET["vsetkykomentare"]) && $_GET["vsetkykomentare"] == "zobrazit"){$comlimit = "";}else{$comlimit = "LIMIT 0,7";}


        $result2 = dbquery("SELECT * FROM bg_comments WHERE comment_delete='0' AND comment_pageid='".$clanok."' AND comment_type='".$type."' AND comment_reply='0' ORDER BY comment_id DESC");
        $rows3 = dbrows($result2);

		if ($rows3 >= 1) {
$result3 = dbquery("SELECT * FROM bg_comments WHERE comment_delete='0' AND comment_pageid='".$clanok."' AND comment_type='".$type."' AND comment_reply='0' ORDER BY comment_id DESC ".$comlimit);

        while($data2 = dbarray($result3)){

		$resultreply = dbquery("SELECT * FROM bg_comments WHERE comment_delete='0' AND comment_pageid='".$clanok."' AND comment_type='".$type."' AND comment_reply='".$data2["comment_id"]."' ORDER BY comment_id DESC"); // def reply
        $rowsrep = dbrows($resultreply); // def reply

echo '
<div class="media komentar">
  <a class="pull-left">
    <img class="media-object img-circle" src="'.useravatar($data2["comment_userid"]).'" alt="'.username($data2["comment_userid"]).'">
  </a>
  <div class="media-body">
    <h4 class="media-heading">'.username($data2["comment_userid"],1).' <span class="time">'.timeago($data2["comment_time"]).'</span></h4>
    '.wordwrap(bbcode(badwords(smiley($data2["comment_text"]))),100," ",1).'
    <div class="clearfix"></div>
    <div class="buttonsinfo">
';

	if(MEMBER){
	echo '<a href="?reply='.$data2["comment_id"].'#komreply" class="btn btn-default btn-xs"><i class="fa fa-share"></i> Odpovedať</a> ';
	}
	if(SADMIN OR (MEMBER && $data2["comment_userid"] == $userinfo["user_id"])){
	echo '<a href="?zmazat='.$data2["comment_id"].'&komentar" onclick="return confirm(\'Zmazať komentár užívateľa '.username($data2["comment_userid"]).' ?\');" title="Odstrániť komentár" class="btn btn-default btn-xs"><i class="fa fa-ban"></i> Odstrániť komentár</a>';
	}
	echo ($rowsrep >= 3 ? ' <a class="btn btn-default btn-xs sreply" data-comid="'.$data2["comment_id"].'"><i class="fa fa-level-down"></i> Zobraziť ďaľšie odpovede ('.($rowsrep-1).')</a>' : "");
    echo '</div>';
		$schovaj = ($rowsrep >= 3 ? 'hidencom kom'.$data2["comment_id"] : "");

		if ($rowsrep >= 1) {
		$i = 0;
        while($datareply = dbarray($resultreply)){

echo '
<div class="media komentarreply '.($i == 0 ? "" : $schovaj).'">
  <a class="pull-left">
    <img class="media-object img-circle" src="'.useravatar($datareply["comment_userid"]).'" alt="'.username($datareply["comment_userid"]).'">
  </a>
  <div class="media-body">
    <h4 class="media-heading">'.username($datareply["comment_userid"],1).' <span class="time">'.timeago($datareply["comment_time"]).'</span></h4>
    '.wordwrap(bbcode(badwords(smiley($datareply["comment_text"]))),100," ",1).'
    <div class="clearfix"></div>
    <div class="buttonsinfo">
';
	if(SADMIN OR (MEMBER && $datareply["comment_userid"] == $userinfo["user_id"])){
	echo '<a href="?zmazat='.$datareply["comment_id"].'&komentar" onclick="return confirm(\'Zmazať komentár užívateľa '.username($datareply["comment_userid"]).' ?\');" title="Odstrániť komentár" class="btn btn-default btn-xs"><i class="fa fa-ban"></i> Odstrániť komentár</a>';
	}
echo '
    </div>
  </div>
</div>
';

		$i++;
		}
        }
echo '
  </div>
</div>
';
	   }
		if($rows3 > "7"){
			if(isset($_GET["vsetkykomentare"]) && $_GET["vsetkykomentare"] == "zobrazit"){
			echo "<a href='?vsetkykomentare=skryt#komentare' class='buttonf'>Skryť všetky komentáre</a>";
			}else{
			echo "<a href='?vsetkykomentare=zobrazit#komentare' class='buttonf'>Zobraziť všetky komentáre</a>";
			}
		}

		}else{
		echo "<p style='padding: 10px;'>Žiadny komentár ešte nebol pridaný. Buďte prvý kto pridá komentár.</p>";
		}
echo "</div>";
}

// LOGIN PROCES

// logOFF
if(isset($_GET['logout'])){

	setcookie("log", '',(time()-3600), "/", "", "0");
	$logged = 0;
	define("MEMBER", false);
	define("SADMIN", false);
	redirect("/");

}

// kontrola prihlasenia + overenie udajov
if(isset($_COOKIE["log"]) == 0){

	setcookie("log", '',(time()-3600), "/", "", "0");
	$logged = 0;

}else{

	$login = explode(".",$_COOKIE['log'],2);
	$bb = dbquery("SELECT * FROM bg_users WHERE user_id='".dbescape($login[0])."' AND user_active='1'");
	$userinfo = dbarray($bb);

	$detectban = dbrows(dbquery("SELECT * FROM da_bans WHERE ban_userid='".$userinfo["user_id"]."' AND ban_durationtime>'".time()."' "));
	if($detectban == 0){
		if($userinfo["user_password"] == $login["1"] && $userinfo["user_id"] == $login["0"] ){
			$logged = 1;
		}else{
			$logged = 0;
			redirect("?logout");
		}
	}else{
		$logged = 0;
		redirect("?logout");
	}

}

// login odoslany
if((isset($_POST["user_email"]) && isset($_POST["user_password"]))){

        $passwordhash = md5(md5(md5($_POST["user_password"])));

        if($_POST["user_email"] != "" && $_POST["user_password"] != "") {

	   		if (filter_var($_POST["user_email"], FILTER_VALIDATE_EMAIL)) {

           	$bb = dbquery("SELECT * FROM bg_users WHERE user_email='".dbescape($_POST["user_email"])."'");
           	$userinfo = dbarray($bb);

		   	if(dbrows($bb) == 1 && $userinfo["user_active"] == 0){$notifdeactive = 1;}

			$detectban = dbrows(dbquery("SELECT * FROM da_bans WHERE ban_userid='".$userinfo["user_id"]."' AND ban_durationtime>'".time()."' "));
			if($detectban == 0){

					if($userinfo["user_email"] != htmlspecialchars($_POST["user_email"])){$bademail = 1;}

					if($userinfo["user_password"] == $passwordhash){
						setcookie("log",$userinfo["user_id"].".".$passwordhash,(time()+(3600*24*12*24)), "/", "", "0");
						$logged = 1;
						$notiflogin = 1;
					}else{
						$badpassword = 1;
					}

			}else{$notifban = $userinfo["user_id"];}

			}else{$bademail = 1;}

		}

}

if($logged == 1){ define("MEMBER", true);}else{define("MEMBER", false);}

if(MEMBER && $userinfo["user_perm"] == "3"){
		define("SADMIN", true); define("ADMIN", true);
 }else{
		define("SADMIN", false);
	if(MEMBER && $userinfo["user_perm"] == "2"){
		define("ADMIN", true);
	}else{
		define("ADMIN", false);
	}
}
function userperm($perm) {
	global $userinfo;
		if(MEMBER && $userinfo["user_perm"] == $perm){
			return true;
		}else{
			if(MEMBER && $userinfo["user_perm"] == "3"){
				return true;
			}else{
				return false;
			}
		}
}

// Update ip+ last activity
if(MEMBER){

    if($_SERVER["REMOTE_ADDR"] != $userinfo["user_ip"]){
    dbquery("UPDATE bg_users SET user_oldip='".$userinfo["user_ip"]."' WHERE user_id='".$userinfo["user_id"]."'");
	dbquery("UPDATE bg_users SET user_ip='".$_SERVER["REMOTE_ADDR"]."' WHERE user_id='".$userinfo["user_id"]."'");
    }

	dbquery("UPDATE bg_users SET user_lastactivity='".time()."' WHERE user_id='".$userinfo["user_id"]."'");
	dbquery("UPDATE bg_users SET user_browser='".getBrowser()."' WHERE user_id='".$userinfo["user_id"]."'");
	dbquery("UPDATE bg_users SET user_os='".getOS()."' WHERE user_id='".$userinfo["user_id"]."'");
}

// favorite
function fav($id,$type = "A",$link = ""){

		if(MEMBER){
			global $userinfo;

	$favr = dbquery("SELECT * FROM bg_favorite WHERE fav_userid = '".$userinfo["user_id"]."' AND fav_type='".$type."' AND fav_pageid='".$id."'");
           $fav = dbarray($favr);
		   $favuser = dbrows($favr);


	if($favuser != "1"){

		if(isset($_GET['oblubene'])){

			dbquery("INSERT INTO bg_favorite(fav_userid, fav_pageid, fav_type, fav_time) VALUES('".$userinfo["user_id"]."','".$id."','".$type."', '".time()."')");
				if($link == ""){
			redirect("/clanok/".$_GET["id"]."/".strip_tags($_GET["n"]));
				}else{
			redirect($link);
				}
		}

	$faved = '<a href="?oblubene" class="favtxt btn btn-success btn-xs"><i class="fa fa-heart"></i> Pridať článok do obľubených</a>';
	}else{

			if(isset($_GET['neoblubene'])){

			dbquery("DELETE FROM bg_favorite WHERE fav_pageid='".$id."' AND fav_type='".$type."' AND fav_userid='".$userinfo["user_id"]."'");
				if($link == ""){
			redirect("/clanok/".$_GET["id"]."/".strip_tags($_GET["n"]));
				}else{
			redirect($link);
				}

		}

	$faved = '<a href="?neoblubene" class="fav btn btn-warning btn-xs"><i class="fa fa-heart"></i> Zmazať článok z obľubených</a>';
	}

		}else{
			$faved = "";
		}

		return $faved;
}

?>
