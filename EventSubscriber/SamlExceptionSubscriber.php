<?php

namespace SchulIT\CommonBundle\EventSubscriber;

use LightSaml\Error\LightSamlException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Twig\Environment;

class SamlExceptionSubscriber implements EventSubscriberInterface {

    private string $retryRoute;
    private string $loggedInRoute;
    private TokenStorageInterface $tokenStorage;
    private Environment $twig;
    private UrlGeneratorInterface $urlGenerator;

    public function __construct(string $retryRoute, string $loggedInRoute, TokenStorageInterface $tokenStorage, Environment $twig, UrlGeneratorInterface $urlGenerator) {
        $this->retryRoute = $retryRoute;
        $this->loggedInRoute = $loggedInRoute;
        $this->tokenStorage = $tokenStorage;
        $this->twig = $twig;
        $this->urlGenerator = $urlGenerator;
    }

    public function onKernelException(ExceptionEvent $event): void {
        $throwable = $event->getThrowable();

        if($throwable instanceof LightSamlException) {
            if($this->tokenStorage->getToken() !== null && $this->tokenStorage->getToken()->isAuthenticated()) {
                $response = new RedirectResponse(
                    $this->urlGenerator->generate($this->loggedInRoute)
                );

                $event->setResponse($response);
                return;
            }

            $response = new Response(
                $this->twig->render('@Common/error/lightsaml.html.twig', [
                    'route' => $this->retryRoute,
                    'exception' => $throwable,
                    'type' => get_class($throwable),
                    'message' => $throwable->getMessage()
                ]),
                200
            );

            $event->setResponse($response);
            $event->allowCustomResponseCode();
        }
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents(): array {
        return [
            KernelEvents::EXCEPTION => [
                ['onKernelException', 10 ]
            ]
        ];
    }
}