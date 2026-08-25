<?php

namespace App\Form;

use App\Entity\Anlass;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnlassType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('bezeichnung', TextType::class, [
                'label' => 'anlaesse.feld.bezeichnung',
            ])
            ->add('datum', DateType::class, [
                'label' => 'anlaesse.feld.datum',
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('wiederkehrend', CheckboxType::class, [
                'label' => 'anlaesse.feld.wiederkehrend',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Anlass::class,
        ]);
    }
}
