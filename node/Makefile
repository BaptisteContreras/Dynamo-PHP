# ============================================================
# 🐳 Node makefile
# ============================================================

DOCKER_COMPOSE = docker compose -f docker/docker-compose.yaml
PHPFPM_EXEC    = $(DOCKER_COMPOSE) exec -it phpfpm-node php
COMPOSER_EXEC  = $(DOCKER_COMPOSE) exec -it phpfpm-node composer

# ------------------------------------------------------------
# 🎨 Helpers
# ------------------------------------------------------------
GREEN  = \033[0;32m
YELLOW = \033[1;33m
CYAN   = \033[0;36m
RESET  = \033[0m

define PRINT_SECTION
	@echo "$(CYAN)============================================================$(RESET)"
	@echo "$(YELLOW) $1 $(RESET)"
	@echo "$(CYAN)============================================================$(RESET)"
endef


.DEFAULT_GOAL := help

# ------------------------------------------------------------
# 🧹 Code Quality
# ------------------------------------------------------------
phpstan:
	$(call PRINT_SECTION,"🔍 Running PHPStan analysis")
	$(PHPFPM_EXEC) -d memory_limit=1G vendor/phpstan/phpstan/phpstan analyse src -c phpstan.dist.neon

phpstan-baseline:
	$(call PRINT_SECTION,"📝 Updating PHPStan baseline")
	$(PHPFPM_EXEC) -d memory_limit=1G vendor/phpstan/phpstan/phpstan analyse src -c phpstan.dist.neon -b

csfixer:
	$(call PRINT_SECTION,"🎨 Running PHP CS Fixer")
	$(PHPFPM_EXEC) vendor/friendsofphp/php-cs-fixer/php-cs-fixer --config=.php-cs-fixer.dist.php fix

# ------------------------------------------------------------
# 🗄️ Database & Migrations
# ------------------------------------------------------------
db-migrate:
	$(call PRINT_SECTION,"📦 Running Doctrine migrations")
	$(PHPFPM_EXEC) bin/console doc:mig:mig

db-migrate-test:
	$(call PRINT_SECTION,"🧪 Running migrations in test env")
	$(PHPFPM_EXEC) bin/console --env=test doctrine:schema:update --force

db-make-migration:
	$(call PRINT_SECTION,"⚙️  Making a new migration")
	$(PHPFPM_EXEC) bin/console make:mig

db-create-test:
	$(call PRINT_SECTION,"🧪 Creating test database")
	$(PHPFPM_EXEC) bin/console --env=test doctrine:database:create

# ------------------------------------------------------------
# 📦 Dependencies
# ------------------------------------------------------------
composer-install:
	$(call PRINT_SECTION,"📥 Installing Composer dependencies")
	$(COMPOSER_EXEC) install --prefer-dist -o

# ------------------------------------------------------------
# 🎨 Assets
# ------------------------------------------------------------
asset-compile:
	$(call PRINT_SECTION,"🎨 Compiling Symfony Asset Map")
	rm -rf public/assets
	$(PHPFPM_EXEC) -d memory_limit=1G bin/console asset-map:compile


# ------------------------------------------------------------
# 🛠️ Meta
# ------------------------------------------------------------
help:
	@echo ""
	@echo "$(GREEN)✨ Node Makefile Commands ✨$(RESET)"
	@echo ""
	@echo " 🧹 Code Quality"
	@echo "   • make phpstan            - Run PHPStan"
	@echo "   • make phpstan-baseline   - Update PHPStan baseline"
	@echo "   • make csfxier         - Run PHP-CS-Fixer"
	@echo ""
	@echo " 🗄️  Database & Migrations"
	@echo "   • make db-migrate         - Run migrations"
	@echo "   • make db-migrate-test    - Run migrations in test env"
	@echo "   • make db-make-migration  - Create a new migration"
	@echo "   • make db-create-test  - Create test database"
	@echo ""
	@echo " 🎨 Assets"
	@echo "   • make asset-compile   - Compile asset map"
	@echo ""
	@echo " 📦 Dependencies"
	@echo "   • make install         - Composer install"
	@echo ""
