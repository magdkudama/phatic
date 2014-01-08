<?php

namespace MagdKudama\Phatic\Tests\Console\Command;

use MagdKudama\Phatic\Console\Application;
use MagdKudama\Phatic\Console\Command\BootstrapCommand;
use MagdKudama\Phatic\Tests\TestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Filesystem\Filesystem;

class BootstrapCommandTest extends TestCase
{
    /** @var Application */
    protected $application;

    /** @var CommandTester */
    protected $commandTester;

    /** @var Filesystem */
    protected $filesystem;

    /** @var Command */
    protected $command;

    protected $options;

    public function setUp()
    {
        $directory = __DIR__ . '/Fixtures/' . uniqid('test_', true);

        $this->filesystem = new Filesystem();
        $this->filesystem->mkdir($directory);

        $this->application = new Application();
        $this->application->add(new BootstrapCommand());
        $this->application->setAutoExit(false);

        $this->command = $this->application->find('bootstrap-application');
        $this->commandTester = new CommandTester($this->command);
        $this->options = [
            'command' => $this->command->getName(),
            '--config' => __DIR__ . '/../Fixtures/phatic.yml',
            '--dir' => $directory
        ];
    }

    public function tearDown()
    {
        $this->filesystem->remove($this->options['--dir']);
    }

    public function testCommandGeneratesContentAndDisplaysText()
    {
        $this->commandTester->execute($this->options);

        $this->assertStringEqualsFile(
            __DIR__ . '/Fixtures/bootstrap-output.txt',
            $this->commandTester->getDisplay(),
            'Command behaves correctly, outputting content to screen'
        );

        $this->assertFileExists($this->options['--dir'] . '/site', 'Assert that "site" directory is generated');
    }

    public function testCommandAsksWhenDirectoryAlreadyExists()
    {
        $this->commandTester->execute($this->options);

        $dialog = $this->command->getHelper('dialog');
        $dialog->setInputStream($this->getInputStream("yes\n"));

        $this->commandTester->execute($this->options);

        $this->assertContains(
            "Directory already exists. Are you sure you want to continue?",
            $this->commandTester->getDisplay(),
            'Command asks when folder already exists'
        );
    }

    public function testOperationGetsCancelledWhenAnswerIsNo()
    {
        $this->commandTester->execute($this->options);

        $dialog = $this->command->getHelper('dialog');
        $dialog->setInputStream($this->getInputStream("no\n"));

        $this->commandTester->execute($this->options);

        $this->assertContains(
            "Operation cancelled",
            $this->commandTester->getDisplay(),
            'Command cancels operation when user does not want to overwrite the existing directory'
        );
    }

    public function testBackupIsGeneratedAfterOverwrite()
    {
        $this->commandTester->execute($this->options);

        $dialog = $this->command->getHelper('dialog');
        $dialog->setInputStream($this->getInputStream("yes\n"));

        $this->commandTester->execute($this->options);

        $this->assertFileExists(
            $this->options['--dir'] . '/.site',
            'Command generates backup when user overwrites "site" directory'
        );
    }

    public function testCommandDoesNotAskWhenForcedOptionIsSet()
    {
        $this->commandTester->execute($this->options);

        $this->commandTester->execute(array_merge($this->options, ['--force' => true]));

        $this->assertNotContains(
            "Directory already exists. Are you sure you want to continue?",
            $this->commandTester->getDisplay(),
            'Command overwrites directory with no interaction if --force option is set'
        );
    }

    /**
     * @param string $input
     */
    protected function getInputStream($input)
    {
        $stream = fopen('php://memory', 'r+', false);
        fputs($stream, $input);
        rewind($stream);

        return $stream;
    }
}