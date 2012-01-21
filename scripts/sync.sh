#!/bin/bash

DATE=$(date +%F)
S3_BUCKET=s3:\/\/iwaat-backup

#sync database
FILENAME=iwaat-db-backup_${DATE}.sql
s3cmd get --force $S3_BUCKET/database/$FILENAME.gz $FILENAME.gz
gunzip $FILENAME
mysql -u jiwaatlucas --password="j23waati\$lucas" iwaat < $FILENAME
rm $FILENAME

#sync images
s3cmd sync $S3_BUCKET/images/apps/ /var/www/iwaat/public_html/images/apps/