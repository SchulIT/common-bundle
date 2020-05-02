<?php

namespace SchoolIT\CommonBundle\SwiftMailer;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class EmailSpoolHelper {
    private $path;
    private $logger;

    public function __construct(string $path, LoggerInterface $logger = null) {
        $this->path = $path;
        $this->logger = $logger ?? new NullLogger();
    }

    public function getSpooledMessages() {
        /** @var \Swift_Message[] $messages */
        $messages = [ ];

        try {
            $di = new \DirectoryIterator($this->path);

            foreach($di as $file) {
                if($file->isFile() && substr($file->getRealPath(), -8) == '.message') {
                    $contents = file_get_contents($file->getRealPath());

                    /** @var \Swift_Message $message */
                    $message = unserialize($contents);
                    $messages[] = $message;
                }
            }
        } catch(\UnexpectedValueException $e) {
            $this->logger->error('Cannot open spool directory.', [
                'path' => $this->path
            ]);
        } catch(\RuntimeException $e) {
            $this->logger->error('Cannot open spool directory as path is empty', [
                'path' => $this->path
            ]);
        }

        return $messages;
    }
}