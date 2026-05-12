#!/bin/bash
install-php-extensions pdo pdo_mysql
php bin/console doctrine:migrations:migrate --no-interaction
/start-container.sh