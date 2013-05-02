<?

$meme_thumbwidth=200;
$meme_editwidth=256;
$meme_fullsize=400;


if($_REQUEST['a']=="ms") {
	$mid=$_REQUEST['mid'];
	echo "<img src=\"$RFS_SITE_URL/include/generate.image.php/?download_it_$mid.png&mid=$mid&owidth=$meme_fullsize\" border=0></a>";
    exit();
}

chdir("../../");
include("header.php");

//echo "<table border=0><tr>"; 
//echo "</tr></table>"; 

function memes_action_new_meme() { eval(scg());
// if($action=="uploadpic"){
		if($memeit=="yes") {
			$donotshowcats=true;
			echo "<p>Select a file to use for the caption.</p>\n";
		}
		else{
			echo "<p>Upload a picture</p>\n";
		}
        echo "<table border=0>\n";
        echo "<form  enctype=\"multipart/form-data\" action=\"$RFS_SITE_URL/modules/memes/memes.php\" method=\"post\">\n";
        echo "<input type=hidden name=action value=uploadpicgo>\n";
		echo "<input type=hidden name=memeit value=$memeit>\n";
		
        echo "<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"93000000\">";
        echo "<tr><td align=right>Select file:      </td><td ><input name=\"userfile\" type=\"file\" size=80> </td></tr>\n";
       
        echo "<tr><td align=right>Safe for work:    </td><td><select name=sfw><option>yes<option>no</select></td></tr>\n";
       
	   echo "
	   <tr>
	   <td align=right>Hide from public: </td>
	   <td><select name=hidden><option>no<option>yes</select> </td>
	   </tr>
	   ";
	if($memeit=="yes") {
		
		echo "<input type=hidden name=category value=Meme>";
		echo "<input type=hidden name=hidden value=no>";
	}
	else {
		 
		echo "<tr><td align=right>Gallery:         </td><td><select name=category>\n";
        $result=sc_query("select * from categories order by name asc"); $numcats=mysql_num_rows($result);
        for($i=0;$i<$numcats;$i++) { $cat=mysql_fetch_object($result); echo "<option>$cat->name"; }
        echo "</select></td></tr>\n";		
	}
		
        echo "<tr><td align=right>Short name :</td><td><input type=textbox name=sname value=\"$name\"></td></tr>\n";
        echo "<tr><td align=right valign=top>Description:</td><td><textarea name=\"desc\" rows=\"7\" cols=\"40\"></textarea></td></tr>\n";
        echo "<tr><td>&nbsp;</td><td><input type=\"submit\" name=\"submit\" value=\"Upload!\"></td></tr>\n";
        echo "</form>\n";
        echo "</table>\n";
    }
/////////////////////////////////////////////////////////////////////////////////
// Upload picture confirm
    if($action=="uploadpicgo"){
            echo "Uploading picture...\n";
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
				if(!empty($memeit)) {
					$p=mfo1("select * from pictures where sname='$sname'");
					$id=$p->id;
					$basepic=$id;
					$action="memegenerate";
					$mid="";
					$private=$hidden;
				}
            }
            else{
                $error ="File upload error!";
                echo "File upload error! [\n";
                echo $_FILES['userfile']['name'];
                echo "][";
                echo $_FILES['userfile']['error'];
                echo "][";
                echo $_FILES['userfile']['tmp_name'] ;
                echo "][";
                echo $uploadFile;
                echo "]\n";
            }
            if(!$error){
                $error .= "No files have been selected for upload";
            }
            sc_info("Status: [$error]","WHITE","GREEN");
           // echo "<p>[<a href=$RFS_SITE_URL/files.php?action=upload>Add another file</a>]\n";

    }


/////////////////////////////////////////////////////////////////////////////////
// MEME delete confirm
if( ($action=="memedeletego") ) {
	if($data->access==255){
		sc_query("delete from meme where id='$mid' limit 1");
	}	
	$action="showmemes";
}
/////////////////////////////////////////////////////////////////////////////////
// MEME use old
if($action=="memedelete") {
	$donotshowcats=true;
	if($data->access==255){
	$dd="<form action=$RFS_SITE_URL/modules/memes/memes.php method=post>Confirm delete meme:
	<input type=submit name=memedelete value=Delete>
	<input type=hidden name=action value=memedeletego>
	<input type=hidden name=mid value=$mid>
	</form>";	
	sc_info($dd,"black","red");	
	$t=$m->name."-".time();// /$t.png
	echo "<a href='$RFS_SITE_URL/include/generate.image.php/$t.png?mid=$m->id&owidth=$meme_fullsize' target=_blank>
	<img src='$RFS_SITE_URL/include/generate.image.php/$t.png?mid=$mid&owidth=256' border=0></a>";
	}
}


/////////////////////////////////////////////////////////////////////////////////
// MEME use old confirm
if($action=="memeuseoldgo") {
		$m=mfo1("select * from meme where id='$name'");
		$id=$m->basepic;
		$name="";
		$action="meme";
}
/////////////////////////////////////////////////////////////////////////////////
// MEME use old
if($action=="memeuseold") {
	$donotshowcats=true;
	sc_optionizer(	sc_phpself(), "action=memeuseoldgo", "meme", "name", 1, "Select base picture", 1);
}
/////////////////////////////////////////////////////////////////////////////////
// MEME save
if($action=="memesave") {
    sc_query("update meme set status='SAVED' where id='$mid'");
    sc_info("SAVED!","WHITE","RED");
    $action="showmemes";
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
		
		// sc_ajax("")
		

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


		echo "<a href='$RFS_SITE_URL/modules/memes/memes.php?action=memesave&mid=$m->id&showfonts=true'>";
		sc_image_text("SAVE THIS MEME","HoW%20tO%20dO%20SoMeThInG.ttf",28,812,74,0,0,150,150,0,0,0,0,1,1);
		echo "</a><BR>";

		echo "<a href='$RFS_SITE_URL/modules/memes/memes.php?action=memeedit&mid=$m->id&showfonts=true'>";
		$wf=str_replace("fonts/","",$m->font);

		sc_image_text(	"Change Font ($wf)","HoW%20tO%20dO%20SoMeThInG.ttf",28,812,74,0,0,10,145,148,1,1,0,1,1);
		echo "</a><BR>";


        $rr=100;
		if($showfonts) {
			//echo "<select>";
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
						
						//echo "<option style=\"background-image:url(".sc_image_text_small_raw($file,$file).");\">$file</option>";
		
		

						echo "<a href='$RFS_SITE_URL/modules/memes/memes.php?action=memegenerate&chgfont=$file&mid=$m->id'>
							<img src='$RFS_SITE_URL/include/generate.image.php/$t.png?action=showfont&font=$file&text_size=16&forcerender=1&oheight=120&forceheight=1&icr=$rr&icg=$rg&icb=$rb' border=0></a>";
				}
			}
			//echo "</select> ";
		}
		
		
		

		echo "</tr></table>";
	}
	else{
		echo "<p>This is not your caption.</p>";		
	}
}
/////////////////////////////////////////////////////////////////////////////////
// MEME vote up
if($action=="muv"){
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
}
/////////////////////////////////////////////////////////////////////////////////
// MEME vote down
if($action=="mdv"){
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
}


/////////////////////////////////////////////////////////////////////////////////
// MEME show memes

function memes_action_showmemes(){ eval(scg());

	echo "<h1>Meme generator</h1>";

	$mcols=5;
	$mrows=6;
	$toget=$mcols*$mrows;

    sc_query("delete FROM meme WHERE TIMESTAMPDIFF(MINUTE,`time`,NOW()) > 5 and status = 'EDIT'");    
	$donotshowcats=true;
	
	$rz=sc_query("select * from meme where 
        `private`!='yes'
        and `status` = 'SAVED'"); $mtotal=mysql_num_rows($rz);

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
	
	///////////// Last 5
	
	//echo "<td valign=top align=center width=210> ";
	sc_info("Last 5 Memes","BLACK","YELLOW");
	$r=sc_query("select * from meme where `private`!='yes' and `status` = 'SAVED' order by time desc");
	for($i=0;$i<5;$i++) {
		$m=mysql_fetch_object($r);
		echo "<div id=$m->id style=\"float: left;\">";
		sc_show1meme($m->id);
		echo "</div>";
	}
	echo "<br style='clear: both;'>";
	

	/////////// End Last 5
	
	sc_info("All Memes","BLACK","YELLOW");
	
	$q="select * from meme  ";
	$q.=" where ";
	if(!empty($onlyshow))
		$q.=" `name`='$onlyshow' and";
	$q.=" `private`<>'yes' and `status` = 'SAVED'";
	$q.=" order by rating desc limit $mtop,$mbot ";

	$r=sc_query($q);
	$n=mysql_num_rows($r); 
	
	//echo "<td style='float:left; overflow: auto; vertical-align:text-top;' >";
	for($i=0;$i<$n;$i++){
		$m=mysql_fetch_object($r);
		echo "<div id=$m->id style=\"float: left;\">";
		sc_show1meme($m->id);
		echo "</div>";
	}
	echo "<br style='clear: both;'>";
	//echo "</td>";

	///////////// Private captions
	/*
	echo "<td valign=top align=center width=240> ";
	sc_info("Your Private Captions","BLACK","GREEN");
	
	if(!empty($data->name)){
		$r=sc_query("select * from meme 
		where
		
		poster='$data->id' and
		private='yes'
		
		order by time desc");
		
		$mn=mysql_num_rows($r);
		
		if($mn>0) {			
			for($i=0;$i<$mn;$i++) {
				$m=mysql_fetch_object($r);				
				$t=$m->name."-".time();
				echo "<a href='$RFS_SITE_URL/include/generate.image.php/$t.png?mid=$m->id&owidth=$meme_fullsize' target=_blank>
				<img src='$RFS_SITE_URL/include/generate.image.php/$t.png?mid=$m->id&owidth=$meme_thumbwidth' border=0></a><br>";
            
            $muser=sc_getuserdata($m->poster); if(empty($muser->name)) $muser->name="anonymous";
            //echo "Contributor: $muser->name<br>
            
                echo "
						Based: [<a href='$RFS_SITE_URL/modules/memes/memes.php?action=showmemes&onlyshow=$m->name'>$m->name</a>]<br>";

    sc_image_text(sc_num2txt($m->rating), "OCRA.ttf", 24, 78, 24, 0, 0, 1, 155, 1, 70, 70, 0, 1, 1);
                    
        echo "<a href='$RFS_SITE_URL/modules/memes/memes.php?action=muv&mid=$m->id'><img src='$RFS_SITE_URL/images/icons/thumbup.png'   border=0 width=24></a>
              <a href='$RFS_SITE_URL/modules/memes/memes.php?action=mdv&mid=$m->id'><img src='$RFS_SITE_URL/images/icons/thumbdown.png' border=0 width=24></a>
						<br>";

				echo "[<a href='$RFS_SITE_URL/modules/memes/memes.php?action=memegenerate&basepic=$m->basepic&name=$m->name'>New Caption</a>]<br>";
				if( ($data->id==$m->poster) ||
					($data->access==255) ) {
					echo "[<a href='$RFS_SITE_URL/modules/memes/memes.php?action=memeedit&mid=$m->id'>Edit</a>] ";
					echo "[<a href='$RFS_SITE_URL/modules/memes/memes.php?action=memedelete&mid=$m->id'>Delete</a>] ";
				}
				echo "<hr>";
			}
		}
		else
		{
			echo "(You haven't created any private captions)";
		}
	}
	else {
		echo"(You must login to create private captions)";
	}
	echo "</td>";
	*/
/////////// End Private captions

	
//	echo "</tr>";
	//echo "</table>";
	
	include("footer.php");
	exit();
}

function memes_action_() { eval(scg());
	memes_action_showmemes();
}


?>
