#!/bin/bash
# Checks if there's a composer.json, and if so, installs/runs composer.

set -eu

cd /opt/app

test -e ./cache.orig || (cp -r cache{,.orig} && rm -rf cache && ln -s /var/phpbb/cache .)
test -e ./files.orig || (cp -r files{,.orig} && rm -rf files && ln -s /var/phpbb/files .)
test -e ./store.orig || (cp -r store{,.orig} && rm -rf store && ln -s /var/phpbb/store .)
test -L ./config.php || (rm config.php && ln -s /var/phpbb/config.php .)

# if [ -f /opt/app/composer.json ] ; then
#     if [ ! -f composer.phar ] ; then
#         curl -sS https://getcomposer.org/installer | php
#     fi
#     php composer.phar install
# fi
