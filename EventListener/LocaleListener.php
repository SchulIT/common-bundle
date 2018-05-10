<?php

namespace SchoolIT\CommonBundle\EventListener;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class LocaleListener implements EventSubscriberInterface {

    private $locales;
    private $logger;

    public function __construct(array $locales, LoggerInterface $logger = null) {
        $this->locales = $locales;
        $this->logger = $logger ?? new NullLogger();
    }

    public function onKernelRequest(GetResponseEvent $event) {
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

    public static function getSubscribedEvents() {
        return [
            KernelEvents::REQUEST => [
                [ 'onKernelRequest', 17 ]
            ]
        ];
    }
}