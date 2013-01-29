#!/bin/bash
php composer.phar install

# clear, create db and load data
php app/console doctrine:database:create
php app/console doctrine:schema:create

# second database
#php app/console doctrine:database:create --connection=auto_catalog
#php app/console doctrine:schema:create --em=auto_catalog

# load fixtures
# php app/console doctrine:fixtures:load -n

# clear cache
php app/console cache:clear -e=prod

# assets install
php app/console assetic:dump
