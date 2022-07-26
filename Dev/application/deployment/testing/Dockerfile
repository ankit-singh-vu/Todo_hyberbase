FROM dyutiman/hbplatform:wf1

RUN rm -R /var/www/public && apt-get -y install php5-rrd

COPY application /var/www/application/
COPY bin /var/www/bin/
COPY catalog /var/www/catalog/
COPY defaults /var/www/defaults/
COPY deployment /var/www/deployment/
COPY deployment/testing/data /var/www/resources/data
COPY env /var/www/env/
COPY library /var/www/library/
COPY public /var/www/public/
COPY resources /var/www/resources/
COPY tests /var/www/tests/

COPY codeception.yml /var/www/codeception.yml
COPY composer.json /var/www/composer.json
COPY functions.php /var/www/functions.php
COPY init.php /var/www/init.php
COPY platform.php /var/www/platform.php
COPY routes.php /var/www/routes.php

RUN cp /var/www/resources/config.template.php /var/www/resources/config.php

RUN sed -i 's:{{db_name}}:wpforever_application:' /var/www/resources/config.php && \
    sed -i 's:{{db_user}}:root:' /var/www/resources/config.php && \
    sed -i 's:{{db_password}}:root:' /var/www/resources/config.php && \
    sed -i 's:{{db_host}}:localhost:' /var/www/resources/config.php && cd /var/www

RUN echo "1" > /var/www/new.release
WORKDIR /var/www

CMD sh deployment/testing/buildtest.sh























