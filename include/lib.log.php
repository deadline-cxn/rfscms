<?
/////////////////////////////////////////////////////////////////////////////////////////
// RFSCMS http://www.sethcoder.com/
/////////////////////////////////////////////////////////////////////////////////////////
function sc_kill($what) {
	echo "<html><head><title>SethCoder</title></head>\n";
	echo "<body>The webpage you were looking for is no longer available... Please try again later.\n";
	sc_log("<font class=sc_admin>[kill start]==========================</font>");
	sc_log("<font class=sc_admin>".$what."</font>");
	sc_log("<font class=sc_admin>============================[kill end]</font>");
	echo "<br>Actions Logged...<br>";
	die("</body></html>");
}
/////////////////////////////////////////////////////////////////////////////////////////
function sc_count() {

		// TODO: Scrub this function
	$countraw++;

	$refer=getenv("HTTP_REFERER");
	$countip=getenv("REMOTE_ADDR");

	$refer_ban=str_replace("&","%26",$refer);
	$refer_ban=str_replace("?","%3F",$refer_ban);

	$countip_ban=$countip;
	$domain_ban=sc_getdomain($refer_ban);
	$a=explode("/",$domain_ban);
	$domain_who=$a[2];

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
			sc_log("SEARCH: ".$searched[$i]);
			$time=date("Y-m-d H:i:s");
			sc_query("insert into `searches` (`search`,            `engine`,      `fullsearch`,`time`)
			         VALUES ('".$searched[$i]."', '$domain_who', '$refer',  '$time')");
		}
	}

	$banip="<a href=\"$RFS_SITE_URL/adm.php?action=banip&ip=$countip_ban\">Ban this IP</a>";
	$banref="<a href=\"$RFS_SITE_URL/adm.php?action=banref&ref=$refer_ban\">Ban this Referral</a>";
	$testweb="<a href=\"http://$countip_ban/\" target=_blank>Test Web Server</a>";
	$bandomain="<a href=\"$RFS_SITE_URL/adm.php?action=bandomain&domain=$domain_ban\">Ban this Domain</a>";
	$whoisip="<a href=\"$RFS_SITE_URL/adm.php?action=nqt&queryType=arin&target=$countip\">WhoIS IP</a>";
	$whoisdm="<a href=\"$RFS_SITE_URL/adm.php?action=nqt&queryType=wwwhois&target=$domain_who\">WhoIS Domain</a>";

	$banned=0;

	if(empty($refer_ban)) $refer_ban="duh";
	if(empty($domain_ban)) $domain_ban="duh";

	if(sc_banned_ref($refer_ban)==true)     $banned=1;
	if(sc_banned_ip($countip_ban)==true)    $banned=1;
	if(sc_banned_domain($domain_ban)==true) $banned=1;

	$what="<br>\n";
	$what.="vIPADD|".$countip."| [$banip][$whoisip][$testweb]<br>";
	$what.="vAGENT|".getenv('HTTP_USER_AGENT')."|<br>\n";
	if(stristr($refer,"<a href")!=FALSE)
		$what.="vREFER|".$refer."|<br>\n ";
	else
		$what.="vREFER|<a href=\"".$refer."\" target=_blank>".$refer_link."</a>|<br>[$banref][$bandomain][$whoisdm]<br>\n ";

	if($banned==1) {
		$unbanip="<a href=\"$RFS_SITE_URL/adm.php?action=unbanip&ip=$countip_ban\">UnBan this IP</a>";
		$unbanref="<a href=\"$RFS_SITE_URL/adm.php?action=unbanref&ref=$refer_ban\">UnBan this Referral</a>";
		$unbandomain="<a href=\"$RFS_SITE_URL/adm.php?action=unbandomain&domain=$domain_ban\">UnBan this Domain</a>";

		$what="BANNED:<br>\n";
		$what.="vIPADD|".$countip."| [$unbanip][$whoisip][$testweb]<br>";
		$what.="vAGENT|".getenv('HTTP_USER_AGENT')."|<br>\n";
		if(stristr($refer,"<a href")!=FALSE)
			$what.="vREFER|".$refer."|<br>\n ";
		else
			$what.="vREFER|<a href=\"".$refer."\" target=_blank>".$refer_link."</a>|<br>[$unbanref][$unbandomain][$whoisdm]<br>\n ";
		sc_kill($what);
	}

	$countit++;
	$counttoday++;

	if(stristr($refer,"google")) {
		$google=1;
		$banned=0;
	}
	if(stristr($refer,"aol"))    {
		$aol=1;
		$banned=0;
	}
	
	if(stristr(getenv('HTTP_USER_AGENT'),"google")) {
		$google=1;
		$banned=0;
	}
	if(stristr($refer,"yahoo"))  {
		$yahoo=1;
		$banned=0;
	}
	if(stristr($refer,"referrerslist")) {
		$referrerslist=1;
		$banned=0;
	}

	// do not count search engine stuff, but log that it was searching the site
	// msnbot
	//if(stristr(getenv('HTTP_USER_AGENT'),"msnbot")!=FALSE) $what=" --------> MSN Bot!";
	// Googlebot
	//if(stristr(getenv('HTTP_USER_AGENT'),"googlebot")!=FALSE) $what=" --------> Google Bot!";
	// Mediapartners-Google
	//if(stristr(getenv('HTTP_USER_AGENT'),"mediapartners-google")!=FALSE) $what=" --------> Ad Google Bot!";
	// Yahoo
	//if(stristr(getenv('HTTP_USER_AGENT'),"yahoo")!=FALSE) $what=" --------> Yahoo Slurp Bot!";
	// Slurp
	//if(stristr(getenv('HTTP_USER_AGENT'),"slurp")!=FALSE) $what=" --------> Yahoo Slurp Bot!";
	// CydralSpider
	//if(stristr(getenv('HTTP_USER_AGENT'),"CydralSpider")!=FALSE) $what=" --------> CydralSpider Bot!";

	// kill some things
	//if(stristr($refer,"BCReporter")!=FALSE)        { $refok=false; sc_kill($what); }
	//if(stristr($refer,".gov")) {$refok=false; sc_kill($what); }
	//if(stristr(getenv('REMOTE_HOST'),".gov")) {$refok=false; sc_kill($what); }
	//if(stristr(getenv('REMOTE_HOST'),".mil")) { $GLOBALS['noslow']="yes"; }

	$url2=explode("/",$refer);

	if($url2['0']=="http:") {
		$url=$url2['0']."//".$url2['2']."/";

		$doone=false;

		if( ($url!="http://www.defectiveminds.com/") &&
		        ($url!="http://ickleazure/") &&
		        ($url!="http://www.sethcoder.com/") &&
		        ($url!="http://sethcoder.com/") )
			$doone=true;
		if( ($url!="http://ickleazure/") &&
		        (stristr($refer,"sphider"))  )
			$doone=true;

		if($doone==true) {
			sc_log($what);

			if($google)        $url="http://www.google.com/";
			if($yahoo)         $url="http://www.yahoo.com/";
			if($referrerslist) $url="http://www.referrerslist.com/";
			if($aol)           $url="http://www.aol.com/";

			$result=sc_query("select * from `link_bin` where `link` = '$url'");
			if(mysql_num_rows($result)) {
				$link=mysql_fetch_object($result);
				$link->referrals=$link->referrals+1;
				sc_query("update `link_bin` set `referrals` = '$link->referrals' where `id` = '$link->id'");
				$time=date("Y-m-d H:i:s");
				sc_query("update `link_bin` set `bumptime` = '$time' where `id` = '$link->id'");
			} else {
				$time=date("Y-m-d H:i:s");
				sc_query("insert into `link_bin` (`link`, `sname`, `time`, `bumptime`, `referrals`, `clicks`, `referral`, `hidden`, `category`,`reviewed`)
				         values('$url','".$url2['2']."','$time','$time','1','0','yes','1','!!!TEMP!!!','no')");
			}
		}
	}

	if($url!="http://www.defectiveminds.com/")
		if(getenv("REMOTE_ADDR")!=$countip) {

			// sc_writecount($countit,$countip,$counttoday,$countdate,$countraw);
	}

	if(date("d")==$countdate) {
		$counttoday++;
		// sc_writecount($countit,$countip,$counttoday,$countdate,$countraw);
	}
	return $countit;
}
/////////////////////////////////////////////////////////////////////////
function sc_log($logtext) { eval(scg());
	$logfile="$RFS_SITE_PATH/log/log.htm";
	$fp2=fopen($logfile,"a");
	if(empty($fp2)) $fp2=fopen($logfile,"w");
	if($fp2) {
		$logtext="<p>".date("Y-m-d H:i:s").": ".$logtext."</p>\n";
		fputs($fp2,$logtext);
		fclose($fp2);
	}
}

?>

