<?php

namespace JJs\Bundle\ResponseConverterBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * Container Extension
 *
 * Selectively configures the response converter based on the services of other
 * bundles which have been configured in the service container.
 *
 * @author Josiah <josiah@jjs.id.au>
 */
class JJsResponseConverterExtension extends Extension
{
    /**
     * XML Namespace Url
     *
     * @var string
     */
    const NamespaceUrl = "http://jjs.id.au/bundles/response-converter";

    /**
     * Container Alias
     *
     * @var string
     */
    const Alias = "response_converter";

    /**
     * {@inheritdoc}
     * 
     * @return string
     */
    public function getAlias()
    {
        return self::Alias;
    }

    /**
     * {@inheritdoc}
     * 
     * @return string
     */
    public function getNamespace()
    {
        return self::NamespaceUrl;
    }

    /**
     * {@inheritdoc}
     *
     * @param array            $config    An array of configuration values
     * @param ContainerBuilder $container A ContainerBuilder instance
     *
     * @throws InvalidArgumentException When provided tag is not defined in this extension
     */
    public function load(array $config, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__."/../Resources/config"));
        $bundles = $container->getParameter('kernel.bundles');

        if (array_key_exists('KnpSnappyBundle', $bundles)) $loader->load('Bundle/KnpSnappyBundle.xml');
    }
}