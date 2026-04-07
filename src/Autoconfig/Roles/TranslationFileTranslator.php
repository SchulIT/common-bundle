<?php

namespace SchulIT\CommonBundle\Autoconfig\Roles;

use Override;
use Symfony\Contracts\Translation\TranslatorInterface;

readonly class TranslationFileTranslator implements RoleTranslatorInterface {

    public function __construct(
        private TranslatorInterface $translator,
        private string $translationDomain = 'autoconfig'
    ) {

    }

    #[Override]
    public function translate(string $roleName): string|null {
        $translationKey = sprintf('roles.%s', $roleName);
        $description = $this->translator->trans($translationKey, [], $this->translationDomain);

        if($description === $translationKey) {
            return null;
        }

        return $description;
    }
}