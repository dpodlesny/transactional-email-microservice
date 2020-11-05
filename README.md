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

**CLI Example**

Create mail request 

```
docker-compose run --rm transactional-email-service-php-cli php bin/console mail:create
```

**API Example**

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
