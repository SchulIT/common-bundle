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
    private $configVariable;
    private $menuService;
    private $translator;
    private $darkModeManager;

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

    public function getFilters() {
        return [
            new TwigFilter('log_level', [ $this, 'logLevel' ]),
            new TwigFilter('format_date', [ $this, 'formatDate' ]),
            new TwigFilter('format_datetime', [ $this, 'formatDatetime' ]),
            new TwigFilter('format_time', [ $this, 'formatTime' ]),
            new TwigFilter('format_w3c', [ $this, 'formatW3cDateTime'])
        ];
    }

    public function getFunctions() {
        return [
            new TwigFunction('is_darkmode', [ $this, 'isDarkModeEnabled' ])
        ];
    }

    public function isDarkModeEnabled(): bool {
        return $this->darkModeManager->isDarkModeEnabled();
    }

    public function formatDate(\DateTimeInterface $date) {
        return $date->format($this->translator->trans('date.format'));
    }

    public function formatDatetime(\DateTimeInterface $dateTime) {
        return $dateTime->format($this->translator->trans('date.with_time'));
    }

    public function formatTime(\DateTimeInterface $dateTime) {
        return $dateTime->format($this->translator->trans('date.time'));
    }

    public function formatW3cDateTime(\DateTimeInterface $dateTime) {
        return $dateTime->format(\DateTimeInterface::W3C);
    }

    public function logLevel($level) {
        return Logger::getLevelName($level);
    }
}