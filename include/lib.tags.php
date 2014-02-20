<?

function sc_ajax_javascript_tags() { eval(scg());
echo '
<script>
function rfs_ajax_func_tags(name,ajv,table,ikey,kv,field,page,act,callback) {
			var http=new XMLHttpRequest();
			var url = "'.$RFS_SITE_URL.'/header.php";
			var params = "action="+callback+
			"&rfaajv="   +encodeURIComponent(ajv)+
			"&rfanname=" +encodeURIComponent(name)+
			"&rfatable=" +encodeURIComponent(table)+
			"&rfaikey="  +encodeURIComponent(ikey)+
			"&rfakv="    +encodeURIComponent(kv)+
			"&rfafield=" +encodeURIComponent(field)+
			"&rfaapage=" +encodeURIComponent(page)+
			"&rfaact="   +encodeURIComponent(act);
			document.getElementById("tags_"+kv).innerHTML="'.sc_ajax_spinner().'";
			http.open("POST", url, true);
			http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			http.setRequestHeader("Content-length", params.length);
			http.setRequestHeader("Connection", "close");
			http.onreadystatechange = function() {
					if(http.readyState == 4 && http.status == 200) {
					document.getElementById("tags_"+kv).innerHTML=http.responseText;
				}
			}
			http.send(params);
		}
</script>
';
}

function sc_ajax_callback_tags_new_tag() { eval(scg());
	$q="update `$rfatable` set `$rfafield`='$rfaajv' where `$rfaikey` = '$rfakv'";
	lib_tags_add_tag($rfaajv);
	sc_ajax_callback_tags_add_tag();
	exit();
}

function sc_ajax_callback_tags_add_tag() { eval(scg());
	sc_database_add($rfatable,"tags","text","not null");
	$obj=mfo1("select * from `$rfatable` where `$rfaikey` = '$rfakv'");
	$tags=$obj->tags;
	$tx=explode(",",$tags);	
	foreach($tx as $k => $v) {
		if($v==$rfaajv) { echo "<font style='color:red;'>ALREADY TAGGED</font>"; exit(); }
	}
	if(empty($tags))
		$tags=$rfaajv;
	else
		$tags.=",$rfaajv";
	$q="update `$rfatable` set `$rfafield`='$tags' where `$rfaikey` = '$rfakv'";
	sc_query($q);	
	$tagz=explode(",",$tags);
	if(!empty($tagz[0])) {
		echo "<div style='clear:both;' >";
	foreach($tagz as $tk => $tv)
			echo "<div style='float:left;' class='tags'>$tv</div>";
	}
	echo "</div>";
	exit();
}

function lib_tags_add_link($table,$id) {
	
	sc_query("delete from tags where tag = ''");
	$r=sc_query("select * from tags order by tag asc");
	$n=mysql_num_rows($r);
	
	echo "<div style='clear:both;'>";
	
	for($i=0;$i<$n;$i++){
	$tag=mysql_fetch_object($r);
	if( (($tag->hidden=="yes") && (sc_yes($_SESSION['hidden']))) ||
		$tag->hidden!="yes" ) {
			echo "<div style='float:left;'>";
			sc_ajax(	"$tag->tag",
						$table,
						"id",
						"$id",
						"tags",
						20,
						"button,nohide,nolabel",
						"files",
						"edit",
						"sc_ajax_callback_tags_add_tag,rfs_ajax_func_tags");
					
			echo "</div>";
		}
			
	}
	echo "</div>";

	sc_ajax(	"New Tag",
				$table,
				"id",
				"$id",
				"tags", 
				36,
				"nohide",
				"files",
				"edit",
				"sc_ajax_callback_tags_new_tag,rfs_ajax_func_tags");
	
}

function lib_tags_add_tag($tag) {
	sc_database_add("tags","tag","text","not null");	
	sc_database_add("tags","hidden","text","not null");
	sc_query("insert into tags (`tag`) values('$tag');");
}

function lib_tags_show_tags($table,$id) {
	$obj=mfo1("select tags from $table where id='$id'");
	$tagz=explode(",",$obj->tags);
	if(!empty($tagz[0])) {
		foreach($tagz as $tk => $tv) {
			
			$tag=mfo1("select * from tags where tag='$tv'"); 
			if( (($tag->hidden=="yes") && (sc_yes($_SESSION['hidden']))) ||
				$tag->hidden!="yes" ) {
			
				$tv=ltrim($tv," "); $tv=rtrim($tv," ");
				echo "<div style='float:left;' class='tags'>";
				if(stristr(sc_canonical_url(),"?"))  echo"<a href=\"".sc_canonical_url()."&tagsearch=$tv\">$tv</a>";
				else echo"<a href=\"".sc_canonical_url()."?tagsearch=$tv\">$tv</a>";
				echo "</div>";
			}
		}
	}
}


?>