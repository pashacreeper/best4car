#!/bin/bash
php composer.phar install

# clear, create db and load data
php app/console doctrine:schema:drop --force
php app/console doctrine:schema:create

#php app/console doctrine:schema:drop --force  --em=auto_catalog
#php app/console doctrine:schema:create --em=auto_catalog

# load fixtures
php app/console doctrine:fixtures:load -n --em=default

# clear cache
# php app/console cache:clear -e=test
# php app/console cache:clear -e=prod

# assets install
php app/console assetic:dump
