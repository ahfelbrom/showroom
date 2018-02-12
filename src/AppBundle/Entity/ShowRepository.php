<?php

namespace AppBundle\Entity;

// -----------------------------------------------------------------------------

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;

// -----------------------------------------------------------------------------


class ShowRepository extends EntityRepository
{
    public function findAllByName($name)
    {
        return $this->createQueryBuilder('s')
            ->where('LOWER(s.name) LIKE :name')
            ->setParameter('name', '%' . $name . '%')
            ->getQuery()
            ->getResult();
    }
}