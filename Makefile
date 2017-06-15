.SILENT:
.PHONY: help install test-unit update test-coverage

ENV ?= dev

COMPOSER_ARGS =
ifeq ($(ENV), prod)
	COMPOSER_ARGS=--prefer-dist --classmap-authoritative --optimize-autoloader --no-dev
endif

help: ## This help
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

###########
# Install #
###########

install: config/parameters.php vendor ## Install application

vendor: composer.lock
	composer install $(COMPOSER_ARGS)

composer.lock: composer.json
	composer update

config/parameters.php:
	cp config/parameters.php.dist config/parameters.php

update: ## Update application
	$(MAKE) --always-make vendor

#########
# Tests #
#########

test-unit: ## Unit tests
	vendor/bin/phpunit

test-coverage: ## Unit tests
	vendor/bin/phpunit --coverage-text
