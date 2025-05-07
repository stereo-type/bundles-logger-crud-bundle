<?php

declare(strict_types=1);
/**
 * @package lk EntityAction.php
 * @copyright 10.06.2024 Zhalyaletdinov Vyacheslav evil_tut@mail.ru
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace AcademCity\LoggerCrudBundle\Application\Enum;

enum EntityAction: string
{
    case CREATED = 'created';
    case UPDATED = 'updated';
    case MODIFIED = 'modified';
    case DELETED = 'removed';
    case RESTORED = 'restored';

    /**
     * @return string
     */
    public function getUserAction(): string
    {
        return match ($this) {
            self::CREATED => 'создал объект',
            self::UPDATED => 'обновил объект',
            self::MODIFIED => 'изменил объект',
            self::DELETED => 'удалил объект',
            self::RESTORED => 'восстановил объект',
        };
    }

    public function getUserActionShort(): string
    {
        return match ($this) {
            self::CREATED => 'создал',
            self::UPDATED => 'обновил',
            self::MODIFIED => 'изменил',
            self::DELETED => 'удалил',
            self::RESTORED => 'восстановил',
        };
    }

}
