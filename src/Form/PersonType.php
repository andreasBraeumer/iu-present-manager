<?php

namespace App\Form;

use App\Entity\Person;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PersonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('vorname', TextType::class, [
                'label' => 'personen.feld.vorname',
            ])
            ->add('nachname', TextType::class, [
                'label' => 'personen.feld.nachname',
                'required' => false,
            ])
            ->add('geburtsdatum', DateType::class, [
                'label' => 'personen.feld.geburtsdatum',
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('beziehung', TextType::class, [
                'label' => 'personen.feld.beziehung',
                'required' => false,
            ])
            ->add('notizen', TextareaType::class, [
                'label' => 'personen.feld.notizen',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Person::class,
        ]);
    }
}
