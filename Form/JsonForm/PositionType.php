<?php

namespace JsonFormBuilderBundle\Form\JsonForm;

use JsonFormBuilder\JsonForm\PositionedElementInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @package DynamicFormBundle\Admin\Form\Type\DynamicForm
 */
class PositionType extends AbstractType implements DataMapperInterface
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('position', IntegerType::class, ['label' => false])
            ->setDataMapper($this);
    }
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PositionedElementInterface::class,
        ]);
    }

    /**
     * @param iterable                   $forms
     * @param PositionedElementInterface $viewData
     */
    public function mapDataToForms($viewData, $forms)
    {
        /** @var FormInterface[] $forms */
        $forms = iterator_to_array($forms);

        $forms['position']->setData($viewData->position());
    }

    /**
     * @param iterable                   $forms
     * @param PositionedElementInterface $viewData
     */
    public function mapFormsToData($forms, &$viewData)
    {
        /** @var FormInterface[] $forms */
        $forms = iterator_to_array($forms);

        if (null === $forms['position']->getData()) {
            return;
        }

        $viewData = $viewData->withPosition($forms['position']->getData());
    }
}
