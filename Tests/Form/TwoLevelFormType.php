<?php

namespace Sandronimus\NelmioFormGetParameterPluginBundle\Tests\Form;

use Sandronimus\NelmioFormGetParameterPluginBundle\Tests\Model\TwoLevelModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TwoLevelFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('text', TextType::class, [
                'required' => false,
            ])
            ->add('oneLevelModel', OneLevelFormType::class, [
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'method' => 'GET',
            'csrf_protection' => false,
            'data_class' => TwoLevelModel::class,
        ]);
    }

    public function getBlockPrefix()
    {
        return 'twoLevel';
    }
}
