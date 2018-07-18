#!/usr/bin/env bash

set -e
set -x

CACHE_DIR="${HOME}/.download_cache/"
BUILD_DIR="/tmp/magento2"

if [ -z $TRAVIS_BUILD_DIR ]; then TRAVIS_BUILD_DIR=`pwd`; fi
if [ -z $TRAVIS_COMMIT ]; then TRAVIS_COMMIT=`git rev-parse HEAD`; fi
if [ -z $MAGENTO_VERSION ]; then MAGENTO_VERSION="2.1.3"; fi
if [ -z $MAGENTO_DB_HOST ]; then MAGENTO_DB_HOST="localhost"; fi
if [ -z $MAGENTO_DB_PORT ]; then MAGENTO_DB_PORT="3306"; fi
if [ -z $MAGENTO_DB_USER ]; then MAGENTO_DB_USER="root"; fi
if [ -z $MAGENTO_DB_PASS ]; then MAGENTO_DB_PASS=""; fi
if [ -z $MAGENTO_DB_NAME ]; then
    MAGENTO_DB_NAME="magento";
fi

CACHE_FILE="${CACHE_DIR}magento-${MAGENTO_VERSION}.tar.gz"

MYSQLPASS=""
if [ ! -z $MAGENTO_DB_PASS ]; then MYSQLPASS="-p${MAGENTO_DB_PASS}"; fi

mkdir -p ${BUILD_DIR}
mkdir -p ${CACHE_DIR}

if [ ! -f "$CACHE_FILE" ]; then
    wget "http://magento.mirror.hypernode.com/releases/magento-${MAGENTO_VERSION}.tar.gz" -O $CACHE_FILE
fi

tar xzf $CACHE_FILE -C /tmp/magento2

find src/Test/Fixtures -type f -print0 | xargs -0 -n 1 sed -i -e "s/MAGENTO_DB_HOST/${MAGENTO_DB_HOST}/g"
find src/Test/Fixtures -type f -print0 | xargs -0 -n 1 sed -i -e "s/MAGENTO_DB_PORT/${MAGENTO_DB_PORT}/g"
find src/Test/Fixtures -type f -print0 | xargs -0 -n 1 sed -i -e "s/MAGENTO_DB_USER/${MAGENTO_DB_USER}/g"
find src/Test/Fixtures -type f -print0 | xargs -0 -n 1 sed -i -e "s/MAGENTO_DB_PASS/${MAGENTO_DB_PASS}/g"
find src/Test/Fixtures -type f -print0 | xargs -0 -n 1 sed -i -e "s/MAGENTO_DB_NAME/${MAGENTO_DB_NAME}/g"

cp -v src/Test/Fixtures/env.php "${BUILD_DIR}/app/etc/env.php"
cp -v src/Test/Fixtures/config.php "${BUILD_DIR}/app/etc/config.php"
cp -v src/Test/Fixtures/install-config-mysql.php "${BUILD_DIR}/dev/tests/integration/etc/install-config-mysql.php"
#cp -v src/Test/Fixtures/phpunit.xml "${BUILD_DIR}/dev/tests/integration/phpunit.xml"

zip --exclude=node_modules/* --exclude=vendor/* --exclude=.git/* -r build.zip src composer.json

REPOSITORY_CONFIG="{\"type\": \"package\",\"package\": { \"name\": \"itonomy/module-backup\", \"version\": \"master\", \"dist\": {\"type\": \"zip\",\"url\": \"${TRAVIS_BUILD_DIR}/build.zip\",\"reference\": \"master\" }, \"autoload\": {\"files\": [\"src/registration.php\"],\"psr-4\": {\"Itonomy\\\\Backup\\\\\": \"\"}}}}"

if [ -d "$HOME/.cache/composer/files/itonomy/" ]; then
    rm -rf $HOME/.cache/composer/files/itonomy/;
fi

( cd "${BUILD_DIR}/" && composer config minimum-stability dev )
( cd "${BUILD_DIR}/" && composer config repositories.itonomy "${REPOSITORY_CONFIG}" )
( cd "${BUILD_DIR}/" && composer require itonomy/module-backup --ignore-platform-reqs )

mysql -u${MAGENTO_DB_USER} ${MYSQLPASS} -h${MAGENTO_DB_HOST} -P${MAGENTO_DB_PORT} -e "DROP DATABASE IF EXISTS \`${MAGENTO_DB_NAME}\`; CREATE DATABASE \`${MAGENTO_DB_NAME}\`;"
#mysql -u${MAGENTO_DB_USER} ${MYSQLPASS} -h${MAGENTO_DB_HOST} -P${MAGENTO_DB_PORT} ${MAGENTO_DB_NAME} < Test/Fixtures/fixtures.sql

chmod 777 "${BUILD_DIR}/var/"
chmod 777 "${BUILD_DIR}/pub/"
chmod 777 "${BUILD_DIR}/vendor/phpunit/phpunit/phpunit"

( cd ${BUILD_DIR} && php -d memory_limit=2048M bin/magento setup:upgrade )
