<?php

namespace PASS\AuthentificationLogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PersonneType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username','text',array("label" =>"Nom d'utilisateur:"))
            //->add('mdp','password',array("label"=> "Mot de pass pour l'utilisateur:"))
           ->add('mdp', 'repeated', array(
            'type' => 'password',
            'first_options' => array('label' => 'mot de passe:'),
            'second_options' => array('label' => 'Confirmation:'),
            'invalid_message' => 'les mots de pass ne sont pas les mêmes',
               ))
    
            //->add('dernierConnexion')
           // ->add('ldap')
            //->add('actif',null,array("label"=>"Compte utilisateur actif?", "action"=>'checked'))
                ->add('mail')
            ->add('groupes',null,array("label"=> "Groupe (control + click souri pour multi-selection):") )
             ->add('Enregistrer','submit')
            
        ;
        
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PASS\AuthentificationLogBundle\Entity\Personne',
              'validation_groups' => array('registration', 'Default')
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'pass_authentificationlogbundle_personne';
    }
}
