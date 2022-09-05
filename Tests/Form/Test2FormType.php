<?php

namespace Sandronimus\NelmioFormGetParameterPluginBundle\Tests\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class Test2FormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('choice', ChoiceType::class, [
                'mapped' => false,
            ])
            ->add('integer', IntegerType::class, [
                'mapped' => false,
            ])
            ->add('number', NumberType::class, [
                'mapped' => false,
            ])
            ->add('checkbox', CheckboxType::class, [
                'mapped' => false,
            ])
            ->add('date', DateType::class, [
                'mapped' => false,
                'widget' => 'single_text',
            ])
            ->add('dateTime', DateTimeType::class, [
                'mapped' => false,
                'widget' => 'single_text',
            ])
            ->add('collection', CollectionType::class, [
                'mapped' => false,
            ])
            ->add('text', TextType::class, [
                'mapped' => false,
            ])
        ;
    }

    public function getBlockPrefix()
    {
        return 'test2';
    }
}
