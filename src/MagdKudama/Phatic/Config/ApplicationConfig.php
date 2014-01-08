<?php

namespace MagdKudama\Phatic\Config;

class ApplicationConfig
{
    protected $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function getConfigFile()
    {
        return $this->config['config_file'];
    }

    public function getAppDirectory()
    {
        return $this->config['app_directory'];
    }

    public function getSiteDirectory()
    {
        return $this->config['site_directory'];
    }

    public function getAssetsPath()
    {
        return $this->config['assets_path'];
    }

    public function getLayoutsPath()
    {
        return $this->config['layouts_path'];
    }

    public function getPostsPath()
    {
        return $this->config['posts_path'];
    }

    public function getResultsPath()
    {
        return $this->config['results_path'];
    }

    public function getPagesPath()
    {
        return $this->config['pages_path'];
    }

    public function getSystemDirectory()
    {
        return $this->config['system_directory'];
    }

    public function getBootDirectory()
    {
        return $this->config['boot_directory'];
    }
}