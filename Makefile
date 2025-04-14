.PHONY : main build-image build-container start test coverage shell stop clean
main: build-image build-container

build-image:
	docker build -t twitch_test_types-php .

build-container:
	docker run -dt --name twitch_test_types-php -v .:/540/TwitchTestTypes twitch_test_types-php
	docker exec twitch_test_types-php composer install

start:
	docker start twitch_test_types-php

test: start
	docker exec twitch_test_types-php ./vendor/bin/phpunit --colors=always --testdox tests/$(target) || true

coverage: start
	docker exec twitch_test_types-php ./vendor/bin/phpunit --coverage-html coverage-report --testdox tests/$(target) || true

shell: start
	docker exec -it twitch_test_types-php /bin/bash

stop:
	docker stop twitch_test_types-php

clean: stop
	docker rm twitch_test_types-php
	rm -rf vendor coverage-report
