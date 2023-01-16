<?php

namespace SchulIT\CommonBundle\Monolog;

use Monolog\LogRecord;
use Monolog\Processor\ProcessorInterface;
use Throwable;

class ExceptionProcessor implements ProcessorInterface {

    public function __invoke(LogRecord $records): LogRecord {
        if(isset($records['context']['exception']) && $records['context']['exception'] instanceof Throwable) {
            $records['extra']['exception'] = [
                'class' => $records['context']['exception']::class,
                'message' => $records['context']['exception']->getMessage(),
                'stacktrace' => $records['context']['exception']->getTraceAsString()
            ];
        }

        return $records;
    }
}