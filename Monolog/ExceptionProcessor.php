<?php

namespace SchulIT\CommonBundle\Monolog;

use Monolog\LogRecord;
use Monolog\Processor\ProcessorInterface;
use Throwable;

class ExceptionProcessor implements ProcessorInterface {

    public function __invoke(LogRecord $record): LogRecord {
        if(isset($record['context']['exception']) && $record['context']['exception'] instanceof Throwable) {
            $record['extra']['exception'] = [
                'class' => $record['context']['exception']::class,
                'message' => $record['context']['exception']->getMessage(),
                'stacktrace' => $record['context']['exception']->getTraceAsString()
            ];
        }

        return $record;
    }
}