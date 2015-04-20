<?php
/////////////////////////////////////////////////////////////////////////////////////////
// RFSCMS http://www.sethcoder.com/
/////////////////////////////////////////////////////////////////////////////////////////
function lib_log_kill($what) {
	echo "<html><head><title>SethCoder</title></head>\n";
	echo "<body>The webpage you were looking for is no longer available... Please try again later.\n";
	lib_log_add_entry("<font class=rfs_admin>[kill start]==========================</font>");
	lib_log_add_entry("<font class=rfs_admin>".$what."</font>");
	lib_log_add_entry("<font class=rfs_admin>============================[kill end]</font>");
	echo "<br>Actions Logged...<br>";
	die("</body></html>");
}
/////////////////////////////////////////////////////////////////////////////////////////
function lib_log_count($user) {
	eval(lib_rfs_get_globals()); 
        //	$countraw++;
	$refer=getenv("HTTP_REFERER");
	$countip=getenv("REMOTE_ADDR");
	$p=lib_domain_canonical_url();
	$r=lib_mysql_fetch_one_object("select * from counters where page = '$p'");
	if(!empty($r->page)){
		$r->hits_raw+=1;
		if($r->last_ip!=$countip){
			$r->last_ip=$countip;
			$r->hits_unique+=1;
		}
		lib_mysql_query("update counters set user='$user' where page='$r->page'");
		lib_mysql_query("update counters set hits_raw='$r->hits_raw' where page='$r->page'");
		lib_mysql_query("update counters set hits_unique='$r->hits_unique' where page='$r->page'");
		lib_mysql_query("update counters set last_ip='$r->last_ip' where page='$r->page'");
	}
	else {
		$ct=1;
		lib_mysql_query("INSERT INTO `counters` (`id`, `page`, `user`, `last_ip`, `hits_raw`, `hits_unique`) VALUES (NULL, '$p', '$user', '$countip', '$ct', '$ct');");
	}	
	$refer_ban=str_replace("&","%26",$refer);
	$refer_ban=str_replace("?","%3F",$refer_ban);
	$countip_ban=$countip;
	$domain_ban=lib_domain_getdomain($refer_ban);
	
	$refer_link=str_replace("%20"," ",$refer);
	$refer_link=str_replace("%2F","/",$refer_link);
	$refer_link=str_replace("%2f","/",$refer_link);
	$refer_link=str_replace("%3D","=",$refer_link);
	$refer_link=str_replace("%3d","=",$refer_link);
	$refer_link=str_replace("?","<br>",$refer_link);
	$refer_link=str_replace("%3F","<br>",$refer_link);
	$refer_link=str_replace("%3f","<br>",$refer_link);
	$refer_link=str_replace("%3A",":",$refer_link);
	$refer_link=str_replace("%3a",":",$refer_link);
	$refer_link=str_replace("%2B","+",$refer_link);
	$refer_link=str_replace("%2b","+",$refer_link);
	$refer_link=str_replace("%26","<br>",$refer_link);
	$refer_link=str_replace("&","<br>",$refer_link);
	$searched=explode("<br>",$refer_link);
	$nsear=count($searched);
	for($i=0; $i<$nsear; $i++) {
		if( (substr($searched[$i],0,2)=="p=") ||
		        (substr($searched[$i],0,2)=="q=") ) {
			lib_log_add_entry("SEARCH: ".$searched[$i]);
			$time=date("Y-m-d H:i:s");
			lib_mysql_query("insert into `searches` (`search`,            `engine`,      `fullsearch`,`time`)
											VALUES ('".$searched[$i]."', '$domain_ban', '$refer',  '$time')");
		}
	}
	$banip=""; if(!empty($countip_ban))   	$banip="[<a href=\"$RFS_SITE_URL/admin/adm.php?action=f_banip&ip=$countip_ban\">Ban this IP</a>]";
	$banref=""; if(!empty($refer_ban)) 	  	$banref="[<a href=\"$RFS_SITE_URL/admin/adm.php?action=f_banref&ref=$refer_ban\">Ban this Referral</a>]";
	$testweb=""; if(!empty($countip_ban)) 	$testweb="[<a href=\"http://$countip_ban/\" target=_blank>Test Web Server</a>]";
	$bandomain=""; if(!empty($domain_ban))	$bandomain="[<a href=\"$RFS_SITE_URL/admin/adm.php?action=f_bandomain&domain=$domain_ban\">Ban this Domain</a>]";
	$whoisip=""; if(!empty($countip))  		$whoisip="[<a href=\"$RFS_SITE_URL/modules/nqt/nqt.php?queryType=arin&target=$countip\">WhoIS IP</a>]";
	$whoisdm=""; if(!empty($domain_ban)) 	$whoisdm="[<a href=\"$RFS_SITE_URL/modules/nqt/nqt.php?queryType=wwwhois&target=$domain_ban\">WhoIS Domain</a>]";
	$banned=0;
	if(empty($refer_ban)) $refer_ban="duh";
	if(empty($domain_ban)) $domain_ban="duh";
	if(lib_domain_banned_ref($refer_ban)==true)     $banned=1;
	if(lib_domain_banned_ip($countip_ban)==true)    $banned=1;
	if(lib_domain_banned_domain($domain_ban)==true) $banned=1;

	$what="<br>\n";
	$what.="IP | ".$countip."| $banip $whoisip $testweb<br>";
	$what.="AGENT|".getenv('HTTP_USER_AGENT')."|<br>\n";
	if(stristr($refer,"<a href")!=FALSE)
		$what.="REFER|".$refer."|<br>\n ";
	else
		$what.="REFER|<a href=\"".$refer."\" target=_blank>".$refer_link."</a>|<br>$banref $bandomain $whoisdm<br>\n ";

	if($banned==1) { 
		$unbanip="[<a href=\"$RFS_SITE_URL/admin/adm.php?action=f_unbanip&ip=$countip_ban\">UnBan this IP</a>]";
		$unbanref="[<a href=\"$RFS_SITE_URL/admin/adm.php?action=f_unbanref&ref=$refer_ban\">UnBan this Referral</a>]";
		$unbandomain="[<a href=\"$RFS_SITE_URL/admin/adm.php?action=f_unbandomain&domain=$domain_ban\">UnBan this Domain</a>]";
		$what="BANNED:<br>\n";
		$what.="IP|".$countip."| $unbanip $whoisip $testweb <br>";
		$what.="AGENT|".getenv('HTTP_USER_AGENT')."|<br>\n";
		if(stristr($refer,"<a href")!=FALSE)
			$what.="REFER|".$refer."|<br>\n ";
		else
			$what.="REFER|<a href=\"".$refer."\" target=_blank>".$refer_link."</a>|<br> $unbanref $unbandomain $whoisdm <br>\n ";
		lib_log_kill($what);
	}

	// $countit++;
	// $counttoday++;

	if(stristr($refer,"google")) { $google=1; $banned=0; }
	if(stristr($refer,"aol"))    { $aol=1;    $banned=0; }
	if(stristr(getenv('HTTP_USER_AGENT'),"google")) { $google=1; $banned=0; }
	if(stristr($refer,"yahoo"))  { $yahoo=1; $banned=0;	}
	if(stristr($refer,"referrerslist")) { $referrerslist=1; $banned=0; }
	
	$do_not_log=false;
	if(stristr($refer,$RFS_SITE_URL)) $do_not_log=true;
	if(stristr($countip,"127.0.0.1")) $do_not_log=true;
	if($do_not_log==false) lib_log_add_entry($what);

	// do not count search engine stuff, but log that it was searching the site
	//if(stristr(getenv('HTTP_USER_AGENT'),"msnbot")!=FALSE) $what=" --------> MSN Bot!"; // msnbot
	//if(stristr(getenv('HTTP_USER_AGENT'),"googlebot")!=FALSE) $what=" --------> Google Bot!";	 // Googlebot
	//if(stristr(getenv('HTTP_USER_AGENT'),"mediapartners-google")!=FALSE) $what=" --------> Ad Google Bot!";	 // Mediapartners-Google
	//if(stristr(getenv('HTTP_USER_AGENT'),"yahoo")!=FALSE) $what=" --------> Yahoo Slurp Bot!"; // Yahoo	
	//if(stristr(getenv('HTTP_USER_AGENT'),"slurp")!=FALSE) $what=" --------> Yahoo Slurp Bot!"; // Slurp	
	//if(stristr(getenv('HTTP_USER_AGENT'),"CydralSpider")!=FALSE) $what=" --------> CydralSpider Bot!"; // CydralSpider

	$url2=explode("/",$refer);
	$url=$url2['0']."//".$url2['2']."/";
	
	if(!empty($google))$url="http://www.google.com/";
	if(!empty($yahoo)) $url="http://www.yahoo.com/";
	if(!empty($referrerslist)) $url="http://www.referrerslist.com/";
	if(!empty($aol))           $url="http://www.aol.com/";
	
	$result=lib_mysql_query("select * from `link_bin` where `link` = '$url'");
	if($result->num_rows) {
		$link=$result->fetch_object();
		$link->referrals=$link->referrals+1;
		lib_mysql_query("update `link_bin` set `referrals` = '$link->referrals' where `id` = '$link->id'");
		$time=date("Y-m-d H:i:s");
		lib_mysql_query("update `link_bin` set `bumptime` = '$time' where `id` = '$link->id'");
	} 
	else {
		$time=date("Y-m-d H:i:s");
		lib_mysql_query("insert into `link_bin` (`link`, `sname`, `time`, `bumptime`, `referrals`, `clicks`, `referral`, `hidden`, `category`,`reviewed`)
										  values('$url','".$url2['2']."','$time','$time','1','0','yes','1','unsorted','no')");
	}
	// if(date("d")==$countdate) {		$counttoday++; 	} 	return $countit;
}
/////////////////////////////////////////////////////////////////////////
function lib_log_add_entry($logtext) {
	eval(lib_rfs_get_globals());
	$logfile="$RFS_SITE_LOG_PATH/log.htm";
	$fp2=fopen($logfile,"a");
	if(empty($fp2)) $fp2=fopen($logfile,"w");
	if($fp2) {
		$logtext="<p>".date("Y-m-d H:i:s").": ".$logtext."</p>\n";
		fputs($fp2,$logtext);
		fclose($fp2);
	}
} 
