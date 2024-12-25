<?php

/**
 * @copyright  2024 Zhalayletdinov Vyacheslav evil_tut@mail.ru
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
declare(strict_types=1);

namespace AcademCity\LoggerCrudBundle;

use LogicException;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class AcademCityLoggerCrudBundle extends AbstractBundle
{
    public function prependExtension(ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $loader = new YamlFileLoader($builder, new FileLocator(dirname(__DIR__) . '/config'));
        $loader->load('services.yaml');

        if ($builder->hasExtension('monolog')) {
            $builder->prependExtensionConfig('monolog', [
                'channels' => ['logger_crud'],
                'handlers' => [
                    'logger_crud_bundle' => [
                        'type' => 'service',
                        'id' => 'monolog.logger_crud_bundle_handler',
                        'level' => 'debug',
                        'channels' => ['logger_crud'],
                    ],
                ],
            ]);
        } else {
            throw new LogicException('The monolog.logger service must have been configured.');
        }
    }

}
