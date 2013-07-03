#!/bin/sh
export PGCLIENTENCODING=UTF8
PGUSER=db-user
DBHOST=db-host
DBNAME=db-name

#vacuumdb -v -z -f -a
#reindexdb dgcomic
#sleep 5

TODAY=`(set \`date +%w%H\`; echo $1)`
pg_dump -U${PGUSER} -h${DBHOST} ${DBNAME} | gzip > /home/somedir/backup/${DBNAME}_$TODAY.dmp.gz
