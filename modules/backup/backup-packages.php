<?

$fpkg    = "/backup/".$dt.".packages.sh";
$fetcapt = "/backup/".$dt.".packages.etc.7z";

echo "Zipping up /etc/apt\n";
system("7z a $fetcapt /etc/apt -mx9");

echo "Creating $fpkg...\n";

$fp=fopen($fpkg,"wt");
 if($fp){
 fwrite($fp,"echo 'Installing packages'\n");
 fwrite($fp,"sudo apt-get update\n");
 fwrite($fp,"sudo apt-get install p7zip-full\n");
 fwrite($fp,"sudo 7z x $fetcapt /\n");

 fwrite($fp,"sudo add-apt-repository ppa:otto-kesselgulasch/gimp\n");

 fwrite($fp,"sudo apt-key adv --fetch-keys http://www.codelite.co.uk/CodeLite.asc\n");
 fwrite($fp,"sudo echo 'deb http://www.codelite.co.uk/ubuntu/ precise universe' >> /etc/apt/sources.list\n");
 fwrite($fp,"sudo echo 'deb-src http://www.codelite.co.uk/ubuntu/ precise universe' >> /etc/apt/sources.list\n");

 fwrite($fp,"sudo wget -q http://www.webmin.com/jcameron-key.asc\n");
 fwrite($fp,"sudo apt-key add jcameron-key.asc\n");
 fwrite($fp,"sudo apt-get update\n");
 fclose($fp);
}

system("dpkg-query -Wf 'sudo apt-get install \${Package} -y\n' | sort -n >> $fpkg");

?>
