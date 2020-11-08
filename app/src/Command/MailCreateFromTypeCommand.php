<?php

declare(strict_types=1);

namespace App\Command;

use App\Model\Mail\Api\Creator\MailCreatorInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

class MailCreateFromTypeCommand extends Command
{
    protected static $defaultName = 'mail:create:from-type';

    /**
     * @var MailCreatorInterface
     */
    protected MailCreatorInterface $mailCreator;

    /**
     * @param MailCreatorInterface $mailCreator
     */
    public function __construct(
        MailCreatorInterface $mailCreator
    ) {
        $this->mailCreator = $mailCreator;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Creates a mail from type(e.x. json).')
            ->addArgument('type', InputArgument::REQUIRED, 'The type of the message (e.x. json)')
            ->addArgument('message', InputArgument::REQUIRED, 'The message in provided type.');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ): int {
        $type = $input->getArgument('type');
        $message = $input->getArgument('message');

        if (is_string($type) === false || is_string($message) === false) {
            $output->writeln('Wrong argument type provided.');

            return Command::FAILURE;
        }

        try {
            $this->mailCreator->createFromType(
                $message,
                $type
            );
        } catch (Throwable $exception) {
            $output->writeln('Following error happened:');
            $output->writeln($exception->getMessage());

            return Command::FAILURE;
        }

        $output->writeln('Mail was successfully created!');

        return Command::SUCCESS;
    }
}
