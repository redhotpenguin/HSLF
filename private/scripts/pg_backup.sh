#!/bin/sh
set -e
TAG="$HOSTNAME_$(TZ=UTC date +%Y-%m-%d_%H:%M:%S_UTC)"

BUCKET="wmmobile/backups"

# postgresql conf
PG_DB_NAME="mobile_advocacy_platform"
PG_USER="postgres"
DB_FILENAME="$PG_DB_NAME.$TAG.sql.gz"
DB_FILEPATH="/tmp/$DB_FILENAME"

echo "Postgresql Database: $PG_DB_NAME"

pg_dump -U $PG_USER $PG_DB_NAME --no-owner | gzip > "$DB_FILEPATH"

s3cmd put "$DB_FILEPATH" "s3://$BUCKET/DB/"
