<?php

namespace SilverShop\ColoredVariations;

use SilverShop\Model\Variation\AttributeType;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\GridField\GridField;

class ColoredProductAttributeType extends AttributeType
{
    /**
     * @var string
     */
    private static $singular_name = "Colored Attribute";

    /**
     * @var string
     */
    private static $plural_name = "Colored Attributes";

    /**
     * @var array<string, string>
     */
    private static $defaults = [
        'Name' => 'Color',
        'Label' => 'Color'
    ];

    /**
     * @var string
     */
    private static $table_name = 'SilverShop_ColoredProductAttributeType';

    public function getCMSFields(): FieldList
    {
        $fields = parent::getCMSFields();
        $values = $fields->fieldByName('Values');

        if ($values instanceof GridField) {
            $values->setModelClass(ColoredProductAttributeValue::class);
        }

        return $fields;
    }
}
