#!/bin/bash

# Create a bunch of folders under the clean /var that php, nginx, and mysql expect to exist
mkdir -p /var/lib/nginx
mkdir -p /var/log
mkdir -p /var/log/nginx
# Wipe /var/run, since pidfiles and socket files from previous launches should go away
# TODO someday: I'd prefer a tmpfs for these.
rm -rf /var/run
mkdir -p /var/run

mkdir -p /var/phpbb

test -e /var/phpbb/cache || cp -r /opt/app/cache.orig /var/phpbb/cache
test -e /var/phpbb/files || cp -r /opt/app/files.orig /var/phpbb/files
test -e /var/phpbb/store || cp -r /opt/app/store.orig /var/phpbb/store
# todo images/avatars/upload
test -e /var/phpbb/config.php || cp /opt/app/config.php.sandstorm /var/phpbb/config.php
test -e /var/phpbb/data.sqlite3 || cp /opt/app/data.sqlite3 /var/phpbb/

# Spawn mysqld, php
/usr/sbin/php5-fpm --nodaemonize --fpm-config /etc/php5/fpm/php-fpm.conf &
# Wait until php have bound their sockets, indicating readiness
while [ ! -e /var/run/php5-fpm.sock ] ; do
    echo "waiting for php5-fpm to be available at /var/run/php5-fpm.sock"
    sleep .2
done

# Start nginx.
/usr/sbin/nginx -g "daemon off;"
