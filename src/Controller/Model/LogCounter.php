<?php

namespace SchulIT\CommonBundle\Controller\Model;

class LogCounter {

    public function __construct(public readonly int $level, public readonly string $name, public int $counter = 0) { }
}