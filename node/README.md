# Dynamo-PHP : Node

This project is a try to implement the Amazon Dynamo paper (see docs/amazon-dynamo-sosp2007.pdf) using PHP and Symfony.


WIP 

## Dev

### Start the stack

```bash

cd docker && docker-compose up -d
```

Once the docker stack is up and running, a Nginx server is listening on port 80 and 443, a Postgres instance is behind the port 5432 and you can
manage your database using adminer on port 8089

- [App](http://localhost)
- [API Swagger](http://localhost/api/doc)
- [Adminer](http://localhost:8089)
- [Postgres](database:5432)

#### Connect to postgres with adminer

Once you opened [Adminer](http://localhost:8089), you have to login using these information :

```
System: PostgreSQL
Serveur: database
User: dev
Password: dev
Database: manager-dev
```

### App configuration

Create a **.env.local** file like this :

```
#.env.local

DATABASE_URL="postgresql://dev:dev@database:5432/manager-dev?serverVersion=16&charset=utf8"
```

### Install dependencies

Inside the php-fpm container: 

```
composer install
```

### Database migration

Inside the php-fpm container:

```
php bin/console doc:mig:mig
```
### Code quality

#### Phpcsfixer

```
docker exec -it docker-phpfpm-node-1 vendor/friendsofphp/php-cs-fixer/php-cs-fixer --config=.php-cs-fixer.dist.php fix
```

#### Phpstan

```
docker exec -it docker-phpfpm-node-1 vendor/bin/phpstan analyse src -c phpstan.dist.neon
```
#### Rector

```
docker exec -it docker-phpfpm-node-1 vendor/bin/rector process --config rector.php
```

#### Extra
On windows, create a volume

```
docker volume create --driver local -o o=bind -o type=none -o device="/path/to/src/node" dynamosrcdir
```