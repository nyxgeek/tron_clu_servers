#!/bin/bash

#
# this is a moving window of number found in the last 12 hours.
# helps to see generally how you are doing numbers-wise


time=`date -d '12 hours ago' +%s`
mysql -bN -u root onedrive_db -BNe "select COUNT(email_address) from onedrive_enum WHERE scrape_date_unix>$time;" >> /var/www/html/spool_latest.txt
tail -n 288 /var/www/html/spool_latest.txt | gnuplot -p -e 'set term png size 900,250;unset xtics;set grid;set out "/var/www/html/last48hours.png";set title "Usernames Found Over 48 Hour Period\n{/*0.7 Moving window of the total found over last 12 hours, polled every 10 minutes}";plot "/dev/stdin" notitle;set out'

echo -n "<P>Total Count: " > /var/www/html/count.txt
COUNT=`mysql -BN -u root onedrive_db -e 'select COUNT(*) from onedrive_enum;'`
printf "%'d" $COUNT >> /var/www/html/count.txt
