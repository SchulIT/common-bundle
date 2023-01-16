<?php

namespace SchulIT\CommonBundle\Utils;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;

class RefererHelper {

    private const RefQueryName = 'ref';

    public function __construct(private readonly RequestStack $requestStack, private readonly RouterInterface $router) { }

    public function getRefererPathFromQuery(array $mapping, string $route, array $parameters = [ ], array $fallbackParameters = [ ]): string {
        $request = $this->requestStack->getMainRequest();
        $referer = $request->query->get(static::RefQueryName, null);

        if(isset($mapping[$referer])) {
            $route = $mapping[$referer];
        } else {
            $parameters = $fallbackParameters;
        }

        return $this->router->generate($route, $parameters);
    }

    public function getRefererPathFromRequest(?string $fallbackRoute = null, array $fallbackParameters = [ ]): string {
        $request = $this->requestStack->getMainRequest();
        $referer = $request->headers->get('referer');

        if($referer === null) {
            if($fallbackRoute === null) {
                return '/';
            }

            return $this->router->generate($fallbackRoute, $fallbackParameters);
        }

        $baseUrl = $request->getSchemeAndHttpHost();
        $lastPath = substr($referer, strpos($referer, $baseUrl) + strlen($baseUrl));

        $parts = parse_url($lastPath);
        $queryParameter = [ ];

        if(isset($parts['query'])) {
            parse_str($parts['query'], $queryParameter);
        }

        if(!isset($parts['path'])) {
            if($fallbackRoute === null) {
                return '/';
            }

            return $this->router->generate($fallbackRoute, $fallbackParameters);
        }

        $params = $this->router->getMatcher()->match($parts['path']);

        $parameters = array_filter($params, function($key) {
            return substr($key, 0, 1) !== '_';
        }, ARRAY_FILTER_USE_KEY);

        $parameters = array_merge($parameters, $queryParameter);

        return $this->router->generate($params['_route'], $parameters);
    }
}