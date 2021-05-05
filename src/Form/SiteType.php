<?php

namespace App\Form;

use App\Entity\Site;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SiteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('domainName', \Symfony\Component\Form\Extension\Core\Type\TextType::class, [
                'disabled' => $options['is_edit']
            ])
            ->add('status', ChoiceType::class, [
                'choices' => array_combine(Site::status, Site::status)
            ])
            ->add('frequency', ChoiceType::class, [
                'choices' => array_combine(Site::frequency, Site::frequency)
            ])
            ->add('user', EntityType::class, [
                'class' => 'App\Entity\User',
                'choice_label' => 'email'])
            ->add('notificationChannels', EntityType::class, [
                'class' => 'App\Entity\NotificationChannel',
                'multiple' => true,
                'choices' => $options['user_id']->getNotificationChannels()->toArray()
            ])
            ->add('save', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Site::class,
            'is_edit' => false,
            'user_id' => null,
        ]);
    }
}
