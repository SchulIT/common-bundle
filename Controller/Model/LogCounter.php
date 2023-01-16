<?php

namespace SchulIT\CommonBundle\Controller\Model;

class LogCounter {

    public function __construct(public readonly string $level, public readonly string $name, public readonly int $counter = 0) { }
}