<?php

namespace PASS\AuthentificationLogBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('pass_authentification_log');
         	$rootNode
->children()
->arrayNode('settings')
->children()
->scalarNode('server')->isRequired()->end()
->scalarNode('port')->isRequired()->end()
->scalarNode('account_suffix')->isRequired()->end()
->scalarNode('base_dn')->isRequired()->end()
->end()
->end()
->arrayNode('settings_user')
->useAttributeAsKey('name')
->prototype('scalar')->end()
->end()
->arrayNode('user_redirects')
->useAttributeAsKey('name')
->prototype('scalar')->end()
->end()
->scalarNode('user_class')->isRequired()->end()
->scalarNode('default_role')->isRequired()->end()
->end();

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        return $treeBuilder;
    }
}
