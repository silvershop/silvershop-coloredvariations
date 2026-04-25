<?php

namespace SilverShop\ColoredVariations;

use SilverShop\Model\Variation\AttributeValue;

class ColoredProductAttributeValue extends AttributeValue
{
    /**
     * @var array<string, string>
     */
    private static $db = [
        'Color' => 'Varchar(6)'
    ];

    /**
     * @var string
     */
    private static $singular_name = "Color";

    /**
     * @var string
     */
    private static $plural_name = "Colors";

    /**
     * @var array<int, string>
     */
    private static $summary_fields = [
        "Value",
        "Color"
    ];

    /**
     * @var string
     */
    private static $table_name = 'SilverShop_ColoredProductAttributeValue';
}
