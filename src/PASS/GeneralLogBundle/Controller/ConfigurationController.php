<?php

/* 


   * Copyright 2015 Version 1.0.0
   * Pour le Pass, projet gestion de log.
   * @author Huygens Adrien
   * contact adrien.huygens@gmail.com
 
 */


namespace PASS\GeneralLogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use PASS\GeneralLogBundle\Entity\ConfigurationSql;
use PASS\GeneralLogBundle\Form\ConfigurationSqlType;

use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Dumper;

use Symfony\Component\Security\Core\User\User;


class ConfigurationController extends Controller
{
    public function indexAction(Request $request){
        $chemin ="";
    $Config = new \PASS\GeneralLogBundle\Entity\ConfigurationSql();
    $form = $this->createForm(new ConfigurationSqlType(), $Config);
    
     $form->handleRequest($request);
    if ($form->isValid()) {
         
        $Config->Enregistrer();
        
    }
    else{
           // $Config->load();
       // $form->setData($Config);
    }
    $factory = $this->get('security.encoder_factory');
    $user = new User('AdminM','test');
    $encoder = $factory->getEncoder($user);
    dump($encoder->encodePassword('test', $user->getSalt()));
   
    
          return $this->render('PASSGeneralLogBundle:form:form.html.twig', Array(
                    "form" => $form->createView(),
                    'titrePage' => 'Changer la configuration de la base de donné',
                    'chemin' => $chemin
            ));
        
    }
}


