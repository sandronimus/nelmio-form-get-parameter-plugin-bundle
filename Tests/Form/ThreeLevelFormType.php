<?php

namespace Sandronimus\NelmioFormGetParameterPluginBundle\Tests\Form;

use Sandronimus\NelmioFormGetParameterPluginBundle\Tests\Model\ThreeLevelModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ThreeLevelFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('text', TextType::class, [
                'required' => false,
            ])
            ->add('twoLevelModel', TwoLevelFormType::class, [
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'method' => 'GET',
            'csrf_protection' => false,
            'data_class' => ThreeLevelModel::class,
        ]);
    }

    public function getBlockPrefix()
    {
        return 'threeLevel';
    }
}
