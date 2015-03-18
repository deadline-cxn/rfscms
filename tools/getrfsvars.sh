grep -Rn \
--exclude-dir=log \
--exclude-dir=wtf \
--exclude-dir=3rdparty \
--exclude-dir=files \
--exclude-dir=images \
--exclude-dir=images_not_included \
--exclude-dir=facebook \
--exclude-dir=backup \
--exclude-dir=log \
--exclude-dir=tools \
--exclude-dir=modules/netman \
'$RFS' ../* \
| awk -F '$' '{print $2}' | awk -F '=' '{print $1}' | sed 's/RFS_//g' \
| awk -F '(' '{print  $1}' | awk -F ')' '{print $1}' | awk -F ',' '{print $1}' \
| awk -F '/' '{print $1}' | awk -F '<' '{print $1}' | awk -F '"' '{print $1}' \
| awk -F '-' '{print $1}' | awk -F '[' '{print $1}' | awk -F ']' '{print $1}' \
| awk -F '\' '{print $1}' | awk -F "'" '{print $1}' | awk -F '!' '{print $1}' \
| awk -F '>' '{print $1}' | awk -F ' ' '{print $1}' | awk -F ';' '{print $1}' \
| awk -F '.' '{print $1}' | awk -F '?' '{print $1}' | awk -F ':' '{print $1}' \
| grep '_' \
| sed 's/^/$RFS_/' \
| sort | uniq



