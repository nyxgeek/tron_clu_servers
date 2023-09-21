#!/bin/bash
#
# this script will graph our daily total count
#


# get our total found
COUNT=`mysql -bN -u root onedrive_db -BNe "select COUNT(email_address) from onedrive_enum;"`

DATE=`date +"%m-%d-%Y"`

# add it to our spool_totals.txt file which will be read in by gnuplot
echo "$DATE $COUNT" >> /var/www/html/spool_totals.txt

gnuplot -p -e 'set term png size 900,900;set format y "%.0f";set grid;set xdata time;set timefmt "%m-%d-%Y";set xtics rotate;set ytics 1000000;set format y "%.1s %c";set out "/var/www/html/total_found.png";set title "Total Usernames Found";plot "/var/www/html/spool_totals.txt" using 1:2 with lines notitle;set out'
