<?php

namespace SchulIT\CommonBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class IdEntity {

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    #[ORM\Column(type: 'string', length: 255, nullable: false)]
    private string $entityId;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    #[ORM\Column(type: 'string', length: 255, nullable: false)]
    private string $id;

    #[ORM\Column(type: 'datetime', nullable: false)]
    private DateTime $expiry;

    public function getEntityId(): string {
        return $this->entityId;
    }

    public function setEntityId(string $entityId): IdEntity {
        $this->entityId = $entityId;

        return $this;
    }

    public function getExpiry(): DateTime {
        return $this->expiry;
    }

    public function setExpiry(DateTime $expiry): IdEntity {
        $this->expiry = $expiry;

        return $this;
    }

    public function getId(): string {
        return $this->id;
    }
    
    public function setId(string $id): IdEntity {
        $this->id = $id;

        return $this;
    }
}