<?php

namespace SchoolIT\CommonBundle\Twig;

use Monolog\Logger;

class CommonExtension extends \Twig_Extension implements \Twig_Extension_GlobalsInterface{
    private $configVariable;
    private $menuService;

    public function __construct(ConfigVariable $configVariable, $menuService) {
        $this->configVariable = $configVariable;
        $this->menuService = $menuService;
    }

    public function getGlobals() {
        return [
            'config' => $this->configVariable,
            'mainMenu' => $this->menuService
        ];
    }

    public function getFilters() {
        return [
            new \Twig_SimpleFilter('log_level', [ $this, 'logLevel' ])
        ];
    }

    public function logLevel($level) {
        return Logger::getLevelName($level);
    }
}