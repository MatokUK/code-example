<?php

namespace Matok\Bundle\MediaBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Matok\Bundle\MediaBundle\Configurator;
use Symfony\Component\DependencyInjection\Definition;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class MatokMediaExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('forms.xml');
        $loader->load('services.xml');
        $loader->load('command.xml');


       // $command = $container->getDefinition('mattias_command');
       // $command->setClass('Matok\Bundle\MediaBundle\Command\NullCommand');

        //var_dump($commad);
        //$this->createMediaParameters($container, $config);

    //    var_dump($config['base_dir']);
      //  $container->setParameter('matok_media.base_dir', addslashes($config['base_dir']));
       // $container->setParameter('matok_media.base_dir2',$config['base_dir']);


       /* $x = $container->getDefinition('matok_media.base_dir');
        var_dump($x);*/

        $this->xxx($container, $config);

        $this->createMediaConfigs($container, $config);

    }

    private function xxx(ContainerBuilder $container, $config)
    {
        //$container->setParameter('matok_media.base_dir', $config['base_dir']);
        $definition = $container->getDefinition('Matok\Bundle\MediaBundle\Image\Dimension');
        $definition->replaceArgument(0, $config['base_dir']);
    }

    private function createMediaConfigs(ContainerBuilder $container, $config)
    {
        foreach ($config['configs'] as $configName => $values) {
            if (!is_dir($config['base_dir'].$values['directory'])) {
                mkdir($config['base_dir'].$values['directory'], 0777, true);
            }
            $definition = new Definition(\Matok\Bundle\MediaBundle\Configurator\Configuration::class,
                                array('_'.$configName, $config['base_dir'], $values['directory'], $values['thumbnails'], $values['allow_original_size']));

            $container->setDefinition('matok_media.config.'.$configName, $definition);
        }
    }
}
