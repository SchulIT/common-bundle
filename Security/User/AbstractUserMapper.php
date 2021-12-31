<?php

namespace SchulIT\CommonBundle\Security\User;

use LightSaml\Model\Protocol\Response;

class AbstractUserMapper {
    protected const ROLES_ASSERTION_NAME = 'urn:roles';

    /**
     * @param Response $response
     * @param string[] $valueAttributes
     * @param string[] $valuesAttributes
     * @return array
     */
    protected function transformResponseToArray(Response $response, array $valueAttributes, array $valuesAttributes): array {
        $result = [ ];

        foreach($valueAttributes as $valueAttribute) {
            if($this->hasAttribute($response, $valueAttribute)) {
                $result[$valueAttribute] = $this->getValue($response, $valueAttribute);
            }
        }

        foreach($valuesAttributes as $valuesAttribute) {
            if($this->hasAttribute($response, $valuesAttribute)) {
                $result[$valuesAttribute] = $this->getValues($response, $valuesAttribute);
            }
        }

        return $result;
    }

    private function hasAttribute(Response $response, $attributeName): bool {
        return $response->getFirstAssertion() !== null
            && $response->getFirstAssertion()->getFirstAttributeStatement() !== null
            && $response->getFirstAssertion()->getFirstAttributeStatement()->getFirstAttributeByName($attributeName) !== null;
    }

    private function getValue(Response $response, $attributeName) {
        return $response->getFirstAssertion()->getFirstAttributeStatement()
            ->getFirstAttributeByName($attributeName)->getFirstAttributeValue();
    }

    private function getValues(Response $response, $attributeName) {
        return $response->getFirstAssertion()->getFirstAttributeStatement()
            ->getFirstAttributeByName($attributeName)->getAllAttributeValues();
    }
}