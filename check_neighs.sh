

#!/bin/sh

NEIGHS=/tmp/neighs.txt
PINGS=10
PINGFOLDER=/tmp/www/ping
PINGFILE=$PINGFOLDER/ping.csv
TXTINFO_URL="http://tetto.bellanzer2.fi.nnx:2006/links"

mkdir -p $PINGFOLDER

curl $TXTINFO_URL > $NEIGHS
NOW=`date +"%Y-%m-%d %H:%H"`

LINKS=`cat $NEIGHS | grep -v IP | grep -v "Table"`;
IFS=$'\n'
for link in $LINKS; do
 echo $link
 SIP=`echo $link |  cut -f 1`
 DIP=`echo $link |  cut -f 2`
 echo -n "$NOW $SIP $DIP" >> $PINGFILE;
 ping -c $PINGS -q $DIP | grep "mdev" | cut -d= -f 2 | sed "s/\// /g" | sed "s/ms//g" >> $PINGFILE
done;
IFS=$' '

rm $NEIGHS

