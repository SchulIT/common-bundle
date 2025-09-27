<?php

namespace SchulIT\CommonBundle\Http\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_CLASS)]
readonly class ForbiddenRedirect extends AbstractRedirect {}
