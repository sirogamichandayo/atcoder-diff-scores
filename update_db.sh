#!/bin/bash
DAYOFWEEK=`date +%w`
HOUROFDAY=`date +%H`

if [ $HOUROFDAY -eq 0 ] ; then
    /app/.heroku/bin/heroku restart -a atcoder-dsum
    php artisan command:updatedb
else
    echo "tt"
fi