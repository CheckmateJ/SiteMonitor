<?php

namespace App\Form;

use App\Entity\SiteTest;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\ArrayType;
use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SiteTestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('url')
            ->add('type')
            ->add('configuration', \Symfony\Component\Form\Extension\Core\Type\TextType::class, array(
                'property_path' => 'configuration[0]'))
            ->add('frequency')
            ->add('site')
            ->add('save', SubmitType::class);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SiteTest::class,
        ]);
    }
}
