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
Clone the repository. Replace www with the name of the folder where 
you want the clone.

> git clone https://github.com/sethcoder/rfscms.git www

> sudo chmod -R 777 www


ZIP:
====
Download the zip file and unzip the folder to the root of your www folder.

> wget https://github.com/sethcoder/rfscms/archive/master.zip

> sudo chmod -R 777 (unzipped folder location)

After you have the files installed, simply use your browser and navigate to
your domain. The install script should take it from there.

If you haven't already done so, you can click on the phpmyadmin
link and update your database to include a valid user and table 
for RFSCMS to use.

Please note, this install has only been tested using linux, and only
limited testing with Windows. If you use other OS configurations,
please let me know if it works ok, or if you encounter problems.

NOTES:
====
In order for this package to work properly, you must edit your php.ini
file to make the following changes.

short_open_tags=on

In the error reporting I recommend adding the following onto the end
~E_NOTICE
This should take care of the Notice messages.

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
