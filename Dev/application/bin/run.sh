#!/usr/bin/env bash

while ! mysqladmin ping -h pxc --silent; do
    echo "Waiting for PXC to come online ...."
    sleep 5
done
echo "PXC is now online".

. ./bin/install.sh application
apache2-foreground
