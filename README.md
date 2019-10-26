# Web Jump - Shop
A simple application to save Products and Categories.

docker run --rm --interactive --tty --volume $PWD:/app --user $(id -u):$(id -g) composer require --dev phpunit/phpunit ^6.5
docker run --rm --interactive --tty --volume $PWD:/app --user $(id -u):$(id -g) composer require doctrine/orm:2.6.4
docker run --rm --interactive --tty --volume $PWD:/app --user $(id -u):$(id -g) composer require zendframework/zend-config-aggregator
docker run --rm --interactive --tty --volume $PWD:/app --user $(id -u):$(id -g) composer require zendframework/zend-servicemanager

docker run -it --rm -v "$PWD":/app -w /app php:7.3-cli ./vendor/phpunit/phpunit/phpunit


http://localhost:9090/