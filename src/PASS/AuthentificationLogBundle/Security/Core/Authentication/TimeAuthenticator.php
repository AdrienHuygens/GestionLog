<?php

/* 


   * Copyright 2015 Version 1.0.0
   * Pour le Pass, projet gestion de log.
   * @author Huygens Adrien
   * contact adrien.huygens@gmail.com
 
 */
namespace PASS\AuthentificationLogBundle\Security\Core\Authentication;

use Symfony\Component\Security\Core\Authentication\SimpleFormAuthenticatorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use PASS\AuthentificationLogBundle\Security\Core\User\ldapProvider;

class TimeAuthenticator extends ldapProvider implements SimpleFormAuthenticatorInterface
{
    private $encoderFactory;

    public function __construct(EncoderFactoryInterface $encoderFactory)
    {
        //var_dump($encoderFactory);
        $this->encoderFactory = $encoderFactory;
    }

    public function createToken(Request $request, $username, $password, $providerKey)
    {
        var_dump($username);
        return new UsernamePasswordToken($username, $password, $providerKey);
    }

    public function authenticateToken(TokenInterface $token, UserProviderInterface $userProvider, $providerKey)
    {
        try {
             
            $user = $userProvider->loadUserByUsername($token->getUsername());
           
        } catch (UsernameNotFoundException $e) {
            throw new AuthenticationException('Invalid username or password');
        }

        $passwordValid = $this->encoderFactory->getEncoder($user)->isPasswordValid($user->getPassword(), $token->getCredentials(), $user->getSalt());

        if ($passwordValid) {
            $currentHour = date('G');
            if ($currentHour < 14 || $currentHour > 16) {
                throw new AuthenticationException('You can only log in between 2 and 4!', 100);
            }

            return new UsernamePasswordToken($user, 'bar', $providerKey, $user->getRoles());
        }

        throw new AuthenticationException('Invalid username or password');
    }

    public function supportsToken(TokenInterface $token, $providerKey)
    {
        return $token instanceof UsernamePasswordToken && $token->getProviderKey() === $providerKey;
    }
}