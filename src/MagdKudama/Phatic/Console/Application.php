<?php

namespace MagdKudama\Phatic\Console;

use MagdKudama\Phatic\Console\Command\BootstrapCommand;
use MagdKudama\Phatic\DependencyInjection\PhaticExtension;
use MagdKudama\Phatic\Utils;
use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Command\ListCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Yaml\Yaml;

class Application extends BaseApplication
{
    const NAME = 'Phatic - Ultra Simple Static Page Generator';
    const VERSION = '1.0';

    private $systemConfig;
    private $resultDirectory;

    /** @var ContainerBuilder|null */
    private $container;

    public function __construct()
    {
        $this->container = null;
        parent::__construct(static::NAME, static::VERSION);
    }

    public function doRun(InputInterface $input, OutputInterface $output)
    {
        $this->guessSystemConfigFile($input);
        $this->guessResultDirectory($input);

        $commands = $this->getContainer()->get('phatic.commands');
        foreach ($commands as $command) {
            $this->add($command);
        }

        return parent::doRun($input, $output);
    }

    public function getContainer()
    {
        $container = $this->container;

        if (null === $container) {
            $container = new ContainerBuilder();
            $this->loadExtensions($container, Yaml::parse($this->systemConfig));
            $container->compile();
        }

        return $container;
    }

    public function guessSystemConfigFile(InputInterface $input)
    {
        $systemConfig = $input->getParameterOption(['--config', '-c']);
        if (false !== $systemConfig) {
            if (!is_file($systemConfig)) {
                throw new FileNotFoundException("Config file {$systemConfig} does not exist!");
            }
            $this->systemConfig = $systemConfig;
            return;
        }

        $systemConfig = Utils::getSystemConfigFileName();
        if (!is_file($systemConfig)) {
            throw new FileNotFoundException("Config file phatic.yml does not exist!");
        }

        $this->systemConfig = $systemConfig;
    }

    public function guessResultDirectory(InputInterface $input)
    {
        $resultantDirectory = $input->getParameterOption(['--dir', '-d']);
        if (false !== $resultantDirectory) {
            if (!file_exists($resultantDirectory)) {
                throw new FileNotFoundException("Directory {$resultantDirectory} does not exist!");
            }
            if (!is_writable($resultantDirectory)) {
                throw new IOException("Directory {$resultantDirectory} is not writable!");
            }

            $this->resultDirectory = (substr($resultantDirectory, 0, -1) == '/') ? $resultantDirectory : $resultantDirectory . '/';
            return;
        }

        $this->resultDirectory = Utils::getBaseDirectory();
    }

    protected function loadExtensions(ContainerBuilder $container, array $configs)
    {
        $extension = new PhaticExtension($this->systemConfig, $this->resultDirectory);
        $extension->load($configs, $container);
        $container->addObjectResource($extension);
    }

    protected function getDefaultInputDefinition()
    {
        return new InputDefinition(array(
            new InputArgument('command', InputArgument::REQUIRED, 'The command to execute.'),
            new InputOption('--version', '-v', InputOption::VALUE_NONE, 'Display this application version.'),
            new InputOption('--config', '-c', InputOption::VALUE_REQUIRED, 'Sets the application config file path.'),
            new InputOption('--dir', '-d', InputOption::VALUE_REQUIRED, 'Sets the application resultant directory.'),
        ));
    }

    protected function getDefaultCommands()
    {
        return [new ListCommand(), new BootstrapCommand()];
    }
}