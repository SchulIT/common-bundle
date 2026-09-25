<?php

namespace SchulIT\CommonBundle\Autoconfig\Roles;

interface RoleResolverInterface {

    /**
     * @return string[]|Role[]
     */
    public function resolve(): array;
}