<?php include("settings.php");

header('content-type: application/json; charset=utf-8');

$sth = dbquery("SELECT * FROM bg_articles WHERE article_suggestion='0' ORDER BY article_date DESC LIMIT 0,6");

$jsonarray = array();

while ($articleinfo = dbarray($sth, MYSQL_ASSOC)) {

			$row_array["title"] = $articleinfo["article_name"];
			$row_array["url"] = "http://desart.sk/clanok/".$articleinfo["article_id"]."/".bezd($articleinfo["article_name"]);
			$row_array["description"] = $articleinfo["article_minitxt"];
			$row_array["author"] = username($articleinfo["article_author"],0);
			$row_array["date"] = $articleinfo["article_date"];
			$row_array["reads"] = $articleinfo["article_reads"];
			$row_array["comments"] = dbcount("(comment_id)", "bg_comments","comment_pageid='".$articleinfo["article_id"]."' AND comment_type='A'");

    array_push($jsonarray,$row_array);
}
print json_encode($jsonarray);