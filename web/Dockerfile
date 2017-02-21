FROM centos:6

MAINTAINER inocop

RUN rpm -ivh http://dl.fedoraproject.org/pub/epel/6/i386/epel-release-6-8.noarch.rpm
RUN rpm -ivh http://rpms.famillecollet.com/enterprise/remi-release-6.rpm
RUN yum install -y httpd
RUN yum install --enablerepo=epel,remi-php56,remi -y \
                              php \
                              php-devel \
                              php-mbstring \
                              php-gd

# enable .htaccess
RUN sed -i -e "s/AllowOverride None/AllowOverride All/g" /etc/httpd/conf/httpd.conf

# set custom_php.ini
COPY ./custom_php.ini /etc/php.d/

CMD ["/usr/sbin/apachectl", "-D", "FOREGROUND"]
