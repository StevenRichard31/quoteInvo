<?php

namespace AppBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, ['label' => 'Nom du produit :'])
            ->add('quantity', null , ['label' => 'Quantité :'])
            ->add('percentageDiscount', null, ['label'=>'Remise(%) :'])
            ->add('priceOutTaxe', null, ['label'=>'Prix Unitaire HT :'])
            ->add('tva', null,['expanded' => false,'multiple' => false, 'placeholder' => 'Choisir une tva...']);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Product'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_product';
    }


}
