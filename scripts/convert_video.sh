#!/bin/sh

QUALITY="${1}"
FILENAME="${2}"
ORIG="${3}"
EMAIL="${4}"

if [ "${QUALITY}" = "1080p HD" ]; then
	WIDTH=1920
	BITRATE=2400
	BUFFSIZE=4800
fi

if [ "${QUALITY}" = "720p HD" ]; then
	WIDTH=1280
	BITRATE=1200
	BUFFSIZE=2400
fi

if [ "${QUALITY}" = "SD" ]; then
	WIDTH=640
	BITRATE=600
	BUFFSIZE=1200
fi

if [ "${ORIG}" = "1080p HD" ]; then
	SRCQUALITY="iTunes Movies (1080p HD)"
fi

if [ "${ORIG}" = "720p HD" ]; then
	SRCQUALITY="iTunes Movies (720p HD)"
fi

if [ "${ORIG}" = "SD" ]; then
	SRCQUALITY="iTunes Movies (SD)"
fi

cd ..

if [ ! -d converted ]; then
	mkdir converted
fi

if [ ! -d converted/"${QUALITY}" ]; then
	mkdir converted/"${QUALITY}"
fi

if [ ! -f converted/"${QUALITY}/${FILENAME}" ]; then
	echo " "
	echo "Conversion of ${FILENAME} started at $(date)"
	echo " "
	nice -n 10 ffmpeg -threads 0 -i "movies/${SRCQUALITY}/${FILENAME}" -b:v ${BITRATE}k -maxrate ${BITRATE}k -bufsize ${BUFFSIZE}k -vf "scale=${WIDTH}:trunc(ow/a/2)*2" "converted/${QUALITY}/.${FILENAME}"
	mv "converted/${QUALITY}/.${FILENAME}" "converted/${QUALITY}/${FILENAME}"
	echo " "
	echo "Conversion of ${FILENAME} finished at $(date)"
	echo " "
fi