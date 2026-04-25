<?php

namespace SilverShop\ColoredVariations\Tests;

use SilverShop\ColoredVariations\ColoredProductAttributeType;
use SilverStripe\Dev\SapphireTest;
use SilverStripe\Forms\LiteralField;

class ColoredProductAttributeTypeTest extends SapphireTest
{
    public function testUnsavedTypeShowsValuesPlaceholderField(): void
    {
        $type = ColoredProductAttributeType::create();
        $fields = $type->getCMSFields();
        $valuesField = $fields->fieldByName('Values');

        $this->assertInstanceOf(LiteralField::class, $valuesField);
    }

    public function testDefaultsSetColorNameAndLabel(): void
    {
        $defaults = ColoredProductAttributeType::config()->get('defaults');

        $this->assertIsArray($defaults);
        $this->assertSame('Color', $defaults['Name'] ?? null);
        $this->assertSame('Color', $defaults['Label'] ?? null);
    }
}
