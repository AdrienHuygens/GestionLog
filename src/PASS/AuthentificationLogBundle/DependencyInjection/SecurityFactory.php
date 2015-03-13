<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace PASS\AuthentificationLogBundle\DependencyInjection;
 
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\FormLoginFactory;
 
class SecurityFactory extends FormLoginFactory
{
    public function getKey()
    {
        return 'webservice-login';
    }
 
    protected function getListenerId()
    {
        return 'security.authentication.listener.form';
    }
 
    protected function createAuthProvider(ContainerBuilder $container, $id, $config, $userProviderId)
    {
        $provider = 'security.authentication_provider.pass_auth_webservice.'.$id;
        $container
            ->setDefinition($provider, new DefinitionDecorator('security.authentication_provider.pass_auth_webservice'))
            ->replaceArgument(1, new Reference($userProviderId))
            ->replaceArgument(3, $id)
        ;
 
        return $provider;
    }
}