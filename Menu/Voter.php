<?php

namespace SchulIT\CommonBundle\Menu;

use Knp\Menu\ItemInterface;
use Knp\Menu\Matcher\Voter\VoterInterface;
use SchulIT\CommonBundle\BC\RequestStackBackwardsCompatibilityTrait;
use Symfony\Component\HttpFoundation\RequestStack;

class Voter implements VoterInterface {

    use RequestStackBackwardsCompatibilityTrait;

    private RequestStack $requestStack;

    public function __construct(RequestStack $requestStack) {
        $this->requestStack = $requestStack;
    }

    /**
     * @inheritDoc
     */
    public function matchItem(ItemInterface $item): ?bool {
        $currentUri = $this->getMainRequest($this->requestStack)->getRequestUri();
        $itemUri = $item->getUri();

        if($itemUri === null) {
            return false;
        }

        if($currentUri === $itemUri) {
            return true;
        }

        if($itemUri === substr($currentUri, 0, strlen($itemUri))) {
            return true;
        }

        return false;
    }
}