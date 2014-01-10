<?php

namespace MagdKudama\Phatic\Console\Command;

use MagdKudama\Phatic\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\DialogHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Filesystem\Filesystem;
use LogicException;

abstract class ContainerAwareCommand extends Command
{
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->getApplication()->guessSystemConfigFile($input);
        $this->getApplication()->guessResultDirectory($input);

        parent::initialize($input, $output);
    }

    public function getApplication()
    {
        $application = parent::getApplication();

        if (!$application instanceof Application) {
            throw new LogicException("Application must be an instance of a PhaticApplication");
        }

        return $application;
    }

    /** @return ContainerBuilder */
    protected function getContainer()
    {
        return $this->getApplication()->getContainer();
    }

    /** @return Filesystem */
    protected function getFileSystem()
    {
        return $this->getContainer()->get('phatic.filesystem');
    }

    /** @return EventDispatcherInterface */
    protected function getDispatcher()
    {
        return $this->getContainer()->get('phatic.dispatcher');
    }

    protected function getParameter($param)
    {
        return $this->getContainer()->getParameter($param, null);
    }

    /** @return DialogHelper */
    protected function getDialog()
    {
        return $this->getHelperSet()->get('dialog');
    }
}