#!/bin/bash

DATE=$(date +%F)
S3_BUCKET=s3:\/\/iwaat-backup

#backup database
DB_FILENAME=iwaat-db-backup_${DATE}.sql.gz
touch $DB_FILENAME
mysqldump -u iwaat-backup --password="idk239i0ef@sf32" --replace --single-transaction iwaat | gzip > $DB_FILENAME
s3cmd put $DB_FILENAME $S3_BUCKET/database/$DB_FILENAME
rm $DB_FILENAME

#backup images
IMG_DIR=/var/www/iwaat/public_html/images/apps/
s3cmd sync /var/www/iwaat/public_html/images/apps/ $S3_BUCKET/images/apps/
