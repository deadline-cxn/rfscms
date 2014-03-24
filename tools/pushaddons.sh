cd /var/www/modules
echo "PUSHING BULLETLOG\n"
cd rfscms_bullet_log
gpush 'update'
echo "PUSHING GNS3\n"
cd ../rfscms_gns3
gpush 'update'
echo "PUSHING NETMAN\n"
cd ../rfscms_netman
gpush 'update'
echo "PUSHING NETMAN TOOLS\n"
cd ../rfscms_netman_tools
gpush 'update'
echo "PUSHING NQT\n"
cd ../rfscms-nqt
gpush 'update'
echo "PUSHING PG\n"
cd ../rfscms_political_game
gpush 'update'
echo "PUSHING FT\n"
cd ../rfsft
gpush 'update'



