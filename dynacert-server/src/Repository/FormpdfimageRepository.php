<?php

namespace App\Repository;

use App\Entity\Formpdfimage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Formpdfimage|null find($id, $lockMode = null, $lockVersion = null)
 * @method Formpdfimage|null findOneBy(array $criteria, array $orderBy = null)
 * @method Formpdfimage[]    findAll()
 * @method Formpdfimage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FormpdfimageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Formpdfimage::class);
    }

    public function updateImages(string $formId,string $images) {
        $this->createQueryBuilder('f')
            ->update()
            ->set('f.images',':images')
            ->setParameter('images', $images)
            ->andWhere('f.formid= :formid')
            ->setParameter('formid', $formId)
            ->getQuery()
            ->execute();

    }
}
