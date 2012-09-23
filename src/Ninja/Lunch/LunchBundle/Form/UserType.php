<?php

namespace Ninja\Lunch\LunchBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username')
            ->add('email')
            ->add('enabled', null, array('required' => false))
            ->add('locked', null, array('required' => false))
            ->add('roles', 'collection', array('type' => 'text', 'allow_add' => true))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ninja\Lunch\LunchBundle\Entity\User'
        ));
    }

    public function getName()
    {
        return 'user_admin';
    }
}
