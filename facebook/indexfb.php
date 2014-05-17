<?
chdir("../");
include("include/lib.all.php");

if($action=="uploadpic") {
    ?>
    <form  enctype="multipart/form-data" action="indexfb.php" method="post">
    <input type=hidden name=action value=uploadpicgo>
    <input type=hidden name=MAX_FILE_SIZE value=93000000>
    <input name="userfile" type="file" size=80>
    <input type=hidden name=sfw value=yes>
    <input type=hidden name=hidden value=no>
    <input type=hidden name=category value=meme>
    <input type="submit" name="submit" value="Upload Picture">
    </form> <?
    exit();
}

if($action=="uploadpicgo"){
    
    $furl="files/pictures/".$_FILES['userfile']['name'];
    
    $furl =str_replace("//","/",$furl);
    $fiurl="/home/dminds1/public_html/".$furl;
    if(move_uploaded_file($_FILES['userfile']['tmp_name'], $fiurl)){
        $error="File is valid, and was successfully uploaded. ";
        $error.="It was stored as [$fiurl]\n";
        $xp_ext = explode(".",$_FILES['userfile']['name'],40);
        $j = count ($xp_ext)-1;
        $ext = "$xp_ext[$j]";
        $filetype=strtolower($ext);
        $filesizebytes=$_FILES['userfile']['size'];
        $time1=date("Y-m-d H:i:s");
        $description=addslashes($description);
        // if(empty($name))
        $name=date("YmdHis");
        $sname=$name;
        $poster=999;
        if($data->id) $poster=$data->id;
        lib_mysql_query("INSERT INTO `pictures` (`name`) VALUES('$name');");
        $ciddeliberate_errorfetch_object(lib_mysql_query("select * from categories where name = '$category'"));
        lib_mysql_query("update `pictures` set `category`='$cid->id'   where `name`='$name'");
        lib_mysql_query("update `pictures` set `sname`='$sname'        where `name`='$name'");
        lib_mysql_query("update `pictures` set `sfw`='$sfw'            where `name`='$name'");
        lib_mysql_query("update `pictures` set `hidden`='$hidden'      where `name`='$name'");
        lib_mysql_query("update `pictures` set description='$desc'     where name='$name'");
        lib_mysql_query("update `pictures` set poster='$poster'        where name='$name'");
        $furl=addslashes($furl);
        lib_mysql_query("update `pictures` set url = '$furl'           where name='$name'");
        lib_mysql_query("update `pictures` set time = '$time1' where   name='$name'");
        $error.= " ---- Added $name to database ---- ";
    }
    else{
        $error ="File upload error!";
        echo "File upload error! [\n";
        echo $_FILES['userfile']['name'];
        echo "][";
        echo $_FILES['userfile']['error'];
        echo "][";
        echo $_FILES['userfile']['tmp_name'] ;
        echo "][";
        echo $uploadFile;
        echo "]\n";
    }
    if(!$error){
        $error .= "No files have been selected for upload";
    }
    echo $error;
    
    ?>
    <script>
    
    parent.$('#example-placeholder').html("<img src=\"<? echo $RFS_SITE_URL."/".$furl; ?>\">");
    
    parent.document.getElementById("upload-button").src="http://www.defectiveminds.com/facebook/indexfb.php?action=uploadpic";
    
    
    </script>
    <?
    exit();
}





if($goback==1) $SESSION['goback']=1;
// include("fb.login.php");

echo "<html><head>";
lib_rfs_echo( "$RFS_SITE_JS_JQUERY");
echo "<title>$RFS_SITE_NAME</title></head>    
<body style='background-color: #ffffff;'> <center>";
$text="Defective Minds";$font="TenOClock.ttf";$fontsize=25;$w = 512;$h =85;$ox=0;$oy=0;$inicr = 255;$inicg = 255;$inicb = 0;$inbcr = 15;$inbcg = 15;$inbcb = 0;$forcerender = 1;$forceheight = 1;
lib_images_text( $text, $font,$fontsize, $w,$h,$ox,$oy, $inicr,$inicg,$inicb, $inbcr,$inbcg,$inbcb, $forcerender, $forceheight);
$text="Facebook Meme Generator";$font="TenOClock.ttf";$fontsize=25;$w = 512;$h =85;$ox=0;$oy=0;$inicr = 255;$inicg = 255;$inicb = 0;$inbcr = 15;$inbcg = 15;$inbcb = 0;$forcerender = 1;$forceheight = 1;
lib_images_text( $text, $font,$fontsize, $w,$h,$ox,$oy, $inicr,$inicg,$inicb, $inbcr,$inbcg,$inbcb, $forcerender, $forceheight);
if($dbg=="off") { $_SESSION['debug_msgs']=false; }
if($dbg=="on") { $_SESSION['debug_msgs']=true; }
//fs_debugfooter(0);

// if($data->id) { echo "<BR>Logged in as $data->name <BR>"; echo "Visit the main website <a href=http://www.defectiveminds.com/>http://www.defectiveminds.com/</a>"; }


$r=lib_mysql_query("select * from meme where status = 'SAVED'");

echo "
<hr>

<table border=0><tr><td>

<div id=\"memecreate\"></div>


<iframe frameborder=0 id=upload-button></iframe>
<div id=\"save-button\"></div>
<div id=\"change-font\"></div>
<div id=\"change-font-color\"></div>
<div id=\"change-top-text\"></div>
<div id=\"change-bottom-text\"></div>

<div id=\"status\"></div>

</td><td>
<div id=\"example-placeholder\"></div>
</td></td></tr></table>";

?>
<script>
// fill with
$('#memecreate').html('<input type=\"button\" onclick=\"memestart()\" value=\"Create New\" name=\"create\" />');

// $('#upload-button').load
document.getElementById("upload-button").src='http://www.defectiveminds.com/facebook/indexfb.php?action=uploadpic';

/// <form method=post enctype=multipart/form-data action=pics.php method=post><input type=hidden name=action value=uploadpicgo><input type=hidden name=MAX_FILE_SIZE value=93000000><input name=userfile type=file></form>' );

$('#save-button').html('<input type=\"button\" onclick=\"savememe()\" value=\"Save Meme\" name=\"savememe\" />');



function uploadfile() {
    $('#status').html("<p> file:"+document.getElementById('userfile').value+"</p>" );
    // $('#status').html(url('http://www.defectiveminds.com/modules/core_memes/memes.php?action=uploadpicgo&userfile='+);
}


function memestart() {
    $('#example-placeholder').html('<p><img src="../images/ajax-loader.gif" width="220" height="19" /></p>');
    $('#example-placeholder').load("http://www.defectiveminds.com/modules/core_memes/memes.php?a=ms&id=328");
    document.getElementById('create').value="Upload an Image";
}



</script>
