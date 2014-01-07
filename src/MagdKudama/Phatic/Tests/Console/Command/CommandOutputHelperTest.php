<?php

namespace MagdKudama\Phatic\Tests\Console\Command;

use MagdKudama\Phatic\Console\Command\CommandOutputHelper;
use MagdKudama\Phatic\Tests\TestCase;
use Symfony\Component\Console\Formatter\OutputFormatter;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\StreamOutput;
use Symfony\Component\Console\Output\Output;

class CommandOutputHelperTest extends TestCase
{
    /** @var StreamOutput */
    protected $output;

    protected $stream;

    public function setUp()
    {
        $this->stream = fopen('php://memory', 'a', false);
        $this->output = new StreamOutput($this->stream, StreamOutput::VERBOSITY_NORMAL, null);
        $this->output->setDecorated(false);
    }

    protected function tearDown()
    {
        $this->stream = null;
    }

    public function testWrite()
    {
        CommandOutputHelper::write($this->output, "Lorem ipsum", "test");
        rewind($this->output->getStream());

        $this->assertEquals(
            '<test>Lorem ipsum</test>' . PHP_EOL,
            stream_get_contents($this->output->getStream()),
            'Write method works properly'
        );
    }

    public function testWriteInfo()
    {
        CommandOutputHelper::writeInfo($this->output, "Lorem ipsum");
        rewind($this->output->getStream());

        $this->assertEquals(
            'Lorem ipsum' . PHP_EOL,
            stream_get_contents($this->output->getStream()),
            'WriteInfo method works properly'
        );
    }

    public function testWriteError()
    {
        CommandOutputHelper::writeError($this->output, "Lorem ipsum");
        rewind($this->output->getStream());

        $this->assertEquals(
            'Lorem ipsum' . PHP_EOL,
            stream_get_contents($this->output->getStream()),
            'WriteError method works properly'
        );
    }

    public function testCommentError()
    {
        CommandOutputHelper::writeError($this->output, "Lorem ipsum");
        rewind($this->output->getStream());

        $this->assertEquals(
            'Lorem ipsum' . PHP_EOL,
            stream_get_contents($this->output->getStream()),
            'WriteComment method works properly'
        );
    }
}