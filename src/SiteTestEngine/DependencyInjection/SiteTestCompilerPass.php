<?php


namespace App\SiteTestEngine\DependencyInjection;


use App\SiteTestEngine\SiteTestInterface;
use App\SiteTestEngine\SiteTestProcessor;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class SiteTestCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if(!$container->has(SiteTestProcessor::class)){
            return ;
        }
        $definition = $container->findDefinition(SiteTestProcessor::class);
        $taggedServices = $container->findTaggedServiceIds('app.site_test');

        foreach ($taggedServices as $id => $tags) {
            // add the transport service to the TransportChain service
            $definition->addMethodCall('addTestType', [new Reference($id)]);
        }
    }
}