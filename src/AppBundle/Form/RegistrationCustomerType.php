<?php
/**
 * Created by PhpStorm.
 * User: RICHA
 * Date: 01/06/2018
 * Time: 18:55
 */

namespace AppBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationCustomerType extends CustomerType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder,$options);
        $builder
            ->add('address', AddressType::class)
            ->add('phones' , CollectionType::class,
                [
                    'entry_type' => PhoneType::class,
                    'entry_options' => ['label' => false],
                    //allow to add "customer.id" as "foreignKey" in "phone table"
                    'by_reference' => false,
                    //allow to add multiple "phone"
                    'allow_add' => true,
                    //allow to delete multiple "phone"
                    'allow_delete' => true,
                    'error_bubbling' => false
                ])
            ->add("submit",SubmitType::class,['label' => 'Ajouter ce client']);
    }
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Customer'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_customer';
    }
}