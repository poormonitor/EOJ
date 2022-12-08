#!/bin/bash
apt-get update
apt install build-essential make flex clang libmariadb-dev libmysql++-dev -y
/usr/sbin/useradd -m -u 1536 judge
cd /home/judge/

git clone https://github.com/poormonitor/eoj.git
USER="jol"
PASSWORD=`date +%s | sha256sum | base64 | head -c 10 ; echo`
CPU=`grep "cpu cores" /proc/cpuinfo |head -1|awk '{print $4}'`

mkdir etc data log backup

cp eoj/web/include/db_info.default.php eoj/web/include/db_info.inc.php
cp eoj/install/java0.policy  /home/judge/etc
cp eoj/install/judge.conf  /home/judge/etc

if grep "OJ_SHM_RUN=0" etc/judge.conf ; then
	mkdir run0 run1 run2 run3
	chown judge run0 run1 run2 run3
fi

sed -i "s/OJ_USER_NAME=root/OJ_USER_NAME=$USER/g" etc/judge.conf
sed -i "s/OJ_PASSWORD=root/OJ_PASSWORD=$PASSWORD/g" etc/judge.conf
sed -i "s/OJ_COMPILE_CHROOT=1/OJ_COMPILE_CHROOT=0/g" etc/judge.conf
sed -i "s/OJ_RUNNING=1/OJ_RUNNING=$CPU/g" etc/judge.conf
sed -i "s/OJ_PYTHON_FREE=0/OJ_PYTHON_FREE=1/g" etc/judge.conf
sed -i "s/DB_USER[[:space:]]*=[[:space:]]*\"root\"/DB_USER=\"$USER\"/g" eoj/web/include/db_info.inc.php
sed -i "s/DB_PASS[[:space:]]*=[[:space:]]*\"root\"/DB_PASS=\"$PASSWORD\"/g" eoj/web/include/db_info.inc.php

chmod 755 backup
chmod 755 etc/judge.conf

COMPENSATION=`grep 'mips' /proc/cpuinfo|head -1|awk -F: '{printf("%.2f",$2/5000)}'`
sed -i "s/OJ_CPU_COMPENSATION=1.0/OJ_CPU_COMPENSATION=$COMPENSATION/g" etc/judge.conf

cd eoj/core
chmod +x ./make.sh
./make.sh

cp /home/judge/eoj/install/judged /etc/init.d/judged
chmod a+x /etc/init.d/judged
update-rc.d judged defaults
systemctl enable judged
systemctl start judged

echo "username:$USER"
echo "password:$PASSWORD"