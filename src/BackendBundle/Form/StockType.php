<?php

namespace BackendBundle\Form;

use BackendBundle\Entity\Entrepot;
use BackendBundle\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StockType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('quantity')
                ->add('unity')
                ->add('product',EntityType::class,[
                    'class'=>Product::class,
                    'choice_label'=>'product_name',
                ])
                ->add('entrepot',EntityType::class,[
                'class'=>Entrepot::class,
                'choice_label'=>'address',
                ])

                ->add('Add',SubmitType::class);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BackendBundle\Entity\Stock'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'backendbundle_stock';
    }


}
