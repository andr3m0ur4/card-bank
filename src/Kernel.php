<?php

namespace CardCollection;

use CardCollection\Controllers\StatusController;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;
    
    public function registerBundles(): iterable
    {
        return [
            new FrameworkBundle(),
        ];
    }

    public function configureContainer(ContainerConfigurator $container)
    {
        // Config do framework
        $container->extension('framework', [
            'test' => true,
        ]);
        
        // REGISTRA CONTROLLERS
        $services = $container->services();
        $services
            ->load('CardCollection\\Controllers\\', __DIR__ . '/Controllers/*')
            ->autowire()
            ->autoconfigure()
            ->public();
    }

    protected function configureRoutes(RoutingConfigurator $routes): void
    {
        $routes->add('status', '/api/v1/status')
               ->controller([StatusController::class, 'showStatus']);
    }
}
