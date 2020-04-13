<?php

namespace BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;



class DeliveryType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('reference',TextType::class,array('attr' => array('class' => 'form-control','placeholder'=>'reference'),'constraints' => array(
            new NotBlank(),
            new Length(array('min' =>4)),
            new Length(array('max' => 20)),
        )))
            ->add('clientName',TextType::class,array('attr' => array('class' => 'form-control','placeholder'=>'clientName'),'constraints' => array(
                new NotBlank(),
                new Length(array('min' =>4)),
                new Length(array('max' => 20)),
            )))
            ->add('driverName',TextType::class,array('attr' => array('class' => 'form-control','placeholder'=>'driverName'),'constraints' => array(
                new NotBlank(),
                new Length(array('min' =>4)),
                new Length(array('max' => 20)),
            )))
            ->add('address',TextType::class,array('attr' => array('class' => 'form-control','placeholder'=>'address'),'constraints' => array(
                new NotBlank(),
                new Length(array('min' =>4)),
                new Length(array('max' => 20)),
            )))
            ->add('statut',TextType::class,array('attr' => array('class' => 'form-control','placeholder'=>'statut'),'constraints' => array(
                new NotBlank(),
                new Length(array('min' =>4)),
                new Length(array('max' => 20)),
            )))
            ->add( 'vehicule',EntityType::class,array(
                'class'=>'BackendBundle:Vehicule',
                    'choice_label'=>'matricule',
                    'multiple'=>false
                )
            );
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BackendBundle\Entity\Delivery'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'espritbundle_delivery';
    }


}
