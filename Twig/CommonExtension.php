<?php

namespace SchulIT\CommonBundle\Twig;

use Monolog\Logger;
use SchulIT\CommonBundle\DarkMode\DarkModeManagerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;
use Twig\TwigFilter;
use Twig\TwigFunction;

class CommonExtension extends AbstractExtension implements GlobalsInterface {
    private ConfigVariable $configVariable;
    private $menuService;
    private TranslatorInterface $translator;
    private DarkModeManagerInterface $darkModeManager;

    public function __construct(ConfigVariable $configVariable, $menuService, TranslatorInterface $translator, DarkModeManagerInterface $darkModeManager) {
        $this->configVariable = $configVariable;
        $this->menuService = $menuService;
        $this->translator = $translator;
        $this->darkModeManager = $darkModeManager;
    }

    public function getGlobals(): array {
        return [
            'config' => $this->configVariable,
            'mainMenu' => $this->menuService
        ];
    }

    public function getFilters(): array {
        return [
            new TwigFilter('log_level', [ $this, 'logLevel' ]),
            new TwigFilter('format_date', [ $this, 'formatDate' ]),
            new TwigFilter('format_datetime', [ $this, 'formatDatetime' ]),
            new TwigFilter('format_time', [ $this, 'formatTime' ]),
            new TwigFilter('format_w3c', [ $this, 'formatW3cDateTime']),
            new TwigFilter('fqcn', [ $this, 'fqcn' ])
        ];
    }

    public function getFunctions(): array {
        return [
            new TwigFunction('is_darkmode', [ $this, 'isDarkModeEnabled' ])
        ];
    }

    public function isDarkModeEnabled(): bool {
        return $this->darkModeManager->isDarkModeEnabled();
    }

    public function formatDate(\DateTimeInterface $date): string {
        return $date->format($this->translator->trans('date.format'));
    }

    public function formatDatetime(\DateTimeInterface $dateTime): string {
        return $dateTime->format($this->translator->trans('date.with_time'));
    }

    public function formatTime(\DateTimeInterface $dateTime): string {
        return $dateTime->format($this->translator->trans('date.time'));
    }

    public function formatW3cDateTime(\DateTimeInterface $dateTime): string {
        return $dateTime->format(\DateTimeInterface::W3C);
    }

    public function fqcn($object): string {
        if(is_array($object)) {
            return '[' .
                implode(
                    ', ',
                    array_map([ $this, 'fqcn'], $object)
                ) .
                ']';
        }

        if(is_scalar($object)) {
            return gettype($object);
        }

        return get_class($object);
    }

    public function logLevel($level): string {
        return Logger::getLevelName($level);
    }
}