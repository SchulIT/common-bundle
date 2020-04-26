<?php

namespace SchoolIT\CommonBundle\Controller;

use SchoolIT\CommonBundle\DarkMode\DarkModeManagerInterface;
use SchoolIT\CommonBundle\Utils\RefererHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DarkModeController extends AbstractController {

    /**
     * @Route("/settings/dark_mode", name="toggle_darkmode")
     */
    public function toggleDarkMode(DarkModeManagerInterface $darkModeManager, RefererHelper $refererHelper) {
        if($darkModeManager->isDarkModeEnabled()) {
            $darkModeManager->disableDarkMode();
        } else {
            $darkModeManager->enableDarkMode();
        }

        return $this->redirect($refererHelper->getRefererPathFromRequest());
    }
}