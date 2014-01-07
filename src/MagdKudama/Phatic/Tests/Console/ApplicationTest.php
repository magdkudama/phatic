<?php

namespace MagdKudama\Phatic\Tests\Console;

use MagdKudama\Phatic\Console\Application;
use MagdKudama\Phatic\Tests\TestCase;
use Symfony\Component\Console\Tester\ApplicationTester;

class ApplicationTest extends TestCase
{
    /** @var Application */
    protected $application;

    /** @var ApplicationTester */
    protected $appTester;

    protected $configFileOption;

    public function setUp()
    {
        $this->configFileOption = ['--config' => __DIR__ . '/Fixtures/phatic.yml'];
        $this->application = new Application();
        $this->application->setAutoExit(false);

        $this->appTester = new ApplicationTester($this->application);
    }

    public function testCommandList()
    {
        $this->appTester->run($this->configFileOption);

        $this->assertStringEqualsFile(
            __DIR__ . '/Fixtures/application-output.txt',
            $this->appTester->getDisplay(),
            'App execution without parameters shows all available commands'
        );
    }

    public function testInitialCommandsAppear()
    {
        $this->appTester->run($this->configFileOption);

        foreach (['bootstrap-application', 'list'] as $command) {
            $this->assertContains(
                $command,
                $this->appTester->getDisplay(),
                'Command ' . $command . 'appears initially'
            );
        }
    }

    public function testIncorrectConfigFileThrowsException()
    {
        $this->appTester->run(['--config' => __DIR__ . '/fake.yml']);

        $this->assertContains(
            '[Symfony\Component\Filesystem\Exception\FileNotFoundException]',
            $this->appTester->getDisplay(),
            'An exception is thrown if the configuration file does not exist'
        );
    }

    public function testIncorrectDirectoryThrowsException()
    {
        $this->appTester->run([
            '--config' => __DIR__ . '/Fixtures/phatic.yml',
            '--dir' => __DIR__ . '/fake'
        ]);

        $this->assertContains(
            '[Symfony\Component\Filesystem\Exception\FileNotFoundException]',
            $this->appTester->getDisplay(),
            'An exception is thrown if the resultant directory does not exist'
        );
    }
}