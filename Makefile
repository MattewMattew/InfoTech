up:
	docker-compose up -d --build

composer-update:
	docker-compose exec php composer update

migrate:
	docker-compose exec php ./yii migrate

init-project: up composer-update migrate

.PHONY: up composer-update migrate