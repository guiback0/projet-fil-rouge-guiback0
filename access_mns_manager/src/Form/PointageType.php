<?php

namespace App\Form;

use App\Entity\Badge;
use App\Entity\Badgeuse;
use App\Entity\Pointage;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PointageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('heure', null, [
                'widget' => 'single_text'
            ])
            ->add('type')
            ->add('badge', EntityType::class, [
                'class' => Badge::class,
'choice_label' => 'id',
            ])
            ->add('badgeuse', EntityType::class, [
                'class' => Badgeuse::class,
'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Pointage::class,
        ]);
    }
}
