PHING_BIN = ./vendor/bin/phing
PHING_IS_INSTALLED=$(shell [ -e $(PHING_BIN) ] && echo 1 || echo 0 )

.PHONY: install

install:
ifeq ($(PHING_IS_INSTALLED), 0)
	composer install
endif
	$(PHING_BIN) install

build:
ifeq ($(PHING_IS_INSTALLED), 0)
	composer install
endif
	$(PHING_BIN) install
	$(PHING_BIN) prepare

start:
	php app/console server:run

stop:
	php app/console server:stop

test:
	$(PHING_BIN) prepare
	$(PHING_BIN) test

analysis:
	$(PHING_BIN) analysis
