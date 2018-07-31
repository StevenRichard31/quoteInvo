<?php

namespace AppBundle\Form;


use AppBundle\Entity\Quote;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationQuoteType extends QuoteType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('customer', null,['label' => 'Selectionner un client :'])
            ->add('paymentMethod', null,['label' => 'Méthode de paiement :','expanded' => false,'multiple' => false,'placeholder' => 'Choisir une méthode de paiement...'])
            ->add('products' , CollectionType::class,
                [
                    'label' => 'Produit(s) :',
                    'entry_type' => ProductType::class,
                    'entry_options' => ['label' => false],
                    //allow to add "quite.id" as "foreignKey" in "quote table"
                    'by_reference' => false,
                    //allow to add multiple "quote"
                    'allow_add' => true,
                    //allow to delete multiple "quote"
                    'allow_delete' => true,
                    'error_bubbling' => false
                ])
            ->add("submit",SubmitType::class, ['label' => 'Valider le devis']);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Quote::class
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