<?php

declare(strict_types=1);

namespace AcademCity\LoggerCrudBundle\DependencyInjection\Compiler;

use AcademCity\LoggerCrudBundle\Application\Service\MonologDBHandler;
use LogicException;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

/**
 * @internal
 */
final class MonologExtensionCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if ($container->hasExtension('monolog')) {
            //            dump($container->getExtensionConfig('monolog'));
            $container->prependExtensionConfig('monolog', [
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

            /**Не работает так. необзодимо в родительском конфиге прописать хендлер (в README описан)*/
//            // Создаём сам сервис хендлера для устранния проблемы с очередностью загрузки
//            $definition = new Definition(MonologDBHandler::class);
//            $definition->addTag('monolog.logger', ['channel' => 'logger_crud']);
//            $container->setDefinition('monolog.handler.logger_crud_bundle', $definition);

        } else {
            throw new LogicException('The monolog.logger service must have been configured.');
        }
    }
}
