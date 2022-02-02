set -xe

# Mysql
mkdir -p /var/run/mysqld
chown -R mysql:mysql /var/run/mysqld
chmod -R 755         /var/run/mysqld
service mysql start
mysql < /trunk/install/db.sql
mysql -e "insert into jol.privilege ( user_id, rightstr ) values('admin','administrator');"

# Hustoj basic file system
useradd -m -u 1536 judge
mkdir -p /home/judge/etc
mkdir -p /home/judge/data
mkdir -p /home/judge/log
mkdir -p /home/judge/backup
mkdir -p /var/log/hustoj
mv /trunk /home/judge/src
chmod -R 700 /home/judge/etc
chmod -R 700 /home/judge/backup
chmod -R 700 /home/judge/src/web/include/
chown -R www-data:www-data /home/judge/data
chown -R www-data:www-data /home/judge/src/web 

# Judge daemon and client
sh /home/judge/src/core/make.sh

# Adjust system configuration
CPU=`grep "cpu cores" /proc/cpuinfo |head -1|awk '{print $4}'`
USERNAME=`cat /etc/mysql/debian.cnf |grep user    |head -1|awk  '{print $3}'`
PASSWORD=`cat /etc/mysql/debian.cnf |grep password|head -1|awk  '{print $3}'`
mv /home/judge/src/web/include/db_info.default.php /home/judge/src/web/include/db_info.inc.php
cp /home/judge/src/install/java0.policy  /home/judge/etc/
cp /home/judge/src/install/judge.conf    /home/judge/etc/
cp /home/judge/src/install/hustoj        /etc/init.d/hustoj
cp /home/judge/src/install/default.conf  /etc/nginx/sites-available/default
sed -i "s#OJ_USER_NAME[[:space:]]*=[[:space:]]*root#OJ_USER_NAME=$USERNAME#g"    /home/judge/etc/judge.conf
sed -i "s#OJ_PASSWORD[[:space:]]*=[[:space:]]*root#OJ_PASSWORD=$PASSWORD#g"      /home/judge/etc/judge.conf
sed -i "s#OJ_COMPILE_CHROOT[[:space:]]*=[[:space:]]*1#OJ_COMPILE_CHROOT=0#g"     /home/judge/etc/judge.conf
sed -i "s#OJ_RUNNING[[:space:]]*=[[:space:]]*1#OJ_RUNNING=$CPU#g"                /home/judge/etc/judge.conf
sed -i "s#OJ_SHM_RUN[[:space:]]*=[[:space:]]*1#OJ_SHM_RUN=0#g"                   /home/judge/etc/judge.conf
sed -i "s#127.0.0.1:9000#unix:/var/run/php/php7.4-fpm.sock#g"    /etc/nginx/sites-available/default
sed -i "s#DB_USER[[:space:]]*=[[:space:]]*\"root\"#DB_USER=\"$USERNAME\"#g"                  /home/judge/src/web/include/db_info.inc.php
sed -i "s#DB_PASS[[:space:]]*=[[:space:]]*\"root\"#DB_PASS=\"$PASSWORD\"#g"                  /home/judge/src/web/include/db_info.inc.php
chmod a+x /etc/init.d/hustoj
update-rc.d hustoj defaults
