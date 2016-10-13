<?php

namespace Toro\Bundle\AdminBundle\Form\ChoiceList;

use Doctrine\Common\Persistence\ObjectRepository;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Bridge\Doctrine\Form\ChoiceList\EntityLoaderInterface;

/**
 * Generic use for Ajax auto-complete
 */
class EntityLoader implements EntityLoaderInterface
{
    /**
     * @var RepositoryInterface|EntityRepository|ObjectRepository
     */
    private $repository;

    /**
     * @var int[]
     */
    private $ids;

    /**
     * @param \int[] $ids
     */
    public function setIds($ids)
    {
        $this->ids = $ids;
    }

    public function __construct(RepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * {@inheritdoc}
     */
    public function getEntities()
    {
        return $this->repository->findBy(['id' => $this->ids]);
    }

    /**
     * {@inheritdoc}
     */
    public function getEntitiesByIds($identifier, array $values)
    {
        return $this->repository->findBy([$identifier => $values]);
    }
}
