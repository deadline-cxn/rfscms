<?php
/////////////////////////////////////////////////////////////////////////////////////////
function lib_ajax_spinner() { eval(lib_rfs_get_globals()); 
 return "<img src=$RFS_SITE_URL/images/icons/spinner.gif>";
}

function lib_ajax_callback_image(){ eval(lib_rfs_get_globals());
	if(lib_access_check($rfaapage,$rfaact)) {
		$q="update `$rfatable` set `$rfafield`='$rfaajv' where `$rfaikey` = '$rfakv'";
		$r=lib_mysql_query($q);
		if($r) {
			echo "<img src='$RFS_SITE_URL/images/icons/check.png' border=0 width=16>";
			$oimg=str_replace("$RFS_SITE_URL/","",$rfaajv);
			echo lib_images_thumb($oimg,64,64,1);
		}		
		else echo "<font style='color:white; background-color:red;'>FAILURE: $q</font>";
	}
	else echo "<font style='color:white; background-color:red;'>NOT AUTHORIZED</font>";
	exit;
}
function lib_ajax_callback_file(){ eval(lib_rfs_get_globals()); exit; }
function lib_ajax_callback_delete() { eval(lib_rfs_get_globals());
	echo "<img src='$RFS_SITE_URL/images/icons/check.png' border=0 width=16>";
}

//////////////////////////////////////////////////////////////////////////////
// default ajax callback function
function lib_ajax_callback(){
    
    eval(lib_rfs_get_globals());
    
	if(lib_access_check($rfaapage,$rfaact)) {
		$rfaajv=addslashes($rfaajv);
		$q="update `$rfatable` set `$rfafield`='$rfaajv' where `$rfaikey` = '$rfakv'";
        
        
        
		$r=lib_mysql_query($q);		
		if($r) echo "<img src='$RFS_SITE_URL/images/icons/check.png' border=0 width=16>";
		else   echo "<font style='color:white; background-color:red;'>FAILURE: $q</font>";
	}
	else     	echo "<font style='color:white; background-color:red;'>NOT AUTHORIZED</font>";
	exit;
}
//////////////////////////////////////////////////////////////////////////////
// default ajax javascript function
function lib_ajax_javascript() { eval(lib_rfs_get_globals());
	$arr=get_defined_functions();
	foreach( $arr['user'] as $k=>$v ) if(stristr($v,"lib_ajax_javascript_")) eval($v."();");
echo '
<script>
function rfs_ajax_hide(x) { var div = document.getElementById(x); div.style.display = "none"; };
function rfs_ajax_func(name, ajv, table, ikey, kv, field, page, act, callback) {
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
			document.getElementById(name+"_div").innerHTML="'.lib_ajax_spinner().'";
			http.open("POST", url, true);
			http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			http.setRequestHeader("Content-length", params.length);
			http.setRequestHeader("Connection", "close");
			http.onreadystatechange = function() {
					if(http.readyState == 4 && http.status == 200) {
					document.getElementById(name+"_div").innerHTML=http.responseText;
				}
			}
			http.send(params);
		}
</script>
';
}
//////////////////////////////////////////////////////////////////////////////
// short ajax function
function rfs_ajax($data,$size,$properties,$access,$callback) {
	$x=explode(",",$data);
	$table=$x[0];
	$key_field=$x[1];
	$key_field_value=$x[2];
	$field_to_modify=$x[3];
	$label=$x[4];
	if(empty($label)) $label="$table($key_field=$key_field_value).$field_to_modify";
	$x=explode(",",$access);
	$access_type=$x[0];
	$access_action=$x[1];
	if(empty($size)) $size="60";
	lib_ajax($label,$table,$key_field,$key_field_value,$field_to_modify,
				$size,$properties,$access_type,$access_action,$callback);

}
//////////////////////////////////////////////////////////////////////////////
// long ajax function
function lib_ajax($rfalabel,$rfatable,$rfaikey,$rfakv,$rfafield,$size,$rfa_properties,$rfaapage,$rfaact,$rfacallback ) {
    eval(lib_rfs_get_globals());
	if(!lib_access_check($rfaapage,$rfaact)) return;
	
	// extract callback functions
	$x=explode(",",$rfacallback);
			$rfacallback=$x[0];
			if(empty($rfacallback))
				$rfacallback="lib_ajax_callback";
			
			$rfajscallback=$x[1];
			if(empty($rfajscallback))
				$rfajscallback="rfs_ajax_func";	
			
	// extract label information
	$x=explode(",",$rfalabel);
			$rfalabel=$x[0];
			$minwidth="min-width: ".$x[1].";";
			
	// extract properties
	$properties=explode(",",$rfa_properties);
		$props=array();
		$rfatype=$properties[0];	
		foreach($properties as $k => $v) $props[$v]=true;
		if($props['nohide'])
			$hidefunc="rfs_ajax_hide('$rfakv');";
		
	// extract size information
	$x=explode(",",$size);
		$width=$x[0];
		$height=$x[1];
		
	// $x=explode(",",$rfafield);		$rfafield=$x[0]; 		$rfaotherfield=$x[1];
	
	$rfanname="RFAJAX_".time()."_".md5($rfakv.$rfalabel.$rfatable.$rfaikey);	
	
	if($rfalabel!="") {
		if($props['nolabel']) {
			echo "<div id='$rfanname"."_div' style='float:left;'></div>\n";	
			echo "<div id='$rfanname"."_label' style='float:left; $minwidth; margin-top: 5px; margin-right: 10px;'></div>\n";
		}
		else {
			echo "<div id='$rfanname"."_div' style='float:left;'>&nbsp;</div>\n";	
			echo "<div id='$rfanname"."_label' style='float:left; $minwidth; margin-top: 5px; margin-right: 10px;'>$rfalabel</div>\n";
		}
	
	}
	echo "<div style='min-width: $width;'>";
	
	$rfakv=addslashes($rfakv);	
	$q="select * from `$rfatable` where `$rfaikey`='$rfakv'";
	$r=lib_mysql_query($q);
	$d=$r->fetch_array();

	if($rfatype=="select") {	
		
		$type=$properties[1];
		
		if($type=="table") {
			$table=$properties[2];
			$key=$properties[3];
			$value=$properties[4];
			
			if(!empty($value)) {
				$q="select * from `$table` where `$key`='".$d[$rfafield]."'";
				$r=lib_mysql_query($q);
				$tdat=$r->fetch_array();
				$tvalue=$tdat[$value];
				$tdata=$tdat[$key];
			}
			else {
				$tvalue=$d[$rfafield];
				$tdata=$d[$rfafield];
			}
			
			if(empty($tvalue)) $tvalue="Select";
			if(empty($tdata))  $tdata="Select";
            
            
			
			echo "<select
					width=\"$width\"
					data-description=\"$rfanname\" 
					data-maincss=\"blue\"
					id=\"$rfanname"."_name\"
					name=\"$rfanname"."_name\"
					
					onchange=\"
                    
                    var select_id = document.getElementById('$rfanname"."_name');
                    var x=select_id.options[select_id.selectedIndex].value;
                    
                    
					$rfajscallback( '$rfanname',
                                    x,
                                    '$rfatable',
                                    '$rfaikey',
                                    '$rfakv',
                                    '$rfafield',
                                    '$rfaapage',
                                    '$rfaact',
                                    '$rfacallback');
                    $hidefunc;
                    this.blur();
                    
                    \"
                    
					style='float:left; min-width: $width;  '>";
			
			echo "<option ";
			
				if(!empty($tdat['image'])) {
					if(file_exists("$RFS_SITE_PATH/".$tdat['image']))
						echo "data-image=\"".lib_images_thumb_raw($tdat['image'],16,0,0)."\" ";						
				}
				else if(!empty($tdat['icon'])) {
					if(file_exists("$RFS_SITE_PATH/".$tdat['icon']))
						echo "data-image=\"".lib_images_thumb_raw($tdat['icon'],16,0,0)."\" ";
				}

			echo "value=\"$tvalue\">$tdata</option>";

			$raa=lib_mysql_query("select * from `$table` order by `$key` asc");
			for($i=0;$i<$raa->num_rows;$i++) {
				$dat=$raa->fetch_array();

				echo "<option ";

				if(!empty($dat['image'])) {
					if(file_exists("$RFS_SITE_PATH/".$dat['image'])) {
						echo "data-image=\"".lib_images_thumb_raw($dat['image'],16,0,0)."\" ";
					}
				}
				else if(!empty($dat['icon'])) {
					if(file_exists("$RFS_SITE_PATH/".$dat['icon'])) {
						echo "data-image=\"".lib_images_thumb_raw($dat['icon'],16,0,0)."\" ";
					}
				}
				
				if(!empty($value)) {
					$q="select * from `$table` where `$key`='".$dat[$key]."'";
					$ree=lib_mysql_query($q);
					$tdat=$ree->fetch_array();
					$tvalue=$tdat[$value];
					$tdata=$tdat[$key];
				}
				else {
					$tvalue=$d[$rfafield];
					$tdata=$d[$rfafield];
				}
				
				echo "NOVALVAR='NOPE' value='";
                if(empty($tvalue)) $tvalue=$tdata;
				echo $tvalue; //$dat[$key];
				echo "' >";
				echo $tdata; //$dat[$key];
				echo "</option>";
			}
			echo "</select>";
		}
		
		echo "</div>";
		echo "<div style='clear:both;'></div>";
		return;		
	}
	
	if($rfatype=="checkbox") {
		$cbx=lib_mysql_fetch_one_object("select * from `$rfatable` where `$rfaikey`='$rfakv'");
		$cbxo="off"; if(lib_rfs_bool_true($cbx->enabled)) $cbxo="on";
		echo "<input 	id=\"$rfanname"."_input\"
						type=\"$rfatype\"
						name=\"$rfanname"."_name\"							
						onchange=\"
						$rfajscallback('$rfanname',this.checked,'$rfatable','$rfaikey','$rfakv','$rfafield','$rfaapage','$rfaact','$rfacallback');
						$hidefunc;	 \" ";
		if($cbxo=="on") echo " checked ";
		echo " ></div><div style='clear:both;'></div>";
		return;
	}
	
	if($rfatype=="button") {
		echo "<button	id=\"$rfanname"."_input\"
					size=\"$width\"					
					name=\"$rfanname"."_name\"					
					value=\"$rfalabel\" 
					onclick=\"	$rfajscallback('$rfanname',this.value,'$rfatable','$rfaikey','$rfakv','$rfafield','$rfaapage','$rfaact','$rfacallback'); $hidefunc; \"> 
					<span class=\"ui-button-text\">$rfalabel</span></button>";
		echo "</div><div style='clear:both;'></div>";
		return;
	}
	
	if($rfatype=="textarea") {
		echo "<textarea 	id=\"$rfanname"."_input\"
							rows=\"$width\"
							cols=\"$height\"
							type=\"$rfatype\"
							name=\"$rfanname"."_name\"							
							onblur=\"$rfajscallback('$rfanname',this.value,'$rfatable','$rfaikey','$rfakv','$rfafield','$rfaapage','$rfaact','$rfacallback');
							$hidefunc;	\" style='float:left;'>";
		$tout=str_replace("<","&lt;",$d[$rfafield]);
		echo $tout."</textarea>";
		echo "</div><div style='clear:both;'></div>";
		return;
	}
	
	// default type = text input
	echo "<input	id=\"$rfanname"."_input\"
					size=\"$width\"
					type=\"$rfatype\"
					name=\"$rfanname"."_name\"
					value=\"".$d[$rfafield]."\"
					onblur=\"$rfajscallback('$rfanname',this.value,'$rfatable','$rfaikey','$rfakv','$rfafield','$rfaapage','$rfaact','$rfacallback');
					$hidefunc;\"
					onkeyup=\"if((event.keyCode==13)) {this.blur();}\"style='float:left;'>";
	echo "</div><div style='clear:both;'></div>";
}
