<?php

namespace SchulIT\CommonBundle\Http;

use Override;
use ReflectionException;
use SchulIT\CommonBundle\Http\Attribute\AbstractRedirect;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\FlashBagAwareSessionInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Throwable;

abstract class AbstractHttpExceptionSubscriber implements EventSubscriberInterface {

    public function __construct(protected UrlGeneratorInterface $urlGenerator,
                                protected TranslatorInterface $translator,
                                private readonly AttributeResolver $attributeResolver) {

    }

    public abstract function supports(Throwable $throwable): bool;

    /**
     * @return class-string<AbstractRedirect>
     */
    public abstract function getAttributeFqcn(): string;

    /**
     * @throws ReflectionException
     */
    public function onKernelException(ExceptionEvent $event): void {
        if(!$this->supports($event->getThrowable())) {
            return;
        }

        $request = $event->getRequest();
        $controller = $request->attributes->get('_controller');
        $methodName = '__invoke';

        if(str_contains($controller, '::')) {
            [$controller, $methodName] = explode('::', $controller);
        }

        if($controller === null || $methodName === null) {
            return;
        }

        /** @var AbstractRedirect|null $attribute */
        $attribute = $this->attributeResolver->resolve($this->getAttributeFqcn(), $controller, $methodName);

        if($attribute === null) {
            return;
        }

        if(!empty($attribute->flashMessage)) {
            $session = $request->getSession();

            if ($session instanceof FlashBagAwareSessionInterface) {
                $session->getFlashBag()->add(
                    'error',
                    $this->translator->trans($attribute->flashMessage, $attribute->flashMessageParameters)
                );
            }
        }

        $event->setResponse(
            new RedirectResponse(
                $this->urlGenerator->generate(
                    $attribute->redirectRoute,
                    $attribute->redirectRouteParameters,
                    UrlGeneratorInterface::ABSOLUTE_URL)
            )
        );
    }

    #[Override]
    public static function getSubscribedEvents(): array {
        return [
            KernelEvents::EXCEPTION => ['onKernelException', -100]
        ];
    }
}
