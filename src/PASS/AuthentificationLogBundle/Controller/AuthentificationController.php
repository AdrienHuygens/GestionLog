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

class AuthentificationController extends Controller {

    /**
     * @Route("/hello/{name}")
     * @Template()


      /*
     *  Controleur pour le formulaire de mon rajout de groupe.
     */
    public function groupeAddAction(Request $request,  Groupe $listingId=null) {
        $chemin = null;

        if ($listingId !== null) {
            $em = $this->getDoctrine()->getRepository('PASSAuthentificationLogBundle:Groupe');

            $groupe = $em->findOneById($listingId);
            $titre = "Modifier un groupe local";
            $chemin = $this->generateUrl('PASS_SupprimerGroupe', array('groupeId' => $listingId->getId()));
        } else {
            $groupe = new Groupe();
            $groupe->setLdap(False);
            $groupe->setActif(True);

            
            $titre = "Ajouter un Groupe local";
        }

        $form = $this->createForm(new GroupeType(), $groupe);

        if ($listingId !== 0) {
            $form->add('actif');
        }
        $form->add('save', 'submit');
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //var_dump(str_replace('<br>', "\n", $groupe->getDescription()));


            $em = $this->getDoctrine()->getManager();
            $em->persist($groupe);
            $em->flush();
            //$this->addFlash('success', '')
            ///$request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');
            //return $this->redirect($this->generateUrl('', array('id' => $advert->getId())));
            if ($listingId === null) {
                return $this->render('PASSAuthentificationLogBundle:authentification:ok.html.twig', Array(
                            "good" => "Groupe bien créé.",
                            'titrePage' => 'Opération éffectué'
                ));
            } else {
                return $this->redirect($this->generateUrl('PASS_GestionGroupe', array('listingId' => $listingId->getId())));
            }
        }

        return $this->render('PASSAuthentificationLogBundle:authentification:form.html.twig', Array(
                    "form" => $form->createView(),
                    'titrePage' => $titre,
                    'chemin' => $chemin
        ));
    }

    public function utilisateurAddAction(Request $request) {

        $chemin = null;

        $user = new Personne();
        $user->setActif(True);
        $user->setLdap(False);
        $ems = $this->getDoctrine() ->getRepository('PASSAuthentificationLogBundle:Groupe');
        $ems2 = $this->getDoctrine() ->getRepository('PASSAuthentificationLogBundle:Role');
        $groupe = $ems->findBy(array("nom"=>'Default'));
       

        $form = $this->createForm(new PersonneType( $ems,$ems2 ), $user);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $factory = $this->get('security.encoder_factory');
            $encoder = $factory->getEncoder($user);
            $user->addGroupe($groupe[0]);
            $user->setMdp($encoder->encodePassword($user->getMdp(), $user->getSalt()));


            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->render('PASSAuthentificationLogBundle:authentification:ok.html.twig', Array(
                        "good" => "utilisateur d'utilisateur bien créé.",
                        'titrePage' => 'Opération éffectué',
            ));
        }
        return $this->render('PASSAuthentificationLogBundle:authentification:form.html.twig', Array(
                    "form" => $form->createView(),
                    'titrePage' => 'Rajouter un utilisateur local',
                    'chemin' => $chemin
        ));
    }

    /**
     * fonction pour ajouter un role a la bdd et création du formulaire de celui si.
     * 
     * @param Request $request récuperation des champs
     */
    public function roleAddAction(Request $request) {
        
    }

    /**
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

    public function okAction() {
        $em = $this->getDoctrine()->getManager();

        
          $this->getUser()->setDernierConnexion(new \DateTime());
          $em->persist($this->getUser());
          $em->flush(); 
          dump($this->getUser()->getRoles());
        return $this->render("PASSAuthentificationLogBundle:authentification:ok.html.twig", Array(
                    'titrePage' => 'Connexion effectué',
                    'good' => null));
    }

    public function utilisateurListingAction($listingId) {
        if ($listingId != 0) {

            $utilisateur = $this->getDoctrine()->getRepository("PASSAuthentificationLogBundle:Personne")->find($listingId);

            return $this->render('PASSAuthentificationLogBundle:listing:recapitulatif.html.twig', Array(
                        'titrePage' => 'Gestion d\'un utilisateur', 'groupe' => $utilisateur->resumer(), 'nom' => $utilisateur->getUsername(),
                        'activiter' => " Information sur l'utilisateur", 'lien' => 'PASS_ModificationUtilisateur', 'id' => $listingId,
                        'activite' => "utilisateur"
            ));
        } else {
            $repoJeune = $this->getDoctrine()->getRepository("PASS\AuthentificationLogBundle\Entity\Personne");
            $tab = $repoJeune->getAllUserNoLdap();
            $tab2 = $repoJeune->getAllUserLdap();
            return $this->render("PASSAuthentificationLogBundle:listing:listing.html.twig", array("titrePage" => "Listing utilisateur", "activite" => 'utilisateur',
                        "tab" => $tab, 'chemin' => "PASS_GestionUtilisateur", "ldap" => $tab2,
            ));
        }
    }

    public function groupeListingAction($listingId) {
        if ($listingId != 0) {

            $groupe = $this->getDoctrine()->getRepository("PASSAuthentificationLogBundle:Groupe")->find($listingId);

            return $this->render('PASSAuthentificationLogBundle:listing:recapitulatif.html.twig', Array(
                        'titrePage' => 'Gestion d\'un groupe', 'groupe' => $groupe->resumer(), 'nom' => $groupe->getNom(),
                        'activiter' => " Information sur le groupe", 'lien' => 'PASS_ModificationGroupe', 'id' => $listingId,
                        'activite' => "groupe"
            ));
        } else {

            $repoJeune = $this->getDoctrine()->getRepository("PASS\AuthentificationLogBundle\Entity\Groupe");
            $tab = $repoJeune->getAllGroupe();

            return $this->render("PASSAuthentificationLogBundle:listing:listing.html.twig", array("titrePage" => "Listing des groupes", "activite" => 'groupe', "tab" => $tab, 'chemin' => "PASS_GestionGroupe",));
        }
    }

    public function utilisateurModificationAction(Request $request, Personne $listingId) {

        $personne = $listingId;
        $form = $this->createForm(new editPersonneType($this->getDoctrine() ->getRepository('PASSAuthentificationLogBundle:Groupe'), $this->getDoctrine()->getRepository('PASSAuthentificationLogBundle:Role')), $personne);
        // $request = $this->getRequest();
        //$form->bindRequest($request);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $factory = $this->get('security.encoder_factory');
            $encoder = $factory->getEncoder($personne);

            //$user->setMdp($encoder->encodePassword($personne->getMdp(), $personne->getSalt()));


            $em = $this->getDoctrine()->getManager();
            $em->persist($personne);
            $em->flush();
            return $this->redirect($this->generateUrl('PASS_GestionUtilisateur', array('listingId' => $listingId->getId())));
        }

        return $this->render('PASSAuthentificationLogBundle:authentification:editPersonneForm.html.twig', Array(
                    "form" => $form->createView(),
                    'titrePage' => 'Modifier un utilisateur local',
                    'id' => $listingId->getId(),
                    'suprimable' => $personne->getSuprimable()
        ));
    }

    public function utilisateurSupprimerAction(Personne $personneId) {

        $em = $this->container->get('doctrine')->getEntityManager();


        //$personne = $em->find('PASSAuthentificationLogBundle:Personne', $id);
        $em->remove($personneId);
        $em->flush();
        return $this->redirect($this->generateUrl('PASS_GestionUtilisateur'));
    }

    public function groupeSupprimerAction(Groupe $groupeId) {

        $em = $this->container->get('doctrine')->getEntityManager();


        //$personne = $em->find('PASSAuthentificationLogBundle:Personne', $id);
        $em->remove($groupeId);
        $em->flush();
        return $this->redirect($this->generateUrl('PASS_GestionGroupe'));
    }

    public function changeMDPAction(Request $request, Personne $personneId) {

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
                        "good" => "utilisateur d'utilisateur bien créé.",
                        'titrePage' => 'Opération éffectué',
            ));
        }


        return $this->render('PASSAuthentificationLogBundle:authentification:changeMdp.html.twig', array(
                    'form' => $form->createView(),
                    'id' => $personneId->getId()
        ));
    }

}
