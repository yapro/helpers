Helpers
---

Classes for solving the most common problems + functions that does not exist in native PHP.

![lib tests](https://github.com/yapro/helpers/actions/workflows/main.yml/badge.svg)

Tests
------------
```sh
docker build -t yapro/helpers:latest -f ./Dockerfile ./
docker run --rm -v $(pwd):/app yapro/helpers:latest bash -c "cd /app \
  && composer install --optimize-autoloader --no-scripts --no-interaction \
  && /app/vendor/bin/phpunit /app/tests --stderr --stop-on-incomplete --stop-on-failure --stop-on-warning --fail-on-warning --stop-on-risky --fail-on-risky -v /app/tests"
```

Dev
------------
```sh
docker build -t yapro/helpers:latest -f ./Dockerfile ./
docker run -it --rm --net=host -v $(pwd):/app -w /app yapro/helpers:latest bash
composer install -o
PHP_IDE_CONFIG="serverName=common" XDEBUG_SESSION=common XDEBUG_MODE=debug XDEBUG_CONFIG="client_port=9003 max_nesting_level=200" /app/vendor/bin/phpunit /app/tests
```
Если с xdebug что-то не получается, напишите: php -dxdebug.log='/tmp/xdebug.log' и смотрите в лог.

- https://xdebug.org/docs/upgrade_guide
- https://www.jetbrains.com/help/phpstorm/2021.1/debugging-a-php-cli-script.html
