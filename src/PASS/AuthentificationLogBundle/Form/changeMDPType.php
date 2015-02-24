<?php

/*
 * Copyright 2015 Version 1.0.0
 * Pour le Pass, projet gestion de log.
 * @author Huygens Adrien
 * contact adrien.huygens@gmail.com
 */

namespace PASS\AuthentificationLogBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\AbstractType;


class changeMDPType extends AbstractType {


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder->add('mdp', 'repeated', array(
            'type' => 'password',
            'first_options' => array('label' => 'mot de passe:'),
            'second_options' => array('label' => 'Confirmation:'),
            'invalid_message' => 'les mots de pass ne sont pas les mêmes',
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'PASS\AuthentificationLogBundle\Entity\Personne',
            'intention' => 'change_password',
            'validation_groups' => array('registration', 'Default')
        ));
    }

    public function getName() {
        return 'pass_authentificationlogbundle_changemdp';
    }

}
