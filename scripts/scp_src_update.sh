#!/bin/sh
SRC_PATH=/home/appname/$1
DEST_PATH=/home/appname/$1
if [ -d $DEST_PATH ]; then
  DEST_PATH=$DEST_PATH/../
fi
scp -rp tenda10@appname.hlsys.net:$SRC_PATH $DEST_PATH
find $DEST_PATH -type d -name ".svn" -exec rm -rf {} \;
chmod -R a+r /home/appname/$1
chmod -R u+w /home/appname/$1
