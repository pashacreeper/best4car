rm -rf app/cache/* app/logs/*

#!/bin/bash
php composer.phar install

# clear, create db and load data
php app/console doctrine:schema:drop --force
php app/console doctrine:schema:create

# load fixtures
php app/console doctrine:fixtures:load -n

# clear cache
php app/console cache:clear -e=prod

# assets install
php app/console assetic:dump
