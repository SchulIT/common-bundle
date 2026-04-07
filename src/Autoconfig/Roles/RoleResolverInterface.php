<?php

namespace SchulIT\CommonBundle\Autoconfig\Roles;

interface RoleResolverInterface {

    /**
     * @return string[]
     */
    public function resolve(): array;
}