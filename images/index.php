<?

// foreach($_GET as $v) echo $v."<br>";

if($action=="deleteicon"){
    @unlink("/var/www/images/".$fname);

}

if($ren==1){
echo "Rename $fname to :"; $file=$fname;
                                echo "<form action=\"".$_SERVER['PHP_SELF']."\" method=\"POST\" enctype=\"multipart/form-data\">";
                                echo "<input type=hidden name=\"action\" value=\"copyicon\">";
                                echo "<input type=hidden name=\"fname\" value=\"$file\">";
                                echo "<input type=hidden name=\"outpath\" value=\"$outpath\">";
                                echo "<input type=text name=\"newname\" value=\"$newname\">";
                                echo "<input type=submit name=\"copy/rename\" value=\"copy/rename\">";
                                echo "</form>";
    exit();

}

if($action=="copyicon"){


    $f1="/var/www/images/".$fname;
        $x=explode("/",$fname);
        $fn=$x[count($x)-1];

        $f2=$outpath.$fn;
        
        if(!empty($newname))
            $f2=$outpath.$newname;

        copy($f1,$f2);
        echo "Copied $f1 to $f2";
    exit();
}

function show_images($dir){
    
    $outpath=$_REQUEST['outpath'];
    $showall=$_REQUEST['showall'];
    $w=$_REQUEST['w'];
    $h=$_REQUEST['h'];        
    $dir_count=0;
    $dirfiles = array();
//    echo "$dir <br>";
    $handle=@opendir($dir) or die("Unable to open filepath [$dir]");
    while (false!==($file = readdir($handle))) array_push($dirfiles,$file);
    closedir($handle);    
    reset($dirfiles);
    asort($dirfiles);

    echo "<table border=0><tr>"; $tr=0;
    while(list ($key, $file) = each ($dirfiles))
    {
        if($file!=".")
            if($file!="..")
            {
                $file="$dir/$file";
                $file=str_replace("//","/",$file);
                $file=str_replace("./","",$file);
                if(is_dir($file)){
//                    echo "FOLDER: $file<br>";
                    //       if(!stristr($file,"_MACOS"))
                    if(!empty($showall))
                         show_images($file);
                    else    
                        echo "Directory: <a href=\"".$_SERVER['PHP_SELF']."?dir=$file&outpath=$outpath&w=$w&h=$h\">$file</a><br>";
                }
                else{
                    	$finfo=pathinfo($file);
                    	$fext=strtolower($finfo['extension']);
 //                    echo "$fext<br>";
                    if( ($fext=="gif") ||
                        ($fext=="png") ||
                        ($fext=="ico") ||
                        ($fext=="bmp") ||
                        ($fext=="jpg") && 
                            (!stristr($fext,"_MACOS")) )
                         {

                    $sz="";
                    if(!empty($w)) { $sz="width=$w height=$h"; }
        echo "<td>";
                    echo "<a href=\"".$_SERVER['PHP_SELF']."?action=copyicon&ren=1&outpath=$outpath&fname=$file\" target=_blank alt='$file' title='$file'><img src=\"./$file\" $sz  border=0></a>";
                        // echo "($file)";
                            if(!empty($outpath)) {
                                echo "<br>[<a href=\"".$_SERVER['PHP_SELF']."?dir=$dir&action=copyicon&outpath=$outpath&fname=$file&w=$w&h=$h\" target=_blank>C</a>]  ";
                                echo "[<a href=\"".$_SERVER['PHP_SELF']."?dir=$dir&action=copyicon&ren=1&outpath=$outpath&fname=$file&w=$w&h=$h\" target=_blank>CR</a>]  ";
                                echo "[<a href=\"".$_SERVER['PHP_SELF']."?dir=$dir&action=deleteicon&outpath=$outpath&fname=$file&w=$w&h=$h\">D</a>]  ";

                            }
    echo "</td>";
                                // echo "<br>";
                        $dir_count++;
                        $tr=$tr+1; if($tr>8) {
                            $tr=0;
                            echo "</tr><tr>";
                        }
                    }
                }
            }
    }
    echo "</table>";

//    echo "$dir_count pictures found...<br>";

}

$x=$_REQUEST['dir'];

show_images("./$x");
?>