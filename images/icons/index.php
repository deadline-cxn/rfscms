<?

function show_images($dir){
    
    
        
    $dir_count=0;
    $dirfiles = array();
    $handle=@opendir($dir) or die("Unable to open filepath monkeys");
    while (false!==($file = readdir($handle))) array_push($dirfiles,$file);
    closedir($handle);    
    reset($dirfiles);

    while(list ($key, $file) = each ($dirfiles))
    {
        if($file!=".")
            if($file!="..")
            {
                $file="$dir/$file";
                $file=str_replace("//","/",$file);
                $file=str_replace("./","",$file);
                if(is_dir($file)){
                    echo "FOLDER: $file<br>";
                    show_images($file);
                }
                else{
                    echo "<img src=\"./$file\"> ($file)<br>";
                    $dir_count++;
                }
            }
    }

    echo "$dir_count pictures found...<br>";

}

show_images("./");
?>