<?php

namespace Toro\Bundle\AdminBundle\Doctrine\ORM;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use Gedmo\Tree\Entity\Repository\NestedTreeRepository;
use Toro\Bundle\CmsBundle\Doctrine\ORM\TaxonRepository;

class NestedTreeTaxonRepository extends TaxonRepository
{
    private $nestedTreeRepository;

    public function __construct(EntityManager $em, ClassMetadata $metadata)
    {
        parent::__construct($em, $metadata);

        $this->nestedTreeRepository = new NestedTreeRepository($em, $metadata);
    }

    public function moveDown($node, $number = 1)
    {
        $this->nestedTreeRepository->moveDown($node, $number);
    }

    public function moveUp($node, $number = 1)
    {
        $this->nestedTreeRepository->moveUp($node, $number);
    }
}
