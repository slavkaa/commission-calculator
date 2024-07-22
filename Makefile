build:
	docker-compose build

up:
	docker-compose up -d

down:
	docker-compose down --remove-orphans

ssh:
	docker-compose exec -ti php-commission-calculator bash

# make run path=var/input/example.txt
run: $(path)
	docker-compose exec -ti php-commission-calculator php src/index.php $(path)

demo:
	docker-compose exec -ti php-commission-calculator php src/index.php var/input/example.txt

composer-install:
	docker-compose run -di php-commission-calculator composer install

composer-dumpautoload:
	docker-compose run -di php-commission-calculator composer dumpautoload
	
test:
	docker-compose run -ti php-commission-calculator php ./vendor/bin/phpunit -c phpunit.xml --testdox
	
analyse:
	docker-compose run -ti php-commission-calculator php ./vendor/bin/phpstan
	
setup: build up composer-install