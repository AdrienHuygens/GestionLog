<?php

namespace PASS\GestionLogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class GroupeOrdinateurType extends AbstractType
{
    private $host;
    private $priority;
    function __construct($host,$priority) {
        $this->host = $host;
        $this->priority = $priority;
    }

    
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
         
        $builder
            ->add('nom')
            ->add('description')
            //->add('actif')
            ->add('ordinateurs','choice',array('choices'=>  $this->host,'multiple' => true,'required' => false ))
            ->add('mail')
             ->add('groupe')
              ->add('priority',null, array('choices'=>  $this->priority))
            ->add('Enregistrer','submit')
            ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PASS\GestionLogBundle\Entity\GroupeOrdinateur'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'pass_gestionlogbundle_groupeordinateur';
    }
}
