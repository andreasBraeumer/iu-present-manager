<?php

namespace App\Form;

use App\Entity\Anlass;
use App\Entity\Geschenk;
use App\Enum\GeschenkStatus;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GeschenkType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titel', TextType::class, [
                'label' => 'geschenke.feld.titel',
            ])
            ->add('beschreibung', TextareaType::class, [
                'label' => 'geschenke.feld.beschreibung',
                'required' => false,
            ])
            ->add('anlass', EntityType::class, [
                'class' => Anlass::class,
                'choice_label' => 'bezeichnung',
                'label' => 'geschenke.feld.anlass',
            ])
            ->add('status', EnumType::class, [
                'class' => GeschenkStatus::class,
                'label' => 'geschenke.feld.status',
                'choice_label' => fn (GeschenkStatus $status) => 'geschenke.status.'.$status->value,
            ])
            ->add('geschaetzterPreis', MoneyType::class, [
                'label' => 'geschenke.feld.geschaetzterPreis',
                'required' => false,
                'currency' => 'EUR',
            ])
            ->add('datum', DateType::class, [
                'label' => 'geschenke.feld.datum',
                'widget' => 'single_text',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Geschenk::class,
        ]);
    }
}
