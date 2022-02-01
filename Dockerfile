FROM ubuntu:18.04

ARG APT_MIRROR="Y"
ARG APT_CA="Y"

RUN [ "$APT_CA" = "Y" ] && apt-get -y update && apt install -y ca-certificates  || true

# Linux: Aliyun Apt Mirrors.
RUN [ "$APT_MIRROR" != "N" ] && sed -i 's/archive.ubuntu.com/mirrors.ustc.edu.cn/g' /etc/apt/sources.list || true

RUN apt-get -y update  && \
    apt-get -y upgrade && \
    DEBIAN_FRONTEND=noninteractive \
    apt-get -y install --no-install-recommends \
        nginx \
        mysql-server \
        libmysqlclient-dev \
        libmysql++-dev \
        php-common \
        php-fpm \
        php-mysql \
        php-gd \
        php-zip \
        php-mbstring \
        php-xml \
        make \
        flex \
        gcc \
        g++ \
        openjdk-11-jdk

COPY core /trunk/core
COPY web /trunk/web
COPY install /trunk/install

COPY docker/ /opt/docker/

RUN bash /opt/docker/setup.sh

# VOLUME [ "/volume", "/home/judge/backup", "/home/judge/data", "/home/judge/etc", "/home/judge/web", "/var/lib/mysql" ]
VOLUME [ "/volume" ]

ENTRYPOINT [ "/bin/bash", "/opt/docker/entrypoint.sh" ]