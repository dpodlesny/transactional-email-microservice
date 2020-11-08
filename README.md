# Email Microservice #

## Compatibility

Docker

PHP 7.4

MYSQL

Symfony 5

RabbitMQ

PHPStan: level 8

PSR12

## Description

This service allows sending emails via API or CLI(see below for examples).

SendGrid is a main mail service by default, in case if SendGrid is not available one of the fallback mail services will be used (Mailjet by default).
 
To change main mail client or to add a new fallback client you need to introduce new MailClientAdapter by implementing ```App\Model\Mail\Adapter\MailClientAdapterInterface``` and update needed arguments of ```App\Model\Mail\Sender\MailSender``` in ```services.yml:55```.

Business logic based on SOLID principles and stored in ```App/Model``` namespace to facilitate maintenance and extension.

Module configuration stored in ```ModuleConfig``` class to keep all module configuration in one place.

## Setup

Init project

    $ make init

Set yours API keys from mailjet and sendgrid in .env and .env.test

```
###> mailjet ###
MJ_APIKEY_PUBLIC='Enter your mailjet api key public'
MJ_APIKEY_PRIVATE='Enter your mailjet api key private'
###< mailjet ###

###> sendgrid ###
SENDGRID_API_KEY='Enter your sendgrid api key'
###< sendgrid ###
```

To run full validation use:

    $ make validate

## Access

[localhost:8088](http://localhost:8088)

## DB Connection

Host: localhost

Port: 33068

User: user

Password: password

## RabbitMQ Connection

[localhost:15672](http://localhost:15672)

User: guest

Password: guest

## CLI

To use cli from docker container use:

    $ docker-compose run --rm transactional-email-service-php-cli

## CLI Examples

To create mail request enter command below and follow the instructions.

```
docker-compose run --rm transactional-email-service-php-cli php bin/console mail:create:manual
```

To run queue consumer use command below.

```
docker-compose run --rm transactional-email-service-php-cli php bin/console mail:consumer:consume
```

## API Examples

Get paginated list of mails

Items per page: 10
```
GET http://localhost:8088/api/mails?page=1
Accept: application/json
```

Create mail request

```
POST http://localhost:8088/api/mails
Content-Type: application/json
Accept: application/json

{
  "subject": "subject",
  "recipient": {
    "name": "name",
    "email": "test@test.com"
  },
  "contents": [
    {
      "type":"text/html",
      "content": "test"
    },
    {
      "type":"text/plain",
      "content": "test"
    }
  ],
  "additionalRecipients": [
    {
      "name": "name 1",
      "email": "test+1@test.com"
    },
    {
      "name": "name 2",
      "email": "test+2@test.com"
    }
  ]
}
```
