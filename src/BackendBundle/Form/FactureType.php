<?php

namespace BackendBundle\Form;

use BackendBundle\Entity\Achat;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class FactureType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('reference',TextType::class,array('attr' => array('class' => 'form-control'),'constraints' => array(
            new NotBlank(),
            new Length(array('min' =>4)),
            new Length(array('max' => 20)),
        )))
            ->add('clientName',TextType::class,array('attr' => array('class' => 'form-control'),'constraints' => array(
                new NotBlank(),
                new Length(array('min' =>4)),
                new Length(array('max' => 20)),
            )))
            ->add('clientType',TextType::class,array('attr' => array('class' => 'form-control'),'constraints' => array(
                new NotBlank(),
                new Length(array('min' =>4)),
                new Length(array('max' => 20)),
            )))
            ->add('typeFacture',TextType::class,array('attr' => array('class' => 'form-control'),'constraints' => array(
                new NotBlank(),
                new Length(array('min' =>4)),
                new Length(array('max' => 20)),
            )))
            ->add('statutFacture',TextType::class,array('attr' => array('class' => 'form-control'),'constraints' => array(
                new NotBlank(),
                new Length(array('min' =>4)),
                new Length(array('max' => 20)),
            )))
            ->add('totalHT',TextType::class,array('attr' => array('class' => 'form-control'),'constraints' => array(
                new NotBlank(),
                new Length(array('min' =>1)),
                new Length(array('max' => 20)),
            )))
            ->add('totalTTC',TextType::class,array('attr' => array('class' => 'form-control'),'constraints' => array(
                new NotBlank(),
                new Length(array('min' =>1)),
                new Length(array('max' => 20)),
            )))

            ->add('achat',EntityType::class,[
                'class'=>Achat::class,
                'choice_label'=>'client_type',
            ])

        ;
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BackendBundle\Entity\Facture'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'backendbundle_facture';
    }


}
