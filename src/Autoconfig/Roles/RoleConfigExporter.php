<?php

namespace SchulIT\CommonBundle\Autoconfig\Roles;

use Symfony\Contracts\Translation\TranslatorInterface;

readonly class RoleConfigExporter {

    public function __construct(
        private RoleResolverInterface $roleResolver,
        private string $roleAttributeName,
        private array $ignoreRoles,
        private RoleTranslatorInterface $roleTranslator,
        private TranslatorInterface $translator,
        private bool $ignoreRolesWithoutTranslation = false,
        private string $translationDomain = 'autoconfig'
    ) { }

    public function getRoleNames(): array {
        $roles = is_array($this->roleResolver) ? $this->roleResolver : $this->roleResolver->resolve();
        return array_filter($roles, fn(string $role) => !in_array($role, $this->ignoreRoles));
    }

    /**
     * @return Role[]
     */
    public function getRoles(bool $ignoreRolesWithoutTranslation = false): array {
        $roles = [ ];

        foreach($this->getRoleNames() as $roleName) {
            $description = $this->roleTranslator->translate($roleName);

            if($description === null && $ignoreRolesWithoutTranslation === true) {
                continue;
            }

            $roles[] = new Role($roleName, $description);
        }

        return $roles;
    }

    public function getConfig(): RoleConfig {
        return new RoleConfig(
            new AttributeConfig(
                $this->translator->trans('role_attribute.display_name', [], $this->translationDomain),
                $this->translator->trans('role_attribute.description', [], $this->translationDomain),
                false,
                $this->roleAttributeName,
                AttributeType::CHOICE,
                true
            ),
            $this->getRoles($this->ignoreRolesWithoutTranslation)
        );
    }
}