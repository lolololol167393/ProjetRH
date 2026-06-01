<?php

namespace App\Form;

use App\Entity\Role;
use App\Entity\User;
use App\Form\ObtentionDiplomeType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('password', PasswordType::class, [
                'label' => 'mot de passe',
                'required' => false,  //valider le formulaire même si vide
                'mapped' => false,  //Dit à Symfony de NE PAS chercher à mettre à jour l'entité automatiquement
                'attr' => ['placeholder' => 'Mot de passe']
            ])
            ->add('email')
            ->add('type_contrat', ChoiceType::class, [
                'choices'  => [
                    'CDI' => 'CDI',
                    'CDD' => 'CDD',
                    'Stage' => 'Stage',
                    'Alternance' => 'Alternance',
                ],
                'label' => 'Type de contrat'
            ])
            ->add('obtentionsDiplomes', CollectionType::class, [
                'entry_type' => ObtentionDiplomeType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,         // Permet d'ajouter des lignes dynamiquement
                'allow_delete' => true,      // Permet de supprimer des lignes
                'by_reference' => false,     // Force l'appel à addObtentionsDiplome() dans User.php
                'label' => 'Diplômes possédés par l\'employé'
            ])
            ->add('role', EntityType::class, [
            'class' => Role::class,
            'choice_label' => 'nom',
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
