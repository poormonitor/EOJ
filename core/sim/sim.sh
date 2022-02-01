#!/bin/bash
EXTENSION=`echo "$1" | cut -d'.' -f2`
for i in ../data/$2/ac/*.$EXTENSION
do
        if [ ! -e "/usr/bin/sim_$EXTENSION" ]
        then
                EXTENSION="text";
        fi
        sim=`/usr/bin/sim_$EXTENSION -p $1 $i |grep ^$1|awk '{print $4}'`
        if [ ! -z $sim ] &&  [ $sim -eq 100 ]
        then
                sim_text=`/usr/bin/sim_text -p $1 $i |grep ^$1|awk '{print $4}'`
                if [ $sim_text -eq 100 ]
                then
                        sim="200"
                        sim_s_id=`basename $i`
                        echo "$sim $sim_s_id" >sim
                        exit $sim
                fi
        fi
        if [ ! -z $sim ] && [ $sim -gt 90 ]
        then
                sim_s_id=`basename $i`
                echo "$sim $sim_s_id" >sim
                exit $sim
        fi
done
exit 0;