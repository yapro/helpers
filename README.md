Helpers
---

Classes for solving the most common problems + functions that does not exist in native PHP.

Tests
------------
```sh
docker build -t yapro/helpers:latest -f ./Dockerfile ./
docker run --rm -v $(pwd):/app yapro/helpers:latest bash -c "cd /app \
  && composer install --optimize-autoloader --no-scripts --no-interaction \
  && /app/vendor/bin/phpunit /app/tests"
```

Dev
------------
```sh
docker build -t yapro/helpers:latest -f ./Dockerfile ./
docker run -it --rm -v $(pwd):/app -w /app yapro/helpers:latest bash
composer install -o
```
