<?php

namespace Sandronimus\NelmioFormGetParameterPluginBundle\Tests;

use Nelmio\ApiDocBundle\NelmioApiDocBundle;
use Sandronimus\NelmioFormGetParameterPluginBundle\NelmioFormGetParameterPluginBundle;
use Symfony\Bundle\DebugBundle\DebugBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Bundle\WebProfilerBundle\WebProfilerBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

class TestKernel extends Kernel
{
    use MicroKernelTrait;

    /**
     * {@inheritdoc}
     */
    public function registerBundles(): iterable
    {
        return [
            new FrameworkBundle(),
            new TwigBundle(),
            new NelmioApiDocBundle(),
            new NelmioFormGetParameterPluginBundle(),
            new DebugBundle(),
            new WebProfilerBundle(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function configureRoutes(RoutingConfigurator $routes)
    {
        $routes->import(__DIR__.'/Controller/', 'attribute');
        $routes->import('@WebProfilerBundle/Resources/config/routing/wdt.xml')->prefix('/_wdt');
        $routes->import('@WebProfilerBundle/Resources/config/routing/profiler.xml')->prefix('/_profiler');

        $routes
            ->add('app.swagger', '/api/doc.json')
            ->methods(['GET'])
            ->defaults(['_controller' => 'nelmio_api_doc.controller.swagger']);

        $routes
            ->add('app.swagger_ui', '/api/doc')
            ->methods(['GET'])
            ->defaults(['_controller' => 'nelmio_api_doc.controller.swagger_ui']);
    }

    /**
     * {@inheritdoc}
     */
    protected function configureContainer(ContainerBuilder $container, LoaderInterface $loader)
    {
        $loader->load(__DIR__ . '/config/services.yaml');

        $framework = [
            'secret' => 'MySecretKey',
            'test' => null,
            'form' => null,
            'assets' => [
                'enabled' => true,
            ],
        ];
        if ($this->getEnvironment() === 'dev') {
            $framework['profiler'] = [
                'only_exceptions' => false,
                'collect_serializer_data' => true,
            ];
        }
        $container->loadFromExtension('framework', $framework);

        // Filter routes
        $container->loadFromExtension('nelmio_api_doc', [
            'areas' => [
                'path_patterns' => ['^/api'],
            ],
            'documentation' => [
                'info' => [
                    'title' => "API",
                    'description' => "REST API",
                    'version' => "1.0.0",
                ],
            ],
        ]);
    }
}
