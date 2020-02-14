<?php

namespace SchoolIT\CommonBundle\EventListener;

use LightSaml\Error\LightSamlBindingException;
use LightSaml\Error\LightSamlContextException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Twig\Environment;

class SamlExceptionListener implements EventSubscriberInterface {

    private $retryRoute;
    private $twig;

    public function __construct(string $retryRoute, Environment $twig) {
        $this->retryRoute = $retryRoute;
        $this->twig = $twig;
    }

    public function onKernelException(ExceptionEvent $event) {
        $throwable = $event->getThrowable();

        if($throwable instanceof LightSamlContextException || $throwable instanceof LightSamlBindingException) {
            $response = new Response(
                $this->twig->render('@Common/exception/saml.html.twig', [
                    'route' => $this->retryRoute
                ])
            );

            $event->setResponse($response);
        }
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents() {
        return [
            KernelEvents::EXCEPTION => [
                ['onKernelException', 0 ]
            ]
        ];
    }
}