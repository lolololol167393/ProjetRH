<?php

namespace App\Form;

use App\Entity\Conge;
use App\Entity\TypeConges;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CongeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // Si c'est l'administrateur qui traite la demande
        if ($options['is_admin_processing']) {
            $builder
                ->add('decision', ChoiceType::class, [
                    'choices'  => [
                        'En attente' => 'En attente',
                        'Accepter' => 'Accepté',
                        'Refuser' => 'Refusé',
                    ],
                    'label' => 'Décision finale RH',
                    'attr' => ['class' => 'form-select']
                ])
                ->add('commentaire', TextareaType::class, [
                    'label' => 'Commentaire / Motif de la décision',
                    'required' => false,
                    'attr' => [
                        'class' => 'form-control', 
                        'rows' => 3,
                        'placeholder' => 'Ex: Validé au vu des effectifs disponibles / Refusé car la période est déjà surchargée.'
                    ]
                ]);
        } else {
            // Sinon, c'est un agent standard qui dépose une nouvelle demande
            $builder
                ->add('date_debut', DateType::class, [
                    'widget' => 'single_text',
                    'label' => 'Date de début',
                    'attr' => ['class' => 'form-control']
                ])
                ->add('date_fin', DateType::class, [
                    'widget' => 'single_text',
                    'label' => 'Date de fin',
                    'attr' => ['class' => 'form-control']
                ])
                ->add('commentaire_demandeur', TextareaType::class, [
                    'label' => 'Votre message / Justification',
                    'required' => false,
                    'attr' => ['class' => 'form-control', 'rows' => 3]
                ])
                ->add('type', EntityType::class, [
                    'class' => TypeConges::class,
                    'choice_label' => 'nom',
                    'label' => 'Type de congé',
                    'attr' => ['class' => 'form-select']
                ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Conge::class,
            // On définit une option personnalisée par défaut à false
            'is_admin_processing' => false,
        ]);
    }
}