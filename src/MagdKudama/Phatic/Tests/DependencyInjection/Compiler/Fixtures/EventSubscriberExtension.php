<?php

namespace MagdKudama\Phatic\Tests\DependencyInjection\Compiler\Fixtures;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class EventSubscriberExtension implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [];
    }
}