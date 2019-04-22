<?php

namespace SchoolIT\CommonBundle\Twig;

use Monolog\Logger;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;
use Twig\TwigFilter;

class CommonExtension extends AbstractExtension implements GlobalsInterface {
    private $configVariable;
    private $menuService;
    private $translator;

    public function __construct(ConfigVariable $configVariable, $menuService, TranslatorInterface $translator) {
        $this->configVariable = $configVariable;
        $this->menuService = $menuService;
        $this->translator = $translator;
    }

    public function getGlobals() {
        return [
            'config' => $this->configVariable,
            'mainMenu' => $this->menuService
        ];
    }

    public function getFilters() {
        return [
            new TwigFilter('log_level', [ $this, 'logLevel' ]),
            new TwigFilter('format_date', [ $this, 'formatDate' ]),
            new TwigFilter('format_datetime', [ $this, 'formatDatetime' ])
        ];
    }

    public function formatDate(\DateTimeInterface $date) {
        return $date->format($this->translator->trans('date.format'));
    }

    public function formatDatetime(\DateTimeInterface $dateTime) {
        return $dateTime->format($this->translator->trans('date.with_time'));
    }

    public function logLevel($level) {
        return Logger::getLevelName($level);
    }
}