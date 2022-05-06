SHELL=/bin/bash

DOCKER_COMPOSE=docker-compose




.PHONY: run install #composer

run: install
	@${DOCKER_COMPOSE} run --rm php php run.php

install: composer


composer:
	@${DOCKER_COMPOSE} run --rm composer install --no-interaction --no-ansi
