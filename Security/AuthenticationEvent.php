<?php

namespace SchoolIT\CommonBundle\Security;

use LightSaml\SpBundle\Security\Authentication\Token\SamlSpResponseToken;
use Symfony\Contracts\EventDispatcher\Event;

class AuthenticationEvent extends Event {
    private $user;
    private $token;

    /**
     * AuthenticationEvent constructor.
     * @param UserInterface $user
     * @param SamlSpResponseToken $token
     */
    public function __construct($user, SamlSpResponseToken $token) {
        $this->user = $user;
        $this->token = $token;
    }

    public function getUser() {
        return $this->user;
    }

    public function getToken() {
        return $this->token;
    }
}