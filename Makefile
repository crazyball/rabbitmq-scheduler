.SILENT:
.PHONY: help install test-unit

ENV ?= dev

COMPOSER_ARGS =
ifeq ($(ENV), prod)
	COMPOSER_ARGS=--prefer-dist --classmap-authoritative --optimize-autoloader --no-dev
endif


## Colors
COLOR_RESET   = \033[0m
COLOR_INFO    = \033[32m
COLOR_COMMENT = \033[33m

## Help
help:
	printf "${COLOR_COMMENT}Usage:${COLOR_RESET}\n"
	printf " make [target]\n\n"
	printf "${COLOR_COMMENT}Available targets:${COLOR_RESET}\n"
	awk '/^[a-zA-Z\-\_0-9\.@]+:/ { \
		helpMessage = match(lastLine, /^## (.*)/); \
		if (helpMessage) { \
			helpCommand = substr($$1, 0, index($$1, ":")); \
			helpMessage = substr(lastLine, RSTART + 3, RLENGTH); \
			printf " ${COLOR_INFO}%-16s${COLOR_RESET} %s\n", helpCommand, helpMessage; \
		} \
	} \
	{ lastLine = $$0 }' $(MAKEFILE_LIST)

###########
# Install #
###########

## Install application
install: parameters.php vendor

vendor: composer.lock
	composer install $(COMPOSER_ARGS)

composer.lock: composer.json
	composer update

parameters.php:
	cp parameters.php.dist parameters.php

#########
# Tests #
#########

test-unit:
	vendor/bin/phpunit
