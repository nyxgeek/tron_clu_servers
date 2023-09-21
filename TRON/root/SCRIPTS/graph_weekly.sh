#!/bin/bash
#generate new data for last hour and append

currentdate=`date --date="-${num} hour" +"%m-%d-%Y:%H:%M"`
currentdateseconds=`date +"%s"`
timeend=`date --date="1 hour ago" +"%s"`

COUNT=`mysql -bN -u root onedrive_db -BNe "select COUNT(email_address) from onedrive_enum WHERE scrape_date_unix BETWEEN $timeend AND $currentdateseconds;"`

echo "$currentdate $COUNT" >> /var/www/html/spool_hourly_totals.txt

tail -n 168 /var/www/html/spool_hourly_totals.txt | gnuplot -p -e 'set term png size 900,250;set grid;set xdata time;set timefmt "%m-%d-%Y:%H:%M";set out "/var/www/html/last168hours.png";set title "Usernames Found per Hour in Last 1 week\n{/*0.7 Each bar represents one hour}";plot "/dev/stdin" using 1:2 with boxes notitle;set out'
