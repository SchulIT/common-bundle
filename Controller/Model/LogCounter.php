<?php

namespace SchoolIT\CommonBundle\Controller\Model;

class LogCounter {
    public $level;

    public $name;

    public $counter;

    public function __construct($level, $name, $counter = 0) {
        $this->level = $level;
        $this->name = $name;
        $this->counter = $counter;
    }
}