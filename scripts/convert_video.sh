#!/bin/sh

QUALITY="${1}"
FILENAME="${2}"
EMAIL="${3}"

if [ "${QUALITY}" = "1080p HD" ]; then
	SRCQUALITY="iTunes Movies (1080p HD)"
	WIDTH=1920
fi

if [ "${QUALITY}" = "720p HD" ]; then
	SRCQUALITY="iTunes Movies (720p HD)"
	WIDTH=1280
fi

if [ "${QUALITY}" = "SD" ]; then
	SRCQUALITY="iTunes Movies (SD)"
	WIDTH=640
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
	ffmpeg -threads 2 -qscale:v 2 -i movies/"${SRCQUALITY}"/"${FILENAME}" scale=${WIDTH}:-1 -strict -2 converted/"${QUALITY}"/."${FILENAME}"
	mv converted/"${QUALITY}"/."${FILENAME}" converted/"${QUALITY}"/"${FILENAME}"
	echo "From: \"Movie DB\" <movies@virajchitnis.com>" > ${EMAILMESSAGE}
	echo "Subject: ${FILENAME} converted!" >> ${EMAILMESSAGE}
	echo "MIME-Version: 1.0" >> ${EMAILMESSAGE}
	echo "Content-Type: text/plain" >> ${EMAILMESSAGE}
	echo " " >> ${EMAILMESSAGE}
	echo "The ${QUALITY} version of ${FILENAME} is ready." >> ${EMAILMESSAGE}
	sendmail ${EMAIL} < ${EMAILMESSAGE}
	rm converting
fi

php -f converter.php