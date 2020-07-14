<?php

namespace SchulIT\CommonBundle\Command;

use Symfony\Bundle\SwiftmailerBundle\Command\SendEmailCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Spooled emails are sent using this command. This command overrides the original command provided by Symfony
 * such as the message-limit option is passed based on the configuration.
 */
class SendMailsCommand extends SendEmailCommand {

    private $messageLimit;
    private $command;

    public function __construct(int $messageLimit, string $name = null) {
        parent::__construct($name);

        $this->messageLimit = $messageLimit;
    }

    public function configure() {
        parent::configure();

        $this->setName('app:mails:send')
            ->setDescription('Sends next batch of mails');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $input->setOption('message-limit', $this->messageLimit);
        return parent::execute($input, $output);
    }
}