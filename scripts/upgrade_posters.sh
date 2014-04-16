#!/bin/sh

if [ -d posters ]; then
	cd posters

	for f in *.jpg
	do
		if [ -f "${f}" ]; then
			if [ -d backup ]; then
				rm "${f}"
				convert -strip -interlace Plane -thumbnail 130 backup/"${f}" "${f}"
			fi
		fi
	done
fi