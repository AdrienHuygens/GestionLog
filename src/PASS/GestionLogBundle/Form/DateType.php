<?php

namespace PASS\GestionLogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DateType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            
            ->add('signe','choice',array("choices"=>array('='=>'=','<'=>'<','between' =>'entre'),'required' => false, 'empty_value' => 'Désactivé'))
            ->add('date1','datetime', array('required' => true, 'years'=>range(2015,2030)))
             ->add('date2','datetime', array('required' => true,'years'=>range(2015,2030) ))
            
        ;
        
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PASS\GestionLogBundle\Entity\Date',
              
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'pass_gestionlogbundle_date';
    }
}
