<?php

namespace SchulIT\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class IdEntity {
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $entityId;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $id;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $expiry;

    /**
     * @return string
     */
    public function getEntityId() {
        return $this->entityId;
    }

    /**
     * @param string $entityId
     *
     * @return IdEntity
     */
    public function setEntityId($entityId) {
        $this->entityId = $entityId;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getExpiry() {
        return $this->expiry;
    }

    /**
     * @param \DateTime $expiry
     *
     * @return IdEntity
     */
    public function setExpiry(\DateTime $expiry) {
        $this->expiry = $expiry;

        return $this;
    }

    /**
     * @return string
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param string $id
     *
     * @return IdEntity
     */
    public function setId($id) {
        $this->id = $id;

        return $this;
    }
}