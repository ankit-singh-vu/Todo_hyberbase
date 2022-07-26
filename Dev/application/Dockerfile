FROM php:7.4.8-apache-buster
MAINTAINER Dyutiman Chakraborty

WORKDIR /var/www/html

COPY application /var/www/html/application
COPY bin /var/www/html/bin
COPY core /var/www/html/core
COPY library /var/www/html/library
COPY public /var/www/html/public
COPY resources /var/www/html/resources
COPY tests /var/www/html/tests
COPY composer.json /var/www/html/composer.json

RUN apt-get update && apt-get install -y wget git zip unzip mariadb-client-10.3 sshpass

# Install additional extensions
RUN apt-get install -y libpq-dev \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo pdo_pgsql pgsql pdo_mysql mysqli

RUN cd /var/www/html && \
    mkdir -p /opt/vendor && ln -s /opt/vendor ./ && \
    wget https://getcomposer.org/composer.phar && \
    php composer.phar install && \
    #php public/index.php dockerbuild install && \
    rm -R /etc/apache2/sites-available && \
    cp -R bin/sites-available /etc/apache2 && \
    a2enmod rewrite && \
    chmod +x ./bin/run.sh

EXPOSE 80
CMD ["./bin/run.sh"]

