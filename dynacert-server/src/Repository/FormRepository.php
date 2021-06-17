<?php

namespace App\Repository;

use App\Entity\Form;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Form|null find($id, $lockMode = null, $lockVersion = null)
 * @method Form|null findOneBy(array $criteria, array $orderBy = null)
 * @method Form[]    findAll()
 * @method Form[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FormRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Form::class);
    }
    public function getForms(): array {
        return $this->createQueryBuilder('f')
            ->select('f.id,f.title')
            ->orderBy('f.date','DESC')
            ->getQuery()
            ->getResult();

    }
    public function getFormsByCategoryId($id): array {
        return $this->createQueryBuilder('f')
            ->select('f.id,f.title')
            ->orderBy('f.date','DESC')
            ->where('f.category='.$id)
            ->getQuery()
            ->getResult();

    }

    public function getConfigurationForm($id): array {
        return $this->createQueryBuilder('f')
            ->select('f.id,f.title, f.config,f.category')
            ->where('f.id='.$id)
            ->getQuery()
            ->getResult();
    }
    public function getFormConfig($idForm): array {
        $sql= "SELECT t1.map,t2.images,t3.title,t3.config FROM formMap t1, formPdfImage t2, form t3 WHERE t1.formId=" . $idForm ." AND t2.formId=" . $idForm . " AND t3.ID=" . $idForm;
        return $this->getEntityManager()
            ->getConnection()
            ->prepare($sql)
            ->executeQuery()
            ->fetchAllAssociative()
            ;
    }

    public function insertForm(string $jsonString) {
        $json = json_decode($jsonString);
        $form=$json->form;
        $formTitle=$json->formTitle;
        $idForm = $json->IDForm;
        $categorySelected = $json->categorySelected;
        $sql = "INSERT INTO `form`(`title`, `config`,`category`) VALUES (:formTitle,:jsonString,:categorySelected)";
        $conn= $this->getEntityManager()->getConnection();
        $stmt=$conn->prepare($sql);
        $stmt->bindValue('formTitle',$formTitle);
        $stmt->bindValue('jsonString',$jsonString);
        $stmt->bindValue('categorySelected',$categorySelected);
        $stmt->executeQuery();
    }
}
