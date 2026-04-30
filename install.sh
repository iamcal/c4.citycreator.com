#!/bin/bash

cd /var/www/html/c4.citycreator.com
(< /dev/urandom tr -dc A-Za-z0-9 | head -c${1:-32};echo) > secrets/session_crypto_key
(< /dev/urandom tr -dc A-Za-z0-9 | head -c${1:-40};echo) > secrets/duo_app_key

ln -s /var/www/html/c4.citycreator.com/site.conf /etc/apache2/sites-available/c4.citycreator.com.conf
a2ensite c4.citycreator.com
service apache2 reload

cd db
./init_db.sh

