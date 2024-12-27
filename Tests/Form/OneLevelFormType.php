<?php

namespace Sandronimus\NelmioFormGetParameterPluginBundle\Tests\Form;

use Sandronimus\NelmioFormGetParameterPluginBundle\Tests\Model\OneLevelModel;
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
use Symfony\Component\OptionsResolver\OptionsResolver;

class OneLevelFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('choice', ChoiceType::class, [
                'required' => false,
                'choices' => [
                    'First',
                    'Second',
                    'Third',
                ],
            ])
            ->add('multipleChoice', ChoiceType::class, [
                'required' => false,
                'multiple' => true,
                'choices' => [
                    'First',
                    'Second',
                    'Third',
                ],
            ])
            ->add('integer', IntegerType::class, [
                'required' => false,
            ])
            ->add('number', NumberType::class, [
                'required' => false,
            ])
            ->add('checkbox', CheckboxType::class, [
                'required' => false,
            ])
            ->add('date', DateType::class, [
                'required' => false,
                'widget' => 'single_text',
            ])
            ->add('dateTime', DateTimeType::class, [
                'required' => false,
                'widget' => 'single_text',
            ])
            ->add('collection', CollectionType::class, [
                'required' => false,
                'entry_type' => IntegerType::class,
                'allow_add' => true,
            ])
            ->add('text', TextType::class, [
                'required' => false,
            ])
            ->add('fieldWithSummaryDoc', TextType::class, [
                'required' => false,
            ])
            ->add('fieldWithVarDoc', TextType::class, [
                'required' => false,
            ])
            ->add('fieldWithFormTypeDoc', TextType::class, [
                'required' => false,
                'documentation' => [
                    'description' => 'Text field with form type documentation',
                    'example' => 'Example string',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'method' => 'GET',
            'csrf_protection' => false,
            'data_class' => OneLevelModel::class,
        ]);
    }


    public function getBlockPrefix()
    {
        return 'oneLevel';
    }
}
