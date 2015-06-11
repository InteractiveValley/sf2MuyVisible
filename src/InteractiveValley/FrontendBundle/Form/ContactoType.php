<?php

namespace InteractiveValley\FrontendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ContactoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name','text',array(
                'label'=>'Nombre',
                'attr'=>array('class'=>'form','placeholder'=>'nombre'),
                'required'=>true
                ))
            ->add('email','email',array(
                'label'=>'Email',
                'attr'=>array('class'=>'form','placeholder'=>'email'),
                
                ))
            ->add('subject','text',array('label'=>'Asunto','attr'=>array('class'=>'form','placeholder'=>'asunto')))
            ->add('phone','text',array('label'=>'Telefono','attr'=>array('class'=>'form','placeholder'=>'telefono')))  
            ->add('body','textarea',array('label'=>'Mensaje','attr'=>array('class'=>'form_area','placeholder'=>'mensaje')))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'InteractiveValley\FrontendBundle\Entity\Contacto'
        ));
    }

    public function getName()
    {
        return 'contacto';
    }
}
