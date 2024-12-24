<?php

declare(strict_types=1);

namespace AcademCity\LoggerCrudBundle\Infrastructure\Repository;

use AcademCity\LoggerCrudBundle\Domain\Entity\Log;
use AcademCity\LoggerCrudBundle\Domain\Repository\LogRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

readonly class LogRepository implements LogRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function find(int $id): ?Log
    {
        return $this->entityManager->getRepository(Log::class)->find($id);
    }

    public function findAll(): array
    {
        return $this->entityManager->getRepository(Log::class)->findAll();
    }

    public function save(Log $log, bool $flush = true): Log
    {
        $this->entityManager->persist($log);
        if ($flush) {
            $this->entityManager->flush();
        }
        return $log;
    }

    public function delete(Log $log, bool $flush = true): void
    {
        $this->entityManager->remove($log);
        if ($flush) {
            $this->entityManager->flush();
        }
    }
}
