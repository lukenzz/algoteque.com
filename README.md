# Course Bundle Recommendation System API

# How to run #

In project folder change name of file ```.env.example``` to ```.env```

### ```.env``` configuration
    1. LOG_PATH= | Path to your log directory
    2. PROVIDER_CONFIG_PATH= | Path to providers list
    3. APP_ENV= | dev / production set project environment
    4. APP_DEBUG= | true / false set error visibility

    Extra: Dev mode allows you to test the application without using tools like Postman.
    Command: php public/index.php

###  Postman JSON data

```angular2html
{
  "topics": {
    "reading": 20,
    "math": 50,
    "science": 30,
    "history": 15,
    "art": 10
  }
}
```

Dependencies:

* docker. See [https://docs.docker.com/engine/installation](https://docs.docker.com/engine/installation)
* docker-compose. See [docs.docker.com/compose/install](https://docs.docker.com/compose/install/)

Once you're done, simply `cd` to your project and run 
  1. `docker-compose build`
  2. `docker-compose up -d`. 

This will initialise and start all the
containers, then leave them running in the background.

## Services exposed outside your environment ##

You can access your application via **`localhost`**. Mailhog and nginx both respond to any hostname, in case you want to
add your own hostname on your `/etc/hosts`

Service|Address outside containers
-------|--------------------------
Webserver|[localhost:34343](http://localhost:34000)

## Hosts within your environment ##

You'll need to configure your application to use any services you enabled:

Service|Hostname|Port number
------|---------|-----------
php-fpm|php-fpm|9000

# Docker compose cheatsheet #

**Note:** you need to cd first to where your docker-compose.yml file lives.

* Start containers in the background: `docker-compose up -d`
* Start containers on the foreground: `docker-compose up`. You will see a stream of logs for every container running.
  ctrl+c stops containers.
* Stop containers: `docker-compose stop`
* Kill containers: `docker-compose kill`
* View container logs: `docker-compose logs` for all containers or `docker-compose logs SERVICE_NAME` for the logs of
  all containers in `SERVICE_NAME`.
* Execute command inside of container: `docker-compose exec SERVICE_NAME COMMAND` where `COMMAND` is whatever you want
  to run. Examples:
    * Shell into the PHP container, `docker-compose exec php-fpm bash`
    * Run symfony console, `docker-compose exec php-fpm bin/console`
    * Open a mysql shell, `docker-compose exec mysql mysql -uroot -pCHOSEN_ROOT_PASSWORD`
      `
