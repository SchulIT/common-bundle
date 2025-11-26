<?php

namespace SchulIT\CommonBundle\EventSubscriber;

use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Kernel;

class ConsoleSubscriber implements EventSubscriberInterface {

    public function __construct(private readonly string $appName, private readonly string $appVersion) {

    }

    public function onConsoleCommand(ConsoleCommandEvent $event): void {
        $command = $event->getCommand();
        $application = $command->getApplication();

        $application
            ->setName($this->appName);

        $version = sprintf("%s (Symfony %s)", $this->appVersion, Kernel::VERSION);
        $application
            ->setVersion($version);
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array {
        return [
            ConsoleEvents::COMMAND => [
                [ 'onConsoleCommand']
            ]
        ];
    }
}