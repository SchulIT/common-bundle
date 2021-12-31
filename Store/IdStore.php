<?php

namespace SchulIT\CommonBundle\Store;

use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use LightSaml\Provider\TimeProvider\TimeProviderInterface;
use LightSaml\Store\Id\IdStoreInterface;
use SchulIT\CommonBundle\Entity\IdEntity;

class IdStore implements IdStoreInterface {
    private EntityManagerInterface $manager;
    private TimeProviderInterface $timeProvider;

    public function __construct(EntityManagerInterface $manager, TimeProviderInterface $timeProvider) {
        $this->manager = $manager;
        $this->timeProvider = $timeProvider;
    }

    public function set(string $entityId, string $id, DateTime $expiryTime): void {
        $idEntry = $this->manager->find(IdEntity::class, ['entityId' => $entityId, 'id' => $id]);
        if (null == $idEntry) {
            $idEntry = new IdEntity();
        }
        $idEntry->setEntityId($entityId)
            ->setId($id)
            ->setExpiry($expiryTime);
        $this->manager->persist($idEntry);
        $this->manager->flush();
    }

    public function has(string $entityId, string $id): bool {
        /** @var IdEntity $idEntry */
        $idEntry = $this->manager->find(IdEntity::class, ['entityId' => $entityId, 'id' => $id]);
        if (null == $idEntry) {
            return false;
        }
        if ($idEntry->getExpiry() < $this->timeProvider->getDateTime()) {
            return false;
        }

        return true;
    }
}