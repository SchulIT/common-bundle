<?php

namespace SchulIT\CommonBundle\Http;

use SchulIT\CommonBundle\Http\Attribute\NotFoundRedirect;
use Override;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class HttpNotFoundExceptionSubscriber extends AbstractHttpExceptionSubscriber {


    #[Override]
    public function supports(Throwable $throwable): bool {
        return $throwable instanceof NotFoundHttpException;
    }

    #[Override]
    public function getAttributeFqcn(): string {
        return NotFoundRedirect::class;
    }
}
