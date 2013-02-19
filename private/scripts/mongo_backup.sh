#!/bin/sh
set -e
TAG="$HOSTNAME_$(TZ=UTC date +%Y-%m-%d_%H:%M:%S_UTC)"

BUCKET="wmmobile/backups"

# mongodb conf
MONGO_DB_NAME="mobile_advocacy_platform"
MONGO_DB_USER="map_user"
MONGO_DB_PASS="jeMEwRArEKwBg7Q"

FILENAME="$MONGO_DB_NAME.$TAG"
FILEPATH="/tmp/$FILENAME"

echo "MongoDB Database: $MONGO_DB_NAME"

mongodump --db $MONGO_DB_NAME -o $FILEPATH --username $MONGO_DB_USER --password $MONGO_DB_PASS

tar -cjf /tmp/$FILENAME.tar $FILEPATH

s3cmd put "/tmp/$FILENAME.tar" "s3://$BUCKET/DATA/"
