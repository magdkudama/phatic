<?php

namespace MagdKudama\Phatic\Tests\Config;

use MagdKudama\Phatic\Config\ApplicationConfig;
use MagdKudama\Phatic\Tests\TestCase;
use MagdKudama\Phatic\Utils;

class ApplicationConfigTest extends TestCase
{
    public function testGetterMethodsWork()
    {
        $arrayConfig = [
            'config_file' => __DIR__,
            'app_directory' => __DIR__,
            'site_directory' => __DIR__ . '/site/',
            'assets_path' => __DIR__ . '/site/_assets/',
            'layouts_path' => __DIR__ . '/site/_pages/_layouts/',
            'posts_path' => __DIR__ . '/site/_posts/',
            'results_path' => __DIR__ . '/site/result/',
            'pages_path' => __DIR__ . '/site/_pages/',
            'system_directory' => Utils::getSystemDirectory(),
            'boot_directory' => Utils::getBootDirectory() . 'bootstrap/site/'
        ];

        $configClass = new ApplicationConfig($arrayConfig);
        $this->assertEquals(
            $arrayConfig['config_file'],
            $configClass->getConfigFile(),
            'Check the config file is the one we set'
        );

        $this->assertEquals(
            $arrayConfig['app_directory'],
            $configClass->getAppDirectory(),
            'Check the app directory is the one we set'
        );

        $this->assertEquals(
            $arrayConfig['site_directory'],
            $configClass->getSiteDirectory(),
            'Check the site directory is the one we set'
        );

        $this->assertEquals(
            $arrayConfig['assets_path'],
            $configClass->getAssetsPath(),
            'Check the assets path is the one we set'
        );

        $this->assertEquals(
            $arrayConfig['layouts_path'],
            $configClass->getLayoutsPath(),
            'Check the layouts path is the one we set'
        );

        $this->assertEquals(
            $arrayConfig['posts_path'],
            $configClass->getPostsPath(),
            'Check the posts path is the one we set'
        );

        $this->assertEquals(
            $arrayConfig['results_path'],
            $configClass->getResultsPath(),
            'Check the results path is the one we set'
        );

        $this->assertEquals(
            $arrayConfig['pages_path'],
            $configClass->getPagesPath(),
            'Check the pages path is the one we set'
        );

        $this->assertEquals(
            $arrayConfig['system_directory'],
            $configClass->getSystemDirectory(),
            'Check the system path is the one we set'
        );

        $this->assertEquals(
            $arrayConfig['boot_directory'],
            $configClass->getBootDirectory(),
            'Check the boot directory is the one we set'
        );
    }
}