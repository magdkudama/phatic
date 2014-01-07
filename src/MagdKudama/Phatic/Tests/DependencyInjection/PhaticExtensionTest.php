<?php

namespace MagdKudama\Phatic\Tests\DependencyInjection;

use MagdKudama\Phatic\DependencyInjection\PhaticExtension;
use MagdKudama\Phatic\Tests\TestCase;
use MagdKudama\Phatic\Utils;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class PhaticExtensionTest extends TestCase
{
    /** @var ContainerBuilder */
    private $container;

    /** @var PhaticExtension */
    private $extension;

    public function setUp()
    {
        $this->container = new ContainerBuilder();
        $this->extension = new PhaticExtension(__DIR__, __DIR__);
    }

    /**
     * @dataProvider badConfigProvider
     * @expectedException Symfony\Component\Config\Definition\Exception\InvalidTypeException
     */
    public function testBadConfigurationThrowsException($config)
    {
        $this->extension->load($config, $this->container);
    }

    public function testContainerDefinitionsAreSet()
    {
        $config = [
            'config' => [
                'extensions' => []
            ]
        ];

        $this->extension->load($config, $this->container);

        $this->assertTrue($this->container->hasParameter('phatic.app_config'), 'Check app_config parameter is set to the container');

        $appConfig = $this->container->getParameter('phatic.app_config');

        $checks = [
            'config_file' => [
                'name' => 'config file',
                'value' => __DIR__
            ],
            'app_directory' => [
                'name' => 'application directory',
                'value' => __DIR__
            ],
            'site_directory' => [
                'name' => 'site directory',
                'value' => __DIR__ . '/site/'
            ],
            'assets_path' => [
                'name' => 'assets path',
                'value' => __DIR__ . '/site/_assets/'
            ],
            'layouts_path' => [
                'name' => 'layouts path',
                'value' => __DIR__ . '/site/_pages/_layouts/'
            ],
            'posts_path' => [
                'name' => 'posts path',
                'value' => __DIR__ . '/site/_posts/'
            ],
            'results_path' => [
                'name' => 'results path',
                'value' => __DIR__ . '/site/result/'
            ],
            'pages_path' => [
                'name' => 'results path',
                'value' => __DIR__ . '/site/_pages/'
            ],
            'system_directory' => [
                'name' => 'system directory',
                'value' => Utils::getSystemDirectory()
            ],
            'boot_directory' => [
                'name' => 'boot directory',
                'value' => Utils::getBootDirectory() . 'bootstrap/site/'
            ]
        ];

        foreach ($checks as $key => $config) {
            $this->assertEquals(
                $config['value'],
                $appConfig[$key],
                'Check the ' . $config['name'] . ' config value is correct'
            );
        }
    }

    /**
     * @expectedException MagdKudama\Phatic\Exception\UndefinedClassException
     */
    public function testExtensionClassMustExist()
    {
        $config = [
            'config' => [
                'extensions' => [
                    'fake' => []
                ]
            ]
        ];

        $this->extension->load($config, $this->container);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testExtensionClassMustImplementInterface()
    {
        $config = [
            'config' => [
                'extensions' => [
                    'MagdKudama\Phatic\Tests\DependencyInjection\Fixtures\WrongExtension' => []
                ]
            ]
        ];

        $this->extension->load($config, $this->container);
    }

    public function testExtensionsAreLoadedCorrectlyAndConfigParamsSet()
    {
        $config = [
            'config' => [
                'extensions' => [
                    'MagdKudama\Phatic\Tests\DependencyInjection\Fixtures\MyTestExtension' => [
                        'param' => 'test'
                    ]
                ]
            ]
        ];

        $this->extension->load($config, $this->container);

        $this->assertEquals(
            1,
            count($this->extension->getExtensionCollection()),
            'Check that extensions are loaded'
        );

        $this->assertTrue(
            $this->container->hasParameter('phatic.mytest.param'),
            'Extension parameters are set into the container (parameter exists)'
        );

        $this->assertEquals(
            'test',
            $this->container->getParameter('phatic.mytest.param'),
            'Extension parameters are set into the container (parameter value is correct)'
        );
    }

    public function badConfigProvider()
    {
        return [
            [
                [
                    'bad'
                ]
            ],
            [
                [
                    'config'
                ]
            ]
        ];
    }
}