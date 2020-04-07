<?php

declare(strict_types=1);

namespace JsonFormBuilderBundle\Form\JsonForm\FormField;

use JsonFormBuilder\JsonForm\FormField\Input;
use JsonFormBuilder\JsonForm\FormField\InputType as InputValueType;
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

class InputType extends AbstractType implements DataMapperInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('formFieldId', HiddenType::class, ['required' => true, 'label' => false])
            ->add('position', NumberType::class, ['required' => true, 'empty_data' => $options['position'], 'translation_domain' => 'json_form_builder'])
            ->add('label', TextType::class, ['required' => true, 'translation_domain' => 'json_form_builder'])
            ->add('inputType', HiddenType::class, ['required' => true, 'empty_data' => $options['type'], 'label' => false])
            ->add('required', CheckboxType::class, ['translation_domain' => 'json_form_builder'])
            ->add('visible', CheckboxType::class, ['translation_domain' => 'json_form_builder'])
            ->add('placeholder', TextType::class, ['translation_domain' => 'json_form_builder'])
            ->setDataMapper($this);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'data_class' => Input::class,
                'type' => InputValueType::TEXT,
                'empty_data' => new Input(
                    Uuid::uuid4()->toString(),
                    '',
                    0
                )
            ])
            ->setRequired('position');
    }

    /**
     * @param Input    $viewData
     * @param iterable $forms
     */
    public function mapDataToForms($viewData, $forms)
    {
        /** @var FormInterface[] $forms */
        $forms = iterator_to_array($forms);

        if (false === $viewData instanceof Input) {
            $forms['formFieldId']->setData(Uuid::uuid4()->toString());

            return;
        }

        $forms['formFieldId']->setData($viewData->formFieldId());
        $forms['label']->setData($viewData->label());
        $forms['required']->setData($viewData->required());
        $forms['visible']->setData($viewData->visible());
    }

    /**
     * @param iterable $forms
     * @param Input    $viewData
     */
    public function mapFormsToData($forms, &$viewData)
    {
        /** @var FormInterface[] $forms */
        $forms = iterator_to_array($forms);

        $formFieldId = $forms['formFieldId']->getData();

        if (true === empty($formFieldId)) {
            $formFieldId = Uuid::uuid4()->toString();
        }

        $viewData = new Input(
            $formFieldId,
            $forms['label']->getData(),
            (int)$forms['position']->getData(),
            InputValueType::fromString($forms['inputType']->getData()),
            $forms['required']->getData(),
            $forms['visible']->getData(),
            $forms['placeholder']->getData()
        );
    }
}
