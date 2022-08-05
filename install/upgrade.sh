cd /home/judge/eoj
git pull
cd /home/judge/eoj/core/
systemctl stop judged
chmod a+x make.sh
bash make.sh
systemctl start judged