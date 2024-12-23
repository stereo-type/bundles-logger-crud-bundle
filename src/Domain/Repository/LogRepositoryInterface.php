<?php

declare(strict_types=1);

namespace LoggerCrudBundle\Domain\Repository;

use LoggerCrudBundle\Domain\Entity\Log;

interface LogRepositoryInterface
{
    public function find(int $id): ?Log;

    public function save(Log $log, bool $flush = true): Log;

    public function delete(Log $log, bool $flush = true): void;

    public function findAll(): array;

}
