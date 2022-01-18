<?php

declare(strict_types=1);

namespace App\Presentation\Form\Quiz;

use App\Presentation\DTO\Quiz\QuestionCreateDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('text', TextType::class, ['label' => 'Set name of question'])
            ->add('answers', CollectionType::class, [
                'entry_type' => QuestionAnswerType::class,
                'allow_add' => true,
                'entry_options' => [
                    'label' => false
                ],
                'prototype_name' => '__que__',
                'row_attr' => ['class' => 'QuizAnswers'],
                'attr' => ['class' => 'buildForm']
            ])
        ;

        $builder->get('answers')->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
            $forms = $event->getForm()->all();
            $setFormWithCorrectAnswer = array_filter($forms, static function (FormInterface $form) {
                return $form->get('correct')->getData();
            });
            if (empty($setFormWithCorrectAnswer) && !empty($forms)) {
                $forms[0]->getParent()->addError(new FormError("You have to select correct answer"));
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => QuestionCreateDTO::class,
            'cascade_validation' => true,
        ]);
    }
}
