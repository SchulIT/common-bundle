<?php

namespace SchoolIT\CommonBundle\Twig;

use Monolog\Logger;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;

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
            new \Twig_SimpleFilter('log_level', [ $this, 'logLevel' ]),
            new \Twig_SimpleFilter('format_date', [ $this, 'format_date' ])
        ];
    }

    public function format_date(\DateTimeInterface $date) {
        return $date->format($this->translator->trans('date.with_time'));
    }

    public function logLevel($level) {
        return Logger::getLevelName($level);
    }
}