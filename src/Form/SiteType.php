<?php

namespace App\Form;

use App\Entity\Site;
use App\Entity\User;
use Doctrine\DBAL\Types\TextType;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SiteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('domainName', \Symfony\Component\Form\Extension\Core\Type\TextType::class, array(
                'attr' => array(
                    'readonly' => false,
                )))
            ->add('status')
            ->add('frequency')
            ->add('user', EntityType::class, [
                'class' => 'App\Entity\User',
                'choice_label' => 'id'])
            ->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Site::class,
        ]);
    }
}
