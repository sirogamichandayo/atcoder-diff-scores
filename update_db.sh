#!/bin/bash
DAYOFWEEK=`date +%w`
HOUROFDAY=`date +%H`

if [ $DAYOFWEEK -eq 2 ] ; then
    /app/.heroku/bin/heroku restart -a atcoder-dsum
    php artisan command:updatedb
fi