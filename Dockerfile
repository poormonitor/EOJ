FROM ubuntu:20.04

RUN sed -i 's/archive.ubuntu.com/mirrors.ustc.edu.cn/g' /etc/apt/sources.list && \
    apt-get -y update  && \
    apt-get -y upgrade && \
    DEBIAN_FRONTEND=noninteractive \
    apt-get -y install --no-install-recommends \
        nginx \
        mariadb-server \
        mariadb-client \
        memcached \
        libmariadb-dev \
        php-common \
        php-fpm \
        php-mysql \
        php-memcached \
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
