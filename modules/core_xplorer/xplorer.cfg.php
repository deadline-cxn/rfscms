<?


$xp_admin_password = "123";

#######################################################################
# Where is xplorer (URL)

$xp_url="$RFS_SITE_URL/modules/core_xplorer";

#######################################################################
# Where is xplorer images (URL)

$xp_images_url = "$xp_url/images/xp_images";

#######################################################################
# Where xplorer is located locally

$xp_path=$RFS_SITE_PATH;

#######################################################################
# Where your files are located on the local hard drive (top folder)

$xp_local_path = $RFS_SITE_PATH;
$xp_local_url  = $RFS_SITE_URL;


#######################################################################
# Link to an edit file function
# default is to use built in 

$xp_edit_file_link = "$xp_url/xplorer.php?action=editfileform&file=";
$xp_edit_file_link_ = "$RFS_SITE_URL/adm.php?action=edit_file&infile=";

$xp_width=1000;

#######################################################################
# Thumbnail width

$xp_thumbwidth="50";

#######################################################################
# Show .nfo in description

$xp_nfo = "on";

#######################################################################
# Background image  (comment out for no background image)

$xp_background = "images/back1.jpg";


#######################################################################
# File type descriptions

$xp_filetypes = array (

                       "doc" =>"MS Word Document",
                       "rtf" =>"Rich Text File",
                       "htm" =>"Hyper text markup language file",
                       "html"=>"Hyper text markup language file",
                       "php" =>"PHP Script",
                       "pl"  =>"Perl Script",
                       "cgi" =>"Perl Script",
                       "asp" =>"Active server page script",
                       "pdf" =>"Adobe Acrobat Document",
                       "txt" =>"Text Document",
                       "xls" =>"Excel Spreadsheet",
                       "jpg" =>"JPEG Image",
                       "bmp" =>"MS Bitmap Image",
                       "png" =>"PNG Image",
                       "gif" =>"GIF Image",
                       "mpg" =>"MPEG Video File",
                       "mpeg"=>"MPEG Video File",
                       "asf" =>"ASF Video File",
                       "avi" =>"AVI video file",
                       "mp3" =>"MP3 Audio File",
                       "exe" =>"Executable",
                       "tar" =>"TAR file",
                       "gz"  =>"GZip file",
                       "tgz" =>"Tar Gzipped file",
                       "cab" =>"Windows CAB File",
                       "7z"  =>"7-Zip File",
                       "zip" =>"PKZip File",
                       "swf" =>"Flash Movie",
                       "dll" =>"Windows Dynamic Link Library",
                       "cpp" =>"C++ Source Code",
                       "c"   =>"C Source Code",
                       "h"   =>"C Header File",
# add more here if you wish
                       );



#######################################################################
# File type icons

$xp_icons = array (
                       "doc" =>"doc.gif",
                       "rtf" =>"doc.gif",
                       "htm" =>"htm.gif",
                       "html"=>"htm.gif",
                       "php" =>"php.gif",
                       "pl"  =>"script.gif",
                       "cgi" =>"script.gif",
                       "asp" =>"script.gif",
                       "pdf" =>"doc.gif",
                       "txt" =>"doc.gif",
                       "xls" =>"xls.gif",
                       "jpg" =>"image.gif",
                       "bmp" =>"image.gif",
                       "png" =>"image.gif",
                       "gif" =>"image.gif",
                       "mpg" =>"movie.gif",
                       "mpeg"=>"movie.gif",
                       "asf" =>"movie.gif",
                       "avi" =>"movie.gif",
                       "mp3" =>"mp3.gif",
                       "exe" =>"exe.gif",
                       "tar" =>"gz.gif",
                       "gz"  =>"gz.gif",
                       "tgz" =>"gz.gif",
                       "cab" =>"gz.gif",
                       "7z"  =>"zip.gif",
                       "zip" =>"zip.gif",
                       "swf" =>"swf.gif",
                       "dll" =>"win.gif",
                       "cpp" =>"doc.gif",
                       "c"   =>"doc.gif",
                       "h"   =>"doc.gif",
);

?>
