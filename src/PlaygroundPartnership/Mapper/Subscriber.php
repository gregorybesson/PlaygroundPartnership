<?php

namespace PlaygroundPartnership\Mapper;

use Doctrine\ORM\EntityManager;
use PlaygroundPartnership\Options\ModuleOptions;
use ZfcBase\Mapper\AbstractDbMapper;

class Subscriber
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     * @var \Doctrine\ORM\EntityRepository
     */
    protected $er;

    /**
     * @var \PlaygroundPartnership\Options\ModuleOptions
     */
    protected $options;

    public function __construct(EntityManager $em, ModuleOptions $options)
    {
        $this->em      = $em;
        $this->options = $options;
    }

    public function findById($id)
    {
        return $this->getEntityRepository()->find($id);
    }

    public function findBy($filter, $order = null, $limit = null, $offset = null)
    {
        return $this->getEntityRepository()->findBy($filter, $order, $limit, $offset);
    }

    public function isSubscriber($partner, $user)
    {
        $entity = $this->getEntityRepository()->findOneBy(array('partner' => $partner, 'user' => $user));
        if ($entity) {
            return true;
        } else {
            return false;
        }
    }
    
    public function findSubscribers($partner)
    {
        return $entity = $this->getEntityRepository()->findBy(array('partner' => $partner));
    }

    public function insert($entity)
    {
        return $this->persist($entity);
    }

    public function update($entity)
    {
        return $this->persist($entity);
    }

    protected function persist($entity)
    {
        $this->em->persist($entity);
        $this->em->flush();

        return $entity;
    }

    public function findAll()
    {
        return $this->getEntityRepository()->findAll();
    }

    public function remove($entity)
    {
        $this->em->remove($entity);
        $this->em->flush();
    }

    public function getEntityRepository()
    {
        if (null === $this->er) {
            $this->er = $this->em->getRepository('PlaygroundPartnership\Entity\Subscriber');
        }

        return $this->er;
    }
}
