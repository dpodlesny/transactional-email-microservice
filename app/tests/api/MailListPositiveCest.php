<?php
declare(strict_types=1);

namespace App\Tests;

use Codeception\Util\HttpCode;

class MailListPositiveCest
{
    public function testGetEmptyMailList(ApiTester $I): void
    {
        $I->setHeaders();
        $I->sendGet('api/mails');
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType(['total' => 'integer', 'items' => 'array']);
        $I->seeResponseContainsJson(
            [
                'total' => 0,
                'items' => [],
            ]
        );
    }

    public function testGetNotEmptyMailList(ApiTester $I): void
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

        $I->sendGet('api/mails');
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType(['total' => 'integer', 'items' => 'array']);
        $I->seeResponseContainsJson(
            [
                'total' => 1,
            ]
        );
        $I->seeResponseMatchesJsonType(
            [
                'id' => 'integer',
                'subject' => 'string',
                'recipient' => 'array',
                'contents' => 'array',
                'additionalRecipients' => 'array',
                'createdAt' => 'string',
                'sentAt' => 'null',
            ],
            '$.items[0]'
        );
    }
}
