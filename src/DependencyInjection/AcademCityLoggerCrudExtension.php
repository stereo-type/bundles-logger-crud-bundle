<?php

/**
 * @copyright  2024 Zhalayletdinov Vyacheslav evil_tut@mail.ru
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
declare(strict_types=1);

namespace AcademCity\LoggerCrudBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Filesystem\Filesystem;

class AcademCityLoggerCrudExtension extends Extension
{
    private const PERMISSIONS_MASK = 0755;
    private string $projectRoot;

    private Filesystem $filesystem;

    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yaml');

        $configuration = new Configuration();
        $this->processConfiguration($configuration, $configs);
        $container->setParameter('academ_city_logger_crud.ignore_entities', $configs[0]['ignore_entities'] ?? []);

        $this->filesystem = new Filesystem();
        $this->projectRoot = $container->getParameter('kernel.project_dir');

        $this->addDoctrineMappings($container);
    }

    private function addDoctrineMappings(ContainerBuilder $container): void
    {
        $subDir = "/config/packages/doctrine";
        $filename = "logger_crud_bundle.php";
        $this->createConfigsFile($subDir, $filename);
    }

    private function createConfigsFile(string $subDir, string $filename, bool $remove = true): void
    {
        $projectConfigDir = $this->projectRoot . $subDir;
        $bundleMappingFile = __DIR__ . "/../Resources$subDir/$filename";
        $targetMappingFile = $projectConfigDir . "/$filename";

        if (!$this->filesystem->exists($projectConfigDir)) {
            $this->filesystem->mkdir($projectConfigDir, self::PERMISSIONS_MASK);
        }

        if (!$this->filesystem->exists($targetMappingFile)) {
            $this->filesystem->copy($bundleMappingFile, $targetMappingFile);
        } elseif ($remove) {
            $this->filesystem->remove($targetMappingFile);
            $this->filesystem->copy($bundleMappingFile, $targetMappingFile);
        }
    }
}
