#!/bin/sh

docker-compose run --user $(id -u):$(id -g) nogues-php ./vendor/phpunit/phpunit/phpunit