<?php

/* 
 * Copyright 2015 Version 1.0.0
 * Pour le Pass, projet gestion de log.
 * @author Huygens Adrien
 * contact adrien.huygens@gmail.com
 */



namespace PASS\AuthentificationLogBundle\Security\Authentification\Factory;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\SecurityFactoryInterface;

class ldapFactory implements SecurityFactoryInterface{
    //la méthode create, qui ajoute le listener et le fournisseur d'authentification au conteneur d'Injection de Dépendances pour le contexte de sécurité approprié ;
     public function create(ContainerBuilder $container, $id, $config, $userProvider, $defaultEntryPoint)
    {
        $providerId = 'security.authentication.provider.ldap.'.$id;
        $container
            ->setDefinition($providerId, new DefinitionDecorator('ldap.security.authentication.provider'))
            ->replaceArgument(0, new Reference($userProvider))
        ;

        $listenerId = 'security.authentication.listener.ldap.'.$id;
        $listener = $container->setDefinition($listenerId, new DefinitionDecorator('ldap.security.authentication.listener'));

        return array($providerId, $listenerId, $defaultEntryPoint);
    }
    
    //a méthode getPosition, qui doit être de type pre_auth, form, http et remember_me et qui définit le moment auquel le fournisseur est appelé ;
    public function getPosition()
    {
        return 'pre_auth';
    }
    //la méthode getKey qui définit la clé de configuration utilisée pour référencer le fournisseur 
    public function getKey()
    {
        return 'ldap';
    }
// la méthode addConfiguration, qui est utilisée pour définir les options 
    public function addConfiguration(NodeDefinition $node)
    {
    }
    
}