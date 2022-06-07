cd /home/judge/hoj
git pull
cd /home/judge/hoj/core/
systemctl stop judged
chmod a+x make.sh
bash make.sh
systemctl start judged