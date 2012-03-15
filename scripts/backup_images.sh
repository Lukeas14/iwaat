#!/bin/bash

source /var/www/iwaat/scripts/config.sh

#backup images
IMG_DIR=/var/www/iwaat/public_html/images/apps/
s3cmd sync /var/www/iwaat/public_html/images/apps/ $S3_BUCKET/images/apps/

echo "Done."