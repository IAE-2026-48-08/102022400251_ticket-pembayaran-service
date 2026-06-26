#!/bin/sh

# Set storage and bootstrap cache permissions
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# Copy .env if not exists
if [ ! -f ".env" ]; then
    echo "Creating .env file..."
    cp .env.example .env
    php artisan key:generate
fi

# Handle database connection setup
if [ "$DB_CONNECTION" = "sqlite" ]; then
    echo "Using SQLite database. Ensuring database file exists..."
    mkdir -p database
    touch database/database.sqlite
    chmod -R 777 database
    chown -R www-data:www-data database
else
    # Wait for MySQL database connection
    echo "Waiting for database connection..."
    until php -r "
    try {
        \$dbh = new PDO('mysql:host=' . getenv('DB_HOST') . ';port=' . getenv('DB_PORT') . ';dbname=' . getenv('DB_DATABASE'), getenv('DB_USERNAME'), getenv('DB_PASSWORD'));
        exit(0);
    } catch (PDOException \$e) {
        exit(1);
    }
    "; do
        echo "Database not ready yet, retrying..."
        sleep 2
    done
    echo "Database is ready!"
fi

# Run migrations
echo "Running migrations..."
php artisan migrate --force

# Execute container's main command
exec "$@"
