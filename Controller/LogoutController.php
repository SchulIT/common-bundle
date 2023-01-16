<?php

namespace SchulIT\CommonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class LogoutController extends AbstractController {

    public function __construct(private readonly string $logoutUrl) { }

    public function logout(): Response {
        return $this->render('@Common/auth/logout.html.twig', [
            'logout_url' => $this->logoutUrl
        ]);
    }
}