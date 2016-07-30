cd ../modules
echo "PUSHING BULLET LOG\n"
cd bullet_log
git remote add github git@github.com:sethcoder/bullet_log.git
git add ./*
gpush 'update'
echo "PUSHING GNS3\n"
cd ../gns3
git remote add github git@github.com:sethcoder/gns3.git
git add ./*
gpush 'update'
echo "PUSHING NETMAN\n"
cd ../netman
git remote add github git@github.com:sethcoder/netman.git
git add ./*
gpush 'update'
echo "PUSHING NETMAN TOOLS\n"
cd ../netman_tools
git remote add github git@github.com:sethcoder/netman_tools.git
git add ./*
gpush 'update'
echo "PUSHING NQT\n"
cd ../nqt
git remote add github git@github.com:sethcoder/nqt.git
git add ./*
gpush 'update'
echo "PUSHING POLITICAL GAME\n"
cd ../political_game
git remote add github git@github.com:sethcoder/political_game.git
git add ./*
gpush 'update'
echo "PUSHING FITNESS TRACKER\n"
cd ../fitness_tracker
git remote add github git@github.com:sethcoder/fitness_tracker.git
git add ./*
gpush 'update'
echo "PUSHING ONE LINERS\n"
cd ../oneliners
git remote add github git@github.com:sethcoder/oneliners.git
git add ./*
gpush 'update'

