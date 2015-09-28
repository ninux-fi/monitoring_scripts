#!/bin/sh

NEIGHS=/tmp/neighs.txt
PINGS=10
PINGFOLDER=/www/ping
PINGFILE=$PINGFOLDER/ping.csv
PINGFILE_BAK=/tmp/ping.csv.bak
TXTINFO_URL="http://localhost:2006/links"
MAX_LINES=1000

mkdir -p $PINGFOLDER

#curl $TXTINFO_URL > $NEIGHS
wget -q -O $NEIGHS $TXTINFO_URL 
NOW=`date +"%Y-%m-%d %H:%H"`

tail -n $MAX_LINES $PINGFILE > $PINGFILE_BAK
mv $PINGFILE_BAK $PINGFILE

LINKS=`cat $NEIGHS | grep -v IP | grep -v "Table"`;
IFS=$'\n'
for link in $LINKS; do
 echo $link
 SIP=`echo $link |  cut -f 1`
 DIP=`echo $link |  cut -f 2`
 echo -n "$NOW $SIP $DIP" >> $PINGFILE;
 ping -c $PINGS -q $DIP | grep "max" | cut -d= -f 2 | sed "s/\// /g" | sed "s/ms//g" >> $PINGFILE
done;
IFS=$' '

rm $NEIGHS
