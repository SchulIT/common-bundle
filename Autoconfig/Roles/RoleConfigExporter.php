<?php

namespace SchulIT\CommonBundle\Autoconfig\Roles;

use Symfony\Contracts\Translation\TranslatorInterface;

readonly class RoleConfigExporter {

    public function __construct(private array $roleHierarchy,
                                private string $roleAttributeName,
                                private array $ignoreRoles,
                                private TranslatorInterface $translator) { }

    public function getRoleNames(): array {
        $roles = array_keys($this->roleHierarchy);
        return array_filter($roles, fn(string $role) => !in_array($role, $this->ignoreRoles));
    }

    /**
     * @return Role[]
     */
    public function getRoles(bool $ignoreRolesWithoutTranslation = false): array {
        $roles = [ ];

        foreach($this->getRoleNames() as $roleName) {
            $translationKey = sprintf('roles.%s', $roleName);
            $description = $this->translator->trans($translationKey, [], 'autoconfig');

            if($description === $translationKey) {
                if($ignoreRolesWithoutTranslation === true) {
                    continue;
                } else {
                    $description = $roleName;
                }
            }

            $roles[] = new Role($roleName, $description);
        }

        return $roles;
    }

    public function getConfig(): RoleConfig {
        return new RoleConfig(
            new AttributeConfig(
                $this->translator->trans('attribute.display_name', [], 'autoconfig'),
                $this->translator->trans('attribute.description', [], 'autoconfig'),
                false,
                $this->roleAttributeName,
                AttributeType::CHOICE,
                true
            ),
            $this->getRoles(true)
        );
    }
}