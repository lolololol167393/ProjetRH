<?php

namespace App\Form;

use App\Entity\Conge;
use App\Entity\TypeConges;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;

class CongeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Calendrier automatique natif HTML5 pour les dates
            ->add('date_debut', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de début'
            ])

            ->add('date_fin', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de fin',
            ])
            
            // Liste déroulante des types de congés (affiche le nom de l'absence)
            ->add('type', EntityType::class, [
                'class' => TypeConges::class,
                'choice_label' => 'nom', // Affiche "Congés Payés", "RTT", etc. au lieu de l'ID 
                'label' => 'Type de congé'
            ])
            
            // Zone de texte pour les explications de l'employé
            ->add('commentaire_demandeur', TextareaType::class, [
                'label' => 'Votre message / Justification',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Conge::class,
        ]);
    }
}
