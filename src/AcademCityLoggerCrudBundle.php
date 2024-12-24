<?php

/**
 * @copyright  2024 Zhalayletdinov Vyacheslav evil_tut@mail.ru
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
declare(strict_types=1);

namespace AcademCity\LoggerCrudBundle;

use AcademCity\LoggerCrudBundle\DependencyInjection\LoggerCrudExtension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class AcademCityLoggerCrudBundle extends Bundle
{
    public function getContainerExtension(): ?ExtensionInterface
    {
        return new LoggerCrudExtension();
    }

}
