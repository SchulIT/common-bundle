<?php

namespace SchulIT\CommonBundle\Menu;

use Knp\Menu\ItemInterface;
use Knp\Menu\Matcher\Voter\VoterInterface;
use Symfony\Component\HttpFoundation\RequestStack;

readonly class Voter implements VoterInterface {

    public function __construct(private RequestStack $requestStack) { }

    /**
     * @inheritDoc
     */
    public function matchItem(ItemInterface $item): ?bool {
        $currentUri = $this->requestStack->getMainRequest()->getRequestUri();
        $itemUri = $item->getUri();

        if($itemUri === null) {
            return false;
        }

        if($currentUri === $itemUri) {
            return true;
        }

        if(str_starts_with($currentUri, $itemUri)) {
            return true;
        }

        return false;
    }
}