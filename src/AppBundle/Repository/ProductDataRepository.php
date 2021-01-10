<?php

namespace AppBundle\Repository;

use AppBundle\Entity\ProductData;
use Doctrine\ORM\EntityRepository;

class ProductDataRepository extends EntityRepository
{
    public function findProductByCode($productCode)
    {
        return $this->getEntityManager()
            ->getRepository(ProductData::class)
            ->findOneBy([
                'productCode' => $productCode
            ]);
    }
}
