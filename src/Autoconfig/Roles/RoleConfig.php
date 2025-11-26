<?php

namespace SchulIT\CommonBundle\Autoconfig\Roles;

readonly class RoleConfig {
    public function __construct(public AttributeConfig $attributeConfig, public array $roles) { }
}