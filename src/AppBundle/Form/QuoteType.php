<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuoteType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('numberQuote',IntegerType::class,['label' => 'Numéro du Devis :'])
            ->add('documentName', null,['label' => 'Nom du dossier :'])
            ->add('percentageAdvencePayment', null, ['label'=>'Acompte(%) :'])
            ->add('creationDate', DateTimeType::class,['label' => 'Date de création :','widget' => 'single_text','format' => 'dd-MM-yyyy'])
            ->add('billingDate',DateTimeType::class,['label' => 'Date de facturation :','widget' => 'single_text','format' => 'dd-MM-yyyy'])
            ->add('validationDeadline',DateTimeType::class,['label' => 'Date limite de validation :','widget' => 'single_text','format' => 'dd-MM-yyyy']);

    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Quote'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_quote';
    }


}
