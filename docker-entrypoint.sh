#!/bin/ash
set -e

python /paperless/src/manage.py migrate

exec "$@"
