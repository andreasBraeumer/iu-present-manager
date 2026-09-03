<?php

namespace App\Form;

use App\Entity\Aufgabe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AufgabeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('beschreibung', TextType::class, [
                'label' => 'aufgaben.feld.beschreibung',
            ])
            ->add('faelligAm', DateType::class, [
                'label' => 'aufgaben.feld.faelligAm',
                'widget' => 'single_text',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Aufgabe::class,
        ]);
    }
}
