<?php
declare(strict_types=1);

namespace App\Tests;

use Codeception\Util\HttpCode;

class MailCreateNegativeCest
{
    public function testCreateMailWithoutSubject(ApiTester $I): void
    {
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
    }

    public function testCreateMailWithoutAdditionalRecipientName(ApiTester $I): void
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
    }

    public function testCreateMailWithoutAdditionalRecipientEmail(ApiTester $I): void
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
    }
}
