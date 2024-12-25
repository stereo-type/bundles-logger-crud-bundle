<?php

/**
 * @copyright  2024 Zhalayletdinov Vyacheslav evil_tut@mail.ru
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
declare(strict_types=1);

namespace AcademCity\LoggerCrudBundle\DependencyInjection;

use Exception;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Filesystem\Filesystem;

class LoggerCrudExtension extends Extension
{
    private const PERMISSIONS_MASK = 0755;

    private Filesystem $filesystem;
    private string $projectRoot;

    /**
     * @return void
     *
     * @throws Exception
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $this->processConfiguration($configuration, $configs);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../../config'));
        $loader->load('services.yaml');

        $loaderPackages = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../../config/packages'));
        $loaderPackages->load('monolog.yaml');

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
        $bundleMappingFile = __DIR__ . "/../..$subDir/$filename";
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
