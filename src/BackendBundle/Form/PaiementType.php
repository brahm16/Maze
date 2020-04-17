<?php

namespace BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class PaiementType extends AbstractType
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
            ->add('paiementType',TextType::class,array('attr' => array('class' => 'form-control'),'constraints' => array(
                new NotBlank(),
                new Length(array('min' =>4)),
                new Length(array('max' => 20)),
            )))
            ->add('rib',TextType::class,array('attr' => array('class' => 'form-control'),'constraints' => array(
                new NotBlank(),
                new Length(array('min' =>10)),
                new Length(array('max' => 30)),
            )))
            ->add('numCheque',TextType::class,array('attr' => array('class' => 'form-control'),'constraints' => array(
                new NotBlank(),
                new Length(array('min' =>10)),
                new Length(array('max' => 30)),
            )))
        ;
    }/**
 * {@inheritdoc}
 */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BackendBundle\Entity\Paiement'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'backendbundle_paiement';
    }


}
