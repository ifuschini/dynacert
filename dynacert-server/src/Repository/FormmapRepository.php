<?php

namespace App\Repository;

use App\Entity\Formmap;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Formmap|null find($id, $lockMode = null, $lockVersion = null)
 * @method Formmap|null findOneBy(array $criteria, array $orderBy = null)
 * @method Formmap[]    findAll()
 * @method Formmap[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FormmapRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Formmap::class);
    }

    public function getMaps($id): array {
        $sql = "SELECT t1.map,t2.images FROM formMap t1, formPdfImage t2 WHERE t1.formId=". $id. " AND t2.formId=" . $id;
        return $this->getEntityManager()
            ->getConnection()
            ->prepare($sql)
            ->executeQuery()
            ->fetchAllAssociative()
            ;
    }
    public function saveMap(string $formId,string $map) {
        $sql = "INSERT INTO `formMap`(`formId`, `map`) VALUES (:formId,:map)";
        $conn= $this->getEntityManager()->getConnection();
        $stmt=$conn->prepare($sql);
        $stmt->bindValue('formId',$formId);
        $stmt->bindValue('map',$map);
        $stmt->executeQuery();

    }

    public function updateMap(string $formId,string $map) {
        $this->createQueryBuilder('f')
            ->update()
            ->set('f.map',':map')
            ->setParameter('map', $map)
            ->andWhere('f.formid= :formid')
            ->setParameter('formid', $formId)
            ->getQuery()
            ->execute();

    }
}
