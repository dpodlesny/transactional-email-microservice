## Transactional Email Microservice ##

**> Info <**

This service allows sending emails via API or CLI(see below for examples). Service will try to send an email via SendGrid as the main service in case if SendGrid is not available Service will try to send mail via one of the fallbacks mail services(Mailjet by default). You can add an additional fallback service by implementing ```App\Model\Mail\Adapter\MailAdapterInterface``` and add a new service to ```App\Model\Mail\Sender\MailSender``` as the second argument in ```service.yml:55```

**> Init <**

Init docker containers

    $ make init

Set yours API keys from mailjet and sendgrid in .env

```
###> mailjet ###
MJ_APIKEY_PUBLIC='Enter your mailjet api key public'
MJ_APIKEY_PRIVATE='Enter your mailjet api key private'
###< mailjet ###

###> sendgrid ###
SENDGRID_API_KEY='Enter your sendgrid api key'
###< sendgrid ###
```

**> Access <**

[localhost:8088](http://localhost:8088)

**> DB Connection <**

Host: localhost

Port: 33068

User: user

Password: password

**> Redis Connection <**

[localhost:15672](http://localhost:15672)

User: guest

Password: guest

**> CLI <**:

To use cli from docker container use:

    $ docker-compose run --rm transactional-email-service-php-cli

**> CLI Example <**

To create mail request enter command below and follow the instructions.

```
docker-compose run --rm transactional-email-service-php-cli php bin/console mail:create
```

To run queue consumer use command below.

```
docker-compose run --rm transactional-email-service-php-cli php bin/console mail:consumer:consume
```

**> API Example <**

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
