<?php

namespace Sandronimus\NelmioFormGetParameterPluginBundle\Tests;

use Nelmio\ApiDocBundle\NelmioApiDocBundle;
use Sandronimus\NelmioFormGetParameterPluginBundle\NelmioFormGetParameterPluginBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

class TestKernel extends Kernel
{
    use MicroKernelTrait;

    public function __construct()
    {
        parent::__construct('test', true);
    }

    /**
     * {@inheritdoc}
     */
    public function registerBundles(): iterable
    {
        return [
            new FrameworkBundle(),
            new NelmioApiDocBundle(),
            new NelmioFormGetParameterPluginBundle(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function configureRoutes(RoutingConfigurator $routes)
    {
        $routes->import(__DIR__.'/Controller/', 'attribute');
    }

    /**
     * {@inheritdoc}
     */
    protected function configureContainer(ContainerBuilder $c, LoaderInterface $loader)
    {
        $framework = [
            'secret' => 'MySecretKey',
            'test' => null,
            'form' => null,
        ];
        $c->loadFromExtension('framework', $framework);

        // Filter routes
        $c->loadFromExtension('nelmio_api_doc', [
            'areas' => [
                'path_patterns' => ['^/api'],
            ],
        ]);
    }
}
