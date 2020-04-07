<?php

declare(strict_types=1);

namespace JsonFormBuilderBundle\Doctrine\Type;

use Doctrine\DBAL\Types\JsonType;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use JsonFormBuilder\JsonForm\FormFieldCollection;

class FormFieldCollectionType extends JsonType
{
    const FORM_FIELD_COLLECTION = 'form_field_collection';

    public function getName(): string
    {
        return self::FORM_FIELD_COLLECTION;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return FormFieldCollection::fromArray(json_decode($value, true));
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value instanceof FormFieldCollection) {
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
