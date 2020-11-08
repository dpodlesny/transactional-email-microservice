<?php
declare(strict_types=1);

namespace App\Tests;

use App\Entity\Mail;
use Codeception\Util\HttpCode;

class MailCreateNegativeCest
{
    public function testCreateMailWithoutSubject(ApiTester $I): void
    {
        $I->declareQueue($I->getMailQueueName(), false, true, false, false);
        $I->setHeaders();
        $I->sendPost(
            'api/mails',
            [
                "recipient" => [
                    "name" => "name",
                    "email" => "test@test.com",
                ],
                "contents" => [
                    [
                        "type" => "text/html",
                        "content" => "test",
                    ],
                    [
                        "type" => "text/plain",
                        "content" => "test",
                    ],
                ],
                "additionalRecipients" => [
                    [
                        "name" => "name 1",
                        "email" => "test+1@test.com",
                    ],
                    [
                        "name" => "name 2",
                        "email" => "test+2@test.com",
                    ],
                ],
            ],
        );
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType([
            'message' => 'string',
        ]);

        $I->dontSeeInRepository(Mail::class);
        $I->seeQueueIsEmpty($I->getMailQueueName());
    }

    public function testCreateMailWithoutRecipient(ApiTester $I): void
    {
        $I->setHeaders();
        $I->sendPost(
            'api/mails',
            [
                "subject" => "subject",
                "contents" => [
                    [
                        "type" => "text/html",
                        "content" => "test",
                    ],
                    [
                        "type" => "text/plain",
                        "content" => "test",
                    ],
                ],
                "additionalRecipients" => [
                    [
                        "name" => "name 1",
                        "email" => "test+1@test.com",
                    ],
                    [
                        "name" => "name 2",
                        "email" => "test+2@test.com",
                    ],
                ],
            ],
        );
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType([
            'message' => 'string',
        ]);

        $I->dontSeeInRepository(Mail::class);
        $I->seeQueueIsEmpty($I->getMailQueueName());
    }

    public function testCreateMailWithoutRecipientName(ApiTester $I): void
    {
        $I->setHeaders();
        $I->sendPost(
            'api/mails',
            [
                "subject" => "subject",
                "recipient" => [
                    "email" => "test@test.com",
                ],
                "contents" => [
                    [
                        "type" => "text/html",
                        "content" => "test",
                    ],
                    [
                        "type" => "text/plain",
                        "content" => "test",
                    ],
                ],
                "additionalRecipients" => [
                    [
                        "name" => "name 1",
                        "email" => "test+1@test.com",
                    ],
                    [
                        "name" => "name 2",
                        "email" => "test+2@test.com",
                    ],
                ],
            ],
        );
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType([
            'message' => 'string',
        ]);

        $I->dontSeeInRepository(Mail::class);
        $I->seeQueueIsEmpty($I->getMailQueueName());
        $I->seeNumberOfMessagesInQueue($I->getMailQueueName(), 0);
    }

    public function testCreateMailWithoutRecipientEmail(ApiTester $I): void
    {
        $I->setHeaders();
        $I->sendPost(
            'api/mails',
            [
                "subject" => "subject",
                "recipient" => [
                    "name" => "name",
                ],
                "contents" => [
                    [
                        "type" => "text/html",
                        "content" => "test",
                    ],
                    [
                        "type" => "text/plain",
                        "content" => "test",
                    ],
                ],
                "additionalRecipients" => [
                    [
                        "name" => "name 1",
                        "email" => "test+1@test.com",
                    ],
                    [
                        "name" => "name 2",
                        "email" => "test+2@test.com",
                    ],
                ],
            ],
        );
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType([
            'message' => 'string',
        ]);

        $I->dontSeeInRepository(Mail::class);
        $I->seeQueueIsEmpty($I->getMailQueueName());
        $I->seeNumberOfMessagesInQueue($I->getMailQueueName(), 0);
    }

    public function testCreateMailWithoutContents(ApiTester $I): void
    {
        $I->setHeaders();
        $I->sendPost(
            'api/mails',
            [
                "subject" => "subject",
                "recipient" => [
                    "name" => "name",
                    "email" => "test@test.com",
                ],
                "additionalRecipients" => [
                    [
                        "name" => "name 1",
                        "email" => "test+1@test.com",
                    ],
                    [
                        "name" => "name 2",
                        "email" => "test+2@test.com",
                    ],
                ],
            ],
        );
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType([
            'message' => 'string',
        ]);

        $I->dontSeeInRepository(Mail::class);
        $I->seeQueueIsEmpty($I->getMailQueueName());
        $I->seeNumberOfMessagesInQueue($I->getMailQueueName(), 0);
    }

    public function testCreateMailWithoutContentsType(ApiTester $I): void
    {
        $I->setHeaders();
        $I->sendPost(
            'api/mails',
            [
                "subject" => "subject",
                "recipient" => [
                    "name" => "name",
                    "email" => "test@test.com",
                ],
                "contents" => [
                    [
                        "content" => "test",
                    ],
                    [
                        "type" => "text/plain",
                        "content" => "test",
                    ],
                ],
                "additionalRecipients" => [
                    [
                        "name" => "name 1",
                        "email" => "test+1@test.com",
                    ],
                    [
                        "name" => "name 2",
                        "email" => "test+2@test.com",
                    ],
                ],
            ],
        );
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType([
            'message' => 'string',
        ]);

        $I->dontSeeInRepository(Mail::class);
        $I->seeQueueIsEmpty($I->getMailQueueName());
        $I->seeNumberOfMessagesInQueue($I->getMailQueueName(), 0);
    }

    public function testCreateMailWithoutContentsContent(ApiTester $I): void
    {
        $I->setHeaders();
        $I->sendPost(
            'api/mails',
            [
                "subject" => "subject",
                "recipient" => [
                    "name" => "name",
                    "email" => "test@test.com",
                ],
                "contents" => [
                    [
                        "type" => "text/html",
                    ],
                    [
                        "type" => "text/plain",
                        "content" => "test",
                    ],
                ],
                "additionalRecipients" => [
                    [
                        "name" => "name 1",
                        "email" => "test+1@test.com",
                    ],
                    [
                        "name" => "name 2",
                        "email" => "test+2@test.com",
                    ],
                ],
            ],
        );
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType([
            'message' => 'string',
        ]);

        $I->dontSeeInRepository(Mail::class);
        $I->seeQueueIsEmpty($I->getMailQueueName());
        $I->seeNumberOfMessagesInQueue($I->getMailQueueName(), 0);
    }

    public function testCreateMailWithoutAdditionalRecipientName(
        ApiTester $I
    ): void {
        $I->setHeaders();
        $I->sendPost(
            'api/mails',
            [
                "subject" => "subject",
                "recipient" => [
                    "name" => "name",
                    "email" => "test@test.com",
                ],
                "contents" => [
                    [
                        "type" => "text/html",
                        "content" => "test",
                    ],
                    [
                        "type" => "text/plain",
                        "content" => "test",
                    ],
                ],
                "additionalRecipients" => [
                    [
                        "email" => "test+1@test.com",
                    ],
                    [
                        "name" => "name 2",
                        "email" => "test+2@test.com",
                    ],
                ],
            ],
        );
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType([
            'message' => 'string',
        ]);

        $I->dontSeeInRepository(Mail::class);
        $I->seeQueueIsEmpty($I->getMailQueueName());
        $I->seeNumberOfMessagesInQueue($I->getMailQueueName(), 0);
    }

    public function testCreateMailWithoutAdditionalRecipientEmail(
        ApiTester $I
    ): void {
        $I->setHeaders();
        $I->sendPost(
            'api/mails',
            [
                "subject" => "subject",
                "recipient" => [
                    "name" => "name",
                    "email" => "test@test.com",
                ],
                "contents" => [
                    [
                        "type" => "text/html",
                        "content" => "test",
                    ],
                    [
                        "type" => "text/plain",
                        "content" => "test",
                    ],
                ],
                "additionalRecipients" => [
                    [
                        "name" => "name 1",
                        "email" => "test+1@test.com",
                    ],
                    [
                        "name" => "name 2",
                    ],
                ],
            ],
        );
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType([
            'message' => 'string',
        ]);

        $I->dontSeeInRepository(Mail::class);
        $I->seeQueueIsEmpty($I->getMailQueueName());
        $I->seeNumberOfMessagesInQueue($I->getMailQueueName(), 0);
    }
}
