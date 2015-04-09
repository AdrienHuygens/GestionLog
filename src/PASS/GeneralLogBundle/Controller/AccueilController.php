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
use PASS\GeneralLogBundle\Entity\ConfigurationLDAP;
use PASS\GeneralLogBundle\Form\ConfigurationLDAPType;
use PASS\GeneralLogBundle\Form\ConfigurationMailType;
use PASS\GeneralLogBundle\Entity\ConfigurationMail;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;

class AccueilController extends Controller {
    public function indexAction(){
       
       if ($this->get('security.context')->isGranted('ROLE_DEFAULT')) {
             
                 
                 
        return $this->render('PASSGeneralLogBundle:form:ok.html.twig', Array(
                   'titrePage' => 'Accueil',
        ));
         }
         else{
         return $this->render('PASSGeneralLogBundle:form:accueil.html.twig', Array(
                   'titrePage' => 'Accueil',
        ));
         }
    }
}
