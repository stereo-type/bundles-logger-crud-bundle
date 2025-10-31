<?php

/**
 * @copyright  2024 Zhalayletdinov Vyacheslav evil_tut@mail.ru
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
declare(strict_types=1);

namespace Slcorp\LoggerCrudBundle;

use Slcorp\LoggerCrudBundle\DependencyInjection\SlcorpLoggerCrudExtension;
use Slcorp\LoggerCrudBundle\DependencyInjection\Compiler\MonologExtensionCompilerPass;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class SlcorpLoggerCrudBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);
        $container->addCompilerPass(new MonologExtensionCompilerPass(), PassConfig::TYPE_BEFORE_OPTIMIZATION, 100);
    }

    public function getContainerExtension(): ?ExtensionInterface
    {
        return new SlcorpLoggerCrudExtension();
    }

}
