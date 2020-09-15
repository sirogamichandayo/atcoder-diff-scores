#!/bin/bash
DAYOFWEEK=`date +%w`

if [ $DAYOFWEEK -eq 2 ]; then
    php artisan command:updatedb
else
    echo "tt"
fi