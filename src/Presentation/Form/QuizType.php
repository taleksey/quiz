<?php

declare(strict_types=1);

namespace App\Presentation\Form;

use App\Presentation\DTO\QuizCreateDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuizType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, ['label' => 'Set name of Quiz', 'required' => false])
            ->add('questions', CollectionType::class, [
                'entry_type' => QuizQuestionType::class,
                'allow_add' => true,
                'entry_options' => [
                    'label' => false
                ],
                'by_reference' => false,
                'required' => false,
            ])
            ->add('token', HiddenType::class, [
                'data' => $options['hiddenToken'] ?? '',
            ])
            ->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => QuizCreateDTO::class,
            'hiddenToken' => 0,
        ]);
    }
}
