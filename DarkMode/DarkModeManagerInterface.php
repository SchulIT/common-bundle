<?php

namespace SchoolIT\CommonBundle\DarkMode;

interface DarkModeManagerInterface {
    public function enableDarkMode(): void;

    public function disableDarkMode(): void;

    public function isDarkModeEnabled(): bool;
}