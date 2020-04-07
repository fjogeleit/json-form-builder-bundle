<?php

declare(strict_types=1);

namespace JsonFormBuilderBundle\Form\JsonForm\FormField;

use JsonFormBuilder\JsonForm\FormField\TextArea;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TextAreaType extends AbstractType implements DataMapperInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('formFieldId', HiddenType::class, ['required' => true, 'translation_domain' => 'json_form_builder'])
            ->add('position', NumberType::class, ['required' => true, 'empty_data' => $options['position'], 'translation_domain' => 'json_form_builder'])
            ->add('label', TextType::class, ['required' => true, 'translation_domain' => 'json_form_builder'])
            ->add('required', CheckboxType::class, ['translation_domain' => 'json_form_builder'])
            ->add('visible', CheckboxType::class, ['translation_domain' => 'json_form_builder'])
            ->add('placeholder', TextType::class, ['translation_domain' => 'json_form_builder'])
            ->setDataMapper($this);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'data_class' => TextArea::class,
                'empty_data' => new TextArea(
                    Uuid::uuid4()->toString(),
                    '',
                    0
                )
            ])
            ->setRequired('position');
    }

    /**
     * @param TextArea  $viewData
     * @param iterable $forms
     */
    public function mapDataToForms($viewData, $forms)
    {
        /** @var FormInterface[] $forms */
        $forms = iterator_to_array($forms);

        if (false === $viewData instanceof TextArea) {
            $forms['formFieldId']->setData(Uuid::uuid4()->toString());

            return;
        }

        $forms['formFieldId']->setData($viewData->formFieldId());
        $forms['label']->setData($viewData->label());
        $forms['required']->setData($viewData->required());
        $forms['visible']->setData($viewData->visible());
        $forms['placeholder']->setData($viewData->placeholder());
    }

    /**
     * @param iterable $forms
     * @param TextArea $viewData
     */
    public function mapFormsToData($forms, &$viewData)
    {
        /** @var FormInterface[] $forms */
        $forms = iterator_to_array($forms);

        $formFieldId = $forms['formFieldId']->getData();

        if (true === empty($formFieldId)) {
            $formFieldId = Uuid::uuid4()->toString();
        }

        $viewData = new TextArea(
            $formFieldId,
            $forms['label']->getData(),
            (int)$forms['position']->getData(),
            $forms['required']->getData(),
            $forms['visible']->getData(),
            $forms['placeholder']->getData()
        );
    }
}
