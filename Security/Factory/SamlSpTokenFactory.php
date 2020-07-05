<?php

namespace SchulIT\CommonBundle\Security\Factory;

use LightSaml\SpBundle\Security\Authentication\Token\SamlSpResponseToken;
use SchulIT\CommonBundle\Security\AuthenticationEvent;
use SchulIT\CommonBundle\Security\SecurityEvents;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Creates a Token based on the Saml Request.
 *
 * This class is used to hook into the process of turning the SAML Response into
 * a Security Token in order to update the user after a success login.
 * @package App\Security\Factory
 */
class SamlSpTokenFactory extends \LightSaml\SpBundle\Security\Authentication\Token\SamlSpTokenFactory {

    private $dispatcher;

    public function __construct(EventDispatcherInterface $dispatcher) {
        $this->dispatcher = $dispatcher;
    }

    /**
     * @inheritDoc
     */
    public function create($providerKey, array $attributes, $user, SamlSpResponseToken $responseToken) {
        $this->dispatcher->dispatch(new AuthenticationEvent($user, $responseToken));

        return parent::create($providerKey, $attributes, $user, $responseToken);
    }
}