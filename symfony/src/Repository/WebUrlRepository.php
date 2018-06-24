<?php

namespace App\Repository;

use App\Entity\WebUrl;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method WebUrl|null find($id, $lockMode = null, $lockVersion = null)
 * @method WebUrl|null findOneBy(array $criteria, array $orderBy = null)
 * @method WebUrl[]    findAll()
 * @method WebUrl[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WebUrlRepository extends ServiceEntityRepository
{
    /**
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, WebUrl::class);
    }
}
