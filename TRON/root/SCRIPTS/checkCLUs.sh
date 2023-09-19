#!/bin/bash
while read line; do echo "$line:";ssh -oStrictHostKeyChecking=no root@"$line" "ps aux | grep onedrive | grep -v grep | grep -E -o 'python3.{1,}' | cut -d ' ' -f2-" </dev/null; done < /root/CLIENTS.txt
