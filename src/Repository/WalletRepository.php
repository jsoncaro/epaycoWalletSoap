<?php

namespace App\Repository;

use App\Entity\Wallet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @method Wallet|null find($id, $lockMode = null, $lockVersion = null)
 * @method Wallet|null findOneBy(array $criteria, array $orderBy = null)
 * @method Wallet[]    findAll()
 * @method Wallet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WalletRepository extends ServiceEntityRepository
{
   /* public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Wallet::class);
    }*/

    private $manager;

    public function __construct
    (
        ManagerRegistry $registry,
        EntityManagerInterface $manager
    )
    {
        parent::__construct($registry, Wallet::class);
        $this->manager = $manager;
    }

    // /**
    //  * @return Wallet[] Returns an array of Wallet objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('w.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Wallet
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

     public function saveWallet($customer)
    {
        $newWallet = new Wallet();
        $newWallet
            ->setBalance(10000)
            ->setCustomer($customer);

        $this->manager->persist($newWallet);
        $this->manager->flush();

        return $newWallet;
    }

    public function rechargeWallet($customer,$value)
    {
        $wallet = $customer->getWallet();
        $currentBallance = $wallet->getBalance();
        $wallet->setBalance((integer)$currentBallance + (integer)$value);

        $this->manager->persist($wallet);
        $this->manager->flush();

        return $wallet;
    }
}
