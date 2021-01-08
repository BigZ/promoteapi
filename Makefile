PHING_BIN = ./vendor/bin/phing
PHING_IS_INSTALLED=$(shell [ -e $(PHING_BIN) ] && echo 1 || echo 0 )
PHP_CS_FIXER=./vendor/bin/php-cs-fixer

.PHONY: install

phing:
ifeq ($(PHING_IS_INSTALLED), 0)
	composer install
endif

install: phing
	$(PHING_BIN) install

prepare: phing
	$(PHING_BIN) prepare

cache:
	php bin/console ca:cl

test: phing
	$(PHING_BIN) test

analysis: phing
	$(PHING_BIN) analysis

fix:
	$(PHP_CS_FIXER) fix ./src

migrate: phing
	$(PHING_BIN) migrate

ci: phing
	$(PHING_BIN) full-build

dev:
	docker-compose up

d:
	docker exec promoteapi_php make $(c)
