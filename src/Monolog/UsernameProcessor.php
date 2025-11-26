<?php

namespace SchulIT\CommonBundle\Monolog;

use Monolog\LogRecord;
use Monolog\Processor\ProcessorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UsernameProcessor implements ProcessorInterface {

    private ?string $username = null;

    public function __construct(private readonly TokenStorageInterface $tokenStorage)
    {
    }

    /**
     * @inheritDoc
     */
    public function __invoke(LogRecord $record): LogRecord {
        $record['extra']['username'] = $this->getUsername();

        return $record;
    }

    private function getUsername(): ?string {
        if($this->username === null) {
            $token = $this->tokenStorage->getToken();

            if($token !== null) {
                $this->username = $token->getUserIdentifier();
            }
        }

        return $this->username;
    }
}