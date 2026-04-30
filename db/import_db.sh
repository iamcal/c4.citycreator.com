#!/bin/bash

DB_NAME="c4.citycreator"
DB_USER="c4.citycreator"

cd "$(dirname "$0")"

DB_PASS=`cat ../secrets/mysql_password`

mysqldump --add-drop-table --no-data -u${DB_USER} -p${DB_PASS} ${DB_NAME} | grep 'DROP TABLE' | mysql -u${DB_USER} -p${DB_PASS} -D${DB_NAME}

$(cat ./*.sql | mysql -u${DB_USER} -p${DB_PASS} -D${DB_NAME})
