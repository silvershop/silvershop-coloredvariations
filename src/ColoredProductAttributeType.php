<?php

namespace SilverShop\ColoredVariations;

use SilverShop\Model\Variation\AttributeType;
use SilverStripe\Forms\GridField\GridField;


class ColoredProductAttributeType extends AttributeType
{
    private static $singular_name = "Colored Attribute";

    private static $plural_name = "Colored Attributes";

    private static $defaults = [
        'Name' => 'Color',
        'Label' => 'Color'
    ];

    public function getCMSFields() {
        $fields = parent::getCMSFields();
        $values = $fields->fieldByname("Values");

        if ($values instanceof GridField) {
            $values->setModelClass(ColoredProductAttributeValue::class);
        }

        return $fields;
    }

}
