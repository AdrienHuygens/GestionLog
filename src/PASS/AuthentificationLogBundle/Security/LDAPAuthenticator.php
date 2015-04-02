<?php

namespace PASS\AuthentificationLogBundle\Security;

use Symfony\Component\Security\Core\Authentication\SimpleFormAuthenticatorInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use PASS\AuthentificationLogBundle\Entity\Personne;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * Description of LDAPAuthenticator
 *
 * @author Laurent Cardon <laurent.cardon@jsb.be>
 */
class LDAPAuthenticator implements SimpleFormAuthenticatorInterface {

    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder) {
        $this->encoder = $encoder;
    }

    public function authenticateToken(\Symfony\Component\Security\Core\Authentication\Token\TokenInterface $token, \Symfony\Component\Security\Core\User\UserProviderInterface $userProvider, $providerKey) {
        dump($token);
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
        } else {
            throw new AuthenticationException("Mauvais type d'utilisateur reçu");
        }
        
        dump($valid);

        if ($valid) {
            
            $token = new UsernamePasswordToken(
            $user,
            $pass,
            $providerKey,
            $user->getRoles()
            );
            dump($token);
            
            return $token;
            
        } else {
            die();
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

            /**
             * 
             * Adrien, tu peux venir mettre ta verif LDAP ici ou renvoyer vers une fonction qui le fais
             * 
             * PS : N'oublie pas de checker cette petite merde !
             * 
             */
            
            return true;
        } else {
            return $this->encoder->isPasswordValid($user, $token->getCredentials());
        }
    }

}
