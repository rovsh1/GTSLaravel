up:
	docker compose up -d --build
composer-install:
	docker compose exec php composer install
key-generate:
	docker compose exec php php artisan key:generate
migrate:
	docker compose exec php php artisan migrate
migrate-fresh:
	docker compose exec php php artisan migrate:fresh
make frontend-admin:
	cd apps/admin; npm install; npm run build
