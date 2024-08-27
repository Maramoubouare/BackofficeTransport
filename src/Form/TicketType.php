<?php

namespace App\Form;

use App\Entity\Ticket;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class TicketType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('companyName', ChoiceType::class, [
                'choices' => [
                    'BANI' => 'Bani',
                    'DIARRA TRANSPORT' => 'Diarra transport',
                    'SONEF' => 'Sonef',
                    'SOMATRA' => 'Somatra',
                    'Africa Tours' => 'Africa Tours',
                ],
            ])
            ->add('departureCity', ChoiceType::class, [
                'choices' => [
                    'BAMAKO' => 'Bamako',
                    'KOULIKORO' => 'Koulikoro',
                    'KAYES' => 'Kayes',
                    'KITA' => 'Kita',
                    'KIDAL' => 'Kidal',
                    'MOPTI' => 'Mopti',
                    'NIONON' => 'Nionon',
                    'NIORO' => 'Nioro',
                    'SENOU' => 'Senou',
                    'SEGOU' => 'Segou',
                    'SIKASSO' => 'Sikasso',
                    'TOMBOUCTOU' => 'Tombouctou',
                ],
            ])
            ->add('arrivalCity', ChoiceType::class, [
                'choices' => [
                    'BAMAKO' => 'Bamako',
                    'KOULIKORO' => 'Koulikoro',
                    'KAYES' => 'Kayes',
                    'KITA' => 'Kita',
                    'KIDAL' => 'Kidal',
                    'MOPTI' => 'Mopti',
                    'NIONON' => 'Nionon',
                    'NIORO' => 'Nioro',
                    'SENOU' => 'Senou',
                    'SEGOU' => 'Segou',
                    'SIKASSO' => 'Sikasso',
                    'TOMBOUCTOU' => 'Tombouctou',
                ],
            ])
            ->add('departureTime', null, [
                'widget' => 'single_text',
            ])
            ->add('arrivalTime', null, [
             'widget' => 'single_text',
            ])
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Bus' => 'bus',
                    'Train' => 'train',
                    'Avion' => 'avion',
                ],
            ])
            ->add('price')
            ->add('date', null, [
                'widget' => 'single_text',
            ])
            ->add('nombre')
            ->add('travelTime')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ticket::class,
        ]);
    }
}
