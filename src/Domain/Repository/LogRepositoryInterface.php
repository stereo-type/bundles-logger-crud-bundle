<?php

declare(strict_types=1);

namespace Slcorp\LoggerCrudBundle\Domain\Repository;

use Slcorp\LoggerCrudBundle\Domain\Entity\Log;

interface LogRepositoryInterface
{
    public function find(int $id): ?Log;

    public function save(Log $log, bool $flush = true): Log;

    public function delete(Log $log, bool $flush = true): void;

    public function findAll(): array;

}
