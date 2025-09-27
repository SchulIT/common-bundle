<?php

namespace SchulIT\CommonBundle\Autoconfig\Roles;

readonly class AttributeConfig {
    public function __construct(public string $displayName,
                                public string $description,
                                public bool $isUserEditable,
                                public string $samlAttributeName,
                                public AttributeType $type,
                                public bool $isMultipleChoice) {
    }
}