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

    /**
     * @return Role[]
     */
    public function getRoles(bool $ignoreRolesWithoutTranslation = false): array {
        $roles = [ ];

        $resolvedRoles = $this->roleResolver->resolve();

        foreach($resolvedRoles as $roleNameOrRole) {
            if($roleNameOrRole instanceof Role) {
                $roles[] = $roleNameOrRole;
            } else if(is_string($roleNameOrRole) && !in_array($roleNameOrRole, $this->ignoreRoles)) {
                $description = $this->roleTranslator->translate($roleNameOrRole);

                if($description === null && $ignoreRolesWithoutTranslation === true) {
                    continue;
                }

                $roles[] = new Role($roleNameOrRole, $description);
            }
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