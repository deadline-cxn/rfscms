grep -nR \
--exclude-dir=develop \
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
--exclude-dir=not_included \
"$1" ../* | xargs sed -i 's/$1/$2/g'

