<?php

namespace Ninja\Lunch\LunchBundle\Form\FoodOrder;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PaymentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('amountPaid', 'money', array(
                'currency' => 'AUD',
                'label' => 'Amount Paid'
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ninja\Lunch\LunchBundle\Entity\FoodOrder\Item'
        ));
    }

    public function getName()
    {
        return 'order_item_paid';
    }
}
