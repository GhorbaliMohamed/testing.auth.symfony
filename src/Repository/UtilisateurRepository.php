<?php

namespace App\Repository;

use App\Entity\Utilisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Utilisateur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Utilisateur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Utilisateur[]    findAll()
 * @method Utilisateur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UtilisateurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Utilisateur::class);
    }

    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
    /*
    public function getPhoneNumber($email)
        {
        $user = $this->findOneBy(['email' => $email]);
        if ($user) {
            return $user->getPhoneNumber();
        } else {
            return null; // Or throw an exception if phone number retrieval is crucial
        }
        }
*/
public function countByRole(string $role): int
    {
        return $this->createQueryBuilder('u')
            ->select('COUNT(u)')
            ->where('u.role = :role')
            ->setParameter('role', $role)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countByAdresse(string $adresse): int
    {
        return $this->createQueryBuilder('u')
            ->select('COUNT(u)')
            ->where('u.adresse = :adresse')
            ->setParameter('adresse', $adresse)
            ->getQuery()
            ->getSingleScalarResult();
    }
public function findAllOrdered(?string $searchQuery, ?string $orderBy, ?string $orderDirection)
{
    $qb = $this->createQueryBuilder('u');

    if ($searchQuery) {
        $qb->where('u.nom LIKE :searchQuery OR u.prenom LIKE :searchQuery OR u.email LIKE :searchQuery OR u.role LIKE :searchQuery')
           ->setParameter('searchQuery', '%' . $searchQuery . '%');
    }

    $qb->orderBy('u.' . $orderBy, $orderDirection ?: 'ASC');

    return $qb->getQuery()->getResult();
}


    public function delete(Utilisateur $user): void
    {
        $this->_em->remove($user);
        $this->_em->flush();
    }
    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}