<?php

namespace PASS\AuthentificationLogBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use PASS\AuthentificationLogBundle\Security\Authentification\Factory\ldapFactory;

class PASSAuthentificationLogBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $extension = $container->getExtension('security');
        $extension->addSecurityListenerFactory(new ldapFactory());
    }
    
}
