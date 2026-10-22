#!/bin/sh
set -eu

# Preserve existing local configuration and data.
if [ -e .env ]; then
    exit 0
fi

cp .env.example .env
perl -p -i -e "s/# FILESYSTEM_DRIVER=local/FILESYSTEM_DRIVER=local/g" .env
perl -p -i -e "s/DB_CONNECTION=mysql/DB_CONNECTION=sqlite/g" .env
perl -p -i -e "s/DB_HOST=localhost/# DB_HOST=localhost/g" .env
perl -p -i -e "s/DB_DATABASE=homestead/DB_DATABASE=storage\/app\/database.sqlite/g" .env
perl -p -i -e "s/DB_USERNAME=homestead/# DB_USERNAME=homestead/g" .env
perl -p -i -e "s/DB_PASSWORD=secret/# DB_PASSWORD=secret/g" .env
perl -p -i -e "s/APP_KEY=SomeRandomKey!!!/APP_KEY=ItoXUel9itUUllTs6qvucyUyAmHt1zBWzQoGkc0dUEGgtrT2lzcIVYFoL5NOZ0W/g" .env
