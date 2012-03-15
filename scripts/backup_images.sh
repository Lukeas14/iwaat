#!/bin/bash

#backup images
IMG_DIR=/var/www/iwaat/public_html/images/apps/
s3cmd sync /var/www/iwaat/public_html/images/apps/ $S3_BUCKET/images/apps/
