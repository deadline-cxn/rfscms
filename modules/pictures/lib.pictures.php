<?
include_once("include/lib.all.php");

sc_access_method_add("pictures", "orphanscan");
sc_access_method_add("pictures", "upload");
sc_access_method_add("pictures", "edit");
sc_access_method_add("pictures", "delete");
sc_access_method_add("pictures", "sort");


////////////////////////////////////////////////////////////////////////////////////////////////////////
// MODULE PICTURES
function sc_module_mini_pictures($x) { eval(scg());
    sc_div("PICTURES MODULE SECTION");
    echo "<h2>Last $x Pictures</h2>";
    $res2=sc_query("select * from `pictures` where `hidden`='no' order by time desc limit 0,$x");
    $numpics=mysql_num_rows($res2); // make pictures table...
	echo "<table border=0 cellspacing=0 cellpadding=0>";
    for($i=0;$i<$numpics;$i++) {
        $picture=mysql_fetch_object($res2);
        if($picture->sfw=="no") $picture->url="$RFS_SITE_URL/files/pictures/NSFW.gif";        
        echo "<tr><td class=contenttd>";
        echo "<a href=\"$RFS_SITE_URL/modules/pictures/pics.php?action=view&id=$picture->id\">".
		        sc_picthumb("$RFS_SITE_PATH/$picture->url",50,0,1)."</a>";
        echo "</td><td class=contenttd width='95%' valign=top>";
        echo "<a href=\"$RFS_SITE_URL/modules/pictures/pics.php?action=view&id=$picture->id\">";
        echo "$picture->sname</a><br>";
        echo sc_trunc($picture->description,50);        
        echo "</td></tr>";
    }
	echo "<tr><td></td><td>";
    echo "(<a href=$RFS_SITE_URL/modules/pictures/pics.php?action=random class=a_cat>Random Picture</a>)<br>";
    echo "(<a href=$RFS_SITE_URL/modules/pictures/pics.php class=a_cat>More...</a>)";
	echo "</td></tr>";
	
	echo "</table>";
    
}

function pics_addorphans($folder,$cat) { eval(scg());
        $dir_count=0;
        $dirfiles = sc_folder_to_array($folder);        
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
                    if( (sc_getfiletype($file)=="gif") ||
						   (sc_getfiletype($file)=="png") ||
                        (sc_getfiletype($file)=="jpg") ) {
                        // $ofolder=str_replace($GLOBALS['RFS_SITE_PATH'],"",$folder);
                        $url = "$folder/$file";
                        $res=sc_query("select * from `pictures` where `url`='$url'");
                        if(!mysql_num_rows($res)) {
                            $time=date("Y-m-d H:i:s");
                            sc_query("insert into `pictures` (`time`,`url`,`category`,`hidden`)
                                                       VALUES('$time','$url','$cat','yes')");
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
