#!/bin/sh
set -e

(crontab -l 2>/dev/null; echo "* * * * * cd /var/www && php bin/console rabbitmq-scheduler:run") | crontab -

[ $# -eq 0 ] && /usr/sbin/crond -f -L /dev/stdout || exec "$@"