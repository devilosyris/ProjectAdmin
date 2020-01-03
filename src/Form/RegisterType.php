<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, ['attr' => ['placeholder' => 'Entrez votre adresse email']])
            ->add('lastname', TextType::class, ['label' => 'Nom de famille', 'attr' =>['placeholder' => 'Entrez votre nom']])
            ->add('firstname', TextType::class, ['label' => 'Prénom', 'attr' =>['placeholder' => 'Entrez votre nom']])
            ->add('password', PasswordType::class, ['label' => 'Mot de passe', 'attr' =>['placeholder' => 'Entrez votre mot de passe']])
            //->add('passwordConfirm', PasswordType::class, ['label' => 'Confirmation de mot de passe', 'attr' => ['placeholder' => 'Entrez votre mot de passe à nouveau pour confirmer celui-ci']])
            ->add('address', TextType::class, ['required' => false, 'label' => 'Adresse', 'attr' =>['placeholder' => 'Entrez votre adresse']])
            ->add('zip', IntegerType::class, ['required' => false, 'label' => 'Code postal', 'attr' =>['placeholder' => 'Entrez votre code postal']])
            ->add('country', CountryType::class, ['required' => false, 'label' => 'Pays'])
            ->add('tel', TelType::class,  ['required' => false, 'label' => 'Télephone', 'attr' =>['placeholder' => 'Entrez votre numéro de téléphone']])
            ->add('companyName', TextType::class, ['required' => false, 'label' => 'Nom de l\'entreprise', 'attr' =>['placeholder' => 'Entrez le nom de votre entreprise']])
            ->add('companyNumber', IntegerType::class, ['required' => false, 'label' => 'Siret de l\'entreprise', 'attr' =>['placeholder' => 'Entrez votre numero de siret']])
            ->add('birthday', BirthdayType::class, ['label' => 'Date de naissance'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
