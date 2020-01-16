<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Invoice;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class InvoiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',TextType::class, ['label' => 'Nom de la facture', 'attr' => ['placeholder' => 'Entrez le nom de votre facture']])
            ->add('amount',MoneyType::class, ['label' => 'Montant', 'attr' => ['placeholder' => 'Entrez le montant de la facture']])
            ->add('paid',ChoiceType::class, [
                'choices' => [
                    'Payé' => true,
                    'Impayé' => false,
                ],
                'label' => 'État', 'attr' => ['placeholder' => 'Entrez l\'état du paiement']
            ])
            ->add('pdf',FileType::class, [
                'label' => 'Pdf',
                'attr' => ['placeholder' => 'Téléchargez le pdf de votre facture'],
    
                // unmapped means that this field is not associated to any entity property
                'mapped' => false,
    
                // make it optional so you don't have to re-upload the PDF file
                // everytime you edit the Product details
                'required' => false,
    
                 // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'mimeTypes' => [
                            'application/pdf',
                        ],
                        'mimeTypesMessage' => 'Ce format n\'est pas valide',
                    ])
                ],
            ])
            ->add('expiry',DateType::class, ['label' => 'Date d\'expiration', 'attr' => ['placeholder' => 'Entrez la date d\'expiration de la facture']])
            ->add('invoice', EntityType::class, [
                'required' => false,
                'class' => Invoice::class,
                'choice_label' => 'name',
                'label' => 'Mensualité', 'attr' => ['placeholder' => 'Renseignez si votre facture est une mensualité et la facture auquelles celle-ci s\'y rapporte']
            ])
            ->add('user', EntityType::class, [
                'required' => false,
                'class' => User::class,
                'choice_label' => 'fullname',
                'label' => 'Utilisateur', 'attr' => ['placeholder' => 'Renseignez l\'utilisateur']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Invoice::class,
        ]);
    }
}
