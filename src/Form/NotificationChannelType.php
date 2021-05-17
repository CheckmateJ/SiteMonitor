<?php

namespace App\Form;

use App\Entity\NotificationChannel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NotificationChannelType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', ChoiceType::class,[
                'choices' => array_combine(NotificationChannel::type, NotificationChannel::type)
            ])
            ->add('destination')
            ->add('defaultValue', ChoiceType::class,[
                'choices' => [0,1]
            ])
            ->add('notificationName')
            ->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => NotificationChannel::class,
        ]);
    }
}
