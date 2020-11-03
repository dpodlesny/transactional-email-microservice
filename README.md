# Transactional Email Microservice #

## Info ##

Service allows to send emails via API or CLI

## Commands ##

**Init docker containers:**

    $ make init

**URL**

[localhost:8088](http://localhost:8088)

**DB**

Host: localhost

Port: 33068

User: user

Password: password

**CLI**:

    $ docker-compose run --rm transactional-email-service-php-cli


**API**

Get paginated list of mails

Items per page: 10
```
GET http://localhost:8088/api/mails?page=1
Accept: application/json
```
