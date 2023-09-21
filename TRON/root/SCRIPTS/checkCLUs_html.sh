#!/bin/bash
echo "<HTML><TABLE>"
while read line; do echo "<TR><TD><B>$line</B></TD></TR>"; echo "<TR><TD><CODE>";ssh -oStrictHostKeyChecking=no root@"$line" "ps aux | grep 'onedrive\|teams_enum\|guestlist' | grep -v grep | grep -E -o 'python3.{1,}' | cut -d ' ' -f2- | sed 's,/root/USERNAMES/,,g'" </dev/null; echo "</CODE></TD></TR>";done < /root/CLIENTS.txt
echo "</TABLE>"
echo ""
cat /var/www/html/count.txt
echo "<P><HR><CODE>"

echo "</CODE></HTML>"
