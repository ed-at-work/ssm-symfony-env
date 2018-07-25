<?php
namespace SSMParameterStoreEnvVar\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * Created by PhpStorm.
 * User: eepstein
 * Date: 7/20/18
 * Time: 3:30 PM
 */
class SSMParameterStoreEnvVarExtension extends Extension
{

    /**
     * Loads a specific configuration.
     *
     * @param array            $configs
     * @param ContainerBuilder $container
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader =
            new YamlFileLoader($container,
                               new FileLocator(__DIR__ .
                                               '/../Resources/config'));
        $loader->load('services.yaml');
    }
}