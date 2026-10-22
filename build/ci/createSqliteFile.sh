#!/bin/sh
set -eu

# Preserve existing local configuration and data.
if [ -e storage/app/database.sqlite ]; then
    exit 0
fi

cp storage/app/database.sqlite.dist storage/app/database.sqlite
