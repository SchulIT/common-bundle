<?php

namespace SchoolIT\CommonBundle\EventListener;

use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Psr\Log\NullLogger;
use Swift_Events_SendEvent;

/**
 * Listens for emails and logs them with monolog (found here: https://stackoverflow.com/a/36167405)
 */
class EmailListener implements \Swift_Events_SendListener {

    private $logger;

    public function __construct(LoggerInterface $logger = null) {
        $this->logger = $logger ?? new NullLogger();
    }

    /**
     * @inheritDoc
     */
    public function beforeSendPerformed(Swift_Events_SendEvent $evt) { }

    /**
     * @inheritDoc
     */
    public function sendPerformed(Swift_Events_SendEvent $evt) {
        $level = $this->getLogLevel($evt);
        $message = $evt->getMessage();

        $this->logger->log($level, sprintf('%s (ID: %s)', $message->getSubject(), $message->getId()), [
            'result' => $evt->getResult(),
            'subject' => $message->getSubject(),
            'from' => $message->getFrom(),
            'to' => $message->getTo(),
            'cc' => $message->getCc(),
            'bcc' => $message->getBcc()
        ]);
    }

    private function getLogLevel(Swift_Events_SendEvent $event): string {
        switch ($event->getResult()) {
            case Swift_Events_SendEvent::RESULT_PENDING:
            case Swift_Events_SendEvent::RESULT_SPOOLED:
                return LogLevel::DEBUG;

            case Swift_Events_SendEvent::RESULT_FAILED:
                return LogLevel::CRITICAL;

            case Swift_Events_SendEvent::RESULT_TENTATIVE:
                return LogLevel::ERROR;

            case Swift_Events_SendEvent::RESULT_SUCCESS:
                return LogLevel::INFO;
        }
    }
}