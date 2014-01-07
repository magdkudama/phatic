<?php

namespace MagdKudama\Phatic\Collection;

use IteratorAggregate;
use Countable;
use Doctrine\Common\Collections\ArrayCollection;
use MagdKudama\Phatic\Console\Command\ContainerAwareCommand;

class CommandCollection implements IteratorAggregate, Countable
{
    /** @var ArrayCollection */
    private $commands;

    public function __construct()
    {
        $this->commands = new ArrayCollection();
    }

    public function add(ContainerAwareCommand $command)
    {
        if (!$this->commands->contains($command)) {
            $this->commands->add($command);
        }

        return $this;
    }

    public function getIterator()
    {
        return $this->commands->getIterator();
    }

    public function count()
    {
        return count($this->commands);
    }
}