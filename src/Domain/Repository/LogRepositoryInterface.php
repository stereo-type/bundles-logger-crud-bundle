<?php

declare(strict_types=1);

namespace AcademCity\LoggerCrudBundle\Domain\Repository;

use AcademCity\LoggerCrudBundle\Domain\Entity\Log;

interface LogRepositoryInterface
{
    public function find(int $id): ?Log;

    public function save(Log $log, bool $flush = true): Log;

    public function delete(Log $log, bool $flush = true): void;

    public function findAll(): array;

}
