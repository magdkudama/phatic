<?php

namespace MagdKudama\Phatic\Collection;

use IteratorAggregate;
use Countable;
use Doctrine\Common\Collections\ArrayCollection;
use MagdKudama\Phatic\Extension;

class ExtensionCollection implements IteratorAggregate, Countable
{
    /** @var ArrayCollection */
    private $extensions;

    public function __construct()
    {
        $this->extensions = new ArrayCollection();
    }

    public function add(Extension $extension)
    {
        if (!$this->extensions->contains($extension)) {
            $this->extensions->add($extension);
        }

        return $extension;
    }

    public function addByFqn($extensionClass)
    {
        if (class_exists($extensionClass)) {
            return $this->add(new $extensionClass());
        }

        return false;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function findByName($name)
    {
        foreach ($this->extensions as $extension) {
            if (get_class($extension) == $name) {
                return $extension;
            }
        }

        return false;
    }

    public function getIterator()
    {
        return $this->extensions->getIterator();
    }

    public function count()
    {
        return count($this->extensions);
    }
}