<?php

namespace App\Form;

use App\Entity\Classroom;
use App\Entity\Session;
use App\Form\ApplicationType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ClassroomType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, $this->getConfiguration("Nom de la classe", "Ex: A1.S1"))
            ->add('capacity', IntegerType::class, $this->getConfiguration("Nombre de place", "Saisir le nombre de place", [
                'attr' => [
                    'min' => 1,
                    'max' => 12,
                    'step' => 1
                ]
            ]))
            ->add('startAt', DateType::class, $this->getConfiguration("Date de début", "Saisir la date de début", [
                "widget" => "single_text"
            ]))
            ->add('endAt', DateType::class, $this->getConfiguration("Date de fint", "Saisir la date de fin", [
                "widget" => "single_text"
            ]))
            ->add('session', EntityType::class, [
                'class' => Session::class,
                'choice_label' => function ($session) {
                    return $session->getName();
                }
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Classroom::class,
        ]);
    }
}
