<?php

namespace PASS\AuthentificationLogBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class PASSAuthentificationLogExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        
            $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
            $ldap = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
            $loader->load('services.yml');
            //$ldap->load('ldap.yml');
            //var_dump($ldap);
                $container->setParameter('simple_ldap.settings', $config['settings']);
            $container->setParameter('simple_ldap.settings_user', $config['settings_user']);
            $container->setParameter('simple_ldap.user_redirects', $config['user_redirects']);
            $container->setParameter('simple_ldap.user_class', $config['user_class']);
            $container->setParameter('simple_ldap.default_role', $config['default_role']);
    }
    public function getXsdValidationBasePath()
    {
        return __DIR__.'/../Resources/config/';
    }

    public function getNamespace()
    {
        return 'zz/';
    }
}
