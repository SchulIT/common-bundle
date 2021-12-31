<?php

namespace SchulIT\CommonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class LogoutController extends AbstractController {

    private string $logoutUrl;

    public function __construct(string $logoutUrl) {
        $this->logoutUrl = $logoutUrl;
    }

    public function logout(): Response {
        return $this->render('@Common/auth/logout.html.twig', [
            'logout_url' => $this->logoutUrl
        ]);
    }
}