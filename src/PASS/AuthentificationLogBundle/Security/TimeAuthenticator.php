<?php

/* 


   * Copyright 2015 Version 1.0.0
   * Pour le Pass, projet gestion de log.
   * @author Huygens Adrien
   * contact adrien.huygens@gmail.com
 
 */
namespace PASS\AuthentificationLogBundle\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\SimpleFormAuthenticatorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use PASS\AuthentificationLogBundle\Entity\Personne;

class TimeAuthenticator implements SimpleFormAuthenticatorInterface
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
       
        $this->encoder = $encoder;
        
    }
    public function createToken(Request $request, $username, $password, $providerKey)
    {
       
        return new UsernamePasswordToken($username, $password, $providerKey);
    }


    public function authenticateToken(TokenInterface $token, UserProviderInterface $userProvider, $providerKey)
    {
      
        try {
               
              
           $user =  $userProvider->loadUserByUsername($token->getUsername());
           
           
          
        } catch (UsernameNotFoundException $e) {
            throw new AuthenticationException('Invalid username or password');
        }
        $currentUser = $token->getUser();
        $passwordValid = $this->encoder->isPasswordValid($user,$token->getCredentials(),$user->getSalt() );
         dump($token->getCredentials());
        
        
        
        //$passwordValid = true;
        //$currentUser = $token->getUser()->getMdp();
        if ($passwordValid) {
            $currentHour = date('G');
            
            if ($currentHour < 8 || $currentHour > 16) {
                
                throw new AuthenticationException(
                    'You can only log in between 2 and 4!',
                    100
                );
            }
            
            $test = new UsernamePasswordToken($user, $token->getCredentials(), $providerKey,array('ROLE_ADMIN'));
            
            
            
            
            //dump($currentUser->getMdp());
            return $test ;
        }
            
           
        throw new AuthenticationException('Invalid username or password');
    }

    public function supportsToken(TokenInterface $token, $providerKey)
    {
        return $token instanceof UsernamePasswordToken
            && $token->getProviderKey() === $providerKey;
    }

  
}
