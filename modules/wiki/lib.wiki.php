<?

include_once("include/lib.all.php");

sc_access_method_add("wiki", "admin");
sc_access_method_add("wiki", "editothers");
sc_access_method_add("wiki", "deleteothers");

if(empty($RFSW_BULLET_IMAGE))
	$RFSW_BULLET_IMAGE=$RFS_SITE_URL."/modules/wiki/images/bullet.gif";

////////////////////////////////////////////////////////////////////////////////////////////////////////
///// MODULE WIKI
function sc_module_mini_wiki($x) { eval(scg());
    sc_div("WIKI MODULE SECTION");
    echo "<h2>Last $x Wiki Page Updates</h2>";
    echo "<table width=100% border=0><tr>";
    echo "<td valign=top class=contenttd>";
    $result=sc_query("select * from wiki order by `updated` desc limit 0, $x");
    $num=mysql_num_rows($result);
    for($i=0;$i<$num;$i++) {
        $page=mysql_fetch_object($result);
        echo "<a href=\"$RFS_SITE_URL/modules/wiki/rfswiki.php?name=$page->name\">$page->name</a> ";   // echo sc_time($page->updated)." by ".$page->author;
        echo "<br>\n";
    }
    echo "<p align=right>(<a href=$RFS_SITE_URL/modules/wiki/rfswiki.php?name=contents class=a_cat>More...</a>)</p>";
    echo "</td></tr></table>";
}


function wikiimg($text) { eval(scg());    
    $text=stripslashes($text);
    $text=str_replace("{{","&#123;",$text);
    $text=str_replace("}}","&#125",$text);
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
                    $outtext.=" </td><td class=warning> <form enctype=\"multipart/form-data\" action=\"rfswiki.php\" method=\"post\">\n";
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
					$outtext.= sc_picthumb("$RFS_SITE_PATH/$img",$w,$h,0);
                }
				else {
                    $outtext.="<img src=\"$RFS_SITE_URL/".$img."\" border=0 >";                    
                }

				if($GLOBALS['rfsw_admin_mode']=="true") {
				// $outtext.="[<a href=rfswiki.php?action=edit_image>Edit Image</a>]";
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
function wikitext($text) {
    $text=wikiimg($text);
    $text=stripslashes($text);
    $text=str_replace("[[","&#91;",$text);
    $text=str_replace("]]","&#93;",$text);
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
						$outtext.="<a class=rfswiki_link href=\"rfswiki.php?name=".urlencode($fnc[0])."\">".$fnc[1]."</a>";
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
                        
                    if($fnc=="ubegin") {
                        $outtext.="<a href=\"".$ila2[1];
                        $outtext.="\" target=\"".$ar1."\">";
                        $outtext.="$ila2[1]</a>";                        
                        
                    }
                    if($fnc=="uend") {
                        $outtext.=nl2br($ila2[1]);
                    }
                        
                    if($fnc=="shellstart"){							  

                        $outtext.="<div class='wikishell'><BR>";
							$xx=$ila2[1];
							$xx=str_replace("\\","&#92;" ,$xx);
                        $outtext.=$xx;
                        $outtext.="<br><br></div>";
                    }
                    if($fnc=="shellend") {
                        $outtext.=nl2br($ila2[1]);                        
                    }
                        
                    if($fnc=="codestart"){
           				$t=time();                            
							// show_codearea("sc_bf_codearea", 15, 80,"wut",$ila2[1]);
							$outtext.='<script language="Javascript" type="text/javascript" src="'.$RFS_SITE_URL.'/3rdparty/editarea/edit_area/edit_area_full.js"></script> 	<script language="Javascript" type="text/javascript"> // initialisation
										editAreaLoader.init({ //
										id: "codecode_'.$t.'" //
										,start_highlight: true //
										,font_size: "8" //
										,font_family: "verdana, monospace" //
										,allow_resize: "n" //
										,allow_toggle: false //
										,language: "en" //
										,syntax: "php" //
										,toolbar: " select_font" //
										// charmap, |, search, go_to_line, |, undo, redo, |, select_font, |, change_smooth_selection, highlight, reset_highlight, |, help"
										//new_document, save, load, |,
										,load_callback: "my_load" //
										,save_callback: "my_save" //
										,plugins: "charmap" //
										,charmap_default: "arrows" }); // 
										</script> ';

							$outtext.="<textarea id=\"codecode_$t\" style=\"height: 10px; width: 50px;\" name=\"codecode_$t\">";
							$outtext.=stripslashes(str_replace("<","&lt;",$ila2[1]))."</textarea>";
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
                                $outtext.= " <img src=".$_GLOBALS['RFSW_BULLET_IMAGE'].">";
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
								stristr($ila2[0],"ftps:") )
							$outtext.="<a class=rfswiki_link href=".$ila2[0]." target=_blank>".$ila2[0]."</a>".nl2br($ila2[1]);
						else
							$outtext.="<a class=rfswiki_link href=rfswiki.php?name=".urlencode($ila2[0]).">".$ila2[0]."</a>".nl2br($ila2[1]);
                    break;
           }
        }
        else
            $outtext.=nl2br($ila[$i]);
    }
    return $outtext;
}

?>
