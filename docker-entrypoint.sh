#!/bin/bash
set -e

# Create SQLite database file if not exists
DATABASE_FILE="/var/www/html/database/database.sqlite"
if [ ! -f "$DATABASE_FILE" ]; then
  touch "$DATABASE_FILE"
fi

# Run migrations
php artisan migrate --force

# Start Apache server
apache2-foreground