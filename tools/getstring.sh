grep -nR \
--exclude-dir=log \
--exclude-dir=wtf \
--exclude-dir=3rdparty \
--exclude-dir=files \
--exclude-dir=files_h \
--exclude-dir=files_b \
--exclude-dir=images \
--exclude-dir=images_not_included \
--exclude-dir=facebook \
--exclude-dir=backup \
--exclude-dir=tools \
--exclude-dir=modules/netman \
"$1" ../*
