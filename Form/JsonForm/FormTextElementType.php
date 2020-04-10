<?php

declare(strict_types=1);

namespace JsonFormBuilderBundle\Form\JsonForm;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormTextElementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('formTextElementId', HiddenType::class, [
                'label' => false,
                'required' => true,
                'translation_domain' => 'json_form_builder'
            ])
            ->add('text', TextType::class, [
                'label' => 'form_label.text',
                'required' => true,
                'translation_domain' => 'json_form_builder'
            ]);

        $positionType = NumberType::class;

        if (false === $options['with_position']) {
            $positionType = HiddenType::class;
        }

        $builder->add('position', $positionType, [
            'required' => true,
            'empty_data' => $options['position'],
            'translation_domain' => 'json_form_builder'
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired('position')
            ->setDefault('with_position', true);
    }
}
