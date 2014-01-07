<?php

namespace MagdKudama\Phatic\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\DialogHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Filesystem\Filesystem;

abstract class ContainerAwareCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->getApplication()->guessSystemConfigFile($input);
        $this->getApplication()->guessResultDirectory($input);
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