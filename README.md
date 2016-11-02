# php-homework 

An pet project for @Letgo interview

## Project Description

the project implements a restful api to manage user profiles

it has 4 endpoints which are better described in the documentation provided inside the api
and inside the RAML spec but, roughly, the are:
 
- POST /user: creation of a profile
- GET /user/[id]: get the specified profile
- POST /user/[id]: edit the specified profile
- POST /user/[id]/picture: upload a picture to @letgo provided CDN
- DELETE /user/[id] delete de user

## instalation

i tried to simplify at maximun the instalation procedure

you will need at least one of these:

- a local [PHP 5.6+](http://php.net/downloads.php) installation
- [docker](https://docs.docker.com/engine/installation/) and [docker-compose](https://docs.docker.com/compose/install/)

you will need also [composer](https://getcomposer.org/download/) to download dependencies

### with local PHP

1- clone this repository https://github.com/javiern/php-homework.git 
```bash
git clone https://github.com/javiern/php-homework.git 
```
2- run composer
```bash
composer install
```
3- start the service
```bash
php -d date.timezone=America/Argentina/Buenos_Aires -S 0.0.0.0:8080 -t web
```

### with local docker and docker-compose

the steps involved are basically the same

docker run --rm -v $(pwd):/app composer/composer install

1- clone this repository https://github.com/javiern/php-homework.git 
```bash
git clone https://github.com/javiern/php-homework.git 
```
2- run composer
```bash
#if you have composer installed locally
composer install

#or if you dont have it locally and want to use docker instead
docker run --rm -v $(pwd):/app composer/composer install
```

3- install the service (with compose)
```bash
docker-compose up -d
```

either way you choose, that's it! you have now the api up and running.

at this point, you see something like these in your console
```bash
PHP 5.6.22 Development Server started at Wed Nov  2 00:05:20 2016
Listening on http://0.0.0.0:8080
Document root is /web
Press Ctrl-C to quit.
```

you can access api documentation at [here](http://localhost:8080/docs/index.html)

## tests

the api comes with a test suite composed of unit tests and acceptance tests that runs agains a live instance.
the tests suite was developed using [codeception](http://codeception.com/) 

### running tests
in order to run the full test suite, you will need the api running, once you have it, just run

```bash
vendor/bin/codecept run
```

you can run acceptance only

```bash
vendor/bin/codecept run acceptance
```

or you can run unit only

```bash
vendor/bin/codecept run unit
```

additionally, if you have phpunit the docker environment described here does not have ), you could get coverage reports for both suites.

the reports will be on tests/_output directory

```bash
vendor/bin/codecept run --coverage --coverage-html
```

well, thats it! 

I hope you like it.