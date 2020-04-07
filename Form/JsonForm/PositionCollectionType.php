<?php

namespace JsonFormBuilderBundle\Form\JsonForm;

use JsonFormBuilder\JsonForm;
use JsonFormBuilder\JsonForm\FormField;
use JsonFormBuilder\JsonForm\FormTextElement;
use JsonFormBuilder\JsonForm\FormFieldCollection;
use JsonFormBuilder\JsonForm\FormTextElementCollection;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PositionCollectionType extends AbstractType implements DataMapperInterface
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('formFields', CollectionType::class, [
                'entry_type' => PositionType::class,
                'empty_data' => FormFieldCollection::emptyList()
            ])
            ->add('formTextElements', CollectionType::class, [
                'entry_type' => PositionType::class,
                'empty_data' => FormTextElementCollection::emptyList()
            ])
            ->setDataMapper($this);
    }

    /**
     * @param FormView      $view
     * @param FormInterface $form
     * @param array         $options
     *
     * @return void
     */
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['sortable'] = [];

        foreach ($view->children['formFields'] as $fieldView) {
            /** @var FormField $formField */
            $formField = $fieldView->vars['value'];
            $fieldView->vars['position'] = $formField->position();
            $fieldView->vars['title'] = $formField->label();

            $view->vars['sortable'][] = $fieldView;
        }

        foreach ($view->children['formTextElements'] as $elementView) {
            /** @var FormTextElement $formElement */
            $formElement = $elementView->vars['value'];
            $elementView->vars['position'] = $formElement->position();
            $elementView->vars['title'] = $formElement->text();

            $view->vars['sortable'][] = $elementView;
        }

        unset($view->children['fields'], $view->children['elements']);

        uasort($view->vars['sortable'], function (FormView $first, FormView $second) {
            if ($first->vars['position'] == $second->vars['position']) {
                return 0;
            }

            return ($first->vars['position'] < $second->vars['position']) ? -1 : 1;
        });
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => JsonForm::class
        ]);
    }

    /**
     * @param JsonForm $viewData
     * @param iterable $forms
     */
    public function mapDataToForms($viewData, $forms)
    {
        if (false === $viewData instanceof JsonForm) {
            return;
        }

        /** @var FormInterface[] $forms */
        $forms = iterator_to_array($forms);

        $forms['formFields']->setData($viewData->formFields());
        $forms['formTextElements']->setData($viewData->formTextElements());
    }

    /**
     * @param iterable $forms
     * @param JsonForm $viewData
     */
    public function mapFormsToData($forms, &$viewData)
    {
        /** @var FormInterface[] $forms */
        $forms = iterator_to_array($forms);

        foreach ($forms['formFields'] as $form) {
            $viewData->replaceFormField($form->getData());
        }

        foreach ($forms['formTextElements'] as $form) {
            $viewData->replaceFormTextElement($form->getData());
        }
    }
}
