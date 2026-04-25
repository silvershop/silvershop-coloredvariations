<?php

namespace SilverShop\ColoredVariations\Tests;

use SilverShop\ColoredVariations\ColoredProductAttributeType;
use SilverShop\ColoredVariations\ColoredProductAttributeValue;
use SilverStripe\Dev\SapphireTest;
use SilverStripe\Forms\GridField\GridField;

class ColoredProductAttributeTypeTest extends SapphireTest
{
    public function testValuesGridUsesColoredValueModel(): void
    {
        $type = ColoredProductAttributeType::create();
        $fields = $type->getCMSFields();
        $valuesField = $fields->fieldByName('Values');

        $this->assertInstanceOf(GridField::class, $valuesField);
        $this->assertSame(ColoredProductAttributeValue::class, $valuesField->getModelClass());
    }

    public function testDefaultsSetColorNameAndLabel(): void
    {
        $defaults = ColoredProductAttributeType::config()->get('defaults');

        $this->assertIsArray($defaults);
        $this->assertSame('Color', $defaults['Name'] ?? null);
        $this->assertSame('Color', $defaults['Label'] ?? null);
    }
}
