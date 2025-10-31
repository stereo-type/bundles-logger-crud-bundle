<?php

declare(strict_types=1);
/**
 * @package lk AbstractEntitySubscriber.php
 * @copyright 10.06.2024 Zhalyaletdinov Vyacheslav evil_tut@mail.ru
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace Slcorp\LoggerCrudBundle\Infrastructure\EventSubscriber;

use BackedEnum;
use Monolog\Attribute\WithMonologChannel;
use Psr\Log\LoggerInterface;
use ReflectionClass;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Throwable;

#[WithMonologChannel('logger_crud')]
abstract class AbstractLoggerSubscriber implements LoggerSubscriberCRUDInterface
{
    public const EVENT_TYPE = 'event';
    public const EVENT_TYPE_ERROR = 'event_error';


    public function __construct(
        protected readonly LoggerInterface $dbLogger,
        protected readonly Security $security,
        protected readonly ParameterBagInterface $parameterBag
    ) {
    }

    protected function logEvent(string $message, string $eventType, BackedEnum $eventAction, array $eventFields = []): void
    {
        try {
            if (class_exists($eventType)) {
                $eventType = (new ReflectionClass($eventType))->getShortName();
            }

            $this->dbLogger->info($message, [
                'type' => static::EVENT_TYPE,
                'event_type' => $eventType,
                'event_action' => $eventAction->value,
                'event_fields' => $eventFields
            ]);
        } catch (Throwable $e) {
            try {
                $this->dbLogger->error($message, [
                    'type' => static::EVENT_TYPE_ERROR,
                    'event_type' => $eventType,
                    'event_action' => $eventAction->value,
                    'error' => $e->getMessage()
                ]);
            } catch (Throwable) {
                /**Перестраховка, чтоб не заруинить маршрут, не является ошибкой, уязвимостью или какой-то проблемой*/
            }
        }
    }
}
