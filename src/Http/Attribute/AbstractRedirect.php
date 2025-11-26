<?php

namespace SchulIT\CommonBundle\Http\Attribute;

abstract readonly class AbstractRedirect {
    public function __construct(public string $redirectRoute,
                                public string $flashMessage,
                                public array $redirectRouteParameters = [ ],
                                public array $flashMessageParameters = [ ],
                                public string $flashMessageType = 'error') {

    }
}
