<?php

namespace SchulIT\CommonBundle\Security;

use LightSaml\SpBundle\Security\Authentication\Token\SamlSpResponseToken;
use Symfony\Contracts\EventDispatcher\Event;

class AuthenticationEvent extends Event {
    private UserInterface $user;
    private SamlSpResponseToken $token;

    public function __construct($user, SamlSpResponseToken $token) {
        $this->user = $user;
        $this->token = $token;
    }

    public function getUser(): UserInterface {
        return $this->user;
    }

    public function getToken(): SamlSpResponseToken {
        return $this->token;
    }
}