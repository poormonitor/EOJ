FROM ubuntu:20.04

ARG CN="N"

RUN [ "$CN" != "N" ] && sed -i 's@//.*archive.ubuntu.com@//mirrors.ustc.edu.cn@g' /etc/apt/sources.list

RUN apt-get -y update  && \
    apt-get -y upgrade && \
    DEBIAN_FRONTEND=noninteractive \
    apt-get -y install --no-install-recommends \
        git nano nginx mariadb-server mariadb-client libmysqlclient-dev \
        default-libmysqlclient-dev libmysql++-dev php-common php-fpm \
        php-mysql php-memcached php-gd php-zip php-mbstring php-xml \
        make flex gcc g++ openjdk-11-jdk python3 python3-pip sqlite3
    
RUN [ "$CN" != "N" ] && pip config set global.index-url https://pypi.tuna.tsinghua.edu.cn/simple

ADD . /trunk/

ADD docker/ /opt/docker/

RUN bash /opt/docker/setup.sh

VOLUME [ "/volume" ]

ENTRYPOINT [ "/bin/bash", "/opt/docker/entrypoint.sh" ]
