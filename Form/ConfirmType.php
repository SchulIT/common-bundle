<?php

namespace SchoolIT\CommonBundle\Form;

use App\Form\FieldsetType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;

class ConfirmType extends AbstractType {
    public function configureOptions(OptionsResolver $resolver) {
        $resolver
            ->setDefaults([
                'message' => 'Soll die Entität (inkl. aller Referenzen) wirklich gelöscht werden?',
                'header' => 'Löschen bestätigen'
            ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('group_general', FieldsetType::class, [
                'legend' => $options['header'],
                'fields' => function(FormBuilderInterface $builder) use($options) {
                    $builder->add('confirm', CheckboxType::class, [
                        'label' => $options['message'],
                        'required' => true,
                        'constraints' => [
                            new IsTrue()
                        ]
                    ]);
                }
            ]);
    }
}