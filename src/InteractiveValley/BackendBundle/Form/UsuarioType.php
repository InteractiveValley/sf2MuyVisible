<?php

namespace InteractiveValley\BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UsuarioType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name','text',array('label'=>'Nombre','attr'=>array('class'=>'form-control')))
            ->add('email','email',array('attr'=>array('class'=>'form-control')))
            ->add('password', 'repeated', array(
                'type' => 'password',
                'invalid_message' => 'Las dos contraseñas deben coincidir',
                'first_options'   => array('label' => 'Contraseña'),
                'second_options'  => array('label' => 'Repite Contraseña'),
                'required'        => false,
                'options' => array(
                    'attr'=>array('class'=>'form-control placeholder')
                )
            )) 
            ->add('salt','hidden')
            ->add('image','hidden')
            ->add('type','hidden')
            ->add('isActive',null,array('label'=>'Activo?','attr'=>array(
                'class'=>'checkbox-inline',
                'placeholder'=>'Es activo',
                'data-bind'=>'value: isActive'
                )))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'InteractiveValley\BackendBundle\Entity\Usuario'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'interactivevalley_backendbundle_usuario';
    }
}
