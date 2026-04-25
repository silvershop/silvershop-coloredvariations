<?php

namespace SilverShop\ColoredVariations\Tests;

use SilverShop\ColoredVariations\ColoredProductAttributeValue;
use SilverStripe\Dev\SapphireTest;

class ColoredProductAttributeValueTest extends SapphireTest
{
    public function testColorDbFieldConfiguration(): void
    {
        $dbFields = ColoredProductAttributeValue::config()->get('db');

        $this->assertIsArray($dbFields);
        $this->assertArrayHasKey('Color', $dbFields);
        $this->assertSame('Varchar(6)', $dbFields['Color']);
    }

    public function testSummaryFieldsIncludeColor(): void
    {
        $summaryFields = ColoredProductAttributeValue::config()->get('summary_fields');

        $this->assertIsArray($summaryFields);
        $this->assertContains('Value', $summaryFields);
        $this->assertContains('Color', $summaryFields);
    }
}
