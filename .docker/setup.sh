#!/bin/bash

openssl genrsa -out private.key 2048
openssl rsa -in private.key -pubout -out public.key
chmod 600 private.key public.key
chown www-data:www-data private.key public.key
echo -e "OAUTH2_ENCRYPTION_KEY='$(php -r 'echo base64_encode(random_bytes(32)), PHP_EOL;')'" >> .env
composer install
php bin/console doctrine:migrations:migrate --no-interaction
apache2-foreground