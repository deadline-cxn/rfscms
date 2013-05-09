<?
include_once("include/lib.all.php");

sc_access_method_add("memes", "upload");
sc_access_method_add("memes", "edit");
sc_access_method_add("memes", "delete");


////////////////////////////////////////////////////////////////////////////////////////////////////////
// MODULE MEMES
function sc_module_mini_memes($x) { eval(scg());
	echo "<h2>Last $x Memes</h2>";
	$r=sc_query("select * from meme where `private`!='yes' and `status` = 'SAVED' order by time desc limit $x");
	for($i=0;$i<$x;$i++) {
		$m=mysql_fetch_object($r);
		if($m) {
			echo "<div id=$m->id style=\"float: left;\">";
			sc_show1minimeme($m->id);
			echo "</div>";
		
		}
	}
	echo "<br style='clear: both;'>"; 
}

function sc_show1meme($inmid) { eval(scg());

	$m=mfo1("select * from meme where id='$inmid'");
	$t=$m->name."-".time();
	echo "<div id='fl_$inmid' class=\"memebox\" 
	style=\"vertical-align:middle; text-align:center\"
	>"; // display:table-cell;position:absolute; width:100%; height:100% \"
	echo "<div class=\"memepic\">";
	echo "<a href='$RFS_SITE_URL/include/generate.image.php/$t.png?mid=$m->id&owidth=$meme_fullsize&forcerender=1' target=_blank>
	<img src='$RFS_SITE_URL/include/generate.image.php/$t.png?mid=$m->id&oheight=$meme_thumbwidth&forcerender=1' border=0></a>"; // owidth=$meme_thumbwidth&
	echo "</div>";
	$muser=sc_getuserdata($m->poster); if(empty($muser->name)) $muser->name="anonymous";
	// echo "<hr>";
	
	sc_image_text("Rating:".sc_num2txt($m->rating), "OCRA.ttf", 15, 78,24,   0,0, 1,255,1, 0, 55,0, 1,0   );
	echo "<a href='$RFS_SITE_URL/modules/memes/memes.php?action=muv&mid=$m->id'><img src='$RFS_SITE_URL/images/icons/thumbup.png'   border=0 width=24></a>";
	echo "<a href='$RFS_SITE_URL/modules/memes/memes.php?action=mdv&mid=$m->id'><img src='$RFS_SITE_URL/images/icons/thumbdown.png' border=0 width=24></a>";
	echo "<hr>";	
	sc_button("$RFS_SITE_URL/modules/memes/memes.php?action=showmemes&onlyshow=$m->name","$m->name");
	sc_button("$RFS_SITE_URL/modules/memes/memes.php?action=memegenerate&basepic=$m->basepic&name=$m->name","New Caption");
	echo "<br>";
	if(sc_access_check("memes","edit")) {
		sc_button("$RFS_SITE_URL/modules/memes/memes.php?action=memeedit&mid=$m->id","Edit");
	}
	if(sc_access_check("memes","delete")) {
		sc_button("$RFS_SITE_URL/modules/memes/memes.php?action=meme_delete&mid=$m->id","Delete");
		echo "<br>";
	}
	echo "</div>";
}

function sc_show1minimeme($inmid) { eval(scg());
	$meme_fullsize=512;
	$meme_thumbwidth=160;
	$m=mfo1("select * from meme where id='$inmid' and `status`='SAVED'");
	$t=$m->name."-".time();
	echo "<div id='fl_$inmid' class=\"memeboxmini\">";
	echo "<div class=\"memepic\">";
	echo "<a href='$RFS_SITE_URL/include/generate.image.php/$t.png?mid=$m->id&oheight=$meme_fullsize&forcerender=1' target=_blank><img src='$RFS_SITE_URL/include/generate.image.php/$t.png?mid=$m->id&owidth=$meme_thumbwidth&forcerender=1' border=0></a>";
	echo "</div>";
	$muser=sc_getuserdata($m->poster); if(empty($muser->name)) $muser->name="anonymous";
	sc_image_text("Rating:".sc_num2txt($m->rating), "OCRA.ttf", 12, 78,24,   0,0, 1,255,1, 0, 55,0, 1,0   );
	
	echo "<a href='$RFS_SITE_URL/modules/memes/memes.php?action=muv&mid=$m->id'><img src='$RFS_SITE_URL/images/icons/thumbup.png'   border=0 width=24></a>";
	echo "<a href='$RFS_SITE_URL/modules/memes/memes.php?action=mdv&mid=$m->id'><img src='$RFS_SITE_URL/images/icons/thumbdown.png' border=0 width=24></a>";
	echo "<hr>";		
	echo "</div>";
}


?>
