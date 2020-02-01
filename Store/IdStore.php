<?php

namespace SchoolIT\CommonBundle\Store;

use Doctrine\ORM\EntityManagerInterface;
use LightSaml\Provider\TimeProvider\TimeProviderInterface;
use LightSaml\Store\Id\IdStoreInterface;
use SchoolIT\CommonBundle\Entity\IdEntity;

class IdStore implements IdStoreInterface {
    /** @var EntityManagerInterface */
    private $manager;

    /** @var  TimeProviderInterface */
    private $timeProvider;

    /**
     * @param EntityManagerInterface $manager
     * @param TimeProviderInterface $timeProvider
     */
    public function __construct(EntityManagerInterface $manager, TimeProviderInterface $timeProvider) {
        $this->manager = $manager;
        $this->timeProvider = $timeProvider;
    }

    /**
     * @param string $entityId
     * @param string $id
     * @param \DateTime $expiryTime
     *
     * @return void
     */
    public function set($entityId, $id, \DateTime $expiryTime) {
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

    /**
     * @param string $entityId
     * @param string $id
     *
     * @return bool
     */
    public function has($entityId, $id) {
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