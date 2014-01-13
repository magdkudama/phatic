<?php

namespace MagdKudama\Phatic\Collection;

use IteratorAggregate;
use Countable;
use Doctrine\Common\Collections\ArrayCollection;
use MagdKudama\Phatic\AbstractProcessor;
use MagdKudama\Phatic\Processor;

class ProcessorCollection implements IteratorAggregate, Countable
{
    /** @var ArrayCollection */
    private $processors;

    public function __construct()
    {
        $this->processors = new ArrayCollection();
    }

    public function add(Processor $processor)
    {
        if (!$this->processors->contains($processor)) {
            $this->processors->add($processor);
        }

        return $this;
    }

    public function getIterator()
    {
        return $this->processors->getIterator();
    }

    public function count()
    {
        return count($this->processors);
    }
}