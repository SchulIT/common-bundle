<?php

namespace SchulIT\CommonBundle\Monolog;

use Monolog\LogRecord;
use Monolog\Processor\ProcessorInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class RequestProcessor implements ProcessorInterface {

    private ?string $userAgent = null;
    private ?string $url = null;
    private ?string $referer = null;

    public function __construct(private RequestStack $requestStack)
    {
    }

    /**
     * @inheritDoc
     */
    public function __invoke(LogRecord $records): LogRecord {
        $records['extra']['useragent'] = $this->getUserAgent();
        $records['extra']['url'] = $this->getUrl();
        $records['extra']['referer'] = $this->getXhrBaseUrl();

        return $records;
    }

    private function getUserAgent(): ?string {
        if($this->userAgent === null && $this->requestStack->getMainRequest() !== null) {
            $this->userAgent = $this->requestStack->getMainRequest()->headers->get('User-Agent');
        }

        return $this->userAgent;
    }

    private function getUrl(): ?string {
        if($this->url === null && $this->requestStack->getMainRequest() !== null) {
            $this->url = $this->requestStack->getMainRequest()->getRequestUri();
        }

        return $this->url;
    }

    private function getXhrBaseUrl(): ?string {
        if($this->referer === null && $this->requestStack->getMainRequest() !== null) {
            $this->referer = $this->requestStack->getMainRequest()->headers->get('Referer');

            // Check if referer is from same host
            if(!empty($this->referer)) {
                $host = parse_url($this->referer, PHP_URL_HOST);

                if($host !== $this->requestStack->getMainRequest()->getHost()) {
                    $this->referer = null;
                }
            }
        }

        return $this->referer;
    }
}