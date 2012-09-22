<?php

namespace Ninja\Lunch\LunchBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FoodItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('price')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ninja\Lunch\LunchBundle\Entity\FoodItem'
        ));
    }

    public function getName()
    {
        return 'ninja_lunch_lunchbundle_fooditemtype';
    }
}
