#!/bin/bash

# Create necessary directories
mkdir -p logs
mkdir -p uploads
mkdir -p storage/cache
mkdir -p storage/sessions

# Set permissions
chmod 755 logs
chmod 755 uploads
chmod 755 storage
chmod 755 storage/cache
chmod 755 storage/sessions

# Create log files
touch logs/error.log
touch logs/access.log
chmod 666 logs/error.log
chmod 666 logs/access.log

# Install dependencies
composer install

# Create .env file if it doesn't exist
if [ ! -f .env ]; then
    cp .env.example .env
    echo "Created .env file from .env.example"
fi

# Create database if it doesn't exist
mysql -u root -e "CREATE DATABASE IF NOT EXISTS bms;"

# Import database schema
if [ -f database/schema.sql ]; then
    mysql -u root bms < database/schema.sql
    echo "Imported database schema"
fi

# Install PHPUnit
composer require --dev phpunit/phpunit

# Create test database
mysql -u root -e "CREATE DATABASE IF NOT EXISTS bms_test;"
if [ -f database/schema.sql ]; then
    mysql -u root bms_test < database/schema.sql
    echo "Created test database"
fi

echo "Development environment setup complete!" 