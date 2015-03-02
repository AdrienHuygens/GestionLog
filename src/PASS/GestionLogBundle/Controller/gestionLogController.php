<?php

/* 
 * Copyright 2015 Version 1.0.0
 * Pour le Pass, projet gestion de log.
 * @author Huygens Adrien
 * contact adrien.huygens@gmail.com
 */
namespace PASS\GestionLogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

use PASS\GestionLogBundle\Entity\Systemevents;

class gestionLogController extends Controller
{
    /**
     * @Route("/affichagelog")
     * @Template()
     */
    public function affichageLogAction(Request $request)
    {
        
         $repo = $this->getDoctrine()->getRepository("PASS\GestionLogBundle\Entity\Systemevents");
            $tab = $repo->getAllLog();
            
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $tab,
            $request->query->get('page', 1)/*page number*/,
            30/*limit per page*/
        );
 
            return $this->render("PASSGestionLogBundle:affichage:affichage.html.twig",
                    array("titrePage" => "Logs serveur",                       
                        "listing" => $pagination
            ));
        
    }
}

