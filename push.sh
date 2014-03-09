#!/bin/bash
rm /var/www/modules/videos/cache/*.jpg
tools/cleanthemes.sh
ver=$(grep 'v' include/version.php | sed 's/[^0-9.]//g')
va=$(<build.dat);
va=`expr $va + 1`;
echo $va > build.dat;
echo "RFS CMS $ver BUILD $va";
git commit -a -m "update $1"
git push github

