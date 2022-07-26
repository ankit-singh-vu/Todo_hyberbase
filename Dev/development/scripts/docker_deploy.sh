#!/bin/bash -x

repo_address=${1:-registry.origin.triophase.com:5005}
repo_username=${2:-deployment}
repo_password=${3:-dSAUDm9aSCrT3AcdLRqx5Shb}

cd /mnt/components/red-ops-development
docker-compose up -d







