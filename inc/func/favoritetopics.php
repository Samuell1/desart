<?php

if(!isset( $_SERVER['HTTP_X_REQUESTED_WITH']) && !($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' )){die("bad request");}

require("../settings.php");

if(!MEMBER){die();}

	$result_subheader4 = dbquery("SELECT forumt_name,forumt_newpost,forumt_reads,forumt_id,forumt_fid,forumt_fav,forumt_userid,forumt_time FROM bg_forumtopic WHERE forumt_fav='1' ORDER BY forumt_newpost DESC");
			

    if(dbrows($result_subheader4) >= 1){
			
		while($dataf3 = dbarray($result_subheader4)){
		
				$postcount = dbcount("(post_id)", "bg_forumtopicpost"," post_topicid='".$dataf3["forumt_id"]."'");
				$limitp = "15";
				$stranatema = ceil($postcount/$limitp);
				
				$odpovedi = dbcount("(post_id)", "bg_forumtopicpost"," post_topicid='".$dataf3["forumt_id"]."'")-1;

                    echo '
                    <div class="forumtema">
                        <div class="row">
                            <div class="col-xs-6 col-md-6 temainfo">
                                <div class="cat">'.forumcat($dataf3["forumt_fid"]).'</div>
                                <a href="/tema/'.$dataf3["forumt_id"].'/'.bezd($dataf3["forumt_name"]).($stranatema > 1 ? "?strana=".$stranatema:"").'" title="'.$dataf3["forumt_name"].'">'.trimlink($dataf3["forumt_name"],40).'</a>
                            </div>
                            <div class="col-sm-4 col-md-4">
                                <span class="infotema pull-right">
                                Autor: '.username($dataf3["forumt_userid"],1).'<br/>
                                Príspevkov: '.$odpovedi.'x
                                </span>
                            </div>
                            <div class="col-md-2 hidden-xs"><a href="/tema/'.$dataf3["forumt_id"].'/'.bezd($dataf3["forumt_name"]).($stranatema > 1 ? "?strana=".$stranatema:"").'" class="otvr popovertoggle '.($dataf3["forumt_fav"]==1 ? "favotvr ":"").'" data-content="<div style=\'width:300px\'>posledná aktivita '.(forumtopic1post($dataf3["forumt_id"]) != ": " ? forumtopic1post($dataf3["forumt_id"]):"").'</div>" data-placement="left" data-trigger="hover">Zobraziť</a></div>
                        </div>
                    </div>
                    ';

		}
        
    }else{
    echo '<p style="font-size:12px;color:#888;">Žiadne založené témy.</p>';   
    }

?>