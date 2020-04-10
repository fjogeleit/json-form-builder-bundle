<?php

declare(strict_types=1);

namespace JsonFormBuilderBundle\Form\JsonForm;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormFieldType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $positionType = NumberType::class;

        if (false === $options['with_position']) {
            $positionType = HiddenType::class;
        }

        $builder
            ->add('formFieldId', HiddenType::class, [
                'required' => true,
                'label' => false
            ])
            ->add('position', $positionType, [
                'label' => 'form_label.position',
                 'required' => true,
                'empty_data' => $options['position'],
                'translation_domain' => 'json_form_builder'
            ])
            ->add('label', TextType::class, [
                'label' => 'form_label.label',
                'required' => true,
                'translation_domain' => 'json_form_builder'
            ])
            ->add('required', CheckboxType::class, [
                'label' => 'form_label.required',
                'translation_domain' => 'json_form_builder',
                'required' => false
            ])
            ->add('visible', CheckboxType::class, [
                'label' => 'form_label.visible',
                'translation_domain' => 'json_form_builder',
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults(['with_position' => true])
            ->setRequired('position');
    }
}
