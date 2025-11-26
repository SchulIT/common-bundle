<?php

namespace SchulIT\CommonBundle\Autoconfig\Roles;

readonly class Role {
    public function __construct(public string $name, public string $description) { }
}