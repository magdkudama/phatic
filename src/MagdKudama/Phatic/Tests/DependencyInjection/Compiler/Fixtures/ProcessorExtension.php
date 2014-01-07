<?php

namespace MagdKudama\Phatic\Tests\DependencyInjection\Compiler\Fixtures;

use MagdKudama\Phatic\AbstractProcessor;

class ProcessorExtension extends AbstractProcessor
{
    public function getCollection()
    {
        return [];
    }

    public function getName()
    {
        return 'processor';
    }

    public function dump($element)
    {
        return null;
    }
}