<?php

namespace SchulIT\CommonBundle\Controller;

use SchulIT\CommonBundle\DarkMode\DarkModeManagerInterface;
use SchulIT\CommonBundle\Utils\RefererHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

class DarkModeController extends AbstractController {

    /**
     * @Route("/settings/dark_mode", name="toggle_darkmode")
     */
    public function toggleDarkMode(DarkModeManagerInterface $darkModeManager, RefererHelper $refererHelper): RedirectResponse {
        if($darkModeManager->isDarkModeEnabled()) {
            $darkModeManager->disableDarkMode();
        } else {
            $darkModeManager->enableDarkMode();
        }

        return $this->redirect($refererHelper->getRefererPathFromRequest());
    }
}