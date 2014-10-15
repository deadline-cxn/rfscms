<?
/////////////////////////////////////////////////////////////////////////////////////////
// RFSCMS http://www.rfscms.org/
/////////////////////////////////////////////////////////////////////////////////////////
// PICTURESS CORE MODULE
/////////////////////////////////////////////////////////////////////////////////////////
include_once("include/lib.all.php");

$RFS_ADDON_NAME="pictures";
$RFS_ADDON_VERSION="1.0.0";
$RFS_ADDON_SUB_VERSION="0";
$RFS_ADDON_RELEASE="";
$RFS_ADDON_DESCRIPTION="RFSCMS Pictures";
$RFS_ADDON_REQUIREMENTS="";
$RFS_ADDON_COST="";
$RFS_ADDON_LICENSE="";
$RFS_ADDON_DEPENDENCIES="";
$RFS_ADDON_AUTHOR="Seth T. Parson";
$RFS_ADDON_AUTHOR_EMAIL="seth.parson@rfscms.org";
$RFS_ADDON_AUTHOR_WEBSITE="http://rfscms.org/";
$RFS_ADDON_IMAGES="";
$RFS_ADDON_FILE_URL="";
$RFS_ADDON_GIT_REPOSITORY="";
$RFS_ADDON_URL=lib_modules_get_base_url_from_file(__FILE__);

lib_menus_register("Pictures","$RFS_SITE_URL/modules/core_pictures/pictures.php");

////////////////////////////////////////////////////////////////////////////////////////////////////////
// MODULE PICTURES
function m_panel_pictures($x) { eval(lib_rfs_get_globals());
    lib_div("PICTURES MODULE SECTION");
    echo "<h2>Last $x Pictures</h2>";
    $res2=lib_mysql_query("select * from `pictures` where `hidden`='no' order by time desc limit 0,$x");
    $numpics=$res2->num_rows; // make pictures table...
	echo "<table border=0 cellspacing=0 cellpadding=0>";
    for($i=0;$i<$numpics;$i++) {
        $picture=$res2->fetch_object();
        if($picture->sfw=="no") $picture->url="$RFS_SITE_URL/files/pictures/NSFW.gif";        
        echo "<tr><td class=contenttd>";
        echo "<a href=\"$RFS_SITE_URL/modules/core_pictures/pictures.php?action=view&id=$picture->id\">".
		        lib_images_thumb("$RFS_SITE_PATH/$picture->url",50,0,1)."</a>";
        echo "</td><td class=contenttd width='95%' valign=top style='padding: 10px;'>";
        echo "<a href=\"$RFS_SITE_URL/modules/core_pictures/pictures.php?action=view&id=$picture->id\">";
		$pname=lib_string_truncate($picture->sname,40);
        echo "$pname</a><br>";
        echo lib_string_truncate($picture->description,50);        
        echo "</td></tr>";
    }
	echo "<tr><td class=contenttd></td><td class=contenttd>";
    echo "(<a href=$RFS_SITE_URL/modules/core_pictures/pictures.php?action=random class=a_cat>Random Picture</a>)<br>";
    echo "(<a href=$RFS_SITE_URL/modules/core_pictures/pictures.php class=a_cat>More...</a>)";
	echo "</td></tr>";
	
	echo "</table>";
    
}

function pics_addorphans($folder,$cat) { eval(lib_rfs_get_globals());
        $dir_count=0;
        $dirfiles = lib_file_folder_to_array($folder);        
        while(list ($key, $file) = each ($dirfiles)){
            if($file!="."){
                if($file!="..")
                    if( ($file!="rendered") &&
                        ($file!="cache") ) {                    
                    $dircheck= $folder."/".$file;
                    $dircheck=str_replace("../","",$dircheck);					
                    if(is_dir($dircheck)) {
                        echo "$dircheck is a folder... checking<br>";
                        $dir_count += pics_addorphans("$dircheck",$cat);  
                    } 
					$ft=lib_file_getfiletype($file);
                    if( ($ft=="gif") ||
						($ft=="tif") ||
						($ft=="png") ||
						($ft=="bmp") ||
						($ft=="svg") ||
                        ($ft=="jpg") ||
						($ft=="jpeg") ) {
                        // $ofolder=str_replace($GLOBALS['RFS_SITE_PATH'],"",$folder);
                        $url = "$folder/$file";
                        $res=lib_mysql_query("select * from `pictures` where `url`='$url'");
                        if(!$res->num_rows) {
                            $time=date("Y-m-d H:i:s");
                            lib_mysql_query("insert into `pictures` (`time`,`url`,`category`,`hidden`) VALUES('$time','$url','unsorted','yes')");
                            echo "Added [$url] to database<br>";
                            $dir_count++;
                        }
                    }
                }
            }
        }
		return $dir_count;
}

?>
