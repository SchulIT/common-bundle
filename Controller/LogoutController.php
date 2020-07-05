<?php

namespace SchulIT\CommonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LogoutController extends AbstractController {

    private $logoutUrl;

    public function __construct(string $logoutUrl) {
        $this->logoutUrl = $logoutUrl;
    }

    public function logout() {
        return $this->render('@Common/auth/logout.html.twig', [
            'logout_url' => $this->logoutUrl
        ]);
    }
}