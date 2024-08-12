# Makefile for Laravel Docker Project

# Variables
DOCKER_COMPOSE = docker compose
CONTAINER_APP = app

# Commands

# Build the Docker containers
build:
	$(DOCKER_COMPOSE) up --build -d

# Start the Docker containers
start:
	$(DOCKER_COMPOSE) up -d

# Stop and remove the Docker containers
down:
	$(DOCKER_COMPOSE) down

# Run Laravel migrations
migrate:
	$(DOCKER_COMPOSE) exec $(CONTAINER_APP) php artisan migrate

# Seed the database
seed:
	$(DOCKER_COMPOSE) exec $(CONTAINER_APP) php artisan db:seed

# Run migrations and seed
migrate-seed: migrate seed

# Rebuild the Docker containers without cache
rebuild:
	$(DOCKER_COMPOSE) build --no-cache

# Run tests
test:
	$(DOCKER_COMPOSE) exec $(CONTAINER_APP) php artisan test

# View container logs
logs:
	$(DOCKER_COMPOSE) logs -f

# Composer Install
composer-install:
	$(DOCKER_COMPOSE) exec $(CONTAINER_APP) composer install

# NPM Install
npm-install:
	$(DOCKER_COMPOSE) exec $(CONTAINER_APP) npm install

# Artisan commands
artisan:
	$(DOCKER_COMPOSE) exec $(CONTAINER_APP) php artisan $(cmd)

# Execute a bash shell inside the app container
bash:
	$(DOCKER_COMPOSE) exec $(CONTAINER_APP) bash
