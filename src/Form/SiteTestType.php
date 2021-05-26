<?php

namespace App\Form;

use App\Entity\Site;
use App\Entity\SiteTest;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SiteTestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $site = new Site();
        $siteTest = new SiteTest();

        $builder
            ->add('url')
            ->add('type', ChoiceType::class, [
                'choices' => array_combine($siteTest::Type, $siteTest::Type)
            ])
            ->add('configuration', TextareaType::class)
            ->add('frequency', ChoiceType::class, [
                'choices' => array_combine($site::frequencyKey, $site::frequencyValue)
            ])
            ->add('testName')
            ->add('save', SubmitType::class);

        $builder->get('configuration')
            ->addModelTransformer(new CallbackTransformer(
                function ($configurationAsArray) {
                    // transform the array to a string
                    return \json_encode($configurationAsArray);
                },
                function ($configurationAsArray) {
                    // transform the string back to an array
                    return \json_decode($configurationAsArray, true);
                }
            ));

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SiteTest::class,
            'is_edit' => false
        ]);
    }
}
