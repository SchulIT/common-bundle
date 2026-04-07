<?php

namespace SchulIT\CommonBundle\Autoconfig\Roles;

use Override;

readonly class RoleHierarchyRoleResolver implements RoleResolverInterface {
    public function __construct(
        private array $roleHierarchy
    ) { }

    #[Override]
    public function resolve(): array {
        return array_unique(
            array_merge(
                array_keys($this->roleHierarchy),
                ['ROLE_USER']
            )
        );
    }
}