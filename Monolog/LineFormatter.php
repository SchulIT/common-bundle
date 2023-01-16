<?php

namespace SchulIT\CommonBundle\Monolog;

use Monolog\Formatter\FormatterInterface;
use Monolog\LogRecord;

class LineFormatter implements FormatterInterface {

    /**
     * @inheritDoc
     */
    public function format(LogRecord $record): mixed {
        $line = $record['message'];

        foreach($record['context'] as $key => $value) {
            $line = str_replace('{' . $key . '}', is_scalar($value) ? $value : json_encode($value, JSON_THROW_ON_ERROR), $line);
        }

        return $line;
    }

    /**
     * @inheritDoc
     */
    public function formatBatch(array $records): array {
        foreach($records as $key => $record) {
            $records[$key] = $this->format($record);
        }

        return $records;
    }
}