#!/bin/bash

# Wait for MySQL to be ready
#while ! mysqladmin ping -h"dicnovel_db" --silent; do
#    echo "Waiting for database connection..."
#    sleep 2
#done

# Install Composer dependencies
php composer.phar install

# Set proper permissions for storage and cache directories
chown -R www-data:www-data /var/www/html/storage
chmod -R 755 /var/www/html/storage

mkdir -p /var/www/html/public/xml
chown -R www-data:www-data /var/www/html/public/xml
chmod -R 755 /var/www/html/public/xml

# Start Apache
apache2-foreground
