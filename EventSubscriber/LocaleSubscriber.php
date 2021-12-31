<?php

namespace SchulIT\CommonBundle\EventSubscriber;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class LocaleSubscriber implements EventSubscriberInterface {

    private array $locales;
    private LoggerInterface $logger;

    public function __construct(array $locales, LoggerInterface $logger = null) {
        $this->locales = $locales;
        $this->logger = $logger ?? new NullLogger();
    }

    public function onKernelRequest(RequestEvent $event) {
        $request = $event->getRequest();

        if (!$request->hasPreviousSession()) {
            return;
        }

        $locale = $request->getPreferredLanguage($this->locales);

        if($locale !== null) {
            $this->logger->debug(
                sprintf('Set locale to "%s" based on request', $locale)
            );

            $request->setLocale($locale);
            $request->getSession()->set('_locale', $locale);
        }
    }

    public static function getSubscribedEvents(): array {
        return [
            KernelEvents::REQUEST => [
                [ 'onKernelRequest', 17 ]
            ]
        ];
    }
}