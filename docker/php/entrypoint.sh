#!/bin/sh
set -e

if [ ! -d vendor ]; then
    composer install --no-interaction --optimize-autoloader
fi

exec "$@"
