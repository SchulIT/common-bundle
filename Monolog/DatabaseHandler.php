<?php

namespace SchulIT\CommonBundle\Monolog;

use Doctrine\DBAL\Connection;
use Monolog\Logger;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class DatabaseHandler extends AbstractDatabaseHandler {
    private $connection;
    private $tokenStorage;
    private $requestStack;

    public function __construct(Connection $connection, TokenStorageInterface $tokenStorage, RequestStack $requestStack, $level = Logger::INFO) {
        parent::__construct($level);

        $this->connection = $connection;
        $this->tokenStorage = $tokenStorage;
        $this->requestStack = $requestStack;
    }

    protected function getConnection() {
        return $this->connection;
    }

    protected function formatRequest(array $record) {
        $details = <<<EOF
Username: %s
User-Agent: %s
URL: %s
Data: %s
EOF;

        $username = null;
        $token = $this->tokenStorage->getToken();

        if($token !== null) {
            /** @var UserInterface $user */
            $user = $token->getUser();

            if($user !== null && $user instanceof UserInterface) {
                $username = $user->getUsername();
            } else if(is_string($user)) {
                $username = $user;
            } else if(method_exists($user, '__toString')) {
                $username = (string)$user;
            }
        }

        $url = null;
        $userAgent = null;

        $request = $this->requestStack->getMasterRequest();

        if($request !== null) {
            $url = $request->getRequestUri();
            $userAgent = $request->headers->get('User-Agent');
        }

        $extra = null;

        if(isset($record['context']['extra'])) {
            $extra = var_export($record['context']['extra'], true);
        }

        return sprintf($details,
            $username ?? 'N/A',
            $userAgent ?? 'N/A',
            $url ?? 'N/A',
            $extra ?? 'N/A'
        );
    }
}