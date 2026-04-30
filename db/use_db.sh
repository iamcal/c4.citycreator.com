#!/bin/bash

DB_NAME="c4.citycreator"
DB_USER="c4.citycreator"

cd "$(dirname "$0")"

DB_PASS=`cat ../secrets/mysql_password`

mysql -u${DB_USER} -p${DB_PASS} -D${DB_NAME}
