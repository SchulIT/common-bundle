<?php

namespace SchulIT\CommonBundle\Monolog;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Types\Types;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Level;
use Monolog\LogRecord;

class DatabaseHandler extends AbstractProcessingHandler {

    public function __construct(private readonly Connection $connection, Level $level = Level::Info) {
        parent::__construct($level, false);
    }

    /**
     * @inheritDoc
     */
    protected function write(LogRecord $record): void {
        $entry = [
            'channel' => $record['channel'],
            'level' => $record['level'],
            'message' => $record['formatted'],
            'time' => $record['datetime'],
            'details' => is_array($record['extra']) ? $record['extra'] : null
        ];

        try {
            $this->connection
                ->insert('log', $entry, [
                    Types::STRING,
                    Types::INTEGER,
                    Types::TEXT,
                    Types::DATETIME_IMMUTABLE,
                    Types::JSON
                ]);
        } catch (Exception) {
            // Logging failed :-/
        }
    }
}