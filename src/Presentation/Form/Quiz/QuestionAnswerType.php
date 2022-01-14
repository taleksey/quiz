<?php

declare(strict_types=1);

namespace App\Presentation\Form\Quiz;

use App\Presentation\DTO\Quiz\QuestionAnswerCreateDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuestionAnswerType extends AbstractType
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
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => QuestionAnswerCreateDTO::class,
        ]);
    }
}
