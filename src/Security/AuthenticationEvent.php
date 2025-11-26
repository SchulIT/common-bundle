<?php

namespace SchulIT\CommonBundle\Security;

use LightSaml\SpBundle\Security\Authentication\Token\SamlSpResponseToken;
use Symfony\Contracts\EventDispatcher\Event;

class AuthenticationEvent extends Event {

    public function __construct(private readonly UserInterface $user, private readonly SamlSpResponseToken $token) {

    }

    public function getUser(): UserInterface {
        return $this->user;
    }

    public function getToken(): SamlSpResponseToken {
        return $this->token;
    }
}