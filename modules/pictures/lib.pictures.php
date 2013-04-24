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
        echo "<a href=\"$RFS_SITE_URL/modules/pictures/pics.php?action=view&id=$picture->id\">".sc_picthumb("$RFS_SITE_PATH/$picture->url",50,0,1)."</a>";        
        echo "</td><td class=contenttd width='95%' valign=top>";
        echo "<a href=\"$RFS_SITE_URL/modules/pictures/pics.php?action=view&id=$picture->id\">";
        echo "$picture->sname</a><br>";
        echo sc_trunc($picture->description,50);        
        echo "</td></tr>";
    }
	echo "</table>";
    echo "<p align=right>(<a href=$RFS_SITE_URL/modules/pictures/pics.php?action=random class=a_cat>Random Picture</a>)";
    echo "(<a href=$RFS_SITE_URL/modules/pictures/pics.php class=a_cat>More...</a>)</p>";
}

function sc_mini_meme($inmid) { eval(scg());

	$m=mfo1("select * from meme where id='$inmid'");
	$t=$m->name."-".time();
	
	echo "<div id='fl_$inmid' style=\"
					height: 280px;
					margin: 5px;
					box-shadow: 5px 5px 5px #888888;
					border:solid 1px #777777;
					border-radius: 5px;
					\" >";
	
	echo "<a href='$RFS_SITE_URL/include/generate.image.php/$t.png?mid=$m->id&owidth=$fullsize' target=_blank>
	<img src='$RFS_SITE_URL/include/generate.image.php/$t.png?mid=$m->id&owidth=$thumbwidth' border=0 
	
	style='max-height: 240px;' ></a><br>";
	$muser=sc_getuserdata($m->poster); if(empty($muser->name)) $muser->name="anonymous";
	echo "
		Based: [<a href='$RFS_SITE_URL/modules/pictures/pics.php?action=showmemes&onlyshow=$m->name'>$m->name</a>]<br>";

		sc_image_text(sc_num2txt($m->rating), "OCRA.ttf",         24, 78,24,   0,0,      1,155,1, 70,70,0, 1,1   );
		
			echo "<a href='$RFS_SITE_URL/modules/pictures/pics.php?action=muv&mid=$m->id'><img src='$RFS_SITE_URL/images/icons/thumbup.png'   border=0 width=24></a>
					<a href='$RFS_SITE_URL/modules/pictures/pics.php?action=mdv&mid=$m->id'><img src='$RFS_SITE_URL/images/icons/thumbdown.png' border=0 width=24></a>
					<br>";

	echo "[<a href='$RFS_SITE_URL/modules/pictures/pics.php?action=memegenerate&basepic=$m->basepic&name=$m->name'>New Caption</a>]<br>";
	if( ($data->id==$m->poster) ||
		($data->access==255) ) {
		echo "[<a href='$RFS_SITE_URL/modules/pictures/pics.php?action=memeedit&mid=$m->id'>Edit</a>] ";
		echo "[<a href='$RFS_SITE_URL/modules/pictures/pics.php?action=memedelete&mid=$m->id'>Delete</a>] ";
	}
	echo "</div>";
}

?>
