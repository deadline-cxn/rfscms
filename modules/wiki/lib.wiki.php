<?
include_once("include/lib.all.php");

sc_add_menu_option("Wiki","$RFS_SITE_URL/modules/wiki/rfswiki.php");

sc_access_method_add("wiki", "admin");
sc_access_method_add("wiki", "edit");
sc_access_method_add("wiki", "delete");
sc_access_method_add("wiki", "editothers");
sc_access_method_add("wiki", "deleteothers");

sc_database_add("wiki","name",   		"text","NOT NULL");
sc_database_add("wiki","revision",		"int",	"NOT NULL");
sc_database_add("wiki","revised_by",	"text","NOT NULL");
sc_database_add("wiki","revision_note","text","NOT NULL");
sc_database_add("wiki","author", 		"text","NOT NULL");
sc_database_add("wiki","text",   		"text","NOT NULL");
sc_database_add("wiki","tags",   		"text","NOT NULL");
sc_database_add("wiki","updated",		"timestamp","ON UPDATE CURRENT_TIMESTAMP NOT NULL");

sc_touch_dir("$RFS_SITE_PATH/images/wiki");

////////////////////////////////////////////////////////////////////////////////////////////////////////
///// MODULE WIKI
function sc_module_mini_wiki($x) { eval(scg());
    sc_div("WIKI MODULE SECTION");
    echo "<h2>Last $x Wiki Page Updates</h2>";
    echo "<table width=100% border=0><tr>";
    echo "<td valign=top class=contenttd>";
	$res=sc_query(" SELECT name, MAX( updated ) FROM wiki GROUP BY name ORDER BY MAX( updated ) DESC LIMIT 0 , $x");
    $num=mysql_num_rows($res);
    for($i=0;$i<$num;$i++) {
        $page=mysql_fetch_object($res);
		 $opage=urlencode($page->name);
        echo "<a href=\"$RFS_SITE_URL/modules/wiki/rfswiki.php?name=$opage\">$page->name</a> ";
        echo "<br>\n";
    }
    echo "<p align=right>(<a href=$RFS_SITE_URL/modules/wiki/rfswiki.php?name=contents class=a_cat>More...</a>)</p>";
    echo "</td></tr></table>";
}


function wikiimg($text) { eval(scg());    
    $text=stripslashes($text);
    $text=str_replace("{{","&#123;",$text);
    $text=str_replace("}}","&#125;",$text);
	
    $outtext="";
    $ila=explode("{",$text);
    for($i=0;$i<count($ila);$i++)    {
        if(stristr($ila[$i],"}"))        {
            $ila2=explode("}",$ila[$i]);

				$imgxvars=explode(",",$ila2[0]);
				$img="$RFS_SITE_WIKI_IMAGES_PATH/". urlencode($imgxvars[0]);

				$w=$imgxvars[1];
				$h=$imgxvars[2];

				if(!file_exists($img)) {
					$outtext.= "<table border=0 class=warning><tr><td class=warning> ";
					$outtext.= sc_picthumb("$RFS_SITE_WIKI_IMAGES_PATH/wiki_warning.png",32,32,1);
					$outtext.="</td><td class=warning> Image not found";
					//////////////////////////////////////////////////////////////////////////////
					if($GLOBALS['rfsw_admin_mode']=="true") {
                    $outtext.=" </td><td class=warning> <form enctype=\"multipart/form-data\" action=\"$RFS_SITE_URL/modules/wiki/rfswiki.php\" method=\"post\">\n";
                    $outtext.="<input type=hidden name=give_file value=yes>\n";
                    $outtext.="<input type=hidden name=name value=\"$name\">\n";
                    $outtext.="<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"99900000\">";
                    $outtext.="<input type=hidden name=short_name value=\"$img\">";
                    $outtext.="<input name=\"userfile\" type=\"file\">";
                    $outtext.="<input type=\"submit\" name=\"submit\" value=\"upload\">\n";
                    $outtext.="</form>\n";
                }
					$outtext.="</td></tr></table>";
                //////////////////////////////////////////////////////////////////////////////
            } else {
				if( ($w) || ($h)) {                    
					$outtext.= sc_picthumb("$RFS_SITE_PATH/$img",$w,$h,1);
                }
				else {
                    $outtext.="<img src=\"$RFS_SITE_URL/".$img."\" border=0 >";                    
                }

				if($GLOBALS['rfsw_admin_mode']=="true") {
				// $outtext.="[<a href=\"$RFS_SITE_URL/modules/wiki/rfswiki.php?action=edit_image\">Edit Image</a>]";
			}

		}
            $outtext.=$ila2[1];
        }
        else
            $outtext.=$ila[$i];
    }
    return $outtext;
}

//////////////////////////////////////////////////////////////////////////////
// WIKITEXT FUNCTION
function wikitext($text) { eval(scg());

	if(empty($RFSW_BULLET_IMAGE))
		$RFSW_BULLET_IMAGE	= $RFS_SITE_URL."/modules/wiki/images/bullet.gif";
	if(empty($RFSW_LINK_IMAGE))
		$RFSW_LINK_IMAGE		= $RFS_SITE_URL."/modules/wiki/images/link2.png";

	$text=str_replace("$$","&#36;",$text);

	$text= wikiimg($text);
	
	$text=str_replace("</h1>\r\n","</h1>",$text);
	$text=str_replace("</h2>\r\n","</h2>",$text);
	$text=str_replace("</h3>\r\n","</h3>",$text);
	$text=str_replace("\r\n<hr>","<hr>",$text);
	$text=str_replace("<hr>\r\n","<hr>",$text);
	$text=str_replace("<hr>\n","<hr>",$text);
	//$text=str_replace("\"","&#34;",$text);
	//$text=str_replace("'","&#39;",$text);
	$text=str_replace("<?","&lt;?",$text);
	$text=str_replace("?>","?&gt;",$text);
	
	$text=str_replace("^^","&#94;",$text);	
    $text=stripslashes($text);
    $text=str_replace("[[","&#91;",$text);
    $text=str_replace("]]","&#93;",$text);
	
	$text=sc_twitter_url($text);
	$text=sc_email_url($text);
	$text=sc_inline_url($text);
	
    $outtext="";
    $ila=explode("[",$text);
    for($i=0;$i<count($ila);$i++)     {
        if(stristr($ila[$i],"]"))         {
            $ila2=explode("]",$ila[$i]);
            $fnc= $ila2[0][0];
			
            switch($fnc)             {
                case "@": 
                
                    // symbolic page link
                    $fnc=explode(",",substr($ila2[0],1));
						$outtext.="<a class=rfswiki_link href=\"$RFS_SITE_URL/modules/wiki/rfswiki.php?name=".urlencode($fnc[0])."\">".$fnc[1]."</a>";
						$outtext.=nl2br($ila2[1]);						
                    break;
                    
                case "#":
                        
                    // list
                   
                    $fnc_=explode(",",substr($ila2[0],1));
                    $fnc=$fnc_[0];
                    $ar1=$fnc_[1];
                    $ar2=$fnc_[2];
                                  
                    d_echo($fnc." ".$ar1." ".$ar2);
                    
                    $fnc=strtolower($fnc);//substr($ila2[0],1));
                    
                    if($GLOBALS['RFS_DEBUG']=="yes")
                        $outtext.=" # FUNCTION $fnc()\n{";                        
						
						if($fnc=="toggledivstart") {
							
							$lstd=explode(",",$ila2[0]);
							$outtext.=sc_togglediv_start_ne($lstd[1],$lstd[2],$lstd[3]);							
							$outtext.=$ila2[1];
							
							
						}
						if($fnc=="toggledivend") {
							$outtext.=sc_togglediv_end_ne();
							$outtext.="<br>";
							$outtext.=$ila2[1];
							
							
						}
                        
						
                    if(	($fnc=="shellstart") ||
							($fnc=="ss") ) {

                        $outtext.="<div class='wikishell'><BR>";
							$xx=nl2br($ila2[1]);
							$xx=str_replace("\\","&#92;" ,$xx);
							$xx=str_replace("<br />","<br /> $" ,$xx);
							

							
                        $outtext.=$xx;
                        $outtext.="<br><br></div>";
                    }
                    if(	($fnc=="shellend") ||
							($fnc=="se")) {
                        $outtext.=nl2br($ila2[1]);                        
                    }
                        
                    if($fnc=="codestart"){
           				$t=md5(generate_password());
						// <script language="Javascript" type="text/javascript" src="'.$RFS_SITE_URL.'/3rdparty/editarea/edit_area/edit_area_full.js">										</script>
										
							$outtext.='<script>
											editAreaLoader.init({
												id: "codecode_'.$t.'",
												start_highlight: true,
												font_size: "8",
												font_family: "terminal, verdana, monospace",
												allow_resize: "both",
												allow_toggle: false,
												language: "en",
												syntax: "php",
												toolbar: "search",												
												load_callback: "my_load",
												save_callback: "my_save",
												plugins: "charmap",
											charmap_default: "arrows" });												
										</script> ';

							$lns=substr_count($ila2[1],"\n");
							$outtext.="<center><textarea 
												id=\"codecode_$t\"
												rows=\"$lns\"
												style=\"width: 80%;\"
												name=\"codecode_$t\">";
							$outtext.=stripslashes(str_replace("<","&lt;",$ila2[1]))."</textarea></center>";
                    }
                    if($fnc=="codeend"){ 
                        $outtext.=nl2br($ila2[1]);
                    }
                        
                    if($fnc=="beginlist"){
                        $outtext.="<table class=rfs_bulletlist width=100%>";
                        $outtext.="<tr><td class=rfs_bulletlist_txt_td>";
                        $outtext.="<table border=0>";
                        $lstd=explode("\n",$ila2[1]);
                        for($li=0;$li<count($lstd);$li++)
                        {
                            $lstd[$li]=str_replace("\r","",$lstd[$li]);
                            $lstd[$li]=str_replace("\n","",$lstd[$li]);
                            if(!empty($lstd[$li]))
                            {
                                $outtext.= "<tr><td class=rfs_bulletlist_txt_td width=20></td>";
                                $outtext.= "<td class=rfs_bulletlist_img_td>";								
                                $outtext.= " <img src=\"$RFSW_BULLET_IMAGE\">";
                                $outtext.= "</td><td class=rfs_bulletlist_txt_td>";
                                $outtext.= $lstd[$li];
                                $outtext.= "</td></tr>";
                            }
                        }

                        $outtext.="</table>";
                        $outtext.= "</td></tr>";
                        $outtext.="</table>";
                    }
                    if($fnc=="endlist") {
                        $outtext.=nl2br($ila2[1]);
                    }
					
                    if($GLOBALS['RFS_DEBUG']=="yes")
                        $outtext.=" }\n";
                    break;

                default:
						if( 	stristr($ila2[0],"http:") || 
								stristr($ila2[0],"https:") ||
								stristr($ila2[0],"ftp:") ||
								stristr($ila2[0],"ftps:") ) {
								
									$outlink   = $ila2[0];
									$shortname = $ila2[0];
									$target    = "_blank";
									
									$exchk=explode(",",$outlink);
									if(count($exchk)>1) {
										$shortname=$exchk[0];
										$outlink=$exchk[1];
										if(!empty($exchk[2])) 
											$target=$exchk[2];
									}
									
									$outlink=str_replace(":","_rfs_colon_",$outlink);
									$outlink=urlencode($outlink);
									
									
									$outtext.="<a class=rfswiki_link href=$RFS_SITE_URL/link_out.php?link=$outlink target=\"$target\">".$shortname;
									$outtext.="  <img src=\"$RFSW_LINK_IMAGE\" border=\"0\" width=\"11\" height=\"10\" ></a> ";
									$outtext.=nl2br($ila2[1]);
							}
						else
							$outtext.="<a class=rfswiki_link href=\"$RFS_SITE_URL/modules/wiki/rfswiki.php?name=".urlencode($ila2[0])."\">".$ila2[0]."</a>".nl2br($ila2[1]);
                    break;
           }
        }
        else
            $outtext.=nl2br($ila[$i]);
    }
	
    return $outtext;
}

?>
