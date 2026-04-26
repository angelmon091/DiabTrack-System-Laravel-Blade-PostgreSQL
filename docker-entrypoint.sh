#!/bin/bash
set -e

# Clear any existing cache to ensure fresh environment variables
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Link storage
php artisan storage:link --force

# Wait for DB connection
if [ "$DB_CONNECTION" = "mysql" ] || [ "$DB_CONNECTION" = "pgsql" ]; then
    echo "Waiting for $DB_CONNECTION to be ready..."
    max_tries=30
    count=0
    
    # Direct PHP connection test - more reliable than artisan monitor during first boot
    until php -r "try { DB::connection()->getPdo(); exit(0); } catch (Exception \$e) { exit(1); }" > /dev/null 2>&1; do
        sleep 2
        count=$((count + 1))
        if [ $count -gt $max_tries ]; then
            echo "Error: Database not ready after 60 seconds."
            # We don't exit here anymore to let the app try to start anyway
            break
        fi
    done
    echo "Database check finished."
fi

# Run migrations
echo "Running migrations..."
php artisan migrate --force --no-interaction

# Run seeders only if needed (Manual recommendation: run once during deployment)
# echo "Running seeders..."
# php artisan db:seed --force

# Cache configuration for production performance
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set the correct port for Render
PORT=${PORT:-80}
sed -ri -e "s!80!$PORT!g" /etc/apache2/ports.conf
sed -ri -e "s!:80!:$PORT!g" /etc/apache2/sites-available/*.conf

# Execute CMD from Dockerfile
exec "$@"
