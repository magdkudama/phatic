<?php

namespace MagdKudama\Phatic\Console\Command;

use MagdKudama\Phatic\Config\ApplicationConfig;
use MagdKudama\Phatic\Console\Command\CommandOutputHelper;
use MagdKudama\Phatic\Console\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Exception\IOException;

class BootstrapCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('bootstrap-application')
            ->setDescription('Bootstraps the generator application')
            ->addOption('force', null, InputOption::VALUE_NONE, 'Delete content without interaction');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);

        try {
            $filesystem = $this->getContainer()->get('phatic.filesystem');

            /** @var ApplicationConfig $config */
            $config = $this->getContainer()->get('phatic.config');
            $bootstrapDirectory = $config->getBootDirectory();
            $resultDirectory = $config->getSiteDirectory();

            if ($filesystem->exists($resultDirectory)) {
                if (!$input->getOption('force')) {
                    $dialog = $this->getDialog();
                    if (!$dialog->askConfirmation($output, '<question>Directory already exists. Are you sure you want to continue? (y/n)</question>', false)) {
                        CommandOutputHelper::cancel($output);
                        return;
                    }
                }
                CommandOutputHelper::writeComment($output, 'Copying directories (for safety purposes)...');
                $filesystem->mirror($resultDirectory, $resultDirectory . '../.site');
                CommandOutputHelper::writeComment($output, 'Removing directories...');
                $filesystem->remove($resultDirectory);
            }

            CommandOutputHelper::writeComment($output, 'Creating directories...');
            $filesystem->mirror($bootstrapDirectory, $resultDirectory);

            CommandOutputHelper::success($output);
        } catch (IOException $e) {
            CommandOutputHelper::writeError($output, $e->getMessage());
        }
    }
}