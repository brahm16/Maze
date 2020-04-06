<?php
/**
 * Created by PhpStorm.
 * User: Ibrahim
 * Date: 06/04/2020
 * Time: 6:05 PM
 */

namespace BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class ProdAchatFourType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('qte',NumberType::class,array('attr' => array('class' => 'form-control'),'constraints' => array(
            new NotBlank(),
            new Length(array('min' =>1)),
            new Length(array('max' => 4)),
        )))
            ->add('product',EntityType::class,array(
                'class'=>'BackendBundle:Product',
                'choice_label'=>'productName',
                'multiple'=>false
            ))
        ;
    }/**
 * {@inheritdoc}
 */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BackendBundle\Entity\ProdAchat'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'backendbundle_prodachat';
    }

}