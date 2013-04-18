<?
/////////////////////////////////////////////////////////////////////////////////////////
// RFSCMS http://www.sethcoder.com/
/////////////////////////////////////////////////////////////////////////////////////////
// sc_div(__FILE__);
/////////////////////////////////////////////////////////////////////////////////////////
srand((double) microtime() * 1000000);  // randomize timer
setlocale(LC_MONETARY, $RFS_SITE_LOCALE);
/////////////////////////////////////////////////////////////////////////////////////////
function sc_maintenance() { eval(scg());
    global $theme;
	sc_div("sc_maintenance start");
	sc_multi_rename("$RFS_SITE_PATH/themes/$theme",$theme,"t");
	// sc_count();
	sc_get_modules();
	$data=sc_getuserdata($_SESSION['valid_user']);
	if($mc_gross>0) $data->donated="yes";
	if(empty($theme))                   $theme=$RFS_SITE_DEFAULT_THEME;
	if(!empty($data->theme))            $theme=$data->theme;
	if(sc_yes($RFS_SITE_FORCE_THEME))   $theme=$RFS_SITE_FORCED_THEME;
    if(!empty($_GET['theme'])) {
		$theme=$_GET['theme'];
		sc_setuservar($data->id,"theme",$theme);
    }
	if(!empty($theme)) {
		if($theme!="$data->theme") {
            if($data) {
                sc_query("UPDATE users SET theme='$theme' where name = '$data->name'");
                $data->theme=$theme;
            }
		} else {
			$theme=$data->theme;
		}
	}
	sc_div("sc_maintenance end [$theme]");
}
/////////////////////////////////////////////////////////////////////////////////////////
function sc_info($t,$c,$c2) { 	echo "<div style=' font-size: 2em; color:$c; background-color:$c2; width:100%;'>$t</div>"; }
/////////////////////////////////////////////////////////////////////////////////////////
function sc_num2txt($x) { 
	$txt=$x." "; if($x==0) $txt=" 0 "; if($x>0) { 	$txt="%2B".$x." "; }
	if($x>1000) { $t=round($x/1000,1); $txt="%2B".$t."k "; }
	if($x>1000000) { $t=round($x/1000000,1); $txt="%2B".$t."m "; }
	return $txt;
}
/////////////////////////////////////////////////////////////////////////////////////////
function sc_flush_buffers(){ 
    ob_end_flush(); 
    ob_flush(); 
    flush(); 
    ob_start(); 
} 
/////////////////////////////////////////////////////////////////////////////////////////
// Make globals available to functions -- >> eval(scg());
function scg() {
	$out="";

	foreach($GLOBALS as $k => $v) {

       $nmc=$k[0];

       if(is_numeric($nmc)) $k="__".$k;

    if(!is_numeric($nmc))
		if( ($k != 'GLOBALS') &&
		    ($k != '_ENV') &&
		    ($k != 'HTTP_ENV_VARS') &&
		    ($k != 'DOCUMENT_ROOT') &&
           ($k != 'GATEWAY_INTERFACE') &&
		    ($k != 'HTTP_ACCEPT') &&
		    ($k != 'HTTP_ACCEPT_CHARSET') &&
		    ($k != 'HTTP_ACCEPT_ENCODING') &&
		    ($k != 'HTTP_ACCEPT_LANGUAGE') &&
		    ($k != 'PHPRC') &&
		    ($k != 'HTTP_CACHE_CONTROL') &&
		    ($k != 'HTTP_CONNECTION') &&
		    ($k != 'HTTP_COOKIE') &&
		    ($k != 'HTTP_HOST') &&
		    ($k != 'HTTP_REFERER') &&
		    ($k != 'HTTP_USER_AGENT') &&
		    ($k != 'PATH') &&
		    ($k != 'QUERY_STRING') &&
		    ($k != 'REDIRECT_STATUS') &&
		    ($k != 'REMOTE_ADDR') &&
		    ($k != 'REMOTE_PORT') &&
		    ($k != 'REQUEST_METHOD') &&
		    ($k != 'REQUEST_URI') &&
		    ($k != 'SCRIPT_FILENAME') &&
		    ($k != 'SCRIPT_NAME') &&
		    ($k != 'SERVER_ADDR') &&
		    ($k != 'SERVER_ADMIN') &&
		    ($k != 'SERVER_NAME') &&
		    ($k != 'SERVER_PORT') &&
		    ($k != 'SERVER_PROTOCOL') &&
		    ($k != 'SERVER_SIGNATURE') &&
		    ($k != 'SERVER_SOFTWARE') &&
		    ($k != 'UNIQUE_ID') &&
		    ($k != '__utma') &&
		    ($k != '__utmz') &&

		    ($k != '__utmb') &&
		    ($k != '__utmc') &&
		    ($k != '__atuvc') &&
		    ($k != 'PHP_SELF') &&
		    ($k != 'REQUEST_TIME') &&
		    ($k != '_POST') &&
		    ($k != 'HTTP_POST_VARS') &&
		    ($k != '_GET') &&
		    ($k != 'HTTP_GET_VARS') &&
		    ($k != '_COOKIE') &&
		    ($k != 'HTTP_COOKIE_VARS') &&
		    ($k != '_SERVER') &&
		    ($k != 'HTTP_SERVER_VARS') &&
		    ($k != '_FILES') &&
		    ($k != 'HTTP_POST_FILES') &&
		    ($k != '_REQUEST') &&
           (!function_exists($k)) ) {

               $k=str_replace("\$","_",$k);

            $out.="\$$k=\$GLOBALS['$k'];\n";

		}

	}
    // d_echo($out);
	return $out;
}
/////////////////////////////////////////////////////////////////////////////////////////
function sc_red()   { 	return "<font style='color:white; background-color:red;'>"; }
/////////////////////////////////////////////////////////////////////////////////////////
function sc_green() { 	return "<font style='color:white; background-color:green;'>"; }
/////////////////////////////////////////////////////////////////////////////////////////
function sc_blue()  { 	return "<font style='color:white; background-color:blue;'>"; }
/////////////////////////////////////////////////////////////////////////////////////////
function sc_flush () { 	echo(str_repeat(' ',256)); 		if (ob_get_length()) { 		@ob_flush();		@flush();		@ob_end_flush(); 	} 	@ob_start();}
/////////////////////////////////////////////////////////////////////////////////////////
function sc_warn($x) { 	eval(scg()); 	echo "<div class=warning><br><img src='$RFS_SITE_URL/images/icons/exclamation2.png' border=0><br><br>$x<br>&nbsp;</div>"; }
/////////////////////////////////////////////////////////////////////////////////////////
function sc_inform($x) { eval(scg());
    echo "<div class=inform>
    <img src='$RFS_SITE_URL/images/icons/Warning.png' width=\"12\" border=\"0\">
    $x<br> </div>"; }
/////////////////////////////////////////////////////////////////////////////////////////
function sc_question($x) { eval(scg());	$x=str_replace("<a ","<a class=ainform ",$x); 	$x=str_replace("<hr>", "<hr class=questionhr> ",$x); 	echo smiles("<center><div class=question align=left><img src='$RFS_SITE_URL/images/icons/3dquestion.png' align=right border=0>$x</div>");}
/////////////////////////////////////////////////////////////////////////////////////////
function sc_yes($x) {
	$x=strtolower($x);
	if( (stristr($x,"true")) || (stristr($x,"yes")) || (stristr($x,"on")) || (stristr($x,"1")) )
		return true;
	return false;
}
/////////////////////////////////////////////////////////////////////////
// RFS SMILIES FILTER
function smiles($text) {
	$query = "select * from smilies";
	$smiley_result = sc_query($query);
	$num_smilies=mysql_num_rows($smiley_result);
	if($num_smilies>0) {
		for($i=0; $i<$num_smilies; $i++) {
			$der = mysql_fetch_array($smiley_result);
			$from=$der['sfrom'];
			$to=$der['sto'];
			$text=str_replace($from,$to,$text);
		}
	}
	// built in stuff
	$data=$GLOBALS['data'];
	$text=str_replace("[site_name]" ,$GLOBALS['site_name'],$text);
	$text=str_replace("[usr]" ,"Users Online :".usersonline($data->name),$text);
	$text=str_replace("[usrs]","Users Logged In :".usersloggedin(),$text);
	$text=str_replace("[users_logged_details]",users_logged_details($data->name),$text);
	$text=str_replace("-{","[",$text);
	$text=str_replace("}-","]",$text);
	return rfs_get($text);
}
/////////////////////////////////////////////////////////////////////////////////////////
// RFS VARIABLE FILTER
function rfs_echo($t) { echo rfs_get($t); }
function rfs_get($t) {
	foreach($GLOBALS as $key => $value) {
		if(is_string($value)) {
			$t=str_replace("\$$key",$value,$t);
		}
	}
	foreach($GLOBALS['RFS_TAGS'] as $key => $value) {
		if(stristr($t,$value)) {
			switch($key) {
			case "RFS_SITE_THEME_FORM_CODE":
				$t= $GLOBALS['RFS_SITE_THEME_FORM_CODE'];
				break;
			case "RFS_PHP_SELF":
				//echo "123.what";
				$t= sc_phpself();
				break;
			case "RFS_SITE_FUNCTION":
				$t= "RUNNING: ($key)($value)";
				break;
			default:
				break;
			}
		}
	}
	return $t;
}
/////////////////////////////////////////////////////////////////////////////////////////
function sc_theme_form() {
	eval(scg());

	if(sc_yes($_SESSION["logged_in"])) {
		if(sc_yes($GLOBALS["RFS_SITE_THEME_DROPDOWN"])) {

			echo "<form action=\"$RFS_SITE_URL\" method=get>
			<select name=theme onchange='this.form.submit()'><option>Theme\n";
			$thms=sc_get_themes();
			while(list($key,$thm)=each($thms))        {
				echo "<option";
				if($thm==$data->theme) echo " selected=selected";
				echo ">".$thm;
			}
			echo "</select><noscript><input type=\"submit\" value=\"Go\"></noscript> </form>";
		}

	}
}
/////////////////////////////////////////////////////////////////////////////////////////
function sc_get_themes() {
	$dr=$GLOBALS['RFS_SITE_PATH']."/themes/";
	$themes=array();
	$d = opendir($dr) or die("Wrong path: $dr");
	while(false!==($entry = readdir($d))) {
		if(($entry != '.') && ($entry != '..') && (!is_dir($dir.$entry)) ) {
			if($entry!="_templates")
				if(!strstr($entry,"."))
					array_push($themes,$entry);
		}
	}
	closedir($d);
	natcasesort($themes);
	reset($themes);
	return $themes;
}
/////////////////////////////////////////////////////////////////////////////////////////
function sc_get_theme_image($x) {
    if(file_exists("$RFS_SITE_PATH/themes/$theme/$x"))
        $rx="$RFS_SITE_URL/themes/$theme/$x";
    else $rx="$RFS_SITE_URL/$x";
    return $rx;
}

/////////////////////////////////////////////////////////////////////////////////////////
// function sc_phpself() { 	$v=str_replace("/rfs","",$_SERVER['PHP_SELF']); 	$what=$GLOBALS['site_url'].$v; 	return ($what); }
/////////////////////////////////////////////////////////////////////////////////////////
function mailto($user,$domain) {
	echo "<META HTTP-EQUIV=\"refresh\" content=\"0;URL=mailto.php?user=$user&domain=$domain\">";
}
/////////////////////////////////////////////////////////////////////////
function sc_time($whattime) { // 0000-00-00 00:00:00
	$dtq=explode(" ",$whattime);
	$date=explode("-",$dtq[0]);
	$time=explode(":",$dtq[1]);
	$t=mktime( intval($time[0]),intval($time[1]),intval($time[2]),
	           intval($date[1]),intval($date[2]), intval($date[0]) );  // h,s,m,mnth,d,y
	return date("M d, Y @ h:i:s a",$t);
}
/////////////////////////////////////////////////////////////////////////
function mailgo($email,$message,$subject) {
	eval(scg());
	$email=str_replace("'at'","@",$email);
	$headers  = "MIME-Version: 1.0\r\n";
	$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
	$message  = $begin.$message;
	$message .= "<p>Automated message from <a href=$RFS_SITE_URL>$RFS_SITE_NAME</a> ~ Do not reply!</p>\n";
	return mail(
    $email,
    $subject ,
    $message,

    "From: $RFS_SITE_ADMIN_EMAIL\r\n$headers");
}
/////////////////////////////////////////////////////////////////////////
function generate_password() {
	$i=0;
	$password="";
	srand((double) microtime() * 1000000);
	while($i<8) {
		$password .= chr(rand(33,122)+1);
		$i=$i+1;
	}
	$password=str_replace("'","1",$password);
	$password=str_replace("`","2",$password);
	$password=str_replace("\\","3",$password);
	$password=str_replace("\"","4",$password);
	$password=str_replace("&","5",$password);
	$password=str_replace("<","6",$password);
	$password=str_replace(">","7",$password);
	return $password;
}
/////////////////////////////////////////////////////////////////////////
function sc_is_valid_email($field) {
	$pattern ="/^([a-zA-Z0-9])+([\.a-zA-Z0-9_-])*@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-]+)+/";
	if(preg_match($pattern, $field)) return false;
	return true;
}
/////////////////////////////////////////////////////////////////////////
function sc_is_valid_name($field) {
	$pattern ="/^([a-zA-Z0-9])+/";
	if(preg_match($pattern, $field)) return false;
	return false;
}
/////////////////////////////////////////////////////////////////////////
function sc_trunc($str,$max_len) {
	if(strlen($str) > $max_len ) {
		$str = substr(trim($str),0,$max_len);
		$str = $str.'...';
	}
	return $str;
}


function sc_countries()
{
?>
<option>United States
<option>Afghanistan
<option>Albania
<option>Algeria
<option>Andorra
<option>Angola
<option>Antigua and Barbuda
<option>Argentina
<option>Armenia
<option>Australia
<option>Austria
<option>Azerbaijan
<option>Bahamas
<option>Bahrain
<option>Bangladesh
<option>Barbados
<option>Belarus
<option>Belgium
<option>Belize
<option>Benin
<option>Bhutan
<option>Bolivia
<option>Bosnia and Herzgovina
<option>Botswana
<option>Brazil
<option>Brunei
<option>Bulgaria
<option>Burkina Faso
<option>Burundi
<option>Cambodia
<option>Cameroon
<option>Canada
<option>Cape Verde
<option>Central African Republic
<option>Chad
<option>Chile
<option>China
<option>Columbia
<option>Comoros
<option>Congo (Brazzaville)
<option>Congo, Democratic Republic
<option>Costa Rica
<option>Croatia
<option>Cuba
<option>Cyprus
<option>Czech Republic
<option>Cote d'lvoire
<option>Denmark
<option>Djibouti
<option>Dominica
<option>Dominican Republic
<option>East Timor (Timor Timur)
<option>Ecuador
<option>Egypt
<option>El Salvador
<option>Equatorial Guinea
<option>Eritrea
<option>Ethiopia
<option>Fiji
<option>Finland
<option>France
<option>Gabon
<option>Gambia
<option>Georgia
<option>Germany
<option>Ghana
<option>Greece
<option>Grenada
<option>Guatemala
<option>Guinea
<option>Guinea-Bissau
<option>Guyana
<option>Haiti
<option>Honduras
<option>Hungary
<option>Iceland
<option>India
<option>Indonesia
<option>Iran
<option>Iraq
<option>Ireland
<option>Israel
<option>Italy
<option>Jamaica
<option>Japan
<option>Jordan
<option>Kazakhstan
<option>Kenya
<option>Kiribati
<option>Korea, Evil
<option>Korea, South
<option>Kuwait
<option>Kyrgyzstan
<option>Laos
<option>Latvia
<option>Lebanon
<option>Lesotho
<option>Liberia
<option>Libya
<option>Liechtenstein
<option>Lithuania
<option>Luxembourg
<option>Macedonia, Former Yugoslav Republic
<option>Madagasgar
<option>Malawi
<option>Malaysia
<option>Maldives
<option>Mali
<option>Malta
<option>Marshall Islands
<option>Mauritania
<option>Mauritius
<option>Mexico
<option>Micronesia, Federated States of
<option>Moldova
<option>Monaco
<option>Mongolia
<option>Morocco
<option>Mozambique
<option>Myanmar
<option>Nambia
<option>Nauru
<option>Nepal
<option>Netherlands
<option>New Zealand
<option>Nicaragua
<option>Niger
<option>Nigeria
<option>Norway
<option>Oman
<option>Pakistan
<option>Palau
<option>Panama
<option>Papua New Guinea
<option>Paraguay
<option>Peru
<option>Phillipines
<option>Poland
<option>Portugal
<option>Qatar
<option>Romania
<option>Russia
<option>Rwanda
<option>Saint Kitts and Nevis
<option>Saint Lucia
<option>Saint Vincent and The Grenadines
<option>Samoa
<option>San Marino
<option>Sao Tome and Principe
<option>Saudia Arabia
<option>Senegal
<option>Serbia and Montenegro
<option>Seychelles
<option>Sierra Leone
<option>Singapore
<option>Slovakia
<option>Slovenia
<option>Solomon Islands
<option>Somalia
<option>South Africa
<option>Spain
<option>Sri Lanka
<option>Sudan
<option>Suriname
<option>Swaziland
<option>Sweden
<option>Switzerland
<option>Syria
<option>Taiwan
<option>Tajikistan
<option>Tanzania
<option>Thailand
<option>Togo
<option>Tonga
<option>Trinidad and Tobago
<option>Tunisia
<option>Turkey
<option>Turkmenistan
<option>Tuvalu
<option>Uganda
<option>Ukraine
<option>United Arab Emirates
<option>United Kingdom
<option>United States
<option>Uruguay
<option>Uzbekistan
<option>Vanuatu
<option>Vatican City
<option>Venezuela
<option>Vietnam
<option>Western Sahara
<option>Yemen
<option>Zambia
<option>Zimbabwe
<?
}

// this file can not have trailing spaces
?>
