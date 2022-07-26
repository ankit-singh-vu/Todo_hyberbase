#!/usr/bin/env bash

/etc/init.d/mysql start
/etc/init.d/apache2 start
echo Creating primary Database ...
mysql -u root -proot -h localhost -e "create database wpforever_application"

echo Installing Dependencies ....

chmod +x /var/www/bin/install.sh
sh /var/www/bin/install.sh localhost
echo Installtion completed. Starting Tests ...

phantomjs --webdriver=4444 --ignore-ssl-errors=true > /tmp/ph.log &
export WD_PID=$!
sleep 3

./codecept.phar run --xml --no-exit

kill $WD_PID