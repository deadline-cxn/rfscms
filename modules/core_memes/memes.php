<?
$meme_thumbwidth=200;
$meme_editwidth=256;
$meme_fullsize=512;

if(!empty($_REQUEST['a']))
if($_REQUEST['a']=="ms") {
	$meme_id=$_REQUEST['meme_id'];
	echo "<img src=\"$RFS_SITE_URL/include/generate.image.php/?download_it_$meme_id.png&meme_id=$meme_id&owidth=$meme_fullsize\" border=0></a>";
    exit();
}

chdir("../../");
include("header.php");

/////////////////////////////////////////////////////////////////////////////////
// New meme
function memes_action_new_meme() { eval(lib_rfs_get_globals());
	echo "<p>Select a file to use for the caption.</p>\n";
	echo "<form  enctype=\"multipart/form-data\" action=\"$RFS_SITE_URL/modules/core_memes/memes.php\" method=\"post\">\n";
	echo "<table border=0>\n";
	echo "<input type=hidden name=action value=new_meme_go>\n";
	echo "<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"93000000\">";
	echo "<tr><td align=right>Select file:      </td><td ><input name=\"userfile\" type=\"file\" size=80> </td></tr>\n";
	echo "<tr><td align=right>Safe for work:    </td><td><select name=sfw><option>yes<option>no</select></td></tr>\n";
	echo "<tr><td align=right>Hide from public: </td><td><select name=hidden><option>no<option>yes</select></td></tr>";
	echo "<input type=hidden name=category value=Meme>";
	echo "<input type=hidden name=hidden value=no>";
	echo "<tr><td align=right>Short name :</td><td><input type=textbox name=sname value=\"$name\"></td></tr>\n";
	echo "<tr><td align=right valign=top>Description:</td><td><textarea name=\"desc\" rows=\"7\" cols=\"40\"></textarea></td></tr>\n";
	echo "<tr><td>&nbsp;</td><td><input type=\"submit\" name=\"submit\" value=\"Upload!\"></td></tr>\n";
	echo "</table>\n";
	echo "</form>\n";
	include("footer.php");
}
function memes_action_new_meme_go() { eval(lib_rfs_get_globals());
	echo "Uploading meme picture...\n";
	$furl="files/pictures/".$_FILES['userfile']['name'];
	$furl =str_replace("//","/",$furl);
	if(move_uploaded_file($_FILES['userfile']['tmp_name'], $furl))            {
		$error="File is valid, and was successfully uploaded. ";
		$error.="It was stored as [$furl]\n";
		$xp_ext = explode(".",$_FILES['userfile']['name'],40);
		$j = count ($xp_ext)-1;
		$ext = "$xp_ext[$j]";
		$filetype=strtolower($ext);
		$filesizebytes=$_FILES['userfile']['size'];
		$time1=date("Y-m-d H:i:s");
		$description=addslashes($description);
		if(empty($name)) $name=$sname;
		$poster=999;
		if($data->id)$poster=$data->id;
		lib_mysql_query("INSERT INTO `pictures` (`name`) VALUES('$name');");
		$id=$_GLOBALS['mysqli_id'];
		//$cid=$r->fetch_object(lib_mysql_query("select * from categories where name = '$category'"));
		lib_mysql_query("update `pictures` set `category`='$category'  where `id`='$id'");
		lib_mysql_query("update `pictures` set `sname`='$sname'        where `id`='$id'");
		lib_mysql_query("update `pictures` set `sfw`='$sfw'            where `id`='$id'");
		lib_mysql_query("update `pictures` set `hidden`='$hidden'      where `id`='$id'");
		lib_mysql_query("update `pictures` set description='$desc'		where `id`='$id'");
		lib_mysql_query("update `pictures` set poster='$poster'			where `id`='$id'");
		$furl=addslashes($furl);
		lib_mysql_query("update `pictures` set url = '$furl' where `id`='$id'");
		lib_mysql_query("update `pictures` set time = '$time1' where `id`='$id'");
		$error.= " ---- Added $name to database ---- ";
		global $basepic;
		$basepic=$id;
		$error.=" $basepic";
		$action="memegenerate";
		$meme_id="";
		$private=$hidden;
		lib_forms_info("Status: [$error]","WHITE","GREEN");	
		memes_action_memegenerate();
	}
	else{
		$error ="File upload error!";
		echo "File upload error! [".$_FILES['userfile']['name']."][".$_FILES['userfile']['error']."][".$_FILES['userfile']['tmp_name']."][".$uploadFile."]\n";
	}
	
	if(!$error){
		$error .= "No files have been selected for upload";
	}
	lib_forms_info("Status: [$error]","WHITE","GREEN");	
	include("footer.php");
}
/////////////////////////////////////////////////////////////////////////////////
// MEME delete confirm
function memes_action_meme_delete() { eval(lib_rfs_get_globals());
	$donotshowcats=true;
	if(lib_access_check("memes","delete")){
		$dd="<form action=$RFS_SITE_URL/modules/core_memes/memes.php method=post>Confirm delete meme:
		<input type=submit name=memedelete value=Delete>
		<input type=hidden name=action value=meme_delete_go>
		<input type=hidden name=meme_id value=$meme_id>
		</form>";	
		lib_forms_info($dd,"black","red");	
		$t=$m->name."-".time();// /$t.png
		echo "<a href='$RFS_SITE_URL/include/generate.image.php/$t.png?meme_id=$m->id&owidth=$meme_fullsize' target=_blank>
		<img src='$RFS_SITE_URL/include/generate.image.php/$t.png?meme_id=$meme_id&owidth=256' border=0></a>";
	}
	else {
		echo "<p>You can not delete memes.</p>";
	}
}
function memes_action_meme_delete_go() { eval(lib_rfs_get_globals());
	if(lib_access_check("memes","delete")) {
		lib_mysql_query("delete from meme where id='$meme_id' limit 1");
	}
	memes_action_showmemes();
}
/////////////////////////////////////////////////////////////////////////////////
// MEME save
function memes_action_meme_save() { eval(lib_rfs_get_globals());
    lib_mysql_query("update meme set status='SAVED' where id='$meme_id'");
    lib_forms_info("SAVED!","WHITE","GREEN");
	 memes_action_showmemes();    
}
/////////////////////////////////////////////////////////////////////////////////
// MEME generate
function memes_action_memegenerate() {
    eval(lib_rfs_get_globals());
    global $mysql_id; 
    global $basepic;
    	
	$name 		= addslashes($name);
	$texttop 	= addslashes($_REQUEST['texttop']);
	$textbottom = addslashes($_REQUEST['textbottom']);	
    
	$poster=999;
    if($data->id) $poster=$data->id;
    if(empty($private)) $private="no";
	
	if($meme_id==0) {
        $infoout="Adding new caption";
        if(empty($texttop)) $texttop="_NEW";
			echo " POSTER [$poster]<br>";
			echo "PICTURE [$basepic] <br>";
			$q="insert into meme
				  ( `name`,`poster`, `basepic`,`texttop`,`status`)
			VALUES('$name','$poster', '$basepic',  '$texttop', 'EDIT');";
            
        lib_mysql_query($q);
        $meme_id=$mysqli_id;
        
        echo "MEME_ID[$meme_id] MYSQL_ID[$mysql_id] ";
       }
	else {
		$infoout="Updating caption $meme_id";
		if(!empty($_REQUEST['name']))
		lib_mysql_query("update meme set `name`  			= '$name'   	     where id='$meme_id'");
		if(!empty($_REQUEST['poster']))
		lib_mysql_query("update meme set `poster`   	 	= '$poster'     	 where id='$meme_id'");
		if(!empty($_REQUEST['texttop']))
		lib_mysql_query("update meme set `texttop`     	= '$texttop'    	 where id='$meme_id'");
		if(!empty($_REQUEST['textbottom']))
		lib_mysql_query("update meme set `textbottom`  	= '$textbottom' 	 where id='$meme_id'");
		if(!empty($_REQUEST['chgfont']))
		lib_mysql_query("update meme set `font`	       = '$chgfont'       where id='$meme_id'");
		if(!empty($_REQUEST['text_color']))
		lib_mysql_query("update meme set `text_color`		= '$text_color'    where id='$meme_id'");
		if(!empty($_REQUEST['text_bg_color']))
		lib_mysql_query("update meme set `text_bg_color`	= '$text_bg_color' where id='$meme_id'");
		if(!empty($_REQUEST['text_size']))
		lib_mysql_query("update meme set `text_size`		= '$text_size'     where id='$meme_id'");
		if(!empty($_REQUEST['private']))
		lib_mysql_query("update meme set `private`		= '$private'       where id='$meme_id'");
		if(!empty($_REQUEST['datborder']))
		lib_mysql_query("update meme set `datborder`		= '$datborder'   	  where id='$meme_id'");
	}	
    $meme=lib_mysql_fetch_one_object("select * from meme where id='$meme_id'");
    $data=lib_users_get_data($poster);	
    $basepic=$meme->basepic;
	lib_forms_info($infoout." >> $meme->id ($meme_id) $meme->name >> $meme->texttop >> $meme->textbottom",	"WHITE","GREEN");
	memes_action_memeedit();
}
/////////////////////////////////////////////////////////////////////////////////
// MEME editor
function memes_action_memeedit() {
    eval(lib_rfs_get_globals()); 
	if(empty($meme_id)) $meme_id=$id;
	lib_forms_info("Editing $name caption #$meme_id","BLACK","#ff9900");
	
    $m=lib_mysql_fetch_one_object("select * from meme where id='$meme_id'");
	$pic=lib_mysql_fetch_one_object("select * from pictures where id='$m->basepic'");
    
    echo "$m->id  $pic->id";
    	
    $p=$data->id;
    if(empty($p)) $p=999;
    
	if( ($m->poster==$p) ||
        lib_access_check( "memes","edit_others") ) {
            
        if($m->poster!=$p) lib_forms_info("NOT YOURS ADMIN! / EDIT ANYWAY (LOL)","WHITE","RED");
            
		if(empty($name)) $name=$m->name;
		if(empty($name)) $nout="SHOW_TEXT_10#20#name=$name".$RFS_SITE_DELIMITER;
		else  {
			$nout="name=$name".$RFS_SITE_DELIMITER;
		}
        
		echo "<table border=0 cellspacing=0 cellpadding=0><tr><td valign=top>";
		$ofont="fonts/impact.ttf";
		$ocolor="white";
		$text_bg_color="black";
		if(!empty($m->font)) $ofont=$m->font;
		if(!empty($m->text_color)) $ocolor=$m->text_color;
		if(!empty($m->text_bg_color)) $text_bg_color=$m->text_bg_color;
		if(empty($private)) $private="no";

		lib_forms_build_quick( "action=memegenerate".$RFS_SITE_DELIMITER.
				 "id=$pic->id".$RFS_SITE_DELIMITER.
				 "meme_id=$m->id".$RFS_SITE_DELIMITER.
				 "chgfont=$m->font".$RFS_SITE_DELIMITER.
				 $nout.
				 "SHOW_SELECTOR_colors#name#text_color#$ocolor".$RFS_SITE_DELIMITER.
				 "SHOW_SELECTOR_colors#name#text_bg_color#$text_bg_color".$RFS_SITE_DELIMITER.
				 "SHOW_TEXT_10#20#datborder=$m->datborder".$RFS_SITE_DELIMITER.
				 "SHOW_TEXT_10#20#private=$private".$RFS_SITE_DELIMITER.
				 "SHOW_TEXT_10#20#text_size=$m->text_size".$RFS_SITE_DELIMITER.
				 "SHOW_TEXT_10#20#texttop=$m->texttop".$RFS_SITE_DELIMITER.
				 "SHOW_TEXT_10#20#textbottom=$m->textbottom",
				 "Go" );
		echo "<p>";
        
		
		$t=$m->name."-".time();
        			
		echo " <a href='$RFS_SITE_URL/include/generate.image.php/$t.png?meme_id=$m->id&owidth=$meme_fullsize&forcerender=1' target=_blank>
				<img src='$RFS_SITE_URL/include/generate.image.php/$t.png?
				meme_id=$m->id&
				owidth=$meme_editwidth&
				forcerender=1'
				border=0>
				</a>";
				
		echo "</p>";

		echo "</td><td width=80% valign=top>";
        
		/*lib_forms_info("<BR>Planning following features<br>
					TODO: Add upload ttf font.<br>
					TODO: Add text color pickers <br>
					TODO: Add border color picker<br>
					TODO: Add TRUE FALSE OPTIONIZER<br>
					&nbsp;","WHITE","RED");
                    */


		echo "<a href='$RFS_SITE_URL/modules/core_memes/memes.php?action=meme_save&meme_id=$m->id&showfonts=true'>";
		lib_images_text("SAVE THIS MEME","HoW%20tO%20dO%20SoMeThInG.ttf",28,812,74,0,0,150,150,0,0,0,0,1,1);
		echo "</a><BR>";

		echo "<a href='$RFS_SITE_URL/modules/core_memes/memes.php?action=memeedit&meme_id=$m->id&showfonts=true'>";
		$wf=str_replace("fonts/","",$m->font);

		lib_images_text(	"Change Font ($wf)","HoW%20tO%20dO%20SoMeThInG.ttf",28,812,74,0,0,10,145,148,1,1,0,1,1);
		echo "</a><BR>";


        $rr=100;
		if($showfonts) {
			$dir_count=0; $dirfiles = array();
			$handle=opendir("$RFS_SITE_PATH/files/fonts") or die("Unable to open filepath");
			while (false!==($file = readdir($handle))) array_push($dirfiles,$file);
			closedir($handle); reset($dirfiles); asort($dirfiles);
			while(list ($key, $file) = each ($dirfiles)){
				if($file!=".") if($file!="..")
					if(!is_dir($dir."/".$file)){
						$t=$m->name."-".time();
						$text_size=12;

						$rr+=15; $rg+=32; $rb+=8; 
						if($rr>255) $rr=100;
						if($rg>255) $rg=0;
						if($rb>255) $rb=0;
		

	echo "<a href='$RFS_SITE_URL/modules/core_memes/memes.php?action=memegenerate&chgfont=$file&meme_id=$m->id'>
							<img src='$RFS_SITE_URL/include/generate.image.php/$t.png?action=showfont&font=$file&text_size=16&forcerender=1&oheight=120&forceheight=1&icr=$rr&icg=$rg&icb=$rb' border=0></a>";
				}
			}
		}
		echo "</tr></table>";
	}
	else{
		echo "<p>This is not your caption.</p>";		
	}
	
	include("footer.php");
}
/////////////////////////////////////////////////////////////////////////////////
// MEME vote up
function memes_action_muv() { eval(lib_rfs_get_globals()); 
    $muv="MUV$meme_id";    
    $action="showmemes";
    if(!$_SESSION[$muv]){        
        $m=lib_mysql_fetch_one_object("select * from meme where id='$meme_id'");
        $m->rating+=1;
        lib_mysql_query("update meme set rating='$m->rating' where id='$meme_id'");
        $_SESSION[$muv]=true;
    } else {
        lib_forms_info("Multiple upvoting is not allowed.","white","red");
    }
	memes_action_showmemes();
}
/////////////////////////////////////////////////////////////////////////////////
// MEME vote down
function memes_action_mdv() { eval(lib_rfs_get_globals());
    $mdv="MDV$meme_id";
    $action="showmemes";
	if(!$_SESSION[$mdv]){
        $m=lib_mysql_fetch_one_object("select * from meme where id='$meme_id'");
        $m->rating-=1;
        lib_mysql_query("update meme set rating='$m->rating' where id='$meme_id'");
        $_SESSION[$mdv]=true;
    }
    else {
        lib_forms_info("Multiple downvoting is not allowed.","white","red");
    }
	memes_action_showmemes();
}
/////////////////////////////////////////////////////////////////////////////////
// MEME show memes
function memes_action_showmemes(){ eval(lib_rfs_get_globals());
	echo "<h1>Meme generator</h1>";
	$mcols=5; $mrows=5;
	$toget=$mcols*$mrows;
	if(empty($mtop)) $mtop=0;
	if(empty($mbot)) $mbot=$toget;
	
    lib_mysql_query("delete FROM meme WHERE TIMESTAMPDIFF(MINUTE,`time`,NOW()) > 5 and status = 'EDIT'");    
	$donotshowcats=true;
	
	$q="select * from meme  ";
	$q.=" where ";
	if(!empty($onlyshow))
		$q.=" `name`='$onlyshow' and";
	$q.=" `private`<>'yes' and `status` = 'SAVED'";
	$q.=" order by rating desc ";
	$ql=" limit $mtop,$mbot ";
	$r=lib_mysql_query($q.$ql);
	$n=$r->num_rows;
	if( $mtop > 0 ) {
		$tmtop=$mtop-$mbot;
		lib_buttons_make_button("$RFS_SITE_URL/modules/core_memes/memes.php?action=showmemes&mtop=$tmtop&mbot=$mbot&onlyshow=$onlyshow","PREVIOUS PAGE");
	} 
	if(!empty($onlyshow)) {
		lib_buttons_make_button("$RFS_SITE_URL/modules/core_memes/memes.php?action=showmemes&mtop=$mtop&mbot=$mbot&onlyshow=","Show All Captions");
	}
	$ql=" limit ".($mtop+$mbot+$toget)." ;";
	$rrr=lib_mysql_query($q.$ql);
	$nnn=$rrr->num_rows;
	//echo "<hr> mtop+mbot+toget [".($mtop+$mbot+$toget)."] mbot+mtop [".($mbot+$mtop)."] nnn[".$nnn."] <BR>";
	if( ($mbot+$mtop) < $nnn) {
		$mtop+=$mbot;
		lib_buttons_make_button("$RFS_SITE_URL/modules/core_memes/memes.php?action=showmemes&mtop=$mtop&mbot=$mbot&onlyshow=$onlyshow","NEXT PAGE");
	}
	
	echo "<hr>";
	for($i=0;$i<$n;$i++){
		$m=$r->fetch_object();
		echo "<div id=$m->id style=\"float: left;\">";
		rfs_show1meme($m->id);
		echo "</div>";
	}
	echo "<br style='clear: both;'>";
	include("footer.php");
	exit();
}
/////////////////////////////////////////////////////////////////////////////////
// MEME default action
function memes_action_() { eval(lib_rfs_get_globals());
	memes_action_showmemes();
}

?>
