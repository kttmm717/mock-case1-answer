init:
	docker compose up -d --build
	docker compose exec php bash -c "if [ ! -f /var/www/composer.json ]; then composer create-project 'laravel/laravel=8.*' . --prefer-dist; fi"
	docker compose exec php composer install
	docker compose exec php bash -c "if [ ! -f .env ]; then cp .env.example .env; fi"
	mkdir -p ./src/storage/app/public/img
	docker compose exec php php artisan key:generate
	docker compose exec php php artisan storage:link
	docker compose exec php chmod -R 777 storage bootstrap/cache

fresh:
	docker compose exec php php artisan migrate:fresh --seed

restart:
	@make down
	@make up

up:
	docker-compose up -d

down:
	docker compose down --remove-orphans

cache:
	docker-compose exec php php artisan cache:clear 
	docker-compose exec php php artisan config:cache 
stop:
	docker-compose stop