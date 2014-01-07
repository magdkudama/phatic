<?php

namespace MagdKudama\Phatic\Tests\DependencyInjection\Compiler\Fixtures;

use Twig_Extension;

class ViewExtension extends Twig_Extension
{
    public function getName()
    {
        return 'extension';
    }
}