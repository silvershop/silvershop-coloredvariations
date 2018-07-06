<?php

namespace SilverShop\ColoredVariations;

use SilverShop\Model\Variation\AttributeValue;

class ColoredProductAttributeValue extends AttributeValue
{
    private static $db = [
        'Color' => 'Varchar(6)'
    ];

    private static $singular_name = "Color";

    private static $plural_name = "Colors";

    private static $summary_fields = [
        "Value",
        "Color"
    ];

    private static $table_name = 'SilverShop_ColoredProductAttributeValue';
}
