<?php

namespace MagdKudama\Phatic\DependencyInjection;

use MagdKudama\Phatic\Collection\ExtensionCollection;
use MagdKudama\Phatic\DependencyInjection\Compiler\CommandPass;
use MagdKudama\Phatic\DependencyInjection\Compiler\EventSubscriberPass;
use MagdKudama\Phatic\DependencyInjection\Compiler\ProcessorPass;
use MagdKudama\Phatic\DependencyInjection\Compiler\ViewExtensionPass;
use MagdKudama\Phatic\DependencyInjection\Configuration;
use MagdKudama\Phatic\Exception\DependencyNotSatisfiedException;
use MagdKudama\Phatic\Exception\UndefinedClassException;
use MagdKudama\Phatic\Extension;
use MagdKudama\Phatic\Utils;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use ReflectionClass;
use InvalidArgumentException;

class PhaticExtension
{
    const INTERFACE_TO_IMPLEMENT = 'MagdKudama\Phatic\Extension';

    /** @var Processor */
    private $processor;

    /** @var Configuration */
    private $configuration;

    /** @var ExtensionCollection */
    private $extensions;

    private $systemConfig;
    private $resultsDirectory;

    public function __construct($systemConfig, $resultsDirectory)
    {
        $this->processor = new Processor();
        $this->configuration = new Configuration();
        $this->extensions = new ExtensionCollection();
        $this->systemConfig = $systemConfig;
        $this->resultsDirectory = $resultsDirectory;
    }

    public function load(array $configs, ContainerBuilder $container)
    {
        $arrayConfig = [
            'config_file' => $this->systemConfig,
            'app_directory' => $this->resultsDirectory,
            'site_directory' => $this->resultsDirectory . '/site/',
            'assets_path' => $this->resultsDirectory . '/site/_assets/',
            'layouts_path' => $this->resultsDirectory . '/site/_pages/_layouts/',
            'posts_path' => $this->resultsDirectory . '/site/_posts/',
            'results_path' => $this->resultsDirectory . '/site/result/',
            'pages_path' => $this->resultsDirectory . '/site/_pages/',
            'system_directory' => Utils::getSystemDirectory(),
            'boot_directory' => Utils::getBootDirectory() . 'bootstrap/site/'
        ];

        $container->setParameter('phatic.app_config', $arrayConfig);

        $extensionsConfigs = [];
        if (isset($configs['config']['extensions'])) {
            foreach ($configs['config']['extensions'] as $extensionLocator => $extensionConfig) {
                if (!class_exists($extensionLocator)) {
                    throw new UndefinedClassException("Class {$extensionLocator} does not exist");
                }
                $refClass = new ReflectionClass($extensionLocator);
                if (!$refClass->implementsInterface(self::INTERFACE_TO_IMPLEMENT)) {
                    throw new InvalidArgumentException("Extensions must implement interface " . self::INTERFACE_TO_IMPLEMENT);
                }

                $this->extensions->addByFqn($extensionLocator);
                $extensionsConfigs[$extensionLocator] = $extensionConfig;
            }
        }

        $tree = $this->configuration->getConfigTree($this->extensions);
        $this->processor->process($tree, $configs);

        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../Config')
        );
        $loader->load('services.yml');

        /** @var Extension $extension */
        foreach ($this->extensions as $extension) {
            if (null !== $extension->getExtensionDependency()) {
                if (!$this->extensions->findByName($extension->getExtensionDependency())) {
                    $message = sprintf("Extension %s requires extension %s", get_class($extension), $extension->getExtensionDependency());
                    throw new DependencyNotSatisfiedException($message);
                }
            }
            $extension->load($extensionsConfigs[get_class($extension)], $container);
        }

        $this->addCompilerPasses($container);
    }

    protected function addCompilerPasses(ContainerBuilder $container)
    {
        $container->addCompilerPass(new CommandPass());
        $container->addCompilerPass(new ViewExtensionPass());
        $container->addCompilerPass(new ProcessorPass());
        $container->addCompilerPass(new EventSubscriberPass());
    }

    /** @return ExtensionCollection */
    public function getExtensionCollection()
    {
        return $this->extensions;
    }
}