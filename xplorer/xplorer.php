<?
#######################################################################
# RFS XPlorer v2.2
#    (formerly known as PHP XPlorer
# By Seth Parson
#
chdir("../../");
include("rfs/header.php");
include("xplorer.cfg.php");

if($newpath=="../") $newpath="";

$newpath = str_replace("//","/",$newpath);
$newpath = str_replace("..","",$newpath);

$xp_dir  = "$xp_local_path/$newpath";
$xp_dir = str_replace("//","/",$xp_dir);

$editedtext = stripslashes( $_REQUEST['editedtext']);

 // remove comments to enable shell commands
if($data->access==255)
{
    if($exec=="yes")
    {
    	//chdir($xp_direxe);
    	//$xp_dir=$xp_direxe;
    	echo "<pre>"; system($args); echo "</pre>";
    }

    echo "<table border=0>\n";
    echo "<form enctype=\"multipart/form-data\" action=\"xplorer.php\" method=\"post\">\n";
    echo "<input type=hidden name=xp_direxe value=\"$xp_dir\">\n";
    echo "<input type=hidden name=exec value=yes>\n";
    echo "<tr><td align=right>shell&gt;</td><td><input name=args width=100 size=100></td>";
    echo "<td>&nbsp;</td><td><input type=\"submit\" name=\"submit\" value=\"Exec\"></td></tr>\n";
    echo "</form>\n";
    echo "</table>\n";
}



echo "<style><!--\n";
echo ".xp_little_black { font-family: tahoma, sans-serif; font-size: xx-small; font-weight: bold; color: #000000;}\n";
echo ".xp_title { font-family: tahoma, sans-serif; font-size: x-small; font-weight: bold; color: #FFFFFF;}\n";
echo ".xp_form {  font-family: courier; font-size:9pt; color: #ffddcc; background-color: #000000; }\n";
echo "--></style>\n";

echo "<style><!--\n";
    echo "
    .xptd
    {
        font-family: tahoma, sans-serif;
        font-size: xx-small;
        font-weight: bold;
        color: #000000;
        background-color: #BFBFBF;
        }\n";
        

    echo "
    .xptd_b
    {
        font-family: tahoma, sans-serif;
        font-size: xx-small;
        font-weight: bold;
        color: #000000;
        background-color: #0000AA;
        }\n";
        
    echo "
    .xptd_c
    {
        font-family: tahoma, sans-serif;
        font-size: xx-small;
        font-weight: bold;
        color: #000000;
        background-color: #FFFFFF;
        }\n";
        
        
    echo ".xpfont { font-family: tahoma, sans-serif; font-size: xx-small; font-weight: bold; color: #FFFFFF;}\n";
    echo ".xpa { font-family: tahoma, sans-serif; font-size: xx-small; font-weight: bold; color: #000000; font-style: normal; text-decoration: none;}\n";
    echo ".xpformitem{ height:18px; width:100px; border-width:1pt; border-color:#000000; border-style:solid; background-color:#88ff88; color:#000000; }\n";
    echo ".xpformbutton{ font-family: tahoma, sans-serif; font-size: xx-small; font-weight: bold; color: #000000; font-style: normal; height:18px;
                       border-width:1pt; border-color:#000000; border-style:solid; background-color:#AAAAAA; color:#000000; }\n";
    echo "--></style>\n";
    

function xp_mysql_install()
{
    $newpath=$GLOBALS['newpath'];
    //echo "<html>\n";
    //echo "<head>\n";
    //echo "<head><title>Xplorer Installer</title>\n";
    //echo "</head>\n";
    //echo "<body bgcolor=\"#000000\" text=\"#FFFFFF\" background=\"$xp_background\">";

    //Create database....

    if ($formpass == "$xp_admin_password")
    {
        echo "Setting up the MySQL database for Xplorer..<br>";
        echo "Checking to see if the 'sessions' directory is writeable:<br>";
        $file = fopen ("./sessions/test.txt", "w") or die ("Error, installation aborted! hint: chmod 777 sessions folder!<br>");
        echo "sessions folder is writeable<br>";
        @fputs($file, "Delete me!");
        @fclose($file);

        echo "Creating table xp_data:<br>";
        $query = "CREATE TABLE xp_data (xp_id int(10) NOT NULL auto_increment, xp_user text, xp_pass text, xp_topfolder text, PRIMARY KEY (xp_id) )";

        mysql_query($query);
        $error = mysql_error();
        echo "<br>$error<br>";
        echo "<br>Installation success!<br>Click <a href=index.php>here</a> to continue.";
    }
    else
    {
        echo "<table align=center bgcolor=#bfbfbf border=1 cellspacing=0 cellpadding=0 bordercolor=#000000><tr><td>This script will set up your MySQL database for Xplorer</td></tr><tr><td><form name=\"form\" method=\"POST\" action=\"$PHP_SELF\">";
        echo "<table><tr>";
        echo "<td>Please enter the admin password:</td>";
        echo "<td><input id=\"password\" name=\"formpass\" value=\"\" type=\"password\" size=\"5\" class=\"formitem\"></td>" ;
        echo "<td><input type=\"submit\" name=\"Submit\" value=\"Submit\" class=\"formbutton\"></td>";
        echo "</tr></table>";
        echo "</form></td></tr></table>";
    }
}

function xp_help()
{
    echo "XPlorer is a web based file explorer.<br>";
    echo "The reason for it is to view, and manage files from a remote location.<br>";
    echo "If you like this program and you find yourself using it alot, please send me 5 bucks.<br>";
    echo "<br>";
    echo "Seth Parson<br>";
    echo "Paypal: seth_coder@hotmail.com<br>";
    echo "Thanks for using my Xplorer script.<br><br>";
    echo "You can find this, and possibly more php scripts at <a href=\"http://www.sethcoder.com/\">SethCoder</a><br>";
    echo "<br>";
    echo "<table><tr><td><a href=\"$xp_url/xplorer.php?newpath=$path\">Back to Xplorer</a></td></tr></table>";
}

function xp_rename($oldname,$newname)
{
    $pw=$GLOBALS['password'];
    $xp_admin_password=$GLOBALS['xp_admin_password'];
    if($pw==$xp_admin_password)
    {
        rename($oldname,$newname);
    }
    else
    {
        echo "You do not have permission to rename";
    }
}

function xp_newfolder($newfolder)
{
    $pw=$GLOBALS['password'];
    $xp_admin_password=$GLOBALS['xp_admin_password'];
    if($pw==$xp_admin_password)
    {
        $t=mkdir($newfolder,0777);
    }
    else
    {
        echo "You do not have permission to create folders";
    }
}

function xp_delete($file)
{
    $pw=$GLOBALS['password'];
    $xp_admin_password=$GLOBALS['xp_admin_password'];
    if($pw==$xp_admin_password)
    {
        if(@filetype("$file")=="dir")   @rmdir("$file");
        else                            @unlink("$file");
    }
    else
    {
        echo "You do not have permission to delete files";
    }
}

function get_mp3_info($filename)
{
    $ID3_V1_TAG_LEN = 128 ;
    $MPEG_FRAME_HEADER_SIZE = 4 ;
    $MPEG_VER_ERROR = 0 ;
    $MPEG_VER_1 = 1 ;
    $MPEG_VER_2 = 2 ;
    $MPEG_VER_2_5 = 3 ;
    $MPEG_VER_STR = array ('?!!', '1.0', '2.0', '2.5') ;
    $MPEG_LAYER_ERROR = 0 ;
    $MPEG_LAYER_I = 1 ;
    $MPEG_LAYER_II = 2 ;
    $MPEG_LAYER_III = 3 ;
    $MPEG_LAYER_STR = array ('?!!', 'I', 'II', 'III') ;
    $MPEG_BITRATE_ERROR = -1 ;
    $MPEG_SAMPLINGRATE_ERROR = -1 ;
    $MPEG_CHANNEL_STEREO = 0 ;
    $MPEG_CHANNEL_JOINTSTEREO = 1 ;
    $MPEG_CHANNEL_DUAL = 2 ;
    $MPEG_CHANNEL_SINGLE = 3 ;
    $MPEG_CHANNEL_STR = array ('Stereo', 'Joint Stereo', 'Dual', 'Mono') ;
    $MPEG_EMPHASIS_NONE = 0 ;
    $MPEG_EMPHASIS_50_15 = 1 ;
    $MPEG_EMPHASIS_ERROR = 2 ;
    $MPEG_EMPHASIS_CCIT_J_17 = 3 ;
    $MPEG_EMPHASIS_STR = array ('None', '50/15 ms', '?!!', 'CCIT J.17') ;

    if ((filesize ($filename) >= $ID3_V1_TAG_LEN) && ($file = fopen ($filename, "r")))
    {
        fseek ($file, filesize ($filename) - $ID3_V1_TAG_LEN) ;
        $buf = fread ($file, $ID3_V1_TAG_LEN);
        fclose ($file);
    }
    else
    {
        $mp3_id3ok = false ;
    };
    if ((strlen ($buf) >= $ID3_V1_TAG_LEN) && (substr ($buf, 0, 3) == "TAG"))
    {
        $mp3_id3ok = true;
        $mp3_title = chop (substr ($buf, 3, 30));
        $mp3_artist = chop (substr ($buf, 33, 30));
        $mp3_album = chop (substr ($buf, 63, 30));
        $mp3_year = chop (substr ($buf, 93, 4));
        $mp3_comment = chop (substr ($buf, 97, 30));
        if (($buf[97 + 28] == 0) && ($buf[97 + 29] != 0))
        {
            $mp3_v1 = false;
            $mp3_track = ord ($buf[97 + 29]);
        }
        else
        {
            $mp3_v1 = true;
        };
        $mp3_genreID = ord ($buf[127]);
        $mp3_genre = "wtf?";
    }
    else
    {
        $mp3_id3ok = false;
    };

    if ((filesize ($filename) >= $MPEG_FRAME_HEADER_SIZE) && ($file = fopen ($filename, "r")))
    {
        $buf = fread ($file, $MPEG_FRAME_HEADER_SIZE);
        fclose ($file);
        $mp3_mphok = true;
    }
    else
    {
        $mp3_mphok = false ;
    };
                                    //version, versionStr
    if (strlen ($buf) == $MPEG_FRAME_HEADER_SIZE)
    {
        switch ((ord ($buf[1]) >> 3) & 3)
        {
            case 0:
                  $mp3_version = $MPEG_VER_2_5;
                  break;
            case 2:
                  $mp3_version = $MPEG_VER_2;
                  break;
            case 3:
                  $mp3_version = $MPEG_VER_1;
                  break;
            default:
                  $mp3_version = $MPEG_VER_ERROR;
                  break;
        };
        $mp3_versionstr = $MPEG_VER_STR[$mp3_version];
    };
                                       //layer, layerstr
    $mp3_layer = (4 - ((ord ($buf[1]) >> 1) & 3)) & 3 ;
    $mp3_layerstr = $MPEG_LAYER_STR[$mp3_layer] ;
                                       //crc
    $mp3_crc = (ord ($buf[1]) & 1) == 0 ;
                                       //kbitrate (kbps)
    $mpegkbitrate = array (
      1 => array (//MPEG 1
        1 => array (0, 32, 64, 96,128,160,192,224,256,288,320,352,384,416,448, $MPEG_BITRATE_ERROR), //Layer I
        2 => array (0, 32, 48, 56, 64, 80, 96,112,128,160,192,224,256,320,384, $MPEG_BITRATE_ERROR), //Layer II
        3 => array (0, 32, 40, 48, 56, 64, 80, 96,112,128,160,192,224,256,320, $MPEG_BITRATE_ERROR)  //Layer III
      ),
      2 => array (//MPEG 2
        1 => array (0, 32, 48, 56, 64, 80, 96,112,128,144,160,176,192,224,256, $MPEG_BITRATE_ERROR), //Layer I)
        2 => array (0,  8, 16, 24, 32, 40, 48, 56, 64, 80, 96,112,128,144,160, $MPEG_BITRATE_ERROR), //Layer II
        3 => array (0,  8, 16, 24, 32, 40, 48, 56, 64, 80, 96,112,128,144,160, $MPEG_BITRATE_ERROR)  //Layer III
      ),
      3 => array (//MPEG 2.5
        1 => array (0, 32, 48, 56, 64, 80, 96,112,128,144,160,176,192,224,256, $MPEG_BITRATE_ERROR), //Layer I)
        2 => array (0,  8, 16, 24, 32, 40, 48, 56, 64, 80, 96,112,128,144,160, $MPEG_BITRATE_ERROR), //Layer II
        3 => array (0,  8, 16, 24, 32, 40, 48, 56, 64, 80, 96,112,128,144,160, $MPEG_BITRATE_ERROR)  //Layer III
      )
    ) ;
    if(($mp3_version != $MPEG_VER_ERROR) && ($mp3_layer != $MPEG_LAYER_ERROR))
    {
      $mp3_kbitrate = $mpegkbitrate[$mp3_version][$mp3_layer][(ord ($buf[2]) >> 4) & 0xF];
    }
    else
    {
      $mp3_kbitrate = $MPEG_BITRATE_ERROR;
    };
                                  //samplingrate
    $mpegsamplingrate = array (
      1 => array (44100, 48000, 32000, $MPEG_SAMPLINGRATE_ERROR), //MPEG 1
      2 => array (22050, 24000, 16000, $MPEG_SAMPLINGRATE_ERROR), //MPEG 2
      3 => array (32000, 16000,  8000, $MPEG_SAMPLINGRATE_ERROR)  //MPEG 2.5
    );
    if($mp3_version != $MPEG_VER_ERROR)
    {
      $mp3_samplingrate = $mpegsamplingrate[$mp3_version][(ord ($buf[2]) >> 2) & 3];
    }
    else
    {
        $mp3_samplingrate = $MPEG_SAMPLINGRATE_ERROR;
    };
                                       //channelmode
    $mp3_channelmode = (ord ($buf[3]) >> 6) & 3 ;
    $mp3_channelmodestr = $MPEG_CHANNEL_STR[$mp3_channelmode] ;
    $mp3_channelmodeext = (ord ($buf[3]) >> 4) & 3 ;
    $channelextstr = array (
      array ('Bands 4-31', 'Bands 8-31', 'Bands 12-31', 'Bands 16-31'),
      array ('', 'Intensity', 'MS', 'MS/Intensity')
    ) ;
    if($mp3_channelmode == $MPEG_CHANNEL_JOINTSTEREO)
    {
        $mp3_channelmodeextstr = ($mp3_layer = $MPEG_LAYER_III) ?
        $channelextstr[1][$mp3_channelmodeext] :
        $channelextstr[0][$mp3_channelmodeext] ;
    }
    else
    {
      $mp3_channelmodeextstr = "";
    };
                                           //copyright
    $mp3_copyright = ((ord ($buf[3]) >> 3) & 1) == 1 ;
                                       //original
    $mp3_original = ((ord ($buf[3]) >> 2) & 1) == 1 ;
                                       //emphasis
    $mp3_emphasis = ord ($buf[3]) & 3 ;
    $mp3_emphasisstr = $MPEG_EMPHASIS_STR[$mp3_emphasis] ;

    if(($mp3_mphok) && ($mp3_kbitrate != $MPEG_BIT_RATE_ERROR))
    {
        $mp3_seconds = filesize($filename) - $MPEG_FRAME_HEADER_SIZE ;
          if ($mp3_id3ok)
          {
              $mp3_seconds -= $ID3_V1_TAG_LEN ;
          };
          $mp3_seconds = round ($mp3_seconds * 8 / ($mp3_kbitrate * 1000)) ;
      }
      else
      {
          $mp3_seconds = 0;
      };

    echo "<table bgcolor=#335599 width=100%>";
    echo "<tr><td bgcolor=#000000 width=30%><font color=#FFFFFF>MP3 Info</font></td></tr>";
    echo "<tr><td>Title</td><td>[$mp3_title]</td></tr>";
    echo "<tr><td>Artist</td><td>[$mp3_artist]</td></tr>";
    echo "<tr><td>Album</td><td>[$mp3_album]</td></tr>";
    echo "<tr><td>Comment</td><td>[$mp3_album]</td></tr>";
    echo "<tr><td>MP3 Layer</td><td>[$mp3_layer]</td></tr>" ;
    echo "<tr><td>KBRate</td><td>[$mp3_kbitrate]</td></tr>" ;
    echo "<tr><td>SampleRate</td><td>[$mp3_samplingrate]</td></tr>" ;
    echo "<tr><td>Seconds</td><td>[$mp3_seconds]</td></tr>";
    echo "</table>";
}

// check for logged on, and access here
// Do some checking to see if the requested folder is higher than what the user
// is able to look at

/*
$data=getuserdata($HTTP_SESSION_VARS['valid_user']);

if($HTTP_SESSION_VARS["logged_in"]!="true")
{
    //$skull=smiles("%X");
    echo"<table align=center><tr><td class=dm_warning><center>$skull ATTENTION!<br>You must be logged in to use xplorer! ";
    echo "What are you waiting for? <a href=join.php style=\"color: black\">JOIN</a> NOW! </td></tr></table>\n";
    include("footer.php");
    exit();
}
*/

// if($data->access==255)
//{

function optionrecursedirs($dir)
{
	echo "<option>$dir";
	$xp_dirss = array();
	$handle = @opendir($dir) or die("Unable to open filepath $dir");
	while (false!==($file = readdir($handle))) array_push($xp_dirss,$file);
	closedir($handle);
	natcasesort($xp_dirss);
	reset($xp_dirss);

	while (list ($key, $file) = each ($xp_dirss))
    	{
        	if($file != "." && $file != ".." )
        	{
			if($file!="fg")if($file!="wiki")if($file!="howtobeevil")
			if(@filetype("$dir$file")=="dir")
			{
				// echo "<option>$dir$file";
				optionrecursedirs("$dir$file/");
			}
        	}
    	}
}

if($action=="upload")
{
	echo "<table border=0>\n";
	echo "<form enctype=\"multipart/form-data\" action=\"xplorer.php\" method=\"post\">\n";
	echo "<input type=hidden name=give_file value=yes>\n";
	echo "<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"93000000\">";
	echo "<tr><td align=right>Put file in:</td><td>\n";

	echo "<select name=local_give>";

	echo "<option>$xp_local_path"."files/";

	optionrecursedirs("$xp_local_path");

	echo "</select>";

	echo "</td></tr>";
	echo "<tr><td align=right>Select file:      </td><td ><input name=\"userfile\" type=\"file\" size=80> </td></tr>\n";
	echo "<tr><td>&nbsp;</td><td><input type=\"submit\" name=\"submit\" value=\"Upload!\"></td></tr>\n";
	echo "</form>\n";
	echo "</table>\n";

}
$give_file     = $_REQUEST['give_file'];
$local_give    = $_REQUEST['local_give'];
if($give_file=="yes")
{

	    $httppath=$xp_url.$local_give;
	    $httppath=str_replace("/home/dminds1/public_html/","",$httppath);
	    $httppath=str_replace("http://www.defectiveminds.com/xplorer/","http://www.defectiveminds.com/",$httppath);

            echo "<p> Uploading files... </p>\n";
            $uploadFile=$local_give.$_FILES['userfile']['name'];
            $uploadFile =str_replace("//","/",$uploadFile);
            if(move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadFile))
            {
                system("chmod 755 $uploadFile");
                $error="File is valid, and was successfully uploaded. ";
                echo "<P>You sent: ".$_FILES['userfile']['name'].", a ".$_FILES['userfile']['size']." byte file with a mime type of".$_FILES['userfile']['type']."</p>\n";
		$httppath=$httppath.$_FILES['userfile']['name'];
                echo "<p>It was stored as [<a href=\"$httppath\">$httppath</a>]</p>\n";
            }
            else
            {
                $error ="File upload error!";
                echo "File upload error! [\n".$_FILES['userfile']['name']."][".$_FILES['userfile']['error']."][".$_FILES['userfile']['tmp_name'] ;
                echo "][";
                echo $uploadFile;
                echo "]\n";
            }
            if(!$error)
            {
                $error .= "No files have been selected for upload";
            }
            echo "<P>Status: [$error]</P>\n";
}

if($action=="editfileform")
{
    echo "<table border=0 cellpadding=0 cellspacing=0 width=400>";
    echo "<form action=xplorer.php method=post>";
    //echo "<tr><td> Rules:<br>";
    //echo " &quote; = [quote]<br>";
    //echo " &lt;/textarea&gt; = </textarea><br>";
    //echo " & = [&]<br>";
    echo "<tr><td>";
    //echo "Enter Admin Password <input name=password type=password><br>";
    echo "<input type=submit name=submit value=Save>";
    

	$file=$path.$file;
	
    if(windows()) 
    {
        $file=str_replace("/","\\",$file);
        $file=str_replace("\\\\","\\",$file);
        
        $time=date("Ydmhisa");
        echo system("copy $file $file.bk.$time.php");
    }

	$arf=filesize($file);
	

    $fp = @fopen($file,"r");
    
    if($fp)
    {
        $pwd=fread($fp,$arf);
        @fclose($fp);
        
    	$pwd=str_replace("</textarea>","&lt;/textarea>",$pwd);
    	
        //echo $pwd;
    }
    if(function_exists('show_codearea'))
    {
        show_codearea("adm_code",40,120,"editedtext",$pwd);
    }
    else
    {
        echo "<textarea name=editedtext cols=200 rows=40 WRAP=OFF class=xp_form>";
        echo $pwd;
        echo "</textarea>";
    }    
    echo " </td></tr>";
    echo "<tr><td>";
    echo "<input type=hidden name=action value=editfilesubmit>";
	echo "<input type=hidden name=path value=\"$path\">";
    echo "<input type=hidden name=file value=\"$file\">";
    echo "</form>";
    echo "</td></tr>";
    echo "</table>";
}

if($action=="editfilesubmit")
{
	$fp=fopen($file,"w");
	//$editedtext=str_replace("<ENDTADONOTEDIT>","</textarea>",$editedtext);
	fwrite($fp, "$editedtext\n\r");
	@fclose($fp);
}

if($action=="showdeleteform")
{
    echo "$newpath";
    echo "<table bgcolor=#bfbfbf border=2 cellpadding=0 cellspacing=0 align=center>";
    echo "<tr><td>";
    echo "<table bgcolor=#bfbfbf cellpadding=0 cellspacing=0><tr><td>";
    echo "<table bgcolor=#0000aa width=100% cellpadding=2 cellspacing=0><tr><td><font color=#ffffff> Confirm file delete </font></td></tr></table>\n";
    echo "</td></tr>";
    echo "<tr><td>&nbsp;</td></tr>";
    echo "<tr><td>";
    $dfile=str_replace("//","/",$xp_local_path.$file);
    echo " <table><tr><td><img src=\"$xp_images_url/skull.gif\"></td><td style=\"color:red\">Are you sure you want to delete $dfile?</td></tr></table>";
    echo "</td></tr>";
    echo "<tr><td>&nbsp;</td></tr>";

    echo "<tr><td><form action=xplorer.php>";
    echo "Enter Admin Password <input name=password type=password>";
    echo "<input type=submit name=Yes value=Yes>";
    echo "<input type=hidden name=action value=deletefile>";
    echo "<input type=hidden name=file value=\"$dfile\">";
    echo "<input type=hidden name=path value=\"$path\">";
    echo "<input type=hidden name=newpath value=\"$newpath\">";
    echo "</form></td></tr>";

    echo "<tr><td>&nbsp;</td></tr>";

    echo "<tr><td>&nbsp;</td></tr>";
    echo "<tr><td align=right><a href=\"$xp_url/xplorer.php?newpath=$newpath\">No, I was jut kidding!</a></td></tr>";
    echo "<tr><td>&nbsp;</td></tr>";
    echo "</td></tr></table>";
    echo "</table>";
}

if($action=="deletefile") xp_delete($file);

if($action=="shownewfolderform")
{
    $file = str_replace($path,"",$file);
    echo "<table bgcolor=#bfbfbf border=2 cellpadding=0 cellspacing=0 align=center>";
    echo "<tr><td>";
    echo "<table bgcolor=#bfbfbf cellpadding=0 cellspacing=0 align=center><tr><td>";
    echo "<table bgcolor=#0000aa width=100% cellpadding=2 cellspacing=0><tr><td><font color=#ffffff> Create new folder </font></td></tr></table>\n";
    echo "</td></tr>";
    echo "<tr><td>&nbsp;</td></tr>";
    echo "<tr><td>";
    echo "<table><tr><td><img src=\"$xp_images_url/keys.gif\"></td><td style=\"color: black\">What do you want to call the new folder?</td></tr></table>";
    echo "</td></tr>";
    echo "<tr><td>&nbsp;</td></tr>";
    echo "<tr><td align=center>";
    echo "<form method=POST action=\"xplorer.php\">\n";
    echo "Enter Admin Password <input name=password type=password><br>";
    echo "New Folder <input type=\"text\" name=\"newfolder\">\n";
    echo "<input type=\"hidden\" name=\"newpath\" value=\"$newpath\">\n";
    echo "<input type=\"hidden\" name=\"path\" value=\"$newpath\">\n";
    echo "<input type=\"hidden\" name=\"action\" value=\"makenewfolder\">\n";
    echo "<input type=\"submit\" Value=\"Make Folder!\"</td></tr>\n";
    echo "</form>\n";
    echo "</td></tr>";
    echo "<tr><td>&nbsp;</td></tr>";
    echo "<tr><td>&nbsp;</td></tr>";
    echo "<tr><td>&nbsp;</td></tr>";
    echo "<tr><td>&nbsp;</td></tr>";
    echo "</td></tr></table>";
    echo "</table>";
}

if($action=="makenewfolder")
{
    $newfolder=$xp_local_path."/".$newpath.$newfolder;
    $newfolder=str_replace("//","/",$newfolder);
    xp_newfolder($newfolder);
}

if($action=="showrenameform")
{
    $file = str_replace($path,"",$file);
    $file=str_replace("//","/",$xp_local_path.$file);
    echo "<table bgcolor=#bfbfbf border=2 cellpadding=0 cellspacing=0 align=center>";
    echo "<tr><td>";
    echo "<table bgcolor=#bfbfbf cellpadding=0 cellspacing=0><tr><td>";
    echo "<table bgcolor=#0000aa width=100% cellpadding=2 cellspacing=0><tr><td><font color=#ffffff> Confirm file rename </font></td></tr></table>\n";
    echo "</td></tr>";
    echo "<tr><td>&nbsp;</td></tr>";
    echo "<tr><td>";
    echo " <table><tr><td><img src=\"$xp_images_url/keys.gif\"></td><td style=\"color: black\">Are you sure you want to rename $file?</td></tr></table>";
    echo "</td></tr>";
    echo "<tr><td>&nbsp;</td></tr>";

    echo "<tr><td align=center>";

    echo "<form method=POST action=\"xplorer.php\">\n";
    echo "Enter Admin Password <input name=password type=password><br>";
    echo "<textarea name=\"newname\" cols=80 rows=3>$file</textarea>\n";

    echo "<input type=\"hidden\" name=\"path\" value=\"$path\">\n";
    echo "<input type=\"hidden\" name=\"newpath\" value=\"$newpath\">\n";
    echo "<input type=\"hidden\" name=\"file\" value=\"$file\">\n";
    echo "<input type=\"hidden\" name=\"action\" value=\"renamefiles\">\n";
    echo "<input type=\"submit\" Value=\"Rename!\"</td></tr>\n";
    echo "</form>\n";

    echo "</td></tr>";
    echo "<tr><td>&nbsp;</td></tr>";
    echo "<tr><td>&nbsp;</td></tr>";
    echo "<tr><td>&nbsp;</td></tr>";
    echo "<tr><td>&nbsp;</td></tr>";
    echo "</td></tr></table>";
    echo "</table>";
}

if($action=="renamefiles") xp_rename($file,$newname);
//}

function xp_dirs($path)
{
    $newpath=$GLOBALS['newpath'];
    $xp_url=$GLOBALS['xp_url'];
    $xp_images_url=$GLOBALS['xp_images_url'];
    $xp_dir_count=0;
    $xp_path=$GLOBALS['xp_path'];
    $xp_local_path=$GLOBALS['xp_local_path'];

    $xp_dirss = array();
    $handle = @opendir($path) or die("Unable to open filepath $path");
    while (false!==($file = readdir($handle))) array_push($xp_dirss,$file);
    closedir($handle);
    natcasesort($xp_dirss);
    reset($xp_dirss);
    while (list ($key, $file) = each ($xp_dirss))
    {
        if($file != "." )
        {
            $relpath = "$path$file";
             if(@filetype("$relpath")=="dir")
            {
                $relpath = str_replace($xp_local_path,"",$relpath);
                $relpath = str_replace(" ","%20",$relpath);
                $relpath = str_replace("//","/",$relpath);
                $xp_dir_count++;
                if($file=="..")
                {
                   $xp_dir = explode("/",$relpath,40);
                   $j = count($xp_dir)-2;
                   $newpath = "..";
                   for($i=1;$i<$j;$i++)
                   {
                      $newpath = "$newpath/";
                      $newpath = "$newpath$xp_dir[$i]";
                   }
                   $relpath=$newpath;
                  echo "<table cellpadding=0 cellspacing=0><tr><td class=xptd_c><a href=\"$xp_url/xplorer.php?newpath=$relpath/\"><img src=$xp_images_url/upfolder.gif border=0></a></td><td class=xptd_c><a href=\"$xp_url/xplorer.php?newpath=$relpath/\" style=\"color: black\">$file</a></td></tr></table>\n";
               }
               else
                  echo "<table cellpadding=0 cellspacing=0><tr><td class=xptd_c><a href=\"$xp_url/xplorer.php?newpath=$relpath/\"><img src=$xp_images_url/folder.gif border=0></a></td><td  class=xptd_c><a href=\"$xp_url/xplorer.php?newpath=$relpath/\" style=\"color: black\">$file</a></td></tr></table>\n";
            }
        }
    }
    return $xp_dir_count;
}

function xplore($path,$xp_thumbs)
{
    $newpath=$GLOBALS['newpath'];
    
    $xp_width=$GLOBALS['xp_width'];

    $xp_url=$GLOBALS['xp_url'];

    $xp_images_url=$GLOBALS['xp_images_url'];

    $xp_path=$GLOBALS['xp_path'];

    $xp_local_path=$GLOBALS['xp_local_path'];

	$xp_local_url=$GLOBALS['xp_local_url'];

    $xp_filetypes=$GLOBALS['xp_filetypes'];

    $xp_icons=$GLOBALS['xp_icons'];


	$xp_thumbwidth=$GLOBALS["xp_thumbwidth"];

	//echo " newpath = [$newpath] <BR>";
	//echo " xp_url = [$xp_url] <BR>";
	//echo " xp_images_url = [$xp_images_url]<BR>";
	//echo " xp_path = [$xp_path]<BR>";
	//echo " xp_local_path = [$xp_local_path]<BR>";


    ##################################################
    # Title bar

    echo "<table align=left width=$xp_width cellspacing=0 cellpadding=0 border=1 bordercolor=#000000>\n";
    echo "<tr>\n";
    echo "<td class=xptd_c>\n";
    echo "<table bgcolor=\"#FFFFFF\" cellspacing=0 cellpadding=0 border=0 width=100%>\n";
    echo "<tr>\n";
    
    echo "<td class=xptd_b>\n";
    echo "</td>\n";
    echo "<td class=xptd_b>\n";
    echo "<img src=$xp_images_url/xplorer.gif >\n";
    echo "</td>\n";
    echo "<td width=100%  class=xptd_b>\n";

    // $path=str_replace("//","/",$path);

    echo "<font class=xp_title> Xploring - $path </font>\n";
    echo "</td>\n";
    echo "</tr>\n";
    echo "</table>\n";
    echo "</td>\n";
    echo "</tr>\n";
    echo "</table>\n";

    echo "<table align=left width=$xp_width cellspacing=0 cellpadding=0 border=1 bordercolor=#000000>\n";
    echo "<tr><td  class=xptd>\n";

    ##################################################
    # tools 1 here

    $toolpath=str_replace("$xp_local_path","",$path);

    echo "<a href=\"$xp_url/xplorer.php?action=upload\"><img src=$xp_images_url/upload.gif border=0 alt=\"Upload files\"></a>\n";
    echo "<a href=\"$xp_url/xplorer.php?action=shownewfolderform&newpath=$newpath\"><img src=$xp_images_url/newfolder.gif border=0 alt=\"New Folder\"></a>\n";

    #
    ##################################################

    echo "</td><td class=xptd>\n";

    ##################################################
    # tools 2 here

    echo "<a href=\"$xp_url/xplorer.php?newpath=$toolpath&xp_thumbs=show\"><img src=$xp_images_url/thumbs.gif border=0 alt=\"Display thumbnails\"></a>\n";
    echo "<a href=\"$xp_url/xplorer.php?newpath=$toolpath\"><img src=$xp_images_url/nothumbs.gif border=0 alt=\"Hide thumbnails\"></a>\n";

    #
    ##################################################

    echo "</td>\n";
    echo "</tr>\n";
    echo "<tr>\n";
    
    echo "<td  class=xptd_c valign=top>\n";

    echo "<table class=xptd width=100%  height=100% cellspacing=0 cellpadding=0 border=0>\n";
    echo "<tr>\n";
    echo "<td  class=xptd background=$xp_images_url/topline.gif><img src=$xp_images_url/topleft.gif></td>\n";
    echo "<td  class=xptd background=$xp_images_url/topline.gif> </td>\n";
    echo "<td  class=xptd background=$xp_images_url/topline.gif><img src=$xp_images_url/topright.gif></td>\n";
    echo "</tr>\n";

    echo "<tr>\n";
    echo "<td class=xptd><img src=$xp_images_url/middleleft.gif></td>";
    echo "<td class=xptd class=xp_little_black>Folders</td>\n";
    echo "<td class=xptd><img src=$xp_images_url/middleright.gif></td>";
    echo "</tr>\n";

    echo "<tr>\n";
    echo "<td class=xptd background=$xp_images_url/bottomline.gif><img src=$xp_images_url/bottomleft.gif></td>\n";
    echo "<td class=xptd background=$xp_images_url/bottomline.gif> </td>\n";
    echo "<td class=xptd background=$xp_images_url/bottomline.gif><img src=$xp_images_url/bottomright.gif></td>\n";
    echo "</tr>\n";
    echo "</table>\n";

    ####################################################
    # Left folder panel

    $xp_dir_count=xp_dirs($path);

    echo "</td><td>\n";

    echo "<table align=center width=100% cellspacing=0 cellpadding=0 border=0 bordercolor=#000000>\n";
    echo "<tr><td>\n";

    echo "<table  class=xptd_c cellspacing=0 cellpadding=0 border=0 width=100%>\n";

    echo "<tr>\n";
    echo "<td class=xptd background=$xp_images_url/topline.gif><img src=$xp_images_url/topleft.gif></td>\n";
    echo "<td class=xptd background=$xp_images_url/topline.gif> </td>\n";
    echo "<td class=xptd background=$xp_images_url/topline.gif><img src=$xp_images_url/topseperator.gif></td>\n";
    echo "<td class=xptd background=$xp_images_url/topline.gif> </td>\n";
    echo "<td class=xptd background=$xp_images_url/topline.gif><img src=$xp_images_url/topseperator.gif></td>\n";
    echo "<td class=xptd background=$xp_images_url/topline.gif> </td>\n";
    echo "<td class=xptd background=$xp_images_url/topline.gif><img src=$xp_images_url/topseperator.gif></td>\n";
    echo "<td class=xptd background=$xp_images_url/topline.gif> </td>\n";
    echo "<td class=xptd background=$xp_images_url/topline.gif><img src=$xp_images_url/topseperator.gif></td>\n";
    echo "<td class=xptd background=$xp_images_url/topline.gif> </td>\n";
    echo "<td class=xptd background=$xp_images_url/topline.gif><img src=$xp_images_url/topseperator.gif></td>\n";
    echo "<td class=xptd background=$xp_images_url/topline.gif> </td>\n";
    echo "<td class=xptd background=$xp_images_url/topline.gif><img src=$xp_images_url/topright.gif></td>\n";
    echo "</tr>\n";

    echo "<tr>\n";
    echo "<td class=xptd><img src=$xp_images_url/middleleft.gif></td>";
    echo "<td class=xptd class=xp_little_black> Name </td>\n";
    echo "<td class=xptd><img src=$xp_images_url/seperator.gif></td>\n";
    echo "<td class=xptd class=xp_little_black> Size </td>\n";
    echo "<td class=xptd><img src=$xp_images_url/seperator.gif></td>\n";
    echo "<td class=xptd class=xp_little_black> Type </td>\n";
    echo "<td class=xptd><img src=$xp_images_url/seperator.gif></td>\n";
    echo "<td class=xptd class=xp_little_black> Modified </td>\n";
    echo "<td class=xptd><img src=$xp_images_url/seperator.gif></td>\n";
    echo "<td class=xptd class=xp_little_black> Description </td>\n";
    echo "<td class=xptd><img src=$xp_images_url/seperator.gif></td>\n";
    echo "<td class=xptd class=xp_little_black> Operation </td>\n";
    echo "<td class=xptd><img src=$xp_images_url/middleright.gif></td>";

    echo "</tr>\n";

    echo "<tr>\n";
    echo "<td class=xptd background=$xp_images_url/bottomline.gif><img src=$xp_images_url/bottomleft.gif></td>\n";
    echo "<td class=xptd background=$xp_images_url/bottomline.gif> </td>\n";
    echo "<td class=xptd background=$xp_images_url/bottomline.gif><img src=$xp_images_url/bottomseperator.gif></td>\n";
    echo "<td class=xptd background=$xp_images_url/bottomline.gif> </td>\n";
    echo "<td class=xptd background=$xp_images_url/bottomline.gif><img src=$xp_images_url/bottomseperator.gif></td>\n";
    echo "<td class=xptd background=$xp_images_url/bottomline.gif> </td>\n";
    echo "<td class=xptd background=$xp_images_url/bottomline.gif><img src=$xp_images_url/bottomseperator.gif></td>\n";
    echo "<td class=xptd background=$xp_images_url/bottomline.gif> </td>\n";
    echo "<td class=xptd background=$xp_images_url/bottomline.gif><img src=$xp_images_url/bottomseperator.gif></td>\n";
    echo "<td class=xptd background=$xp_images_url/bottomline.gif> </td>\n";
    echo "<td class=xptd background=$xp_images_url/bottomline.gif><img src=$xp_images_url/bottomseperator.gif></td>\n";
    echo "<td class=xptd background=$xp_images_url/bottomline.gif> </td>\n";
    echo "<td class=xptd background=$xp_images_url/bottomline.gif><img src=$xp_images_url/bottomright.gif></td>\n";
    echo "</tr>\n";

    $numfiles=0;
    $totalsize=0;

    $xp_files = array();
    $xp_dirss = array();

    $handle = @opendir($path) or die("Unable to open filepath $path");
    while (false!==($file = readdir($handle)))
    {
        if(@filetype("$path$file")=="dir")
        {
            array_push($xp_dirss,$file);
        }
        else
        {
            array_push($xp_files,$file);
        }
    }
    closedir($handle);

    natcasesort($xp_dirss);
    reset($xp_dirss);
    natcasesort($xp_files);
    reset($xp_files);

################################################################################
#list dirs

    while (list ($key, $file) = each ($xp_dirss))
    {
        if($file != "." && $file != ".." )
        {
            $numfiles++;
            $relpath = "$path$file";
            $xp_ext = explode(".",$file,40);
            $j = count ($xp_ext)-1;
            $ext = "$xp_ext[$j]";
            $chkext=strtolower($ext);
            $filename = $file;
            $filetype = "File Directory";
            $relpath = "$relpath/";
            $filedate = date("j/m/y H:ia", filemtime($relpath));
            $fsize = filesize($relpath);
            $totalsize+=$fsize;
            echo "<tr>\n";
# left edge

            echo "<td class=xptd_c></td>\n";
# Dir name
            echo "<td class=xptd_c align=left>\n";
            $relpath = str_replace($xp_local_path,"",$relpath);
            $relpath = str_replace(" ","%20",$relpath);
            $relpath = str_replace("//","/",$relpath);
            echo "<table cellspacing=0 cellpadding=0 border=0>\n";
            echo "<tr>\n";
            echo "<td class=xptd_c>\n";
            echo "<a href=\"$xp_url/xplorer.php?newpath=$relpath\"><img src=\"$xp_images_url/folder.gif\" border=0></a>\n";
            echo "</td>";
            echo "<td  class=xptd_c>\n";
            echo "<a href=\"$xp_url/xplorer.php?newpath=$relpath\" style=\"color: black\">$filename</a>\n";
            echo "</td>\n";
            echo "</tr>\n";
            echo "</table>\n";
            echo "</td>\n";
# seperator
            echo "<td class=xptd_c></td>\n";
# filesize
            echo "<td  class=xptd_c style=\"color: black\"> (dir) </td>\n";
# seperator
            echo "<td class=xptd_c></td>\n";
# filetype
            echo "<td  class=xptd_c style=\"color: black\">$filetype</td>\n";
# seperator
            echo "<td class=xptd_c></td>\n";
# filedate
            echo "<td class=xptd_c style=\"color: black\">$filedate</td>\n";
# seperator
            echo "<td class=xptd_c></td>\n";
# description
            echo "<td class=xptd_c style=\"color: black\">&nbsp;</td>\n";
# seperator
            echo "<td class=xptd_c></td>\n";
# Operations....
            echo "<td class=xptd_c>\n";
            echo "<table cellspacing=0 cellpadding=0 border=0>\n";
            echo "<tr>\n";
            ######################################################
            # individual operations
            echo "<td class=xptd_c>\n";
            echo "<a href=\"$xp_url/xplorer.php?action=showdeleteform&file=$relpath&path=$path&newpath=$newpath\"><img src=$xp_images_url/delete.gif border=0 alt=\"Delete\" title=\"Delete\"></a>\n";
            echo "</td>";
            echo "<td class=xptd_c>\n";
            echo "<a href=\"$xp_url/xplorer.php?action=showrenameform&file=$relpath&path=$path&newpath=$newpath\"><img src=$xp_images_url/rename.gif border=0 alt=\"Rename\" title=\"Rename\"></a>\n";
            echo "</td>";
            echo "<td class=xptd_c>\n";
            echo "<a href=\"$xp_url/xplorer.php?action=showmoveform&file=$relpath&path=$path&newpath=$newpath\"><img src=$xp_images_url/move.gif border=0 alt=\"Move\" title=\"Move\"></a>\n";
            echo "</td>";


            echo "</tr>\n";
            echo "</table>\n";
            echo "</td>\n";

# right edge
            echo "<td class=xptd_c></td>\n";
            echo "</tr>\n";
        }
    }

################################################################################
#list files
    while (list ($key, $file) = each ($xp_files))
    {
        if($file != "." && $file != "..")
        {
            $numfiles++;
            $relpath = "$path$file";


            $xp_ext = explode(".",$file,40);
            $j = count ($xp_ext)-1;
            $ext = "$xp_ext[$j]";
            $chkext=strtolower($ext);
            $filename = $file;
            $filetype = $xp_filetypes[$chkext];
            $fileicon = $xp_icons[$chkext];
            if($fileicon=="") $fileicon="unknown.gif";
            if($filetype=="") $filetype = "$ext file";
            if($filetype=="") $filetype = "Unknown file type";
            $filedate = date("j/m/y H:ia", filemtime($relpath));
            $fsize = filesize($relpath);
            $totalsize+=$fsize;
            $fsize = intval($fsize);

            $xp_file_url=$xp_local_url;



            $xp_strip=str_replace($xp_local_path,"",$path).$filename;
            $xp_strip=stripslashes($xp_strip);
            $xp_strip=str_replace("//","/",$xp_strip);
            $xp_file_url.=$xp_strip;



            echo "<tr>\n";

# left edge
            echo "<td class=xptd_c></td>\n";
# filename
            echo "<td  class=xptd_c align=left>\n";
            $relpath = str_replace($xp_local_path,"",$relpath);
            $relpath = str_replace(" ","%20",$relpath);
            echo "<table cellspacing=0 cellpadding=0 border=0>\n";
            echo "<tr>\n";
            echo "<td class=xptd_c><a href=\"$xp_file_url\"><img src=\"$xp_images_url/$fileicon\" border=0></a></td>\n";
            echo "<td class=xptd_c><a href=\"$xp_file_url\" style=\"color: black\">$filename</a></td>\n";
            echo "</tr>\n";
            echo "</table>\n";
            echo "</td>\n";
# seperator
            echo "<td class=xptd_c></td>\n";
# filesize
            $infor = "$filename.nfo";
            if($fsize>1024000)
            {
                $fsize=round(($fsize)/1024000);
                echo "<td class=xptd_c><a href=\"$xp_file_url\" style=\"color: black\">$fsize mB</a></td>\n";
            }
            else
            {
                if($fsize>1024)
                {
                    $fsize=round(($fsize)/1024);
                    echo "<td class=xptd_c><a href=\"$xp_file_url\" style=\"color: black\">$fsize kB</a></td>\n";
                }
                else
                {
                    echo "<td class=xptd_c><a href=\"$xp_file_url\" style=\"color: black\">$fsize Bytes</a></td>\n";
                }
            }
# seperator
            echo "<td class=xptd_c></td>\n";
# filetype
            echo "<td class=xptd_c><a href=\"$xp_file_url\" style=\"color: black\">$filetype</a></td>\n";
# seperator
            echo "<td class=xptd_c></td>\n";
# filedate
            echo "<td class=xptd_c><a href=\"$xp_file_url\" style=\"color: black\">$filedate</a></td>\n";
# seperator
            echo "<td class=xptd_c></td>";
# description
            echo "<td class=xptd_c>\n";

            $fpath="$path$file"; //$fpath=str_replace("xplorer/","",$fpath);

            @include($infor);# die("No .NFO file found");

            if($chkext=="bmp" || $chkext=="jpg" || $chkext=="gif" || $chkext=="jpeg" || $chkext=="png")
            {
                if($xp_thumbs=="show")
                {
                    echo "<a href=\"$xp_file_url\" style=\"color: black\"><img src=\"$xp_file_url\" width=\"$xp_thumbwidth\"></a><br>\n";
                }

                $image_size   = @getimagesize("$fpath");
                $image_height = $image_size[1];
                $image_width  = $image_size[0];
                if($image_height)
                    echo "<font style=\"color: black\"> $image_width x $image_height image</font>\n";
            }
            if(($chkext=="mp3") && ($xp_thumbs=="show"))
            {
                get_mp3_info("$fpath");
            }
            echo "</td>\n";
# seperator
            echo "<td class=xptd_c></td>";
# Operations....
            echo "<td class=xptd_c>\n";
            echo "<table cellspacing=0 cellpadding=0 border=0>\n";
            echo "<tr>\n";
            ######################################################
            # individual operations
            echo "<td class=xptd_c><a href=\"$xp_url/xplorer.php?action=showdeleteform&file=$relpath&path=$path&newpath=$newpath\"><img src=$xp_images_url/delete.gif border=0 alt=\"Delete\" title=\"Delete\"></a></td>\n";
            echo "<td class=xptd_c><a href=\"$xp_url/xplorer.php?action=showrenameform&file=$relpath&path=$path&newpath=$newpath\"><img src=$xp_images_url/rename.gif border=0 alt=\"Rename\" title=\"Rename\"></a></td>\n";
            echo "<td class=xptd_c><a href=\"$xp_url/xplorer.php?action=showmoveform&file=$relpath&path=$path&newpath=$newpath\"><img src=$xp_images_url/move.gif border=0 alt=\"Move\" title=\"Move\"></a></td>\n";


            $xp_edit_file_link=$GLOBALS['xp_edit_file_link'];
            echo "<td class=xptd_c><a href=\"$xp_edit_file_link$file&path=$path&newpath=$newpath\"><img src=$xp_images_url/xpeditf.gif border=0 alt=\"Edit\" title=\"Edit\"></a></td>\n";
            echo "<td class=xptd_c><a href=\"$xp_url/xplorer.php?action=addfiletodb&file_add=$xp_local_path$relpath&file_url=$xp_file_url\"><img src=$xp_images_url/plus.gif border=0 alt=\"Add $filename To Public Files\"></a></td>\n";


            echo "</tr>\n";
            echo "</table>\n";
            echo "</td>\n";
# right edge
            echo "<td class=xptd_c></td>\n";
            echo "</tr>\n";
        }
    }

################################################################################

    $jork=$numfiles;
    while($jork<$xp_dir_count)
    {
        echo "<tr><td class=xptd_c>&nbsp;</td></tr>\n";
        $jork++;
    }
    echo "\n</table>\n";
    echo "</td></tr></table>";
    echo "</td></tr><tr><td class=xptd style=\"color: black\">$numfiles object(s)</td>";
    echo "<td class=xptd style=\"color: black\">";

    if($totalsize>1024000)
    {

       $totalsize=round(($totalsize)/1024000);
        echo "$totalsize mB";
    }
    else
    {
        if($totalsize>1024)
        {
            $totalsize=round(($totalsize)/1024);
            echo "$totalsize kB\n";
        }
        else
        {
            echo "$totalsize Bytes\n";
        }
    }
    echo " (Disk free space: ";
    $freespace = @diskfreespace($path);
    if($freespace=="")
        echo "??? Error!)";
    else
    {
        if($freespace>1024000000)
        {
            $freespace=round(($freespace)/1024000000);
            echo "$freespace gB";
        }
        else
        {
            if($freespace>1024000)
            {
                $freespace=round(($freespace)/1024000);
                echo "$freespace mB";
            }
            else
            {
                if($freespace>1024)
                {
                  $freespace=round(($freespace)/1024);
                  echo "$freespace kB\n";
                }
                else
                {
                  echo "$freespace Bytes\n";
                }
            }
        }
    }
    echo ")</td></tr></table>";
    echo "<table width=$xp_width align=left bgcolor=#FFFFBB cellpadding=0 cellspacing=0 border=1 bordercolor=#000000><tr><td class=xptd_b style=\"color: black\">XPlorer by Seth Parson ~ Visit <a href=\"http://www.sethcoder.com/\"><font color=#0000FF>SethCoder.com</font></a>.</td></tr></table>";
}

if($data->access==255)
{

	xplore($xp_dir,$xp_thumbs);


}
else
{
    $skull=smiles("^X");
    echo"<table align=center><tr><td class=warning><center>$skull ATTENTION!<br>You are not authorized to use xplorer!</td></tr></table>\n";
}

include("rfs/footer.php");

?>
