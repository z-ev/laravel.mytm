#!/usr/bin/env bash

touch /etc/nginx/conf.d/docker.conf
envsubst '$SERVER_NAME, $PROJECT_PATCH, $ROOT, $CONFIG, $PHP_HOST, $NODEJS_HOST' < /etc/nginx/conf.d/docker.conf.template > /etc/nginx/conf.d/docker.conf
nginx -g "daemon off;"
