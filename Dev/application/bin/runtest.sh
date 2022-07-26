#!/usr/bin/env bash

phantomjs --webdriver=4444 --ignore-ssl-errors=true > /tmp/ph.log &
export WD_PID=$!
echo $WD_PID

#./codecept.phar run
./codecept.phar run --xml --no-exit

kill $WD_PID