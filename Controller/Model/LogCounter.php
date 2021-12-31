<?php

namespace SchulIT\CommonBundle\Controller\Model;

class LogCounter {
    public string $level;

    public string $name;

    public int $counter;

    public function __construct(string $level, string $name, int $counter = 0) {
        $this->level = $level;
        $this->name = $name;
        $this->counter = $counter;
    }
}