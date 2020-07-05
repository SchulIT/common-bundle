<?php

namespace SchulIT\CommonBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;

class ConfirmType extends AbstractType {
    public function configureOptions(OptionsResolver $resolver) {
        $resolver
            ->setDefaults([
                'message' => 'confirm.remove.message',
                'message_parameters' => [ ],
                'header' => 'confirm.remove.label'
            ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('group_general', FieldsetType::class, [
                'legend' => $options['header'],
                'fields' => function(FormBuilderInterface $builder) use($options) {
                    $builder->add('confirm', CheckboxType::class, [
                        'label' => $options['message'],
                        'label_translation_parameters' => $options['message_parameters'],
                        'required' => true,
                        'constraints' => [
                            new IsTrue()
                        ],
                        'label_attr' => [
                            'class' => 'checkbox-custom'
                        ]
                    ]);
                }
            ]);
    }
}