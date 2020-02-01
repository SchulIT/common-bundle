<?php

namespace SchoolIT\CommonBundle\Monolog;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Types;
use Monolog\Handler\AbstractProcessingHandler;

abstract class AbstractDatabaseHandler extends AbstractProcessingHandler {

    /**
     * @inheritDoc
     */
    protected function write(array $record) {
        try {
            $this->createEntryFromRecord($record);

            $this->setBubble(false);
        } catch(\Exception $e) {
            // Something went wrong -> bubble record
            $this->setBubble(true);
        }
    }

    /**
     * @return Connection
     */
    protected abstract function getConnection();

    private function createEntryFromRecord(array $record) {
        $entry = [
            'channel' => $record['channel'],
            'level' => $record['level'],
            'message' => $record['message'],
            'time' => $record['datetime'],
            'details' => null
        ];

        $details = $this->formatRequest($record);

        if(!empty($details)) {
            $details .= PHP_EOL . $this->formatException($record);
        } else {
            $details = $this->formatException($record);
        }

        $entry['details'] = $details;

        $this->getConnection()
            ->insert('log', $entry, [
                Types::STRING,
                Types::INTEGER,
                Types::TEXT,
                Types::DATETIME_MUTABLE,
                Types::TEXT
            ]);
    }

    protected abstract function formatRequest(array $record);

    protected function formatException(array $record) {
        $context = $record['context'];

        if(!isset($context['exception']) || !$context['exception'] instanceof \Exception) {
            return null;
        }

        /** @var \Exception $exception */
        $exception = $context['exception'];

        $details = <<<EOF
Class: %s
Message: %s
File: %s @ line %d
Trace:
%s
EOF;

        return sprintf($details,
            get_class($exception),
            $exception->getMessage(),
            $exception->getFile(),
            $exception->getLine(),
            $exception->getTraceAsString()
        );
    }
}