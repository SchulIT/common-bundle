<?php

namespace SchulIT\CommonBundle\EventSubscriber;

use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Kernel;

readonly class ConsoleSubscriber implements EventSubscriberInterface {

    public function __construct(
        #[Autowire(param: 'app.common.name')] private string $appName,
        #[Autowire(param: 'app.common.version')] private string $appVersion
    ) {

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

    public static function getSubscribedEvents(): array {
        return [
            ConsoleCommandEvent::class => 'onConsoleCommand'
        ];
    }
}