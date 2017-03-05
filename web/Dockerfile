FROM centos:6

MAINTAINER inocop

RUN rpm -ivh http://dl.fedoraproject.org/pub/epel/6/i386/epel-release-6-8.noarch.rpm
RUN rpm -ivh http://rpms.famillecollet.com/enterprise/remi-release-6.rpm
RUN yum install -y httpd
RUN yum install --enablerepo=epel,remi-php56,remi -y \
                              php \
                              php-devel \
                              php-mbstring \
                              php-xml \
                              php-pecl-xdebug \
                              php-gd

# set vhost.conf
COPY ./vhost.conf /etc/httpd/conf.d/vhost.conf

# set custom_php.ini
COPY ./custom_php.ini /etc/php.d/

# setting remote debug
ARG HOST_IP_ADDR
RUN sed -i -e "s/{{HOST_IP_ADDR}}/$HOST_IP_ADDR/g" /etc/php.d/custom_php.ini

# install composer
WORKDIR /tmp
RUN curl -sS https://getcomposer.org/installer | php
RUN mv composer.phar /usr/local/bin/composer


CMD ["/usr/sbin/apachectl", "-D", "FOREGROUND"]
