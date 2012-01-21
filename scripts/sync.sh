#!/bin/bash

DATE=$(date +%F)
FILENAME=iwaat-db-backup_${DATE}.sql
s3cmd get --force s3://iwaat-backup/$FILENAME.gz $FILENAME.gz
gunzip $FILENAME
mysql -u root -p iwaat < $FILENAME