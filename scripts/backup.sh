#!/bin/bash

DATE=$(date +%F)
FILENAME=iwaat-db-backup_${DATE}.sql.gz
touch $FILENAME
mysqldump -u iwaat-backup --password="idk239i0ef@sf32" --single-transaction iwaat | gzip > $FILENAME
s3cmd put $FILENAME s3://iwaat-backup/$FILENAME
rm $FILENAME