<?php
/**
 * Created by PhpStorm.
 * User: RICHA
 * Date: 25/06/2018
 * Time: 10:28
 */

namespace AppBundle\Form;


use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\Invoice;

class RegistrationInvoiceType extends InvoiceType
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
                    'by_reference' => false,
                    //allow to add multiple "product"
                    'allow_add' => true,
                    //allow to delete multiple "product"
                    'allow_delete' => true,
                    'error_bubbling' => false
                ])
            ->add("submit",SubmitType::class, ['label' => 'Valider la facture']);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Invoice::class
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_invoice';
    }
}