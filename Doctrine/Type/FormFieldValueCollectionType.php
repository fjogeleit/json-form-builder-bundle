<?php

declare(strict_types=1);

namespace JsonFormBuilderBundle\Doctrine\Type;

use Doctrine\DBAL\Types\JsonType;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use JsonFormBuilder\JsonResult\FormFieldValueCollection;

class FormFieldValueCollectionType extends JsonType
{
    const FORM_FIELD_VALUE_COLLECTION = 'form_field_value_collection';

    public function getName(): string
    {
        return self::FORM_FIELD_VALUE_COLLECTION;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return FormFieldValueCollection::fromArray(json_decode($value, true));
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value instanceof FormFieldValueCollection) {
            $value = json_encode($value);
        }

        return $value;
    }

    /**
     * {@inheritdoc}
     */
    public function requiresSQLCommentHint(AbstractPlatform $platform)
    {
        return ! $platform->hasNativeJsonType();
    }
}
