<?php

namespace App\Presentation\Form;

use App\Presentation\DTO\QuizQuestionCreateDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuizQuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('text', TextType::class, ['label' => 'Set name of question', 'required' => false])
            ->add('answers', CollectionType::class, [
                'entry_type' => QuizQuestionAnswerType::class,
                'allow_add' => true,
                'entry_options' => [
                    'label' => false
                ],
                'prototype_name' => '__que__',
                'row_attr' => ['class' => 'QuizAnswers'],
                'attr' => ['class' => 'buildForm'],
                'required' => false,
            ])
        ;

        $builder->get('answers')->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event){
            $forms = $event->getForm()->all();
            $setFormWithCorrectAnswer = array_filter($forms, function (Form $form){
                return $form->get('correct')->getData();
            });
            if (empty($setFormWithCorrectAnswer) && !empty($forms)) {
                $forms[0]->getParent()->addError(new FormError("You have to select correct answer"));
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => QuizQuestionCreateDTO::class,
            'cascade_validation' => true,
        ]);
    }
}
