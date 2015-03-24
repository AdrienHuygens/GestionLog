<?php

// https://www.youtube.com/watch?v=5idECbKd_oo#t=677 15min

namespace PASS\AuthentificationLogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use PASS\AuthentificationLogBundle\Entity\ldapPersonne;
use Symfony\Component\HttpFoundation\Request;
use \PASS\AuthentificationLogBundle\Entity\Personne;


class ImportationPersonneController extends Controller {

    public function importPersonneAction(Request $request) {

        $server = $this->container->getParameter("ldap_server");
        
        $port = $this->container->getParameter("ldap_port");
        $dn = $this->container->getParameter("ldap_dn");
        $filtre = $this->container->getParameter("ldap_filtre");

        /*
         * 
         * gestion de connexion à ldap pour la recherche des utilisateurs
         */
        try {
            $ds = ldap_connect($server);

            $sr = ldap_search($ds, $dn, $filtre);
            $info = ldap_get_entries($ds, $sr);

            $utilisateur = array();
            foreach ($info as $i) {

                if (isset($i['count'])) {

                    $display = null;
                    $mail = null;
                    if (isset($i["mail"])) {
                        $mail = $i["mail"][0];
                    }
                    if (isset($i["displayname"])) {
                        $display = $i["displayname"][0];
                    }
                    $utilisateur[$i["uid"][0]] =new ldapPersonne ($i["uid"][0], $display , $mail);
                }
            }
           
            
              $form =$this->createFormBuilder()
                
               ->add('utilisateur','choice',array('choices'=> $utilisateur,'multiple' => true,'required' => false, 'expanded'=>true ))
                -> add('importer','submit')
                      ->getForm() ;
                ;
                 $form->handleRequest($request);
                 if ($form->isSubmitted() && $form->isValid()) {
                     
                 $em = $this->getDoctrine()->getManager();
           
          
                   foreach ($form->getData() as $userldap)
                   {
                       
                       $userP = new Personne();
                       $userP->setLdap(True);
                       $userP->setMail($utilisateur[$userldap[0]]->getMail());
                       $userP->setUsername($userldap);
                       $userP->setRoles("ROLE_ADMIN");
                       $em->persist($userP);
                        $em->flush();
                   }
                      
                     
                 }
            
            
            
        } catch (Exception $e) {
            return $this->render('PASSGeneralLogBundle:erreur:ErreurLDAP.html.twig', Array("good" => $e));
        }

        ldap_close($ds);
        return $this->render('PASSAuthentificationLogBundle:Import:importUser.html.twig', Array(
                               
                         "form" => $form->createView(),
                        
      ));
        
    }

}
