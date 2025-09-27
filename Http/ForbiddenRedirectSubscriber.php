<?php

namespace SchulIT\CommonBundle\Http;

use SchulIT\CommonBundle\Http\Attribute\ForbiddenRedirect;
use Override;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Throwable;

class ForbiddenRedirectSubscriber extends AbstractHttpExceptionSubscriber {

    #[Override]
    public function supports(Throwable $throwable): bool {
        return $throwable instanceof AccessDeniedHttpException;
    }

    #[Override]
    public function getAttributeFqcn(): string {
        return ForbiddenRedirect::class;
    }
}
