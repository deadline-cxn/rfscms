cd ../modules
echo "PUSHING BULLETLOG\n"
cd bullet_log
gpush 'update'
echo "PUSHING GNS3\n"
cd ../gns3
gpush 'update'
echo "PUSHING NETMAN\n"
cd ../netman
gpush 'update'
echo "PUSHING NETMAN TOOLS\n"
cd ../netman_tools
gpush 'update'
echo "PUSHING NQT\n"
cd ../nqt
gpush 'update'
echo "PUSHING PG\n"
cd ../political_game
gpush 'update'
echo "PUSHING FT\n"
cd ../fitness_tracker
gpush 'update'
