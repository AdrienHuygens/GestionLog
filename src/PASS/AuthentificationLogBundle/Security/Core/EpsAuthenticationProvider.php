<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace PASS\AuthentificationLogBundle\Security\Core;
 
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\AuthenticationServiceException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authentication\Provider\UserAuthenticationProvider;
use PASS\AuthentificationLogBundleBundle\Service\Service;
 
class EpsAuthenticationProvider extends UserAuthenticationProvider
{
    private $encoderFactory;
    private $userProvider;
    private $service;
 
    /**
     * @param Service $service
     * @param \Symfony\Component\Security\Core\User\UserProviderInterface $userProvider
     * @param UserCheckerInterface $userChecker
     * @param $providerKey
     * @param EncoderFactoryInterface $encoderFactory
     * @param bool $hideUserNotFoundExceptions
     */
    public function __construct( $service, UserProviderInterface $userProvider, UserCheckerInterface $userChecker, $providerKey, EncoderFactoryInterface $encoderFactory, $hideUserNotFoundExceptions = true)
    {
        parent::__construct($userChecker, $providerKey, $hideUserNotFoundExceptions);
        $this->encoderFactory   = $encoderFactory;
        $this->userProvider     = $userProvider;
        $this->service          = $service;
    }
 
    /**
     * {@inheritdoc}
     */
    protected function checkAuthentication(UserInterface $user, UsernamePasswordToken $token)
    {
        $currentUser = $token->getUser();
 
        if ($currentUser instanceof UserInterface) {
            if ($currentUser->getPassword() !== $user->getPassword()) {
                throw new BadCredentialsException('The credentials were changed from another session.');
            }
        } else {
            if (!$presentedPassword = $token->getCredentials()) {
                throw new BadCredentialsException('The presented password cannot be empty.');
            }
 
            if (! $this->service->authenticate($token->getUser(), $presentedPassword)) {
                throw new BadCredentialsException('The presented password is invalid.');
            }
        }
    }
 
    /**
     * {@inheritdoc}
     */
    protected function retrieveUser($username, UsernamePasswordToken $token)
    {
        $user = $token->getUser();
        if ($user instanceof UserInterface) {
            return $user;
        }
 
        try {
            $user = $this->userProvider->loadUserByUsername($username);
 
            if (!$user instanceof UserInterface) {
                throw new AuthenticationServiceException('The user provider must return a UserInterface object.');
            }
 
            return $user;
        } catch (UsernameNotFoundException $notFound) {
            throw $notFound;
        } catch (\Exception $repositoryProblem) {
            throw new AuthenticationServiceException($repositoryProblem->getMessage(), $token, 0, $repositoryProblem);
        }
    }
}