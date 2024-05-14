DC = USER_ID=$(shell id -u) GROUP_ID=$(shell id -g) docker-compose -f ./compose.yaml

########################################################################################################################
################################################## DEV TOOLS ###########################################################
########################################################################################################################
.PHONY: create
create: build init

.PHONY: build
build:
	@$(DC) build

.PHONY: build-force
build-force:
	@$(DC) build --no-cache

.PHONY: up
up:
	@$(DC) up -d

.PHONY: down
down:
	@$(DC) down

.PHONY: destroy
destroy:
	@$(DC) down -v

.PHONY: init
init: up
	@$(DC) exec php composer install

.PHONY: shell
shell:
	@$(DC) exec php sh

################################################### DATABASE ###########################################################
.PHONY: migrations
migrations:
	@$(DC) exec php composer migrations

.PHONY: migration
migration:
	@$(DC) exec php composer migration

.PHONY: clear-cache
clear-cache:
	@$(DC) exec php composer cache:clear

########################################################################################################################
#################################################### TESTS #############################################################
########################################################################################################################
.PHONY: test
test: test-stan test-code-sniffer test-mess-detector test-magic-numbers test-deptrac test-unit test-integration test-acceptance

#################################################### STATIC ############################################################
.PHONY: test-stan
test-stan:
	@$(DC) exec php composer test:stan

.PHONY: test-code-sniffer
test-code-sniffer:
	@$(DC) exec php composer test:code-sniffer

.PHONY: test-code-sniffer-fix
test-code-sniffer-fix:
	@$(DC) exec php composer test:code-sniffer-fix

.PHONY: test-mess-detector
test-mess-detector:
	@$(DC) exec php composer test:mess-detector

.PHONY: test-magic-numbers
test-magic-numbers:
	@$(DC) exec php composer test:magic-numbers-detection

.PHONY: test-deptrac
test-deptrac:
	@$(DC) exec php composer test:deptrac

##################################################### UNIT #############################################################
.PHONY: test-unit
test-unit:
	@$(DC) exec php composer test:unit

################################################## INTEGRATION #########################################################
.PHONY: test-integration
test-integration:
	@$(DC) exec php composer test:integration

################################################### ACCEPTANCE #########################################################
.PHONY: test-acceptance
test-acceptance:
	@$(DC) exec php composer test:acceptance

#################################################### MUTATION ##########################################################
.PHONY: test-mutation
test-mutation:
	@$(DC) exec php composer test:mutation

#################################################### COVERAGE ##########################################################
.PHONY: test-coverage
test-coverage:
	@$(DC) exec php composer test:coverage
