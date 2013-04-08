<?


system("sudo umount /home/parson/.gvfs");
system("umount /home/parson/.gvfs");
system("rm -R /home/parson/.compiz");
system("rm -R /home/parson/.compiz-1");
system("rm -R /home/parson/.gnome2");
system("rm -R /home/parson/.gnome2_private");
system("rm -R /home/parson/.pork");
system("rm -R /home/parson/.netbeans");
system("rm -R /home/parson/.w3m");
system("rm -R /home/parson/.xchat2");
system("rm -R /home/parson/.cache");
system("rm -R /home/parson/.thumbnails");
system("rm -R /home/parson/.kde");
system("rm -R /home/parson/.gvfs");
system("rm -R /home/parson/.gconf");
system("rm -R /home/parson/.gegl_0.0");
system("rm -R /home/parson/.mozilla");

$zzzout="7z a /backup/".$dt.".home.parson.7z /home/parson -mx9";
system($zzzout);

?>


