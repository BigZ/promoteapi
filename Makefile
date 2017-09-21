PHING_IS_INSTALLED=$(shell [ -e ./vendor/bin/phing ] && echo 1 || echo 0 )

.PHONY: install

install:
    ifeq ($(PHING_IS_INSTALLED), 0)
        composer install
    endif
	phing full-build

start:
    php app/console server:run

stop:
    php app/console server:stop

test:
	phing tests
