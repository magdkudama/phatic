<?php

namespace MagdKudama\Phatic\Tests\DependencyInjection\Compiler\Fixtures;

use MagdKudama\Phatic\Processor;

class ProcessorExtension implements Processor
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