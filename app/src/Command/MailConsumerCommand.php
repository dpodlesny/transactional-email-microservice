<?php

declare(strict_types=1);

namespace App\Command;

use App\Model\Mail\MailConfig;
use App\Model\Mail\Saver\MailSaverInterface;
use App\Model\Mail\Sender\MailSenderInterface;
use App\Model\Queue\QueueFactory;
use App\Repository\MailRepository;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MailConsumerCommand extends Command
{
    protected static $defaultName = 'mail:consumer:consume';

    protected InputInterface $input;
    protected OutputInterface $output;

    /**
     * @var QueueFactory
     */
    protected QueueFactory $queueFactory;

    /**
     * @var MailConfig
     */
    protected MailConfig $mailConfig;

    /**
     * @var MailSenderInterface
     */
    protected MailSenderInterface $mailSender;

    /**
     * @var MailRepository
     */
    protected MailRepository $mailRepository;

    /**
     * @var MailSaverInterface
     */
    protected MailSaverInterface $mailSaver;

    /**
     * @param QueueFactory $queueFactory
     * @param MailConfig $mailConfig
     * @param MailSenderInterface $mailSender
     * @param MailRepository $mailRepository
     * @param MailSaverInterface $mailSaver
     */
    public function __construct(
        QueueFactory $queueFactory,
        MailConfig $mailConfig,
        MailSenderInterface $mailSender,
        MailRepository $mailRepository,
        MailSaverInterface $mailSaver
    ) {
        $this->queueFactory = $queueFactory;
        $this->mailConfig = $mailConfig;
        $this->mailSender = $mailSender;
        $this->mailRepository = $mailRepository;
        $this->mailSaver = $mailSaver;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Consumes messages from mail queue. Sends mail with different mail services.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;

        $output->writeln('Starting consuming process...');

        $connection = $this->queueFactory->createAMQPStreamConnection();
        $channel = $connection->channel();

        $output->writeln('Waiting for messages...');

        $channel->queue_declare($this->mailConfig->getQueueName(), false, true, false, false);
        $channel->exchange_declare(
            $this->mailConfig->getQueueExchangeName(),
            AMQPExchangeType::DIRECT,
            false,
            true,
            false
        );
        $channel->queue_bind($this->mailConfig->getQueueName(), $this->mailConfig->getQueueExchangeName());
        $channel->basic_consume(
            $this->mailConfig->getQueueName(),
            $this->mailConfig->getQueueConsumerTag(),
            false,
            false,
            false,
            false,
            function (AMQPMessage $message): void {
                $this->processMessage($message);
            }
        );

        register_shutdown_function(
            function (
                AMQPChannel $channel,
                AMQPStreamConnection $connection
            ): void {
                $this->shutdown($channel, $connection);
            },
            $channel,
            $connection
        );

        while ($channel->is_consuming()) {
            $channel->wait();
        }

        return Command::SUCCESS;
    }

    /**
     * @param AMQPMessage $message
     *
     * @return void
     */
    protected function processMessage(AMQPMessage $message): void
    {
        $this->output->writeln('Message received...');
        $this->output->writeln($message->body);

        $body = json_decode($message->body, true);

        if (isset($body['mail_id']) === false) {
            $this->output->writeln('Wrong message body');

            return;
        }

        $mail = $this->mailRepository->find($body['mail_id']);

        if ($mail === null || $mail->getSentAt() !== null) {
            $this->output->writeln('Mail was not found or already sent.');

            return;
        }

        if ($this->mailSender->send($mail)) {
            $this->output->writeln('Mail was sent successfully.');
            $mail->markAsSentAt();

            $this->mailSaver->save($mail);

            return;
        }

        $this->output->writeln('Some error happened while sending mail.');
    }

    /**
     * @param AMQPChannel $channel
     * @param AMQPStreamConnection $connection
     *
     * @return void
     */
    protected function shutdown(
        AMQPChannel $channel,
        AMQPStreamConnection $connection
    ): void {
        $channel->close();
        $connection->close();
    }
}
