#!/bin/bash
php bin/console doctrine:migrations:migrate --no-interaction
/start-container.sh