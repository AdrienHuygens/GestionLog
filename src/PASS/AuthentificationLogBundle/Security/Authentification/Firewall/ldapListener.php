<?php

/* 
 * Copyright 2015 Version 1.0.0
 * Pour le Pass, projet gestion de log.
 * @author Huygens Adrien
 * contact adrien.huygens@gmail.com
 */
namespace PASS\AuthentificationLogBundle\Security\Authentication\Firewall;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Http\Firewall\ListenerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use PASS\AuthentificationLogBundle\Security\Authentication\Token\ldapUserToken;

class ldapListener implements ListenerInterface{

    protected $securityContext;
    protected $authentificationManager;
    
    public function __construct(SecurityContextInterface $securityContext,AuthenticationManagerInterface $authenticationManager) {
        
       $this->securityContext = $securityContext;
       $this->authentificationManager = $authenticationManager;
    }
    
    public function handle(GetResponseEvent $event){
        
        $request = $event->getRequest();
        $ldapRegex = '/UsernameToken Username="([^"]+)", PasswordDigest="([^"]+)", Nonce="([^"]+)", Created="([^"]+)"/';
        if(1 !== preg_match($ldapRegex, $request->headers->get(), $matches))
        
        $token = new ldapUserToken();
        $token->setUser($matches[1]);
        
        $token->digest   = $matches[2];
        $token->nonce    = $matches[3];
        $token->created  = $matches[4];

        try {
            $authToken = $this->authentificationManager->authenticate($token);

            $this->securityContext->setToken($authToken);
        } catch (AuthenticationException $failed) {
            // ... you might log something here

            // To deny the authentication clear the token. This will redirect to the login page.
            // $this->securityContext->setToken(null);
            // return;

            // Deny authentication with a '403 Forbidden' HTTP response
            $response = new Response();
            $response->setStatusCode(403);
            $event->setResponse($response);

        }
        
    }
    
    
    
    
}