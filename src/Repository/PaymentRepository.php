<?php

namespace App\Repository;

use App\Entity\Payment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Uid\Uuid;


/**
 * @method Payment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Payment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Payment[]    findAll()
 * @method Payment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PaymentRepository extends ServiceEntityRepository
{
    private $manager;

    public function __construct
    (
        ManagerRegistry $registry,
        EntityManagerInterface $manager
    )
    {
        parent::__construct($registry, Payment::class);
        $this->manager = $manager;
    }

    // /**
    //  * @return Payment[] Returns an array of Payment objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Payment
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function savePayment($wallet,$value){
        $newPayment = new Payment();
        $token = $this->genterateToken();
        $customer = $wallet->getCustomer();
        $newPayment
            ->setToken($token)
            ->setConfirmed(false)
            ->setValue((integer)$value)
            ->setWallet($wallet);

        $this->manager->persist($newPayment);
        $this->manager->flush();

        return $newPayment;
    }


    private function genterateToken(){

        $uuid = Uuid::v6();
        $token = substr(str_shuffle(strtoupper((string)str_replace("-", "",$uuid))),  
                       0, 6);
        return $token;
    }
}
