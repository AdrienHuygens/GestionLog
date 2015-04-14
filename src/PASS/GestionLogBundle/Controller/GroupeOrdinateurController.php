<?php

namespace PASS\GestionLogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use PASS\GestionLogBundle\Entity\GroupeOrdinateur;
use PASS\GestionLogBundle\Form\GroupeOrdinateurType;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;

class GroupeOrdinateurController extends Controller
{
    
    private function host(){
         $repo = $this->getDoctrine()->getRepository("PASS\GestionLogBundle\Entity\Systemevents");
         $host = array();
         $temp = $repo->getHost();
         
         foreach($temp as $hote){
             $host[$hote['fromhost']]= $hote['fromhost'];
         }
         return $host;
    }
    
    /**
     * 
     * @Secure(roles="ROLE_GROUPE_ORDI_C, ROLE_ADMIN")
    */
    
    public function groupeOrdinateurAddAction(Request $request){
        
    
       $chemin ="";

        $Groupe = new GroupeOrdinateur();
        
        
        
        $priority = $this->getDoctrine()->getRepository("PASS\GestionLogBundle\Entity\priority")->getPriorityMin();

        $form = $this->createForm(new GroupeOrdinateurType($this->host(),$priority), $Groupe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($Groupe);
            $em->flush();

            return $this->render('PASSGeneralLogBundle:form:ok.html.twig', Array(
                        "good" => "Le groupe à bien été créé.",
                        'titrePage' => 'Opération éffectué',
            ));
        }
        return $this->render('PASSGeneralLogBundle:form:form.html.twig', Array(
                    "form" => $form->createView(),
                    'titrePage' => 'Rajouter un utilisateur local',
                    'chemin' => $chemin
        ));
    }
        
    /**
     * 
     * @Secure(roles="ROLE_GROUPE_ORDI_U, ROLE_ADMIN")
    */
  public function GroupeOrdinateurModificationAction(Request $request, GroupeOrdinateur $listingId) {
       $chemin =    $this->generateUrl('PASS_GroupeOrdinateurDelete', array('groupeId' => $listingId->getId()));
        $priority = $this->getDoctrine()->getRepository("PASS\GestionLogBundle\Entity\priority")->getPriorityMin();
        $Groupe = $listingId;
        $form = $this->createForm(new GroupeOrdinateurType($this->host(),$priority), $Groupe);
        // $request = $this->getRequest();
        //$form->bindRequest($request);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            

            $em = $this->getDoctrine()->getManager();
            $em->persist($Groupe);
            $em->flush();
            return $this->redirect($this->generateUrl('PASS_GroupeOrdinateurListing', array('listingId' => $listingId->getId())));
        }

        return $this->render('PASSGeneralLogBundle:form:form.html.twig', Array(
                    "form" => $form->createView(),
                    'titrePage' => 'Modifier un utilisateur local',
                    
                    'chemin' => $chemin));
    }

        /**
     * 
     * @Secure(roles="ROLE_GROUPE_ORDI_C,ROLE_GROUPE_ORDI_R, ROLE_ADMIN")
    */
     public function groupeOrdinateurListingAction($listingId) {
        if ($listingId != 0) {
            
            $groupe = $this->getDoctrine()->getRepository("PASSGestionLogBundle:GroupeOrdinateur")->find($listingId);
           
            return $this->render('PASSGeneralLogBundle:form:recapitulatif.html.twig', Array(
                        'titrePage' => 'Gestion d\'un Groupe d\'ordinateur', 'groupe' => $groupe->resumer(), 'nom' => $groupe->getNom(),
                        'activiter' => "Groupe d'ordinateur", 'lien' => 'PASS_GroupeOrdinateurModification', 'id' => $listingId
            ));
        } else {
            $repoJeune = $this->getDoctrine()->getRepository("PASSGestionLogBundle:GroupeOrdinateur");
            $tab = $repoJeune->getNameGroupe();

            return $this->render("PASSGeneralLogBundle:form:listing.html.twig", array("titrePage" => "Listing des groupes D'ordinateur", "activite" => 'groupeOrdi',
                        "tab" => $tab, 'chemin' => "PASS_GroupeOrdinateurListing"
            ));
        }
    }
    
    /**
     * 
     * @Secure(roles="ROLE_GROUPE_ORDI_D, ROLE_ADMIN")
     */
    public function GroupeOrdinateurSupprimerAction(GroupeOrdinateur $groupeId) {

        $em = $this->container->get('doctrine')->getEntityManager();


        //$personne = $em->find('PASSAuthentificationLogBundle:Personne', $id);
        $em->remove($groupeId);
        $em->flush();
        return $this->redirect($this->generateUrl('PASS_GroupeOrdinateurListing'));
    }
    
}
