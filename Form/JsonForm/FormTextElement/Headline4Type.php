<?php

declare(strict_types=1);

namespace JsonFormBuilderBundle\Form\JsonForm\FormTextElement;

use JsonFormBuilder\JsonForm\FormTextElement\Headline4;
use JsonFormBuilderBundle\Form\JsonForm\FormTextElementType;
use JsonFormBuilderBundle\Form\JsonForm\FormTextElementTypeInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Headline4Type extends AbstractType implements FormTextElementTypeInterface, DataMapperInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->setDataMapper($this);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'data_class' => Headline4::class,
                'empty_data' => new Headline4(Uuid::uuid4()->toString(), '', 0)
            ]);
    }

    /**
     * @param Headline4 $viewData
     * @param iterable  $forms
     */
    public function mapDataToForms($viewData, $forms)
    {
        /** @var FormInterface[] $forms */
        $forms = iterator_to_array($forms);

        if (false === $viewData instanceof Headline4) {
            $forms['formTextElementId']->setData(Uuid::uuid4()->toString());

            return;
        }

        $forms['formTextElementId']->setData($viewData->formTextElementId());
        $forms['text']->setData($viewData->text());
        $forms['position']->setData($viewData->position());
    }

    /**
     * @param iterable  $forms
     * @param Headline4 $viewData
     */
    public function mapFormsToData($forms, &$viewData)
    {
        /** @var FormInterface[] $forms */
        $forms = iterator_to_array($forms);

        $formTextElementId = $forms['formTextElementId']->getData();

        if (true === empty($formTextElementId)) {
            $formTextElementId = Uuid::uuid4()->toString();
        }

        $viewData = new Headline4(
            $formTextElementId,
            $forms['text']->getData(),
            (int)$forms['position']->getData()
        );
    }

    public function getParent()
    {
        return FormTextElementType::class;
    }

    public function getFormTextElement(): string
    {
        return Headline4::class;
    }
}
