<?
$meme_thumbwidth=200;
$meme_editwidth=256;
$meme_fullsize=512;

if($_REQUEST['a']=="ms") {
	$mid=$_REQUEST['mid'];
	echo "<img src=\"$RFS_SITE_URL/include/generate.image.php/?download_it_$mid.png&mid=$mid&owidth=$meme_fullsize\" border=0></a>";
    exit();
}

chdir("../../");
include("header.php");

/////////////////////////////////////////////////////////////////////////////////
// New meme
function memes_action_new_meme() { eval(scg());
	echo "<p>Select a file to use for the caption.</p>\n";
	echo "<form  enctype=\"multipart/form-data\" action=\"$RFS_SITE_URL/modules/memes/memes.php\" method=\"post\">\n";
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
function memes_action_new_meme_go() { eval(scg());
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
		sc_query("INSERT INTO `pictures` (`name`) VALUES('$name');");
		$cid=mysql_fetch_object(sc_query("select * from categories where name = '$category'"));
		sc_query("update `pictures` set `category`='$cid->id'  where `name`='$name'");
		sc_query("update `pictures` set `sname`='$sname'        where `name`='$name'");
		sc_query("update `pictures` set `sfw`='$sfw'            where `name`='$name'");
		sc_query("update `pictures` set `hidden`='$hidden'      where `name`='$name'");
		sc_query("update `pictures` set description='$desc' where name='$name'");
		sc_query("update `pictures` set poster='$poster' where name='$name'");
		$furl=addslashes($furl);
		sc_query("update `pictures` set url = '$furl' where name='$name'");
		sc_query("update `pictures` set time = '$time1' where name='$name'");
		$error.= " ---- Added $name to database ---- ";
		
		$p=mfo1("select * from pictures where sname='$sname'");
		$id=$p->id;
		$basepic=$id;
		$action="memegenerate";
		$mid="";
		$private=$hidden;
		sc_info("Status: [$error]","WHITE","GREEN");	
		memes_action_memegenerate();
	}
	else{
		$error ="File upload error!";
		echo "File upload error! [".$_FILES['userfile']['name']."][".$_FILES['userfile']['error']."][".$_FILES['userfile']['tmp_name']."][".$uploadFile."]\n";
	}
	
	if(!$error){
		$error .= "No files have been selected for upload";
	}
	sc_info("Status: [$error]","WHITE","GREEN");	
	include("footer.php");
}
/////////////////////////////////////////////////////////////////////////////////
// MEME delete confirm
function memes_action_meme_delete() { eval(scg());
	$donotshowcats=true;
	if(sc_access_check("memes","delete")){
		$dd="<form action=$RFS_SITE_URL/modules/memes/memes.php method=post>Confirm delete meme:
		<input type=submit name=memedelete value=Delete>
		<input type=hidden name=action value=meme_delete_go>
		<input type=hidden name=mid value=$mid>
		</form>";	
		sc_info($dd,"black","red");	
		$t=$m->name."-".time();// /$t.png
		echo "<a href='$RFS_SITE_URL/include/generate.image.php/$t.png?mid=$m->id&owidth=$meme_fullsize' target=_blank>
		<img src='$RFS_SITE_URL/include/generate.image.php/$t.png?mid=$mid&owidth=256' border=0></a>";
	}
	else {
		echo "<p>You can not delete memes.</p>";
	}
}
function memes_action_meme_delete_go() { eval(scg());
	if(sc_access_check("memes","delete")) {
		sc_query("delete from meme where id='$mid' limit 1");
	}
}
/////////////////////////////////////////////////////////////////////////////////
// MEME save
function memes_action_meme_save() { eval(scg());
    sc_query("update meme set status='SAVED' where id='$mid'");
    sc_info("SAVED!","WHITE","RED");
	 memes_action_showmemes();    
}
/////////////////////////////////////////////////////////////////////////////////
// MEME generate
function memes_action_memegenerate() { eval(scg());
    	
	$name 		= addslashes($name);
	$texttop 	= addslashes($_REQUEST['texttop']);
	$textbottom = addslashes($_REQUEST['textbottom']);	
    
	$poster=999;
    if($data->id) $poster=$data->id;
    if(empty($private)) $private="no";
	
	if($mid==0) {		
        $infoout="Adding new caption";
        if(empty($texttop)) $texttop="_NEW";
			echo " POSTER [$poster]<br>";
			echo "PICTURE [$basepic] <br>";
			$q="insert into meme
				  ( `name`,`poster`, `basepic`,`texttop`,`status`)
			VALUES('$name','$poster', '$basepic',  '$texttop', 'EDIT');";
        sc_query($q);
        $GLOBALS['mid']=mysql_insert_id();
       } else {
		$infoout="Updating caption $mid";
		
		sc_query("update meme set `name`  			= '$name'   	     where id='$mid'");
		sc_query("update meme set `poster`   	 	= '$poster'     	 where id='$mid'");
		sc_query("update meme set `texttop`     	= '$texttop'    	 where id='$mid'");
		sc_query("update meme set `textbottom`  	= '$textbottom' 	 where id='$mid'");
		sc_query("update meme set `font`	        = '$chgfont'       where id='$mid'");
		sc_query("update meme set `text_color`		= '$text_color'    where id='$mid'");
		sc_query("update meme set `text_bg_color`	= '$text_bg_color' where id='$mid'");
		sc_query("update meme set `text_size`		= '$text_size'     where id='$mid'");
		sc_query("update meme set `private`		    = '$private'       where id='$mid'");
		sc_query("update meme set `datborder`		= '$datborder'   	  where id='$mid'");
	}	
    $meme=mfo1("select * from meme where id='$mid'");
    $data=sc_getuserdata($poster);
    // $action="memeedit";
    $basepic=$meme->basepic;
	sc_info($infoout." >> $meme->id ($mid) $meme->name >> $meme->texttop >> $meme->textbottom",	"WHITE","GREEN");
	memes_action_memeedit();
}
/////////////////////////////////////////////////////////////////////////////////
// MEME editor
function memes_action_memeedit() { eval(scg()); 
	if(empty($mid)) $mid=$id;
	sc_info("Editing $name caption #$mid","BLACK","#ff9900");
	$m=mfo1("select * from meme where id='$mid'");
	$pic=mfo1("select * from pictures where id='$m->basepic'");	
    $p=$data->id;
    if(empty($p)) $p=999;
	if( ($m->poster==$p) || ($data->access==255) ) {
		if($m->poster!=$p)
            sc_info("NOT YOURS ADMIN! / EDIT ANYWAY (LOL)","WHITE","RED");
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

		sc_bqf( "action=memegenerate".$RFS_SITE_DELIMITER.
				 "id=$pic->id".$RFS_SITE_DELIMITER.
				 "mid=$m->id".$RFS_SITE_DELIMITER.
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
		echo " <a href='$RFS_SITE_URL/include/generate.image.php/$t.png?mid=$m->id&owidth=$meme_fullsize&forcerender=1' target=_blank>
				<img src='$RFS_SITE_URL/include/generate.image.php/$t.png?
				mid=$m->id&
				owidth=$meme_editwidth&
				forcerender=1'
				border=0>
				</a>";
				
		echo "</p>";

		echo "</td><td width=80% valign=top>";
        
		/*sc_info("<BR>Planning following features<br>
					TODO: Add upload ttf font.<br>
					TODO: Add text color pickers <br>
					TODO: Add border color picker<br>
					TODO: Add TRUE FALSE OPTIONIZER<br>
					&nbsp;","WHITE","RED");
                    */


		echo "<a href='$RFS_SITE_URL/modules/memes/memes.php?action=meme_save&mid=$m->id&showfonts=true'>";
		sc_image_text("SAVE THIS MEME","HoW%20tO%20dO%20SoMeThInG.ttf",28,812,74,0,0,150,150,0,0,0,0,1,1);
		echo "</a><BR>";

		echo "<a href='$RFS_SITE_URL/modules/memes/memes.php?action=memeedit&mid=$m->id&showfonts=true'>";
		$wf=str_replace("fonts/","",$m->font);

		sc_image_text(	"Change Font ($wf)","HoW%20tO%20dO%20SoMeThInG.ttf",28,812,74,0,0,10,145,148,1,1,0,1,1);
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
		

						echo "<a href='$RFS_SITE_URL/modules/memes/memes.php?action=memegenerate&chgfont=$file&mid=$m->id'>
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
function memes_action_muv() { eval(scg()); 
    $muv="MUV$mid";    
    $action="showmemes";
    if(!$_SESSION[$muv]){        
        $m=mfo1("select * from meme where id='$mid'");
        $m->rating+=1;
        sc_query("update meme set rating='$m->rating' where id='$mid'");
        $_SESSION[$muv]=true;
    } else {
        sc_info("Multiple upvoting is not allowed.","white","red");
    }
	memes_action_showmemes();
}
/////////////////////////////////////////////////////////////////////////////////
// MEME vote down
function memes_action_mdv() { eval(scg());
    $mdv="MDV$mid";
    $action="showmemes";
	if(!$_SESSION[$mdv]){
        $m=mfo1("select * from meme where id='$mid'");
        $m->rating-=1;
        sc_query("update meme set rating='$m->rating' where id='$mid'");
        $_SESSION[$mdv]=true;
    }
    else {
        sc_info("Multiple downvoting is not allowed.","white","red");
    }
	memes_action_showmemes();
}
/////////////////////////////////////////////////////////////////////////////////
// MEME show memes
function memes_action_showmemes(){ eval(scg());
	echo "<h1>Meme generator</h1>";
	$mcols=5; $mrows=6;
	$toget=$mcols*$mrows;
    sc_query("delete FROM meme WHERE TIMESTAMPDIFF(MINUTE,`time`,NOW()) > 5 and status = 'EDIT'");    
	$donotshowcats=true;
	$rz=sc_query("select * from meme where `private`!='yes' and `status` = 'SAVED'"); $mtotal=mysql_num_rows($rz);
	if(empty($mtop)) $mtop=0;
	if(empty($mbot)) $mbot=$toget;
	if( $mtop > 0 ) {
		$tmtop=$mtop-$mbot;
		sc_button("$RFS_SITE_URL/modules/memes/memes.php?action=showmemes&mtop=$tmtop&mbot=$mbot&onlyshow=$onlyshow","PREVIOUS PAGE");
	} 
	if(!empty($onlyshow)) {
			sc_button("$RFS_SITE_URL/modules/memes/memes.php?action=showmemes&mtop=$mtop&mbot=$mbot&onlyshow=","Show All Captions");
		
	}
	if( ($mbot+$mtop) < $mtotal) {
		$mtop+=$mbot;
		sc_button("$RFS_SITE_URL/modules/memes/memes.php?action=showmemes&mtop=$mtop&mbot=$mbot&onlyshow=$onlyshow","NEXT PAGE");
	}
	$q="select * from meme  ";
	$q.=" where ";
	if(!empty($onlyshow))
		$q.=" `name`='$onlyshow' and";
	$q.=" `private`<>'yes' and `status` = 'SAVED'";
	$q.=" order by rating desc limit $mtop,$mbot ";
	$r=sc_query($q);
	$n=mysql_num_rows($r); 
	for($i=0;$i<$n;$i++){
		$m=mysql_fetch_object($r);
		echo "<div id=$m->id style=\"float: left;\">";
		sc_show1meme($m->id);
		echo "</div>";
	}
	echo "<br style='clear: both;'>";
	include("footer.php");
	exit();
}
/////////////////////////////////////////////////////////////////////////////////
// MEME default action
function memes_action_() { eval(scg());
	memes_action_showmemes();
}

?>
