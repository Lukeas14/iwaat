#!/bin/bash

DATE=$(date +%F)
S3_BUCKET=s3:\/\/iwaat-backup

#backup database
DB_FILENAME=iwaat-db-backup_${DATE}.sql.gz
touch $DB_FILENAME
mysqldump -u iwaat-backup --password="idk239i0ef@sf32" --replace --single-transaction iwaat | gzip > $DB_FILENAME
s3cmd put $DB_FILENAME $S3_BUCKET/database/$DB_FILENAME
rm $DB_FILENAME

#backup app images
IMG_DIR=/var/www/iwaat/public_html/images/apps/
s3cmd sync $IMG_DIR $S3_BUCKET/images/apps/

#IMG_FILENAME=app-images_${DATE}.gz
#touch $IMG_FILENAME
#tar -czf $IMG_FILENAME --totals $IMG_DIR
#s3cmd put $IMG_FILENAME s3://iwaat-backup/$IMG_FILENAME
#rm $IMG_FILENAME