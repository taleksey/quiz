<?php

namespace App\Presentation\Form;

use App\Presentation\DTO\QuizQuestionAnswerCreateDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class QuizQuestionAnswerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'correct',
                RadioType::class,
                [
                    'row_attr' => ['class' => 'col-2'],
                    'attr' => ['class' => 'radio-button'],
                    'value' => '__value__',
                    'required' => false,
                ]
            )
            ->add(
                'text',
                TextType::class,
                [
                    'label' => 'Set answer of question',
                    'row_attr' => ['class' => 'col-8'],
                    'required' => false,
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => QuizQuestionAnswerCreateDTO::class,
        ]);
    }
}
