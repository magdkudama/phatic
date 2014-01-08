<?php

namespace MagdKudama\Phatic;

use MagdKudama\Phatic\Config\ApplicationConfig;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Twig_Environment as View;

abstract class AbstractProcessor
{
    protected $finder;
    protected $view;
    protected $fileSystem;
    protected $dispatcher;
    protected $config;

    public function __construct(Finder $finder, Filesystem $fileSystem, View $view, EventDispatcherInterface $dispatcher, ApplicationConfig $config)
    {
        $this->finder = $finder;
        $this->fileSystem = $fileSystem;
        $this->view = $view;
        $this->dispatcher = $dispatcher;
        $this->config = $config;
    }

    /** @return Finder */
    public function getFinder()
    {
        return $this->finder;
    }

    /** @return View */
    public function getView()
    {
        return $this->view;
    }

    /** @return Filesystem */
    public function getFileSystem()
    {
        return $this->fileSystem;
    }

    /** @return EventDispatcherInterface */
    public function getDispatcher()
    {
        return $this->dispatcher;
    }

    /** @return ApplicationConfig */
    public function getConfig()
    {
        return $this->config;
    }

    abstract function getCollection();

    abstract function getName();

    abstract function dump($element);
}