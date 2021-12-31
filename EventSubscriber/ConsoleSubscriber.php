<?php

namespace SchulIT\CommonBundle\EventSubscriber;

use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Kernel;

class ConsoleSubscriber implements EventSubscriberInterface {

    use ContainerAwareTrait;

    public function onConsoleCommand(ConsoleCommandEvent $event) {
        $command = $event->getCommand();
        $application = $command->getApplication();

        $application
            ->setName($this->container->getParameter('app.common.name'));

        $version = sprintf("%s (Symfony %s)", $this->container->getParameter('app.common.version'), Kernel::VERSION);
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