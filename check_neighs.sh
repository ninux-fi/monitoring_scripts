#!/bin/sh

NEIGHS=/tmp/neighs.txt
PINGS=10
PINGFOLDER=/var/www/html/ping
PINGFILE=$PINGFOLDER/ping.csv
PINGFILE_BAK=/tmp/ping.csv.bak
TXTINFO_URL="http://localhost:2006/links"
MAX_LINES=100

mkdir -p $PINGFOLDER

#curl $TXTINFO_URL > $NEIGHS
wget -q -O $NEIGHS $TXTINFO_URL 
NOW=`date +"%Y-%m-%d %H:%M"`

if  [ -e $PINGFILE ]; then
 tail -n $MAX_LINES $PINGFILE > $PINGFILE_BAK
 mv $PINGFILE_BAK $PINGFILE
fi 

LINKS=`cat $NEIGHS | grep -v IP | grep -v "Table"`;
IFS=$'\n'
for link in $LINKS; do
 echo $link
 SIP=`echo $link |  cut -f 1`
 DIP=`echo $link |  cut -f 2`
 # ping, then get the stats and strip newline
 PINGRES=$(ping -c $PINGS -q $DIP | grep "max" | cut -d= -f 2 | \
		sed "s/\// /g" | sed "s/ms//g" | sed "s/\n//g")
 echo -n "$NOW $SIP $DIP" >> $PINGFILE;
 if [ -n "$PINGRES" ]; then # check if string is not null
    echo $PINGRES >> $PINGFILE;
 else
    echo " -1 -1 -1" >> $PINGFILE;
 fi

done;
IFS=$' '

rm $NEIGHS
