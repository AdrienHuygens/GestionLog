<?php

/* 
 * Copyright 2015 Version 1.0.0
 * Pour le Pass, projet gestion de log.
 * @author Huygens Adrien
 * contact adrien.huygens@gmail.com
 */

namespace PASS\AuthentificationLogBundle\Security\Authentication\Firewall;

use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\NonceExpiredException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use PASS\AuthentificationLogBundle\Security\Authentication\Token\ldapUserToken;

class ldapProvider implements AuthenticationProviderInterface{
    
   private $userProvider;
   private $cacheDir;
   
   public function __construct(UserProviderInterface $userProvider, $cacheDir){
       
       $this->userProvider = $userProvider;
       $this->cacheDir = $cacheDir;
   }
   
    public function authenticate(TokenInterface $token) {
        $user = $this->userProvider->loadUserByUsername($token->getUsername());
        if($user && $this->validateDigest($token->digest, $token->nonce, $token->created, $user->getPassword()))
        {
            $auhtenticatedToken = new ldapUserToken();
            $authenticatedToken->setUser($user);
            return $authenticatedToken;
        }
        throw new AuthenticationException('LDAP authentification failed');
    }
    protected function validateDigest($difest, $nonce, $created, $secret){
        if (time() - strtotime($created) > 300) {
            return false;
        }

        // Valide que le nonce est unique dans les 5 minutes
        if (file_exists($this->cacheDir.'/'.$nonce) && file_get_contents($this->cacheDir.'/'.$nonce) + 300 > time()) {
            throw new NonceExpiredException('Previously used nonce detected');
        }
        file_put_contents($this->cacheDir.'/'.$nonce, time());

        // Valide le Secret
        $expected = base64_encode(sha1(base64_decode($nonce).$created.$secret, true));

        return $digest === $expected;
    }
     public function supports(TokenInterface $token)
    {
        return $token instanceof WsseUserToken;
    }
}