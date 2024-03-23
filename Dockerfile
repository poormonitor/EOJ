FROM ubuntu:22.04

ARG CN="N"
ARG CN_MIRROR="mirrors.bfsu.edu.cn"

RUN [ "$CN" != "N" ] && \
    sed -i "s@//.*archive.ubuntu.com@//${CN_MIRROR}@g" /etc/apt/sources.list || true

RUN apt-get -y update  && \
    apt-get -y upgrade && \
    DEBIAN_FRONTEND=noninteractive \
    apt-get -y install --no-install-recommends \
    git nano nginx mariadb-server mariadb-client default-libmysqlclient-dev \
    php-common php-fpm php-mysql php-memcached php-gd php-zip php-mbstring \
    php-xml make flex gcc g++ openjdk-11-jdk python3 python3-pip sqlite3

RUN [ "$CN" != "N" ] &&  \
    pip config set global.index-url "https://${CN_MIRROR}/pypi/web/simple" || true

ADD . /trunk/

ADD docker/ /opt/docker/

RUN bash /opt/docker/setup.sh

VOLUME [ "/volume" ]

ENTRYPOINT [ "/bin/bash", "/opt/docker/entrypoint.sh" ]
