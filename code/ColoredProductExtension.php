<?php

class ColoredProductExtension extends DataExtension{

	private static $many_many = array(
		"Images" => "Image"
	);

	function updateCMSFields(FieldList $fields) {
		$fields->insertAfter($field = new UploadField('Images', 'Images'), 'Image');
		$field->setAllowedMaxFileNumber(3);
	}

	/**
	 * Get all the colors for this product
	 * @return DataList colored attribute values
	 */
	function getColors() {
		return ColoredProductAttributeValue::get()
			->innerJoin(
				"ProductVariation_AttributeValues",
				"\"ProductVariation_AttributeValues\".\"ProductAttributeValueID\" = ".
					"\"ColoredProductAttributeValue\".\"ID\""
			)
			->innerJoin(
				"ProductVariation",
				"\"ProductVariation_AttributeValues\".\"ProductVariationID\" = ".
					"\"ProductVariation\".\"ID\""
			)
			->filter("ProductID",$this->owner->ID);
	}

	/**
	 * Add image lists to colors;
	 * @return DataList colors list customised with image lists
	 */
	function Colors() {
		$colors = $this->getColors();
		$images = $this->owner->Images();
		
		//add images to output
		$output = new ArrayList();

		foreach($colors as $color) {
			$images = new ArrayList();
			foreach($color->ProductVariation() as $variation) {
				if($variation->Images()) {
					$images->merge($variation->Images());
				}
			}
			$output->push($color->customise(array(
				'Images' => $images
			)));
		}

		return $output;
	}
	
}