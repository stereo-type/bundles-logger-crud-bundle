<?php

declare(strict_types=1);

namespace Slcorp\LoggerCrudBundle\Application\Service;

use Slcorp\LoggerCrudBundle\Domain\Entity\Log;
use Doctrine\ORM\EntityManagerInterface;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\LogRecord;
use Symfony\Bundle\SecurityBundle\Security;

class MonologDBHandler extends AbstractProcessingHandler
{
    private array $buffer = [];

    public function __construct(private readonly EntityManagerInterface $em, private readonly Security $security)
    {
        parent::__construct();
    }

    protected function write(LogRecord $record): void
    {
        $user = $this->security->getUser();
        $logEntry = new Log();
        $logEntry->setUser($user);
        $logEntry->setMessage($record->message);
        $logEntry->setLevel($record->level);
        $logEntry->setExtra($record->extra);
        $logEntry->setContext($record->context);
        $this->buffer[] = $logEntry;
    }

    public function saveLogs(): void
    {
        /**TODO придумать что  делать при 500, при этом EntityManager закрывается и его нельзя перезапустить*/
        if ($this->em->isOpen()) {
            if (!empty($this->buffer)) {
                foreach ($this->buffer as $logEntry) {
                    $this->em->persist($logEntry);
                }
                $this->em->flush();
            }
        }
    }
}
