<?php

namespace App\Form;

use App\Entity\Anhang;
use App\Enum\AnhangTyp;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnhangType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('typ', EnumType::class, [
                'class' => AnhangTyp::class,
                'label' => 'anhaenge.feld.typ',
                'choice_label' => fn (AnhangTyp $typ) => 'anhaenge.typ.'.$typ->value,
            ])
            ->add('inhalt', TextType::class, [
                'label' => 'anhaenge.feld.inhalt',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Anhang::class,
        ]);
    }
}
