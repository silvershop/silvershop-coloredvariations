<?php

class ColoredProductVariation extends DataExtension{

	/**
	 * Get all the colors for this product
	 * @return DataList colored attribute values
	 */
	function getColors(){
		return ColoredProductAttributeValue::get()
			->innerJoin(
				"ProductVariation_AttributeValues",
				"ProductVariation_AttributeValues.ProductAttributeValueID = ".
					"ColoredProductAttributeValue.ID"
			)
			->innerJoin(
				"ProductVariation",
				"ProductVariation_AttributeValues.ProductVariationID = ".
					"ProductVariation.ID"
			)
			->filter("ProductID",$this->owner->ID);
	}
	
}