#!/bin/bash
# remember to have tronserver added to your /etc/hosts file!

rsync -av wordlists@tronserver:/home/wordlists/USERNAMES/ /root/USERNAMES/ --delete
rsync -av wordlists@tronserver:/home/wordlists/DOMAINS/ /root/DOMAINS/ --delete
rsync -av wordlists@tronserver:/home/wordlists/onedrive_enum.py /root/onedrive_enum.py
rsync -av wordlists@tronserver:/home/wordlists/sync_lists.sh /root/sync_lists.sh
rsync -av wordlists@tronserver:/usr/games/clients/ /usr/games/
