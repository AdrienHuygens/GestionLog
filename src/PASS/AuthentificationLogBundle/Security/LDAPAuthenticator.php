<?php

namespace PASS\AuthentificationLogBundle\Security;

use Symfony\Component\Security\Core\Authentication\SimpleFormAuthenticatorInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use PASS\AuthentificationLogBundle\Entity\Personne;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;



class LDAPAuthenticator implements SimpleFormAuthenticatorInterface {

    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder) {
        $this->encoder = $encoder;
    }

    public function authenticateToken(\Symfony\Component\Security\Core\Authentication\Token\TokenInterface $token, \Symfony\Component\Security\Core\User\UserProviderInterface $userProvider, $providerKey) {
        
        try {
            $user = $userProvider->loadUserByUsername($token->getUsername());
        } catch (UsernameNotFoundException $ex) {
            throw new AuthenticationException("Ce nom d'utilisateur n'est pas connu");
        }

        if ($user && $user instanceof Personne) {
            $valid = $this->passwordCheck($token,$user);
            if($user->getLdap()){
                $pass = $token->getCredentials();
            }else{
                $pass = $user->getPassword();
            }
        } 
        else if($user && $user instanceof \Symfony\Component\Security\Core\User\UserInterface){
                $pass = $user->getPassword();
             $valid =$this->encoder->isPasswordValid($user, $token->getCredentials());
        }
        
        else {
           
           //throw new AuthenticationException("Mauvais type d'utilisateur reçu");
             
        }
        
       

        if ($valid) {
            
            $token = new UsernamePasswordToken(
            $user,
            $pass,
            $providerKey,
            $user->getAllRoles()
                  //array("ROLES_ADMIN")
            );
          
            
            return $token;
            
        } else {
           
            throw new AuthenticationException('Invalid username or password');
        }
    }

    public function createToken(\Symfony\Component\HttpFoundation\Request $request, $username, $password, $providerKey) {
        return new UsernamePasswordToken($username, $password, $providerKey);
    }

    public function supportsToken(\Symfony\Component\Security\Core\Authentication\Token\TokenInterface $token, $providerKey) {
        return $token instanceof UsernamePasswordToken && $token->getProviderKey() === $providerKey;
    }

    private function passwordCheck(\Symfony\Component\Security\Core\Authentication\Token\TokenInterface $token, Personne $user) {
        if ($user->getLdap()) {
                 $configs = new \PASS\GeneralLogBundle\Entity\ConfigurationLDAP();
                 if ($configs->getLdapConnexion()){
                    try{
                     $db = ldap_connect ($configs->getLdapServer(),$configs->getLdapPort());
                     ldap_set_option( $db, LDAP_OPT_PROTOCOL_VERSION, 3);
                     ldap_set_option( $db, LDAP_OPT_REFERRALS, 0);
                    $r = ldap_bind($db, 'uid='.$token->getUsername().','.$configs->getLdapDn(), $token->getCredentials());
                    }
                catch (Exeption $e){
                    throw new AuthenticationException('Erreur avec la connexion LDAP');
                }
                    
                    return $r;
            /**
             * 
             * Adrien, tu peux venir mettre ta verif LDAP ici ou renvoyer vers une fonction qui le fais
             * 
             * PS : N'oublie pas de checker cette petite merde !
             * 
             */
                 }
                 else {
                     throw new AuthenticationException('Connxion ldap refuser contacter l\'administrateur');
                 }
            
        } else {
            
            return $this->encoder->isPasswordValid($user, $token->getCredentials());
        }
    }

}
