# Technical stack
Laravel
PostgresSQL
Redis
Pgadmin
Nginx

# Clone project
git clone git@github.com:fluentech-group/laravel_template.git <project-name>
cd <project-name>

# Copy .env
copy .env.example => .env

# Build & run docker service
docker compose up -d

# Check all service are running good
docker ps -f name=laravel
=> should appear 5 service (app, web, postgres, redis, pgadmin)

# Install dependencies
docker exec -it laravel-app sh
composer install

# Permissions
sudo chmod -R 777 storage/
