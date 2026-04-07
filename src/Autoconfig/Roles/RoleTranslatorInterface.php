<?php

namespace SchulIT\CommonBundle\Autoconfig\Roles;

interface RoleTranslatorInterface {
    public function translate(string $roleName): string|null;
}