#!/usr/bin/env bash

cp ./deployment/testing/Dockerfile ./
docker build -t wpforeverapp:testing .
docker run --name wpforeverapp_testing wpforeverapp:testing
docker rm wpforeverapp_testing > /dev/null
docker rmi -f wpforeverapp:testing > /dev/null

rm ./Dockerfile