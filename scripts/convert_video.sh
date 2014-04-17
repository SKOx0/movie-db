#!/bin/sh

QUALITY="${1}"
FILENAME="${2}"
ORIG="${3}"
EMAIL="${4}"

if [ "${QUALITY}" = "1080p HD" ]; then
	WIDTH=1920
fi

if [ "${QUALITY}" = "720p HD" ]; then
	WIDTH=1280
	BITRATE=1000
	BUFFSIZE=2000
fi

if [ "${QUALITY}" = "SD" ]; then
	WIDTH=640
	BITRATE=500
	BUFFSIZE=1000
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
	touch converting
	EMAILMESSAGE="/tmp/movie_db_converter_email.txt"
	echo "From: \"Movie DB\" <movies@virajchitnis.com>" > ${EMAILMESSAGE}
	echo "Subject: Converting ${FILENAME}" >> ${EMAILMESSAGE}
	echo "MIME-Version: 1.0" >> ${EMAILMESSAGE}
	echo "Content-Type: text/plain" >> ${EMAILMESSAGE}
	echo " " >> ${EMAILMESSAGE}
	echo "The conversion of ${FILENAME} to ${QUALITY} has started. You will receive another email informing you of its completion." >> ${EMAILMESSAGE}
	sendmail ${EMAIL} < ${EMAILMESSAGE}
	echo " "
	echo "Conversion of ${FILENAME} started at $(date)"
	echo " "
	nice -n 10 ffmpeg -threads 0 -i "movies/${SRCQUALITY}/${FILENAME}" -b:v ${BITRATE}k -maxrate ${BITRATE}k -bufsize ${BUFFSIZE}k -vf scale=${WIDTH}:-1 "converted/${QUALITY}/.${FILENAME}"
	mv "converted/${QUALITY}/.${FILENAME}" "converted/${QUALITY}/${FILENAME}"
	echo " "
	echo "Conversion of ${FILENAME} finished at $(date)"
	echo " "
	echo "From: \"Movie DB\" <movies@virajchitnis.com>" > ${EMAILMESSAGE}
	echo "Subject: ${FILENAME} converted!" >> ${EMAILMESSAGE}
	echo "MIME-Version: 1.0" >> ${EMAILMESSAGE}
	echo "Content-Type: text/plain" >> ${EMAILMESSAGE}
	echo " " >> ${EMAILMESSAGE}
	echo "The ${QUALITY} version of ${FILENAME} is ready." >> ${EMAILMESSAGE}
	sendmail ${EMAIL} < ${EMAILMESSAGE}
	rm converting
fi