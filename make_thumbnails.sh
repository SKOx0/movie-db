#!/bin/sh

cd /var/www/html/chitnis.no-ip.org/movies/posters/

for f in *.jpg
do
	if [ -f "${f}" ]; then
		convert -strip -interlace Plane "${f}" prog_"${f}"
		mv prog_"${f}" "${f}"
	fi
done

echo "Posters converted to progressive."