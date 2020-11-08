<?php

declare(strict_types=1);

namespace App\Tests;

use App\Entity\Mail;
use App\Model\Mail\Adapter\MailClientAdapterInterface;
use App\Model\Mail\Adapter\MailjetClientAdapter;
use App\Model\Mail\Adapter\SendgridClientAdapter;
use App\Model\Mail\Sender\MailSender;
use App\Model\Mail\Sender\MailSenderInterface;
use Codeception\Test\Unit;
use PHPUnit\Framework\MockObject\MockObject;

class MailSenderTest extends Unit
{
    /**
     * @var UnitTester
     */
    protected UnitTester $tester;

    /**
     * @return void
     */
    public function testFallbackClientMustBeCalledIfMainIsNotAvailable(): void
    {
        /** @var MailClientAdapterInterface|MockObject $mainMailClient */
        $mainMailClient = $this
            ->getMockBuilder(SendgridClientAdapter::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mainMailClient->method('send')->willReturn(false);

        /** @var MailClientAdapterInterface|MockObject $fallbackMailClient */
        $fallbackMailClient = $this
            ->getMockBuilder(MailjetClientAdapter::class)
            ->disableOriginalConstructor()
            ->getMock();

        $fallbackMailClient->method('send')->willReturn(true);

        /** @var MailSenderInterface $mailSender */
        $mailSender = new MailSender($mainMailClient, [
            $fallbackMailClient,
        ]);

        $mainMailClient
            ->expects($this->once())
            ->method('send');

        $fallbackMailClient
            ->expects($this->once())
            ->method('send');

        $this->tester->assertTrue($mailSender->send(new Mail()));
    }

    /**
     * @return void
     */
    public function testSecondFallbackClientMustNotBeCalledIfFirstIsAvailable()
    {
        /** @var MailClientAdapterInterface|MockObject $mainMailClient */
        $mainMailClient = $this
            ->getMockBuilder(SendgridClientAdapter::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mainMailClient->method('send')->willReturn(false);

        /** @var MailClientAdapterInterface|MockObject $fallbackMailClient */
        $fallbackMailClient = $this
            ->getMockBuilder(MailjetClientAdapter::class)
            ->disableOriginalConstructor()
            ->getMock();

        $fallbackMailClient->method('send')->willReturn(true);

        /** @var MailClientAdapterInterface|MockObject $secondFallbackMailClient */
        $secondFallbackMailClient = $this
            ->getMockBuilder(SendgridClientAdapter::class)
            ->disableOriginalConstructor()
            ->getMock();

        /** @var MailSenderInterface $mailSender */
        $mailSender = new MailSender($mainMailClient, [
            $fallbackMailClient,
            $secondFallbackMailClient,
        ]);

        $secondFallbackMailClient
            ->expects($this->never())
            ->method('send');

        $this->tester->assertTrue($mailSender->send(new Mail()));
    }

    /**
     * @return void
     */
    public function testSecondFallbackClientMustBeCalledIfMainAndFirstFallbackClientsIsNotAvailable()
    {
        /** @var MailClientAdapterInterface|MockObject $mainMailClient */
        $mainMailClient = $this
            ->getMockBuilder(SendgridClientAdapter::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mainMailClient->method('send')->willReturn(false);

        /** @var MailClientAdapterInterface|MockObject $fallbackMailClient */
        $fallbackMailClient = $this
            ->getMockBuilder(MailjetClientAdapter::class)
            ->disableOriginalConstructor()
            ->getMock();

        $fallbackMailClient->method('send')->willReturn(false);

        /** @var MailClientAdapterInterface|MockObject $secondFallbackMailClient */
        $secondFallbackMailClient = $this
            ->getMockBuilder(SendgridClientAdapter::class)
            ->disableOriginalConstructor()
            ->getMock();

        $secondFallbackMailClient->method('send')->willReturn(true);

        /** @var MailSenderInterface $mailSender */
        $mailSender = new MailSender($mainMailClient, [
            $fallbackMailClient,
            $secondFallbackMailClient,
        ]);

        $mainMailClient
            ->expects($this->once())
            ->method('send');

        $fallbackMailClient
            ->expects($this->once())
            ->method('send');

        $secondFallbackMailClient
            ->expects($this->once())
            ->method('send');

        $this->tester->assertTrue($mailSender->send(new Mail()));
    }
}
