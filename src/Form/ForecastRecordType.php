<?php

namespace App\Form;

use App\Entity\ForecastRecord;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ForecastRecordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('timestamp', DateTimeType::class, [
                'widget' => 'single_text',
                'input' => 'datetime',
                'required' => true,
                'html5' => true,
            ])
            ->add('temperature_c', NumberType::class, [
                'required' => true,
                'scale' => 1,
            ])
            ->add('humidity', NumberType::class, [
                'required' => false,
                'scale' => 1,
            ])
            ->add('athmospheric_pressure', NumberType::class, [
                'required' => true,
                'scale' => 1,
            ])
            ->add('wind_speed', NumberType::class, [
                'required' => false,
            ])
            ->add('any_precipitations_for_the_nearest_6_hours', CheckboxType::class, [
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ForecastRecord::class,
        ]);
    }
}
