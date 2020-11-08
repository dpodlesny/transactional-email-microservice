<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\Content;
use App\Entity\Mail;
use App\Entity\Recipient;
use App\Model\Mail\MailConfig;
use App\Model\Mail\Saver\MailSaverInterface;
use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;
use Throwable;

class MailCreateCommand extends Command
{
    protected static $defaultName = 'mail:create:manual';

    /**
     * @var MailSaverInterface
     */
    protected MailSaverInterface $mailSaver;

    /**
     * @var MailConfig
     */
    protected MailConfig $mailConfig;

    /**
     * @var InputInterface
     */
    protected InputInterface $input;

    /**
     * @var OutputInterface
     */
    protected OutputInterface $output;

    /**
     * @var QuestionHelper
     */
    protected QuestionHelper $questionHelper;

    /**
     * @param MailSaverInterface $mailSaver
     * @param MailConfig $mailConfig
     */
    public function __construct(
        MailSaverInterface $mailSaver,
        MailConfig $mailConfig
    ) {
        $this->mailSaver = $mailSaver;
        $this->mailConfig = $mailConfig;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('Creates a mail with manual input parameters.');
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
        $this->input = $input;
        $this->output = $output;
        $this->questionHelper = $this->getHelper('question');

        $this->output->writeln([
            'Mail Creator',
            '============',
            '',
        ]);

        $mail = new Mail();

        $this->fillSubject($mail);
        $this->fillRecipient($mail);
        $this->fillContents($mail);

        $addAdditionalRecipientQuestion = new ConfirmationQuestion(
            'Do you want to add additional recipients?(yes/no)' . PHP_EOL,
            false
        );

        if ($this->questionHelper->ask($this->input, $this->output, $addAdditionalRecipientQuestion)) {
            $this->fillAdditionalRecipients($mail);
        }

        try {
            $this->mailSaver->save($mail);
        } catch (Throwable $exception) {
            $this->output->writeln($exception->getMessage());

            return Command::FAILURE;
        }

        $this->output->writeln('Mail was successfully created!');

        return Command::SUCCESS;
    }

    /**
     * @param Mail $mail
     *
     * @return Mail
     */
    protected function fillSubject(Mail $mail): Mail
    {
        $subjectQuestion = new Question('Please enter the subject of the mail' . PHP_EOL);
        $this->addStringValidationToQuestion($subjectQuestion);
        $subject = $this->questionHelper->ask($this->input, $this->output, $subjectQuestion);
        $mail->setSubject($subject);

        return $mail;
    }

    /**
     * @param Mail $mail
     *
     * @return Mail
     */
    protected function fillRecipient(Mail $mail): Mail
    {
        $recipient = $this->createRecipient();

        $mail->setRecipient($recipient);

        return $mail;
    }

    /**
     * @return Recipient
     */
    protected function createRecipient(): Recipient
    {
        $recipient = new Recipient();

        $recipientNameQuestion = new Question('Please enter the name of the recipient' . PHP_EOL);
        $this->addStringValidationToQuestion($recipientNameQuestion);
        $recipientName = $this->questionHelper->ask($this->input, $this->output, $recipientNameQuestion);
        $recipient->setName($recipientName);

        $recipientEmailQuestion = new Question('Please enter the email of the recipient' . PHP_EOL);
        $this->addEmailValidationToQuestion($recipientEmailQuestion);
        $recipientEmail = $this->questionHelper->ask($this->input, $this->output, $recipientEmailQuestion);

        $recipient->setEmail($recipientEmail);

        return $recipient;
    }

    /**
     * @param Mail $mail
     *
     * @return Mail
     */
    protected function fillContents(Mail $mail): Mail
    {
        $content = new Content();
        $content->setMail($mail);

        $typeQuestion = new ChoiceQuestion(
            'Please select type of the content',
            $this->mailConfig->getAllowedContentTypes(),
            0
        );

        $typeQuestion->setErrorMessage('Type %s is not supported.');
        $type = $this->questionHelper->ask($this->input, $this->output, $typeQuestion);
        $content->setType($type);

        $bodyQuestion = new Question('Please enter the body of the mail' . PHP_EOL);
        $this->addStringValidationToQuestion($bodyQuestion);
        $body = $this->questionHelper->ask($this->input, $this->output, $bodyQuestion);
        $content->setContent($body);

        $mail->addContent($content);

        $addAdditionalContentQuestion = new ConfirmationQuestion('Add additional content?(yes/no)' . PHP_EOL, false);

        if ($this->questionHelper->ask($this->input, $this->output, $addAdditionalContentQuestion)) {
            $this->fillContents($mail);
        }

        return $mail;
    }

    /**
     * @param Mail $mail
     *
     * @return Mail
     */
    protected function fillAdditionalRecipients(Mail $mail): Mail
    {
        $recipient = $this->createRecipient();
        $recipient->setMail($mail);
        $mail->addAdditionalRecipient($recipient);

        $addAdditionalRecipientQuestion = new ConfirmationQuestion(
            'Add additional recipient?(yes/no)' . PHP_EOL,
            false
        );

        if ($this->questionHelper->ask($this->input, $this->output, $addAdditionalRecipientQuestion)) {
            $this->fillAdditionalRecipients($mail);
        }

        return $mail;
    }

    /**
     * @param Question $question
     *
     * @return Question
     */
    protected function addStringValidationToQuestion(
        Question $question
    ): Question {
        $question->setValidator(function ($answer) {
            if (!is_string($answer)) {
                throw new RuntimeException('Field can\'t be empty.');
            }

            return $answer;
        });

        return $question;
    }

    /**
     * @param Question $question
     *
     * @return Question
     */
    protected function addEmailValidationToQuestion(
        Question $question
    ): Question {
        $question->setValidator(function ($answer) {
            if (!is_string($answer) || !filter_var($answer, FILTER_VALIDATE_EMAIL)) {
                throw new RuntimeException('Email is not valid.');
            }

            return $answer;
        });

        return $question;
    }
}
