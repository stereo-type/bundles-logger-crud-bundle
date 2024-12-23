<?php

declare(strict_types=1);
/**
 * @package lk LogSaveSubscriber.php
 * @copyright 11.06.2024 Zhalyaletdinov Vyacheslav evil_tut@mail.ru
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace LoggerCrudBundle\Infrastructure\EventSubscriber;

use LoggerCrudBundle\Application\Service\MonologDBHandler;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\TerminateEvent;

class LogSaveSubscriber implements EventSubscriberInterface
{
    private MonologDBHandler $dbHandler;

    public function __construct(MonologDBHandler $dbHandler)
    {
        $this->dbHandler = $dbHandler;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'kernel.terminate' => 'onKernelTerminate',
//            'kernel.exception' => 'onKernelException',
//            'kernel.finish_request' => 'onKernelFinishRequest',
        ];
    }

    /**Почему-то не вызывается*/
    public function onKernelTerminate(TerminateEvent $event): void
    {
        $this->dbHandler->saveLogs();
    }

    //    public function onKernelException(ExceptionEvent $event): void
    //    {
    //        $this->dbHandler->saveLogs();
    //    }
    //
    //    public function onKernelFinishRequest(FinishRequestEvent $event): void
    //    {
    //        $this->dbHandler->saveLogs();
    //    }

}
