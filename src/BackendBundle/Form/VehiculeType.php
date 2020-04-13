<?php

namespace BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VehiculeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('matricule',TextType::class,array('attr' => array('class' => 'form-control'),'constraints' => array(
            new NotBlank(),
            new Length(array('min' =>4)),
            new Length(array('max' => 20)),
        )))
            ->add('weight',TextType::class,array('attr' => array('class' => 'form-control'),'constraints' => array(
                new NotBlank(),
                new Length(array('min' =>4)),
                new Length(array('max' => 20)),
            )))
            ->add('etat',TextType::class,array('attr' => array('class' => 'form-control'),'constraints' => array(
                new NotBlank(),
                new Length(array('min' =>4)),
                new Length(array('max' => 20)),
            )))
            ->add('marque',TextType::class,array('attr' => array('class' => 'form-control'),'constraints' => array(
                new NotBlank(),
                new Length(array('min' =>4)),
                new Length(array('max' => 20)),
            )))
            ->add('description',TextType::class,array('attr' => array('class' => 'form-control'),'constraints' => array(
                new NotBlank(),
                new Length(array('min' =>4)),
                new Length(array('max' => 20)),
            )));


    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BackendBundle\Entity\Vehicule'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'espritbundle_vehicule';
    }


}
