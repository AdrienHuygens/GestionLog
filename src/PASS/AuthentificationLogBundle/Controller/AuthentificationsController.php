<?php

// https://www.youtube.com/watch?v=5idECbKd_oo#t=677 15min

namespace PASS\AuthentificationLogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use PASS\AuthentificationLogBundle\Entity\Groupe;
use PASS\AuthentificationLogBundle\Form\GroupeType;
use PASS\AuthentificationLogBundle\Entity\Personne;
use PASS\AuthentificationLogBundle\Form\PersonneType;
use PASS\AuthentificationLogBundle\Form\editPersonneType;
use PASS\AuthentificationLogBundle\Form\changeMDPType;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use PASS\AuthentificationLogBundle\Form\groupeRoleType;
use PASS\AuthentificationLogBundle\Form\roleType;

class AuthentificationsController extends Controller {

   

    
   
    
    public function changeMDPAction(Request $request, Personne $personneId) {
        
         if ((!$this->get('security.context')->isGranted('ROLE_USER_U') && !$this->get('security.context')->isGranted('ROLE_ADMIN')) && $personneId->getUsername() !== $this->getUser()->getUsername() &&!$this->get('security.context')->isGranted('ROLE_MEMORY') 
                          || ($this->get('security.context')->isGranted('ROLE_MEMORY') && $personneId->getSuprimable() )) {
             
                    throw new AccessDeniedException('Vous avez pas acces à cette partie.');
                  }
        $form = $this->createForm(new changeMDPType(), $personneId);

        $form->handleRequest($request);

        if ($form->isValid()) {

            $factory = $this->get('security.encoder_factory');
            $encoder = $factory->getEncoder($personneId);

            $personneId->setMdp($encoder->encodePassword($personneId->getMdp(), $personneId->getSalt()));


            $em = $this->getDoctrine()->getManager();
            $em->persist($personneId);
            $em->flush();

            return $this->render('PASSAuthentificationLogBundle:authentification:ok.html.twig', Array(
                        "good" => "Modification mot de passe.",
                        'titrePage' => 'Opération éffectué',
            ));
        }


        return $this->render('PASSAuthentificationLogBundle:authentification:changeMdp.html.twig', array(
                    'form' => $form->createView(),
                    'id' => $personneId->getId()
        ));
    }
    
    
    
    
    public function changeMonMDPAction(Request $request) {
        
                  $personneId = $this->getDoctrine()->getRepository("PASS\AuthentificationLogBundle\Entity\Personne")->find($this->getUser()->getId());
        
         if ((!$this->get('security.context')->isGranted('ROLE_USER_U') && !$this->get('security.context')->isGranted('ROLE_ADMIN')) && $personneId->getUsername() !== $this->getUser()->getUsername() &&!$this->get('security.context')->isGranted('ROLE_MEMORY') 
                          || ($this->get('security.context')->isGranted('ROLE_MEMORY') && $personneId->getSuprimable() )) {
             
                    throw new AccessDeniedException('Vous avez pas acces à cette partie.');
                  }
                          $form = $this->createForm(new changeMDPType(), $personneId);

        $form->handleRequest($request);

        if ($form->isValid()) {

            $factory = $this->get('security.encoder_factory');
            $encoder = $factory->getEncoder($personneId);

            $personneId->setMdp($encoder->encodePassword($personneId->getMdp(), $personneId->getSalt()));


            $em = $this->getDoctrine()->getManager();
            $em->persist($personneId);
            $em->flush();

            return $this->render('PASSAuthentificationLogBundle:authentification:ok.html.twig', Array(
                        "good" => "Modification mot de passe.",
                        'titrePage' => 'Opération éffectué',
            ));
        }


        return $this->render('PASSAuthentificationLogBundle:authentification:changeMdp.html.twig', array(
                    'form' => $form->createView(),
                    'id' => $personneId->getId()
        ));
    }
    
    
    
  
    
    /**
     * 
     * @Secure(roles="ROLE_DROIT_U, ROLE_ADMIN")
     */
    public function roleUserAction(Request $request,Personne $personne){
         $form = $this->createForm(new groupeRoleType($this->getDoctrine() ->getRepository('PASSAuthentificationLogBundle:Groupe'), $this->getDoctrine()->getRepository('PASSAuthentificationLogBundle:Role')), $personne);
    
         
         $groupetmp = clone  $personne->getGroupes();
         $roletmp = clone  $personne->getRoles();
         
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
          
            
            //$user->setMdp($encoder->encodePassword($personne->getMdp(), $personne->getSalt()));
            foreach($groupetmp as $groupe){
                if(! $groupe->getSupprimable()) $personne->addGroupe($groupe);
            }
            foreach($roletmp as $role){
                if($role->getType() =="") $personne->addRole($role);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($personne);
           
            $em->flush();
             $token = new UsernamePasswordToken(
           $this->getUser(),
            null,
            'ldap',
            $this->getUser()->getAllRoles()
                  //array("ROLES_ADMIN")
            );
            $this->container->get('security.context')->setToken($token);    
           
           
             $utilisateur = $this->getDoctrine()->getRepository("PASSAuthentificationLogBundle:Personne")->find($personne->getId());

            return $this->render('PASSAuthentificationLogBundle:listing:recapitulatif.html.twig', Array(
                        'titrePage' => 'Gestion d\'un utilisateur', 'groupe' => $utilisateur->resumer(), 'nom' => $utilisateur->getUsername(),
                        'activiter' => " Information sur l'utilisateur", 'lien' => 'PASS_ModificationUtilisateur', 'id' => $personne->getId(),
                        'activite' => "utilisateur", "good" => "Modification éffectué"
            ));
        }
         return $this->render('PASSAuthentificationLogBundle:authentification:form.html.twig', Array(
                    "form" => $form->createView(),
                    'titrePage' => 'Modifier les groupes et les roles d\'un utilisateur',
                    'id' => $personne->getId(),
                    'suprimable' => $personne->getSuprimable()
        ));
        
         
         
         
    }
    
     public function roleGroupeAction(Request $request,Groupe $groupe){
         $form = $this->createForm(new roleType( $this->getDoctrine()->getRepository('PASSAuthentificationLogBundle:Role')), $groupe);
    
         
         
         $roletmp = clone  $groupe->getRoles();
         
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
          
            
            //$user->setMdp($encoder->encodePassword($personne->getMdp(), $personne->getSalt()));
            
            foreach($roletmp as $role){
                if($role->getType() =="") $groupe->addRole($role);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($groupe);
           
            $em->flush();
             $token = new UsernamePasswordToken(
           $this->getUser(),
            null,
            'ldap',
            $this->getUser()->getAllRoles()
                  //array("ROLES_ADMIN")
            );
            $this->container->get('security.context')->setToken($token);    
           
           
             $utilisateur = $this->getDoctrine()->getRepository("PASSAuthentificationLogBundle:groupe")->find($groupe->getId());

            return $this->render('PASSAuthentificationLogBundle:listing:recapitulatif.html.twig', Array(
                        'titrePage' => 'Gestion d\'un groupe', 'groupe' => $groupe->resumer(), 'nom' => $groupe->getNom(),
                        'activiter' => " Information sur le groupe", 'lien' => 'PASS_ModificationGroupe', 'id' => $groupe->getId(),
                        'activite' => "groupe" ));
        }
         return $this->render('PASSAuthentificationLogBundle:authentification:form.html.twig', Array(
                    "form" => $form->createView(),
                    'titrePage' => 'Modifier les groupes et les roles d\'un utilisateur',
                    'id' => $groupe->getId(),
                    
        ));
        
         
         
         
    }
    
    /*
     * function pour la vérification de connexion.
     */
    public function loginAction(Request $request) {
        //var_dump($request);
        $session = $request->getSession();
// get the login error if there is one
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }
        return $this->render('PASSAuthentificationLogBundle:authentification:login.html.twig', array(
// last username entered by the user
                    'last_username' => $session->get(SecurityContext::LAST_USERNAME),
                    'error' => $error,
        ));
    }
    
     /**
     * 
     * @Secure(roles="ROLE_DEFAULT, ROLE_ADMIN, ROLE_MEMORY")
     */
    public function okAction() {
        $em = $this->getDoctrine()->getManager();

        if (!$this->get('security.context')->isGranted('ROLE_MEMORY')){
          $this->getUser()->setDernierConnexion(new \DateTime());
          $em->persist($this->getUser());
        $em->flush(); }
        
      
                new \PASS\GeneralLogBundle\Entity\Loggers($this->getDoctrine()->getManager(), "L'utilisateur :". $this->getUser()->getUsername()
                       ." avec l'ip: ".$_SERVER['REMOTE_ADDR']." viens de se connecter",5,4);
         // dump($this->getUser()->getRoles());
        return $this->render("PASSAuthentificationLogBundle:authentification:ok.html.twig", Array(
                    'titrePage' => 'Connexion effectué',
                    'good' => null));
    }
}
