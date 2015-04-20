<?php
$gcdx=explode("/",getcwd());
if(array_pop($gcdx)=="include")	chdir("..");
include_once("include/lib.div.php");
include_once("config/config.php");
include_once("include/session.php");
function adm_action_f_rfs_db_element_ed1() { eval(lib_rfs_get_globals());
	echo "<p> </p>";
	lib_buttons_make_button("$rtnpage?action=$rtnact","Go back");
	echo "<h2>Edit $label</h2>";
	lib_forms_build(  "$RFS_SITE_URL/admin/adm.php",
            "action=f_rfs_db_element_ed2".$RFS_SITE_DELIMITER.
			"table=$table".$RFS_SITE_DELIMITER.
			"id=$id".$RFS_SITE_DELIMITER.
			"rtnpage=$rtnpage".$RFS_SITE_DELIMITER.
			"rtnact=$rtnact".$RFS_SITE_DELIMITER,
            "$table",
            "select * from $table where `id`='$id'",
            "", "id", "omit", "", 60, "Modify" );
}
function adm_action_f_rfs_db_element_ed2() { eval(lib_rfs_get_globals());
	lib_mysql_update_database($table,"id",$id,"");
	lib_domain_gotopage("$rtnpage?action=$rtnact");
}
function adm_action_f_rfs_db_element_del1($rtnpage,$rtnact,$table,$id) {
	eval(lib_rfs_get_globals());
	//echo "DEL ELEMENT 1 <br>";
	//echo "$table $id<br>";
	//echo "$rtnpage<br>";
	//echo "$rtnact<br>";
	$r=lib_mysql_query("select * from `$table` where id='$id'");
	$element=$r->fetch_object();
	lib_forms_confirm(	"Delete database element <br>
						table:$table<br>
						id:$id<br>
						name: $element->name<br>
						?",
					"$RFS_SITE_URL/admin/adm.php?action=f_rfs_db_element_del2",
					"table=$table".$RFS_SITE_DELIMITER.
					"id=$id".$RFS_SITE_DELIMITER.
					"rtnpage=$rtnpage".$RFS_SITE_DELIMITER.
					"rtnact=$rtnact");
	
}
function adm_action_f_rfs_db_element_del2($rtnpage,$rtnact,$table,$id) { eval(lib_rfs_get_globals());
	lib_mysql_query("delete from `$table` where `id`='$id' limit 1");
	lib_domain_gotopage("$rtnpage?action=$rtnact");
}
function rfs_db_element_edit($label,$rtnpage,$rtnact,$table, $id) { eval(lib_rfs_get_globals());
lib_buttons_make_button("$rtnpage?action=f_rfs_db_element_ed1&label=$label&table=$table&id=$id&rtnpage=$rtnpage&rtnact=$rtnact","Edit");
lib_buttons_make_button("$rtnpage?action=f_rfs_db_element_del1&label=$label&table=$table&id=$id&rtnpage=$rtnpage&rtnact=$rtnact","Delete");
echo "$label ";
}
function lib_forms_join_vars($x){
    if(!is_array($x)) return;
     foreach ($x as $y => $z) {
        if(is_string($k) || (is_int($k) && $k < 0)){
            echo "$y -> $z";
        }
    }
}
function lib_forms_optionize($return_page,$hiddenvars,$table,$key,$use_id_method,$default,$on_change_method) {
	///////////////////////////////////////////////////////////////////////////////////////////////
	// Optionizer
	// $return_page		RETURN PAGE or INLINE
	// $hiddenvars		hidden vars to include (INLINE mode ignores this)
	// $table				MySQL table or FOLDER 
	// $key				key of MySQL table or FOLDERMODE
	// $use_id_method		0 or 1: Uses id field of MySQL if 1
	// $default			Default option
	// $on_change_method	0 or 1: Use javascript on_change method if 1
	eval(lib_rfs_get_globals());
	$folder_mode=0;
	if(	($use_id_method==3) || 
		($key=="FOLDERMODE")) {
		$folder_mode=1;
		$use_id_method=0;
	}
	if($return_page!="INLINE")
		echo "<form action=\"$return_page\" method=\"POST\" enctype=\"application/x-www-form-URLencoded\">";
	
	$hv=explode(lib_mysql_delimiter($hiddenvars),$hiddenvars);
	$omit='';
	$where='';
	$distinct='';

    for($hz=0;$hz<count($hv);$hz++){
        $he=explode("=",$hv[$hz]);
        for($hy=0;$hy<count($he);$hy+=2){
			$dontshowhv=false;
			if($he[$hy]=="SELECTNAME") {
				$selname=$he[$hy+1];
				$dontshowhv=true;
			}			
			if($he[$hy]=="DISTINCT") {
				$dontshowhv=true;
				if($he[$hy+1]=="TRUE"){
					$distinct=" DISTINCT ";			
				}
			}
			if($he[$hy]=="omit") {
				$dontshowhv=true;
				if($omit=='')  $omit=$he[$hy+1];
				else           $omit=$omit.", ".$he[$hy+1];
			}
			if($he[$hy]=="include") {
				$dontshowhv=true;
				if($incl=='')  $incl=$he[$hy+1];
				else			 $incl=$incl.", ".$he[$hy+1];
			}
			if($dontshowhv==true) {
				
			}
			else {
				if($return_page!="INLINE")
					echo "<input type=\"hidden\" name=\"".$he[$hy]."\" value=\"".$he[$hy+1]."\">";
			}
        }
    }
	if(!empty($omit)) {
	$exomit=explode(",",$omit);
	if(count($exomit)) {
		for($omi=0;$omi<count($exomit);$omi++){
				$exwhat=explode(":",$exomit[$omi]);
				$op="!=";
				if(stristr($exwhat[1],"like ")){ 
					$op="";
					$exwhat[1]="not ".$exwhat[1];
				}
				if($where==''){
					if(!empty($exwhat[0]))
						$where.=" where $exwhat[0] $op ";
							if(!empty($op)) $where.="'";
							$where.=$exwhat[1];
							if(!empty($op)) $where.="'";
				}
				else{
					if(!empty($exwhat[0]))
						$where.=" and $exwhat[0] $op ";
							if(!empty($op)) $where.="'";
							$where.=$exwhat[1];
							if(!empty($op)) $where.="'";
				}
			}
		}
	}
	if(!empty($incl)) {
		$exincl=explode(",",$incl);
		if(count($exincl)) {
			for($ini=0;$ini<count($exincl);$ini++){
					$exwhat=explode(":",$exincl[$ini]);
					$op="=";
					if(stristr($exwhat[1],"like ")) $op="";					
					if($where==''){
						if(!empty($exwhat[0]))
							$where.=" where $exwhat[0] $op ";
							if(!empty($op)) $where.="'";
							$where.=$exwhat[1];
							if(!empty($op)) $where.="'";
							
					}
					else{
						if(!empty($exwhat[0]))
							$where.=" and $exwhat[0] $op ";
							if(!empty($op)) $where.="'";
							$where.=$exwhat[1];
							if(!empty($op)) $where.="'";
					}
				}
			}
		}
	if($folder_mode==0) {		 
		 if(stristr($key,lib_mysql_delimiter($key))) {
				$xkey=explode(lib_mysql_delimiter($key),$key);
				$key=$xkey[0];
				$key2=$xkey[1];				
		 } else $key2='';		 
		 if(empty($selname)) $selname=$key;
		 
		 $hasimage="";
		 $result = lib_mysql_query("SHOW COLUMNS FROM `$table` LIKE 'image'");
		 $exists = ($result->num_rows)?TRUE:FALSE;
		 if($exists) $hasimage= ",image";
		 
		 $hasicon="";
		 $result = lib_mysql_query("SHOW COLUMNS FROM `$table` LIKE 'icon'");
		 $exists = ($result->num_rows)?TRUE:FALSE;
		 if($exists) $hasicon= ",icon";
		 
		$scoq="select $distinct $key$hasimage$hasicon";
		if(!empty($key2))
			$scoq.=",$key2";
			
		if($use_id_method)
			$scoq.=",id";
			
		$scoq.=" from `$table` $where order by $key asc";			
		$r=lib_mysql_query($scoq);
		
		// echo "<p> $scoq </p>";
		
		echo "<select name=\"$selname\" "; // id=\"optionizer_$selname\"
		if($on_change_method) echo "onchange=\"this.form.submit()\" ";
		echo ">";
		echo "<option value=\"$default\">$default</option>";
		echo "<option value=\"--- None ---\">--- None ---</option>";
		
		if($r) {
			while($d=$r->fetch_object()){
				echo "<option ";
				if(!empty($d->image)) {
					if(file_exists("$RFS_SITE_PATH/$d->image"))
						echo "data-image=\"".lib_images_thumb_raw($d->image,16,0,0)."\" ";
						echo " IMAGE-WHAT=\"$d->image\" ";
				}
				else if(!empty($d->icon)) {
					if(file_exists("$RFS_SITE_PATH/$d->icon"))
						echo "data-image=\"".lib_images_thumb_raw($d->icon,16,0,0)."\" ";
						echo " ICON-WHAT=\"$d->icon\" ";
				}				
			
				if($use_id_method){
					echo "value=\"$d->id\" ";
				}
				else {
					echo "value=\"".$d->$key."\" ";
				}
				echo ">".$d->$key;

				if(!empty($d->$key2)) {
					echo "(".$d->$key2.")";
				}
				echo "</option>";
			}
		}
		echo "</select>";
	}

	else {
		if(empty($selname)) $selname=$key;

		echo "<select name=\"$selname\" "; // id=\"optionizer_$selname\"
		if($on_change_method) echo "onchange=\"this.form.submit()\" ";
		echo ">";
		echo "<option value=\"$default\">$default</option>";
		echo "<option value=\"--- None ---\">--- None ---</option>";

			$dirfiles = array();
			if(stristr($table,"$RFS_SITE_URL/")) 
				$table=str_replace("$RFS_SITE_URL/","",$table);
			$handle=opendir($table) or die("Unable to open filepath");
			while (false!==($file = readdir($handle))) array_push($dirfiles,$file);
			closedir($handle);
			reset($dirfiles);
			asort($dirfiles);

			while(list ($key, $file) = each ($dirfiles)){
			if($file!=".") if($file!="..")
				if(!is_dir($dir."/".$file))
					echo "<option value=\"$file\">$file</option>";
			}

		echo "</select>";
	}

	if($return_page!="INLINE") {
		if(!$on_change_method)
			echo "<input type=\"submit\" name=\"submit\" value=\"Change\">";
		echo "</form>";
	}
}
function lib_forms_optionize_file( $select_name, $file,	$default )  {
	echo "<select name=\"$select_name\">";
	echo "<option>$default";
	if(file_exists($file)) {
		$fp=fopen($file,"r");	
		while( $ln=fgets($fp)) {
				echo "<option>$ln";
		}
		fclose($fp);
	}	
	echo "</select>";
}
function lib_forms_optionize_folder($select_name,$folder,$wildcard,$include_dirs,$include_files,$default ) {
		
	///////////////////////////////////////////////////////////////////////////////////////////////
	// $select_name 	= name of select element
	// $folder			= path to folder ie; /var/www/tools
	// $wildcard		= wildcard
	// $include_dirs	= true/false
	// $include_files	= true/false
	// $default 		= default text to put in the select (first option))	
	echo "<select name=\"$select_name\">";
	if(!empty($default))
		echo "<option>$default";
	else
		echo "<option>- Select -";
	echo "<option>$folder";
	$dirfiles = array();
	$handle=opendir($folder) or die("Unable to open filepath");
	while (false!==($file = readdir($handle))) array_push($dirfiles,$file);
	closedir($handle);
	reset($dirfiles);
	asort($dirfiles);
	while(list ($key, $file) = each ($dirfiles)){		
		$chack="$folder/$file";
		if( ($file=="lost+found") ||
			($file=="\$RECYCLE.BIN") ) {				
		}
		else {
			if(substr($file,0,1)!=".") {
				if(lib_rfs_bool_true($include_dirs)) {
					if(!is_file($chack))
						echo "<option value=\"$chack\">$chack";
				}
				if(lib_rfs_bool_true($include_files)) {
					if(is_file($chack))
						echo "<option value=\"$chack\">$chack";
				}
			}
		}
	}	
	echo "</select>";
}
function lib_forms_build_add($table){
	///////////////////////////////////////////////////////////////////////////////////////////////
	// simple add form based on table
	lib_forms_build(lib_domain_phpself(),"action=add",$table,"","","name","include","",60,"add");
}
function lib_forms_build_quick($hiddenvars,$submit){
	///////////////////////////////////////////////////////////////////////////////////////////////
	// $hiddenvars = list of 
	// takes 2 vars and will build a form using lib_forms_build
	eval(lib_rfs_get_globals()); 

	lib_forms_build(lib_domain_phpself(),$hiddenvars,"","","","","","",20,$submit);
}
function lib_forms_build($page,$hiddenvars,$table,$query,$hidevars,$specifiedvars,$svarf,$tabrefvars,$width,$submit) {
	///////////////////////////////////////////////////////////////////////////////////////////////
	// lib_forms_build (build form)
	// $page        	= page that the form will action 
	// $hiddenvars	= list of hiddenvars and/or
	//
	//						DBX_XXX
	//						LABEL_XXX
	//						SHOW_XXX_#ROWS#COLS#<varname>=<defaultvault>
	//
	//						SHOW_FILE_varname	
	// 						SHOW_CODEAREA_varname
	//						SHOW_TEXT_varname
	//						SHOW_CLEARFOCUSTEXT_varname
	//						SHOW_PASSWORD_varname
	//						SHOW_SELECTOR_(TABLE_NAME OR NOTABLE)#(TABLE_FIELD OR IGNORED)#varname#DEFAULT#option1#option2#...
	//						SHOW_TEXTAREA
	// EXAMPLES: 
	// 'SHOW_SELECTOR_colors#name#text_color#$ocolor'
	// 'SHOW_SELECTOR_exam_question_types#type#type#$qt->type'
	// 'SHOW_TEXT_address=1132 Jones Street'
	// 'SHOW_TEXT_subject=$subject'
	// 'SHOW_CODEAREA_300#600#message=$message'
	// 'SHOW_TEXTAREA_300#600#message=$message'
	// 
	// $table		  	= which table to use
	// $query       	= query of fields to include in the form, if empty will use all fields
	// $hidevars    	= list of vars to hide, seperated by $RFS_SITE_DELIMITER
	// $specifiedvars	= specify a var
	// $svarf      	= include or omit (will either include only $specifiedvars, or will omit only $specifiedvars)
	// $tabrefvars 	=
	// $width      	= default width of the form
	// $submit     	= the submit button text
	//
	eval(lib_rfs_get_globals());
	$gt=1;
	$delimiter=$RFS_SITE_DELIMITER;	
    if(!stristr($page,$RFS_SITE_URL)) $page="$RFS_SITE_URL/$page";
    if(empty($svarf)) $svarf="omit";	
	echo "<form action=\"$page\" method=\"POST\" enctype=\"multipart/form-data\">";
	echo "<table cellspacing=0 cellpadding=0>";
    echo "<tr><td>";
	d_echo($hiddenvars);	
	$hidvar_a=explode(lib_mysql_delimiter($hiddenvars),$hiddenvars);
    for($i=0;$i<count($hidvar_a);$i++){
        $hidvar_b=explode("=",$hidvar_a[$i]);
         d_echo("$hidvar_b[0] $hidvar_b[1]");
        if( (!stristr($hidvar_b[0],"DBX_")) &&
            (!stristr($hidvar_b[0],"LABEL_")) &&
            (!stristr($hidvar_b[0],"SHOW_")) ){
				d_echo("[".$hidvar_b[0]." = ".$hidvar_b[1]."]");
				echo "<input type=hidden name=\"".$hidvar_b[0]."\" value=\"".$hidvar_b[1]."\">\n";
        }
    }

    echo "</td>";	
    echo "<td></td></tr>";
	
    $gt++; if($gt>2) $gt=1;
	
	$hvars=explode(lib_mysql_delimiter($hidevars),$hidevars);
	$svars=explode(lib_mysql_delimiter($specifiedvars),$specifiedvars);
	$tvars=explode(lib_mysql_delimiter($tabrefvars),$tabrefvars);

    if(!empty($query)) {
        $res=lib_mysql_query($query);
		if($res) {
			$dat=$res->fetch_object();
			for($i=0;$i<count($hidvar_a);$i++) {
				$hidvar_b=explode("=",$hidvar_a[$i]);
				if(empty($dat->{$hidvar_b[0]}))
				@eval("\$dat->".$hidvar_b[0]."=\"".$hidvar_b[1]."\";");				
			}
		}
    }
    if(!empty($table)){
        $result = lib_mysql_query("SHOW FULL COLUMNS FROM `$table`");
		if($result)
        while($i=$result->fetch_assoc()) {
            $this_codearea=false;
            $name=ucwords(str_replace("_"," ",$i['Field']));
            $tref=0;
            for($k=0;$k<count($tvars);$k++){
                $tparts=explode("=",$tvars[$k]);
                if($tparts[0]==$i['Field']){
                    $tref=1;
                    $tref_table=$tparts[1];
                }
            }
            if($tref){
                echo "<tr><td class=rfs_project_table_$gt align=right>\n";
                echo $name;
                echo "</td><td class=rfs_project_table_$gt>";
                echo "<select name=\"".$i['Field']."\">";
                if(!empty($dat->{$i['Field']})){
                   $q="select * from `$tref_table` where `id`='".$dat->{$i['Field']}."'";
                   $tres=lib_mysql_query($q);
                   $obj=$tres->fetch_object();
				   echo "<option value=$obj->id>$obj->name";
               }
                $tres=lib_mysql_query("select * from `$tref_table` order by `name`");
                for($k=0;$k<$tres->num_rows;$k++){
                    $obj=$tres->fetch_object();
                    echo "<option value=$obj->id>$obj->name";
                }
                echo "</select>";
                echo "</td></tr>";
                $gt++; if($gt>2) $gt=1;
            }
            else{                
                if($svarf=="include") $omit=0;
                if($svarf=="omit") $omit=1;
                for($k=0;$k<count($svars);$k++){
                    if($svarf=="include"){
                        if($svars[$k]==$i['Field']) $omit=1;
                    }
                    if($svarf=="omit"){
                        if($svars[$k]==$i['Field']) $omit=0;
                    }
                }

                if($omit==1){
                    $hidden=0;
                    $relabel=false;
                    $type="text";
                    $TT=0;
                    $rows=6;
                    $cols=$width;
                    for($k=0;$k<count($hvars);$k++){
                        if($hvars[$k]==$i['Field']){
                            $hidden=1;
                            $type="hidden";
                        }
                    }
                    $hidvar_a=explode(lib_mysql_delimiter($hiddenvars),$hiddenvars);
					for($j=0;$j<count($hidvar_a);$j++){
						$hidvar_b=explode("=",$hidvar_a[$j]);					
						if(stristr($hidvar_b[0],"DBX_")){
							$field=explode("DBX_",$hidvar_b[0]);
							if($field[1]==$i['Field']){
								$TT=1;
								$type=$hidvar_b[1];
								break;
							}
							else{							
								$rw=explode("#",$field[1]);
								if(count($rw)==3){
									$rows=$rw[0];
									$cols=$rw[1];
									$taname=$rw[2];
									d_echo("[3 DBX_ Count]");
								}
								else if(count($rw)==2){
									$rows=$rw[0];
									$taname=$rw[1];
									d_echo("[2 DBX_ Count]");
								}

								if($taname==$i['Field']){
									$TT=1;
									$type=$hidvar_b[1];
									d_echo( "[".$rw[1]."]");
									d_echo( "[".$rw[2]."]");
									d_echo( "[".count($rw)."]");
								}
							}
						}

						if(stristr($hidvar_b[0],"LABEL_")){
							$field=explode("LABEL_",$hidvar_b[0]);
							if($i['Field']==$field[1])
								$relabel=true;
							$label=$hidvar_b[1];
						}
					}
					if($hidden==0){
						echo "<tr><td class=rfs_project_table_$gt align=right>\n";
						if($relabel==true)	echo $label;
						else               	echo $name;
						echo "</td><td class=rfs_project_table_$gt>";
					}
					if($i['Field']=="password")	$type="password";
					if($i['Field']=="pass") 	$type="password";

					switch($type){  // button checkbox image radio reset  
						
						case "textarea":
							echo " <textarea rows=$rows cols=$cols name=\"";
							echo $i['Field'];
							echo "\">";
							$code=str_replace("</textarea>","&lt;/textarea>",$dat->{$i['Field']});
							echo stripslashes($code);
							echo "</textarea>\n";
							break;

						case "codearea":
							$this_codearea=true;
							$godat=$dat->{$i['Field']};
							lib_forms_codearea("lib_forms_build_codearea", $rows,$cols,$i['Field'],$godat);
							break;
							
						case "colorpicker":						
							$cp=$i['Field'];
							echo "<!-- flooble.com Color Picker start -->";
							include($GLOBALS['site_path']."/js/flooble_color_picker.js");
							echo " &nbsp;&nbsp;<a href=\"javascript:pickColor('pick$cp');\" id=\"pick$cp\"
							style=\"border: 1px solid #000000; font-family:Verdana; font-size:14px;
							text-decoration: none;\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>
							<input id=\"pick$cp"."field\" size=\"7\"
							onChange=\"relateColor('pick$cp', this.value);\" title=\"color\" name=\"";
							echo $cp;
							echo "\" value=\"".$dat->{$i['Field']}."\">
							<script language=\"javascript\">relateColor('pick$cp', getObj('pick$cp"."field').value);</script>
							<noscript></noscript>\n<!-- flooble Color Picker end -->\n";
							break;

						case "hidden":
							echo "<tr><td height=0px; width=0px;>";
							
						case "file":
						case "text":
						case "submit":
						case "password":

							if($type=="file"){
								$fn=$dat->{$i['Field']};
								if(!empty($fn)){
									$ft=lib_file_getfiletype($fn);
									if( ($ft=="gif") ||
										($ft=="png") ||
										($ft=="jpg") ||
										($ft=="jpeg") ||
										($ft=="bmp") ){
										echo "<img width=32 height=32 src=\"$$RFS_SITE_URL/$fn\">";
									}
									echo "currently [$fn]";
									echo "<br>";
								}
							}

							echo " <input type=\"$type\" ";
							echo "size=$width ";
							echo "name=\"".$i['Field']."\" ";
								$outvar=$dat->{$i['Field']};
								$outvar=str_replace("\"","&quot;",$outvar);
							echo "value=\"$outvar\">\n";

							if($hidden==1)
								echo "</td><td>";

						default:
							break;
					}
					if($hidden==0){
						echo"</td></tr>\n";
						$gt++; if($gt>2) $gt=1;
					}
                }
            }
        }
    }
    $hidvar_a=explode(lib_mysql_delimiter($hiddenvars),$hiddenvars);
    for($j=0;$j<count($hidvar_a);$j++) {
        $hidvar_b=explode("=",$hidvar_a[$j]);
        d_echo("[$hidvar_b[0]] [$hidvar_b[1]]");				
		if(stristr($hidvar_b[0],"SHOW_FILE_")) {			
			$field=explode("#",str_replace("SHOW_FILE_","",$hidvar_b[0]));
			$cols=$width;
			$rows=6;
			$taname=$hidvar_b[0];
			if($field[0]) $taname=$field[0];
			$rw=explode("#",$hidvar_b[0]);			
			echo "<tr><td class=rfs_project_table_$gt align=right>";
			echo ucwords(str_replace("_"," ",$taname));
			echo " </td>
			<td ><input name=\"$taname\" type=\"file\" size=80> </td></tr>\n";
		}
		
		if(stristr($hidvar_b[0],"SHOW_SELECTOR_")) {			
			// examples:
			// SHOW_SELECTOR_colors#name#text_color#$ocolor
			// SHOW_SELECTOR_exam_question_types#type#type#$qt->type
			// SHOW_SELECTOR_NOTABLE_			
            if($this_codearea==false){
            $field=explode("#",str_replace("SHOW_SELECTOR_","",$hidvar_b[0]));
			  $default=$field[3];
            $name=$field[2];
            $key=$field[1];
            $table=$field[0];			
				$keys=explode("&",$key);
				if(count($keys)>1) {
					$key=join($RFS_SITE_DELIMITER,$keys);
				}
            echo "<tr><td class=\"rfs_project_table_$gt\" align=right>";			
			echo ucwords(str_replace("_"," ",$name));
            echo "</td><td class=\"rfs_project_table_$gt\">";
			if($table!="NOTABLE") {
				lib_forms_optionize("INLINE","SELECTNAME=$name".$RFS_SITE_DELIMITER,$table,$key,0,$default,0);
			}
			else {
				echo "<select name =$name>";
				echo "<option >$default";
				for($i=4;$i<count($field);$i++) {
					echo "<option>".$field[$i];
				}
				echo "</select>";					
			}
			echo "</td></tr>";
            $gt++; if($gt>2) $gt=1;
		}
	}
		
		
	if(stristr($hidvar_b[0],"SHOW_CODEAREA_")) {
		if($this_codearea==false){
		$field=explode("#",str_replace("SHOW_CODEAREA_","",$hidvar_b[0]));
		$name=$field[2];
		$cols=$field[1];
		$rows=$field[0];
		//echo "[".$hidvar_b[0]."][$rows][$cols]";
		echo "<tr><td class=rfs_project_table_$gt align=right>";
		echo "</td><td class=rfs_project_table_$gt>";
		lib_forms_codearea( "lib_forms_build_codearea",$rows,$cols,$name,$hidvar_b[1]);
		echo "</td></tr>";
		$gt++; if($gt>2) $gt=1;
		}
	}

	if(stristr($hidvar_b[0],"SHOW_CLEARFOCUSTEXT_")) {
		$hidvar_b[0]=str_replace("SHOW_CLEARFOCUSTEXT_","SHOW_TEXT_",$hidvar_b[0]);
		$clearfocus=" onfocus=\"this.value=''; \"";
	}

	if(stristr($hidvar_b[0],"SHOW_TEXT_")){
		 d_echo("SHOW_TEXT_ found... ".$hidvar_b[0]);
		$field=explode("#",$hidvar_b[0]);
		$hidvar_b[0]=str_replace("SHOW_TEXT_","",$hidvar_b[0]);
		$cols=$width;
		$rows=6;
		$taname=$hidvar_b[0];
		$rw=explode("#",$hidvar_b[0]);

		if(count($rw)==3){
			$rows=$rw[0];
			$cols=$rw[1];
			$taname=$rw[2];
		}
		else if(count($rw)==2){
			$rows=$rw[0];
			$taname=$rw[1];
		}
		echo "<tr><td class=rfs_project_table_$gt align=right>";
		echo ucwords(str_replace("_"," ",$taname));
		echo "</td><td class=rfs_project_table_$gt>";

			echo " <input ";
			echo "size=\"$cols\" ";
			echo "name=\"".$taname."\" ";
			echo "value=\"".$hidvar_b[1]."\"";
			echo $clearfocus;
			echo ">\n";
		echo "</td></tr>";
		$gt++; if($gt>2) $gt=1;
	}

	if(stristr($hidvar_b[0],"SHOW_TEXTAREA_")){
		$field=explode("#",$hidvar_b[0]);
		$hidvar_b[0]=str_replace("SHOW_TEXTAREA_","",$hidvar_b[0]);
		$cols=$width;
		$rows=6;
		$taname=$hidvar_b[0];
		$rw=explode("#",$hidvar_b[0]);
		if(count($rw)==3){
			$rows=$rw[0];
			$cols=$rw[1];
			$taname=$rw[2];
		}
		else if(count($rw)==2){
			$rows=$rw[0];
			$taname=$rw[1];
		}
		// echo "--- $field[1] $hidvar_b[1]<br>";
		echo "<tr><td class=rfs_project_table_$gt align=right>";
		echo ucwords(str_replace("_"," ",$taname));
		echo "</td><td class=rfs_project_table_$gt>";
		echo "<textarea rows=$rows cols=$cols name=\"$taname\">";
		$code=str_replace("</textarea>","&lt;/textarea>",$hidvar_b[1]);
		echo stripslashes($code);
		echo "</textarea>";
		/*
		echo " <input ";
		echo "size=$width ";
		echo "name=\"".$field[1]."\" ";
		echo "value=\"\"";
		echo ">\n";
		*/
		echo "</td></tr>";
		$gt++; if($gt>2) $gt=1;
	}

	if(stristr($hidvar_b[0],"SHOW_PASSWORD_")){
		$field=explode("#",$hidvar_b[0]);
		$hidvar_b[0]=str_replace("SHOW_PASSWORD_","",$hidvar_b[0]);
		$cols=$width;
		$rows=6;
		$taname=$hidvar_b[0];
		$rw=explode("#",$hidvar_b[0]);
		//echo "[".$rw[0]."]<br>";
		//echo "[".$rw[1]."]<br>";
		//echo "[".$rw[2]."]<br>";
		//echo "[".count($rw)."]<bn>";
		if(count($rw)==3){
			$rows=$rw[0];
			$cols=$rw[1];
			$taname=$rw[2];
		}
		else if(count($rw)==2){
			$rows=$rw[0];
			$taname=$rw[1];
		}
		// echo "--- $field[1] $hidvar_b[1]<br>";
		echo "<tr><td class=rfs_project_table_$gt align=right>";
		echo ucwords(str_replace("_"," ",$taname));
		echo "</td><td class=rfs_project_table_$gt>";

		echo " <input type=password ";
		echo "size=$cols ";
		echo "name=\"".$taname."\" ";
		echo "value=\"".$hidvar_b[1]."\"";
		echo ">\n";

		//echo "<textarea rows=$rows cols=$cols name=\"$taname\">";
		//$code=str_replace("</textarea>","&lt;/textarea>",$hidvar_b[1]);
		//echo stripslashes($code);
		//echo "</textarea>";
		/*
		echo " <input ";
		echo "size=$width ";
		echo "name=\"".$field[1]."\" ";
		echo "value=\"\"";
		echo ">\n";
		*/
		echo "</td></tr>";
		$gt++; if($gt>2) $gt=1;
		}
    }

    if(!empty($submit)){
	    echo "<tr><td></td><td>";        
	    echo "<input style='font-size:x-small; min-width:100px;' type=submit name=submit value=\"$submit\">";        
	    echo "</td></tr>";
    }
    echo "</table>";
	echo "</form>";
}
function lib_forms_option_countries() {
	echo "
	<option>United States
	<option>Afghanistan
	<option>Albania
	<option>Algeria
	<option>Andorra
	<option>Angola
	<option>Antigua and Barbuda
	<option>Argentina
	<option>Armenia
	<option>Australia
	<option>Austria
	<option>Azerbaijan
	<option>Bahamas
	<option>Bahrain
	<option>Bangladesh
	<option>Barbados
	<option>Belarus
	<option>Belgium
	<option>Belize
	<option>Benin
	<option>Bhutan
	<option>Bolivia
	<option>Bosnia and Herzgovina
	<option>Botswana
	<option>Brazil
	<option>Brunei
	<option>Bulgaria
	<option>Burkina Faso
	<option>Burundi
	<option>Cambodia
	<option>Cameroon
	<option>Canada
	<option>Cape Verde
	<option>Central African Republic
	<option>Chad
	<option>Chile
	<option>China
	<option>Columbia
	<option>Comoros
	<option>Congo (Brazzaville)
	<option>Congo, Democratic Republic
	<option>Costa Rica
	<option>Croatia
	<option>Cuba
	<option>Cyprus
	<option>Czech Republic
	<option>Cote d'lvoire
	<option>Denmark
	<option>Djibouti
	<option>Dominica
	<option>Dominican Republic
	<option>East Timor (Timor Timur)
	<option>Ecuador
	<option>Egypt
	<option>El Salvador
	<option>Equatorial Guinea
	<option>Eritrea
	<option>Ethiopia
	<option>Fiji
	<option>Finland
	<option>France
	<option>Gabon
	<option>Gambia
	<option>Georgia
	<option>Germany
	<option>Ghana
	<option>Greece
	<option>Grenada
	<option>Guatemala
	<option>Guinea
	<option>Guinea-Bissau
	<option>Guyana
	<option>Haiti
	<option>Honduras
	<option>Hungary
	<option>Iceland
	<option>India
	<option>Indonesia
	<option>Iran
	<option>Iraq
	<option>Ireland
	<option>Israel
	<option>Italy
	<option>Jamaica
	<option>Japan
	<option>Jordan
	<option>Kazakhstan
	<option>Kenya
	<option>Kiribati
	<option>Korea, Best
	<option>Korea, South
	<option>Kuwait
	<option>Kyrgyzstan
	<option>Laos
	<option>Latvia
	<option>Lebanon
	<option>Lesotho
	<option>Liberia
	<option>Libya
	<option>Liechtenstein
	<option>Lithuania
	<option>Luxembourg
	<option>Macedonia, Former Yugoslav Republic
	<option>Madagasgar
	<option>Malawi
	<option>Malaysia
	<option>Maldives
	<option>Mali
	<option>Malta
	<option>Marshall Islands
	<option>Mauritania
	<option>Mauritius
	<option>Mexico
	<option>Micronesia, Federated States of
	<option>Moldova
	<option>Monaco
	<option>Mongolia
	<option>Morocco
	<option>Mozambique
	<option>Myanmar
	<option>Nambia
	<option>Nauru
	<option>Nepal
	<option>Netherlands
	<option>New Zealand
	<option>Nicaragua
	<option>Niger
	<option>Nigeria
	<option>Norway
	<option>Oman
	<option>Pakistan
	<option>Palau
	<option>Panama
	<option>Papua New Guinea
	<option>Paraguay
	<option>Peru
	<option>Phillipines
	<option>Poland
	<option>Portugal
	<option>Qatar
	<option>Romania
	<option>Russia
	<option>Rwanda
	<option>Saint Kitts and Nevis
	<option>Saint Lucia
	<option>Saint Vincent and The Grenadines
	<option>Samoa
	<option>San Marino
	<option>Sao Tome and Principe
	<option>Saudia Arabia
	<option>Senegal
	<option>Serbia and Montenegro
	<option>Seychelles
	<option>Sierra Leone
	<option>Singapore
	<option>Slovakia
	<option>Slovenia
	<option>Solomon Islands
	<option>Somalia
	<option>South Africa
	<option>South Sudan
	<option>Spain
	<option>Sri Lanka
	<option>Sudan	
	<option>Suriname
	<option>Swaziland
	<option>Sweden
	<option>Switzerland
	<option>Syria
	<option>Taiwan
	<option>Tajikistan
	<option>Tanzania
	<option>Thailand
	<option>Togo
	<option>Tonga
	<option>Trinidad and Tobago
	<option>Tunisia
	<option>Turkey
	<option>Turkmenistan
	<option>Tuvalu
	<option>Uganda
	<option>Ukraine
	<option>United Arab Emirates
	<option>United Kingdom
	<option>United States
	<option>Uruguay
	<option>Uzbekistan
	<option>Vanuatu
	<option>Vatican City
	<option>Venezuela
	<option>Vietnam
	<option>Western Sahara
	<option>Yemen
	<option>Zambia
	<option>Zimbabwe
	";
}
function lib_forms_css_file($css_file,$returnpage,$returnaction,$hiddenvars) { eval(lib_rfs_get_globals());
	$hvar=array();
	$hvars=explode($RFS_SITE_DELIMITER,$hiddenvars);
	for($i=0;$i<count($hvars);$i++) {
		$tt=explode("=",$hvars[$i]);
		$hvar[$tt[0]]=$tt[1];
	}
	lib_forms_optionize_file("addcss","$RFS_SITE_PATH/tools/classes.out.txt", "CSS Classes");

	$f=file_get_contents($css_file);
	$cssx=explode("}",$f);
	for($i=0;$i<count($cssx)-1;$i++) {
		$cssx2=explode("{",$cssx[$i]);
		echo "\n\n<hr>\n\n";

		$buttout="$returnpage?action=$returnaction".
			"&delact=delbase".
			"&delete=".urlencode($base).
			"&cssvalue=".urlencode($cssvalue).
			"&outfile=".urlencode($css_file);

			foreach ($hvar as $vn => $vv){
				$buttout.="&$vn=$vv";
			}
			echo " \n <!-- *******************************	NEW SECTION ********************************************************* --> \n";
			lib_buttons_make_button($buttout,"Delete");

		echo "$cssx2[0] { <br>";
echo "<table border=0>";
		$cssx3=explode(";",$cssx2[1]);
		for($j=0;$j<count($cssx3)-1;$j++) {
			$cssx4=explode(":",$cssx3[$j]);
echo "


<!-- *** TR  START *** -->

<tr>
<td>
";
			$base=trim($cssx2[0]);
			$sub=trim($cssx4[0]);
			$cssvalue=trim($cssx4[1]);
			$buttout="$returnpage?action=$returnaction".
			"&delact=delsub".
			"&delete=".urlencode($base).
			"&sub=".urlencode($sub).
			"&cssvalue=".urlencode($cssvalue).
			"&outfile=".urlencode($css_file);

			foreach ($hvar as $vn => $vv){
				if($vn!="update")
				$buttout.="&$vn=$vv";
			}
$piece = trim($cssx4[0]);

echo "
[<a href=\"$buttout\">Delete</a>]
</td>
<!-- **** PIECE [ $piece ]  START **** -->
<td width=200>";
echo "$piece:";
echo "</td>

<!-- **** FORM START **** -->

<form method=post action=\"$returnpage\">	

<td>";
echo "<a name=\"$base$sub\"></a> \n ";
		$cssvalue=str_replace("\"","'",$cssvalue);
echo "
<input type=\"hidden\" name=\"thm\" value=\"$thm\">
<input type=\"hidden\" name=\"outfile\" value=\"$css_file\">
<input type=\"hidden\" name=\"action\" value=\"$returnaction\">
<input type=\"hidden\" name=\"update\" value=\"$base\">
<input type=\"hidden\" name=\"sub\" value=\"$sub\">
<input type=\"hidden\" name=\"cssvalue\" value=\"$cssvalue\">


<!-- **** PIECE [ $piece ]  VALUE [$cssvalue] **** -->

<input name=\"newvalue\" value=\"$cssvalue\" ";
			if( 	(substr(trim($cssx4[1]),0,1)=="#") || 
					(    stristr($cssx4[0],"color")) )
				echo " type=\"color\" onchange=\"this.form.submit();\" ";
			else
				echo "onblur=\"this.form.submit();\" ";
echo " size=60 \">

</td>

<!-- **** FORM END **** -->
</form>


</tr> 
<!-- *** TR  END *** -->

";
	}
		echo "
</table>
}";
		echo "


";
	}
}
function lib_forms_php_file($php_file,$returnpage,$returnaction,$hiddenvars) { eval(lib_rfs_get_globals());

	$hvar=array();
	$hvars=explode($RFS_SITE_DELIMITER,$hiddenvars);
	for($i=0;$i<count($hvars);$i++) {
		$tt=explode("=",$hvars[$i]);
		$hvar[$tt[0]]=$tt[1];
	}

	echo "\n\n\n";
	echo "<table border=0>\n";
	echo "<tr>\n";
	echo "<td>\n";
	echo "<form action=$returnpage method=\"post\">\n";
	echo "</td>\n";
        echo "<td>\n";
 	echo "<input type=hidden name=action value=\"$returnaction\">\n";
	echo "<input type=hidden name=add value=\"var\">\n";
	echo "<input type=hidden name=outfile value=\"$php_file\">\n";
	foreach ($hvar as $vn => $vv) {
		echo "<input type=hidden name=\"$vn\" value=\"$vv\">\n";
	}
	echo "</td>\n";
	echo "<td width=200>\n";
	/////////////////////////////////////////////////////////////////////////////////////////
	lib_forms_optionize_file( "addvar", "$RFS_SITE_PATH/tools/rfsvars_out.txt", "Add a system variable");
	echo "<input name=varvalue size=60 value=\"\">\n";
	echo "<input type=submit value=\"Add\">\n";
	echo "</form>\n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "</table>\n";

	echo "\n<hr>\n\n";

	echo "<table border=0>\n";

	$fp=fopen($php_file,"r");
	while( $ln=fgets($fp)) {
		if 	((substr($ln,0,2)=="<?") ||
			 (substr($ln,0,2)=="?>") ||
			 (substr($ln,0,2)=="//") ||
			 (substr($ln,0,1)=="\r")||
			 (substr($ln,0,1)=="\n") ) {
		} else {
			$varx=explode("=",$ln);
			$varx[0]=trim($varx[0]," ");
			echo "    <tr>\n";
			echo "        <td>\n";
			echo "            <form method=post action=\"$returnpage\">\n";

			echo "            [<a href=\"$returnpage?action=$returnaction&delete=$varx[0]&outfile=$php_file";
			foreach ($hvar as $vn => $vv){
				echo "&$vn=$vv";
			}
			echo "\">delete</a>] <br>\n";
			echo "        </td>\n";
			echo "        <td>\n";
			echo "            ".$varx[0]."\n";

			$varx[1]=trim($varx[1]," ");
			$varx[1]=trim($varx[1],"\n");
			$varx[1]=trim($varx[1],"\r");
			$varx[1]=trim($varx[1],";");
			$varx[1]=trim($varx[1],"\"");
			$varx[1]=str_replace("\'","\\'",$varx[1]);
			$varx[1]=str_replace("<","&lt;",$varx[1]);
			$varx[1]=str_replace(">","&gt;",$varx[1]);

			echo "        </td>\n";
			echo "        <td>\n";

			/*lib_ajax_file( "Name,80",
				"files",
				"id",
				"$id",
				"name",
				70,
				"",
				"files","edit","");*/

			if(stristr($varx[0],"login_form")) {
				echo " (NOT SHOWN)";
				//echo "<textarea >";
				//echo $varx[1];
				//echo "</textarea>";
			}
			else {
				echo "            <input type=\"hidden\" name=\"thm\" value=\"$thm\">\n";
				echo "            <input type=\"hidden\" name=\"outfile\" value=\"$php_file\">\n";
				echo "            <input type=\"hidden\" name=\"action\" value=\"$returnaction\">\n";
				echo "            <input type=\"hidden\" name=\"update\" value=\"$varx[0]\">\n";
				echo "            <input type=\"hidden\" name=\"phpvalue\" value=\"$varx[1]\">\n";
				echo "            <input name=\"newvalue\" value=\"$varx[1]\" ";
				if( 	(substr(trim($varx[1]),0,1)=="#") ||
					(    stristr($varx[0],"color")) )
					echo " type=\"color\" onchange=\"this.form.submit();\" ";
				else
					echo "onblur=\"this.form.submit();\" ";
				echo " size=60 >\n";
				// echo "<input size=60 value='".$varx[1]."' ";
				// 				if(substr($varx[1],0,1)=="#") echo "class='color' ";
				//			echo ">";
			}
			echo "            </form><br>\n";
			echo "        </td>\n";
			echo "    </tr>\n";
		}
	}
	echo "</table>\n\n\n\n";
	fclose($fp);
}
function lib_forms_codearea($id,$rows,$cols,$name,$indata){ eval(lib_rfs_get_globals());

	echo " <script language=\"Javascript\"
					type=\"text/javascript\"
					src=\"$RFS_SITE_URL/3rdparty/editarea/edit_area/edit_area_full.js\">
			</script>\n";
	
	echo ' <script language="Javascript" type="text/javascript">
		// initialisation

		editAreaLoader.init({
			id: "<? echo $id; ?>"	// id of the textarea to transform
			,start_highlight: true
			,font_size: "8"
			,font_family: "verdana, monospace"
			,allow_resize: "y"
			,allow_toggle: false
			,language: "en"
			,syntax: "php"
			,toolbar: " charmap, |, search, go_to_line, |, undo, redo, |, select_font, |, change_smooth_selection, highlight, reset_highlight, |, help"
			//new_document, save, load, |,
			,load_callback: "my_load"
			,save_callback: "my_save"
			,plugins: "charmap"
			,charmap_default: "arrows" });

		// callback functions
		function my_save(id, content){
		    id.form.submit();
			
		}
		function my_load(id){
			editAreaLoader.setValue(id, "The content is loaded from the load_callback function into EditArea");
		}
		function test_setSelectionRange(id){
			editAreaLoader.setSelectionRange(id, 100, 150);
		}
		function test_getSelectionRange(id){
			var sel =editAreaLoader.getSelectionRange(id);
			alert("start: "+sel["start"]+"\nend: "+sel["end"]);
		}
		function test_setSelectedText(id){
			text= "[REPLACED SELECTION]";
			editAreaLoader.setSelectedText(id, text);
		}
		function test_getSelectedText(id){
			alert(editAreaLoader.getSelectedText(id));
		}
		function editAreaLoaded(id){
			if(id=="example_2"){
				open_file1();
				open_file2();
			}
		}
		function open_file1(){
			var new_file= {id: "to\\ Ã© # â¬ to", text: "$authors= array();\n$news= array();", syntax: "php", title: 
			
	"beautiful title"};
			editAreaLoader.openFile(
			"example_2", new_file);
		}
		function open_file2(){
			var new_file= {id: "Filename", text: "<a href=\"toto\">\n\tbouh\n</a>\n<!-- it\'s a comment -->", syntax: "html"};
			editAreaLoader.openFile("example_2", new_file);
		}
		
		
		function toogle_editable(id){
            editAreaLoader.execCommand(id, "set_editable", !editAreaLoader.execCommand(id, "is_editable"));
		}
	</script>';
	
	//alert("Here is the content of the EditArea '"+ id +"' as received by the save callback function:\n"+content);
	//// function close_file1(){	editAreaLoader.closeFile("example_2", "to\\ Ã© # â¬ to");}
	//    $ca_rows=$rows*16; $ca_cols=$cols*7.20;
    echo "<textarea id=\"$id\" style=\"height: $rows"."px; width: $cols"."px;\" name=\"$name\">";
    if(stristr($indata,"FILE_LOAD_")){
        $file=$GLOBALS['site_path'].str_replace("FILE_LOAD_","",$indata);
        $fp=fopen($file,"r");
        if($fp){
            $indata = fread($fp, filesize($file));
            fclose($fp);
        }
    }
    $code=str_replace("</textarea>","&lt;/textarea>",$indata);
    echo stripslashes($code);
    echo "</textarea>";
    //echo "<BR><a href=\"http://sourceforge.net/projects/editarea/\" target=_blank>EditArea</a> JavaScript Browser Editor";
}
function lib_forms_warn($x) {
	eval(lib_rfs_get_globals()); 
	echo "<div class=warning><br><img src='$RFS_SITE_URL/images/icons/exclamation2.png' border=0 align=left>$x</div>";
}
function lib_forms_inform($x) { eval(lib_rfs_get_globals());
    echo "<div class=inform>
    <img src='$RFS_SITE_URL/images/icons/Warning.png' width=\"12\" border=\"0\">
    $x<br> </div>"; }
function lib_forms_question($inquest) {
	$inquest=str_replace("<a ","<a class=ainform ",$inquest);
	$inquest=str_replace("<hr>", "<hr class=questionhr> ",$inquest);
	echo "<center><div class=question align=left><img src='";
	echo $_GLOBALS["RFS_SITE_URL"];
	echo "/images/icons/3dquestion.png' align=right border=0>";
	echo lib_string_convert_smiles($inquest);
	echo "</div>";
}
function lib_forms_system_message() {
    eval(lib_rfs_get_globals());
    $RFS_ADDON_URL=lib_modules_get_url("profile");
	if($_SESSION['logged_in']){
		if(empty($data->pass)) {
			lib_forms_info("You have not established a password. [<a href=\"$RFS_ADDON_URL?action=show_password_form\" style=\"font-size: 1em;\">Change your password</a>]","WHITE","RED");
		}
		if(empty($data->email)) {
			lib_forms_info("You have not established an email address. [<a href=\"$RFS_ADDON_URL\" style=\"font-size: 1em;\">Add your email</a>]","WHITE","RED");
		}
	}
}
function lib_forms_info($t,$c,$c2) {
$border="#000";
if( $c2=="#000" || $c2=="black" || $c2="#000000" ) $border="#0f0";
echo "<div style='
padding: 5px;
font-size: 1em;
font-weight: bold;
color:$c;
background-color:$c2;
border: 1px solid $border;
border-radius: 5px;
width:100%;'>$t</div>";
}
function lib_forms_confirm($message,$page,$hiddenvars){
	echo "\n<lib_forms_confirm [START]================================================ />\n";
    echo "<table border=0 width=400>\n";
    echo "<tr><td align=center>\n";
    echo "<br>\n";
    echo "<form action=\"$page\" method=\"POST\" enctype=\"application/x-www-form-URLencoded\">\n";
    echo "<div class=confirmform>";
    echo "$message";
	$hidvar_a=explode(lib_mysql_delimiter($hiddenvars),$hiddenvars);
    for($i=0;$i<count($hidvar_a);$i++){
        $hidvar_b=explode("=",$hidvar_a[$i]);
        echo "<input type=hidden name=\"".$hidvar_b[0]."\" value=\"".$hidvar_b[1]."\">\n";
    }
	echo "<input style='font-size:x-small' type=submit name=yes value=Yes>\n";
	echo "</div>";
	echo "<br><br>\n";
	echo "</td></tr>\n";
	echo "</table>\n";
	echo "</form>\n";
	echo "<lib_forms_confirm [END]================================================ />\n";
}
function lib_forms_theme_select() { eval(lib_rfs_get_globals());
		if(lib_rfs_bool_true($GLOBALS["RFS_SITE_THEME_DROPDOWN"])) {
			$data=lib_users_get_data($_SESSION['valid_user']);
			$loc=lib_domain_canonical_url();
			$loc=str_replace("&theme=$theme","",$loc);
			$loc=str_replace("?theme=$theme","",$loc);
			$sep="?";
			if(stristr($loc,"?")) $sep="&";
			echo "<select name=\"theme\"
						onchange=\"";
					echo "document.location='$loc$sep'+'theme='+this.value\"";
			echo "	style=\"width:120px;\"><option>Theme\n";
			$thms=lib_themes_get_array();
			while(list($key,$thm)=each($thms)){
				echo "<option";
				if($thm==$data->theme) echo " selected=selected";
				echo ">".$thm;
			}
			echo "</select>";
		}
}
