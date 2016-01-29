<?php

if(!isset( $_SERVER['HTTP_X_REQUESTED_WITH']) && !($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' )){die("bad request");}

require("../settings.php");

if(!MEMBER){die();}

	$result_subheader4 = dbquery("SELECT DISTINCT(post_topicid) FROM bg_forumtopicpost WHERE post_userid='".$userinfo["user_id"]."' ORDER BY post_time DESC LIMIT 0,16");
			

    if(dbrows($result_subheader4) >= 1){
			
		while($dataf3 = dbarray($result_subheader4)){
		
				$postcount = dbcount("(post_id)", "bg_forumtopicpost"," post_topicid='".$dataf3["post_topicid"]."'");
				$limitp = "15";
				$stranatema = ceil($postcount/$limitp);
				
				$odpovedi = dbcount("(post_id)", "bg_forumtopicpost"," post_topicid='".$dataf3["post_topicid"]."'")-1;
                $myodpovedi = dbcount("(post_id)", "bg_forumtopicpost"," post_topicid='".$dataf3["post_topicid"]."' AND post_userid='".$userinfo["user_id"]."'");
                if(MEMBER){
                    if(forumtopiclock($dataf3["post_topicid"]) == 0){
                       if (forumtopicread($userinfo["user_id"],$dataf3["post_topicid"],$postcount)) {
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
                            <div class="col-xs-6 col-md-6 temainfo">
                                <div class="cat">'.forumcat(forumcatid($dataf3["post_topicid"])).'</div>
                                <a href="/tema/'.$dataf3["post_topicid"].'/'.bezd(forumtopicname($dataf3["post_topicid"])).($stranatema > 1 ? "?strana=".$stranatema:"").'" title="'.forumtopicname($dataf3["post_topicid"]).'">'.trimlink(forumtopicname($dataf3["post_topicid"]),37).'</a>
                            </div>
                            <div class="col-sm-4 col-md-4">
                                <span class="infotema pull-right">
                                Moje príspevky:  '.$myodpovedi.'x<br/>
                                Príspevkov: '.$odpovedi.'x
                                </span>
                            </div>
                            <div class="col-md-2 hidden-xs"><a href="/tema/'.$dataf3["post_topicid"].'/'.bezd(forumtopicname($dataf3["post_topicid"])).($stranatema > 1 ? "?strana=".$stranatema:"").'" class="otvr '.($newp=="activityotvr" ? $newp:"").'" title="posledná aktivita '.(forumtopic1post($dataf3["post_topicid"]) != ": " ? forumtopic1post($dataf3["post_topicid"]):"").'" title="posledná aktivita '.(forumtopic1post($dataf3["post_topicid"]) != ": " ? forumtopic1post($dataf3["post_topicid"]):"").'">Zobraziť</a></div>
                        </div>
                    </div>
                    ';

		}
        
    }else{
    echo '<p style="font-size:12px;color:#888;">Žiadne založené témy.</p>';   
    }

?>