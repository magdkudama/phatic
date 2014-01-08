<?php

namespace MagdKudama\Phatic\Console\Command;

use Symfony\Component\Console\Output\OutputInterface;

class CommandOutputHelper
{
    /**
     * @param string $tag
     * @param string $message
     */
    public static function write(OutputInterface $output, $message, $tag)
    {
        $startTag = sprintf("<%s>", $tag);
        $endTag = sprintf("</%s>", $tag);
        $output->writeln($startTag . $message . $endTag);
    }

    /**
     * @param string $message
     */
    public static function writeInfo(OutputInterface $output, $message)
    {
        self::write($output, $message, "info");
    }

    /**
     * @param string $message
     */
    public static function writeError(OutputInterface $output, $message)
    {
        self::write($output, $message, "error");
    }

    /**
     * @param string $message
     */
    public static function writeComment(OutputInterface $output, $message)
    {
        self::write($output, $message, "comment");
    }

    public static function success(OutputInterface $output)
    {
        self::writeInfo($output, 'Command successfully executed!');
    }

    public static function cancel(OutputInterface $output)
    {
        self::writeInfo($output, 'Operation cancelled');
    }
}