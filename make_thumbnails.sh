#!/bin/sh

cd /var/www/chitnis.no-ip.org/movies/posters/

if [ ! -d backup ]; then
        mkdir backup
fi

for f in *.jpg
do
        if [ -f "${f}" ]; then
                if [ ! -f backup/"${f}" ]; then
                        mv "${f}" backup/"${f}"
                        convert -strip -interlace Plane -thumbnail 40.5 backup/"${f}" "${f}"
                fi
        fi
done

echo "Created movie poster thumbnails!"
