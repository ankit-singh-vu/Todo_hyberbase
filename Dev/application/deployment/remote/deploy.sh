#!/usr/bin/env bash

rancher-compose --project-name Application \
    --url $RANCHER_DEPLOYMENT_URL \
    --access-key $RANCHER_DEPLOYMENT_ACCESS_KEY \
    --secret-key $RANCHER_DEPLOYMENT_SECRET_KEY up -d --force-upgrade --confirm-upgrade --pull
