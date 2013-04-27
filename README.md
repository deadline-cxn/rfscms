RFS CMS - Really Frickin Simple Content Management System.
 
This project is available free of charge,
and you may use it and modify it under the GNU GPL v3.

The RFS CMS home page is http://www.sethcoder.com/
If you have any questions, comments or suggestions,
please use the forum, email me: defectiveseth@gmail.com,
or contact me on twitter @Sethcoder.

INSTALLATION:
=================================================================
You must set up Apache2, PHP, and MySQL prior to installation.
See the official wiki for more information about how to set this up.

There are two ways to install. GIT and ZIP.

GIT:
====
If you use git to install, you gain the benefit of git pull updates.
One caveat to this is that you must not alter any of the files from
the repository. If you do, the admin update feature will not work 
untill you remove or stash those modified files.

mkdir /path/to/your/www
cd /path/to/your/www
git init
git remote add github git://github.com/sethcoder/rfscms.git
git pull github master
chmod 777 config

ZIP:
====
I plan on adding zip updating to the update feature in admin. As of
right now, the only way to use the admin update feature is by using git.

Download the zip file:
https://github.com/sethcoder/rfscms/archive/master.zip
Unzip the folder to the root of your www folder.
cd /path/to/your/www
chmod 777 config

After you have the files, simply use your browser and navigate to
your domain. The install script should take it from there.

If you haven't already done so, you can click on the phpmyadmin
link and update your database to include a valid user and table 
for RFSCMS to use.

Please note, this install has only been tested using linux, if you
use other OS configurations, please let me know if it works ok, or
if you encounter problems.

================================================================

Some parts of this project, the 3rdparty folder in particular,
are from other projects. I have made the best effort of
providing documentation, and disclosure every where I can when it 
comes to these 3rd party sources. Other parts, such as icons have
all been checked to ensure they are available to redistribute with
projects such as this one. If you feel there is a mistake, there
is copyrighted content, or I have left out important information,
please contact me, and I will make sure these issues are corrected.

Thanks,
Seth
