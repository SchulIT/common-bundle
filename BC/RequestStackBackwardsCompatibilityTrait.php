<?php

namespace SchulIT\CommonBundle\BC;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

trait RequestStackBackwardsCompatibilityTrait {
    protected function getMainRequest(RequestStack $requestStack): ?Request {
        if(method_exists($requestStack, 'getMainRequest')) {
            return $requestStack->getMainRequest();
        }

        return $requestStack->getMasterRequest();
    }
}