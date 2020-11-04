<?php
declare(strict_types=1);

namespace App\Tests;

use Codeception\Util\HttpCode;

class MailCreatePositiveCest
{
    public function testCreateMailWithAllFields(ApiTester $I): void
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
                        "email" => "test+2@test.com",
                    ],
                ],
            ],
        );
        $I->seeResponseCodeIs(HttpCode::CREATED);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType([
            'id' => 'integer',
            'subject' => 'string',
            'recipient' => 'array',
            'contents' => 'array',
            'additionalRecipients' => 'array',
            'createdAt' => 'string',
            'sentAt' => 'null',
        ]);
        $I->seeResponseMatchesJsonType(
            [
                'name' => 'string',
                'email' => 'string',
            ],
            '$.recipient'
        );
        $I->seeResponseMatchesJsonType(
            [
                'name' => 'string',
                'email' => 'string',
            ],
            '$.additionalRecipients[0]'
        );
        $I->seeResponseMatchesJsonType(
            [
                'type' => 'string',
                'content' => 'string',
            ],
            '$.contents[0]'
        );
    }

    public function testCreateMailWithMinimumFields(ApiTester $I): void
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
                ],
            ],
        );
        $I->seeResponseCodeIs(HttpCode::CREATED);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType([
            'id' => 'integer',
            'subject' => 'string',
            'recipient' => 'array',
            'contents' => 'array',
            'additionalRecipients' => 'array',
            'createdAt' => 'string',
            'sentAt' => 'null',
        ]);
        $I->seeResponseMatchesJsonType(
            [
                'name' => 'string',
                'email' => 'string',
            ],
            '$.recipient'
        );
        $I->seeResponseMatchesJsonType(
            [
                'type' => 'string',
                'content' => 'string',
            ],
            '$.contents[0]'
        );
    }
}
