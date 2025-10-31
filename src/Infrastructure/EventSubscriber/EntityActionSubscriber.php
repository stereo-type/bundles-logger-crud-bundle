<?php

declare(strict_types=1);
/**
 * @package lk EntiTySubscriber.php
 * @copyright 10.06.2024 Zhalyaletdinov Vyacheslav evil_tut@mail.ru
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace Slcorp\LoggerCrudBundle\Infrastructure\EventSubscriber;

use Slcorp\LoggerCrudBundle\Application\Enum\EntityAction;
use Slcorp\LoggerCrudBundle\Domain\Entity\Log;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Doctrine\Persistence\ObjectManager;
use Psr\Log\LoggerInterface;
use ReflectionClass;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Throwable;

class EntityActionSubscriber extends AbstractLoggerSubscriber implements EventSubscriber
{
    public const EXCLUDED_ENTITY = [
        Log::class
    ];

    private array $ignoreEntities;

    public function __construct(LoggerInterface $dbLogger, Security $security, ParameterBagInterface $params)
    {
        parent::__construct($dbLogger, $security, $params);
        $this->ignoreEntities = array_merge(self::EXCLUDED_ENTITY, $params->get('academ_city_logger_crud.ignore_entities'));
    }

    /**
     * @return string[]
     */
    public function getSubscribedEvents()
    {
        return [
            Events::postPersist,
            Events::preUpdate,
            Events::preRemove,
        ];
    }

    /**
     * @param LifecycleEventArgs<ObjectManager> $args
     * @return bool
     */
    private function needLog(LifecycleEventArgs $args): bool
    {
        if (in_array(get_class($args->getObject()), $this->ignoreEntities, true)) {
            return false;
        }
        return true;
    }

    /**
     * @param EntityAction $action
     * @param LifecycleEventArgs<ObjectManager> $args
     * @param array $fields
     * @return void
     */
    private function _log(EntityAction $action, LifecycleEventArgs $args, array $fields = []): void
    {
        if ($this->needLog($args)) {
            $entity = $args->getObject();
            $this->logEvent(
                $this->getMessage($entity, $action),
                'EntityEvent',
                $action,
                array_merge([
                    'class' => get_class($entity),
                    'entity' => $this->getEntityArray($entity),
                ], $fields)
            );
        }
    }

    /**
     * @param LifecycleEventArgs<ObjectManager> $args
     * @return void
     */
    public function postPersist(LifecycleEventArgs $args): void
    {
        $this->_log(EntityAction::CREATED, $args);
    }

    public function preUpdate(PreUpdateEventArgs $args): void
    {
        $action = EntityAction::UPDATED;
        $params = [];
        if ($change = $args->getEntityChangeSet()) {
            $old = $new = [];
            foreach ($change as $k => $v) {
                $old[$k] = $v[0];
                $new[$k] = $v[1];
            }
            $params['old'] = $old;
            $params['new'] = $new;
            if (isset($change['delete'])) {
                $delete = $change['delete'];
                if ($delete[0] && !$delete[1]) {
                    $action = EntityAction::RESTORED;
                } elseif (!$delete[0] && $delete[1]) {
                    $action = EntityAction::DELETED;
                }
            }
        }

        $this->_log($action, $args, $params);
    }

    /**
     * @param LifecycleEventArgs<ObjectManager> $args
     * @return void
     */
    public function preRemove(LifecycleEventArgs $args): void
    {
        $this->_log(EntityAction::DELETED, $args);
    }


    protected function getMessage(object $entity, EntityAction $eventAction): string
    {
        try {
            $user = $this->security->getUser();
            $username = $user ? $user->getUserIdentifier() : '--Не определен--';
            $action = $eventAction->getUserAction();
            $entityClass = $entity::class;
            $entityClassShort = (new ReflectionClass($entityClass))->getShortName();
            $id = $entity->getId();
            return "Пользователь, $username, $action \"$entityClassShort\" c id = $id";
        } catch (Throwable $e) {
            return 'Не получилось сформировать текст события: ' . $e->getMessage();
        }
    }

    protected function getEntityArray(object $entity): array
    {
        if (method_exists($entity, 'toArray')) {
            return $entity->toArray();
        }
        return get_object_vars($entity);
    }
}
