# Web Jump - Shop
A simple application to save Products and Categories.

docker run --rm --interactive --tty --volume $PWD:/app --user $(id -u):$(id -g) composer require --dev phpunit/phpunit ^6.5
docker run --rm --interactive --tty --volume $PWD:/app --user $(id -u):$(id -g) composer require doctrine/orm:2.6.4
docker run --rm --interactive --tty --volume $PWD:/app --user $(id -u):$(id -g) composer require symfony/yaml
docker run --rm --interactive --tty --volume $PWD:/app --user $(id -u):$(id -g) composer require zendframework/zend-config-aggregator
docker run --rm --interactive --tty --volume $PWD:/app --user $(id -u):$(id -g) composer require zendframework/zend-servicemanager
docker run --rm --interactive --tty --volume $PWD:/app --user $(id -u):$(id -g) composer require zendframework/zend-inputfilter
docker run --rm --interactive --tty --volume $PWD:/app --user $(id -u):$(id -g) composer require ramsey/uuid-doctrine
docker run --rm --interactive --tty --volume $PWD:/app --user $(id -u):$(id -g) composer require symfony/console ^4

docker-compose run --user $(id -u):$(id -g) nogues-php ./vendor/phpunit/phpunit/phpunit --stop-on-failure


http://localhost:9090/
http://localhost:8080/