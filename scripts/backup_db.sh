#!/bin/bash

source /var/www/iwaat/scripts/config.sh

#backup database
DB_FILENAME=iwaat-db-backup_${DATE}.sql.gz
touch $DB_FILENAME
mysqldump -u jliwaatwesley --password="eiof2\!J\@Issd\$I6kfsd4" --replace --single-transaction iwaat | gzip > $DB_FILENAME
s3cmd put $DB_FILENAME $S3_BUCKET/database/$DB_FILENAME
rm $DB_FILENAME

echo "Done."