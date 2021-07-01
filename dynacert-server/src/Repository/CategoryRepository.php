<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Category|null updateCategory(string $id,string $name)
 * @method Category[] getCategories()
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }
    public function getCategories(): array {
        return $this->createQueryBuilder('c')
            ->select('c.id,c.name')
            ->orderBy('c.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function updateCategory(string $id,string $name) {
        return $this->createQueryBuilder('c')
            ->update()
            ->set('c.name',':name')
            ->setParameter('name', $name)
            ->andWhere('c.id= :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->execute();
  
    }

    public function deleteCategory(string $id) {
        return $this->createQueryBuilder('c')
            ->delete()
            ->andWhere('c.id= :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->execute();
    }
}
