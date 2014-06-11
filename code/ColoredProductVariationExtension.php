<?php

class ColoredProductVariationExtension extends DataExtension{

	private static $many_many = array(
		"Images" => "Image"
	);

	function updateCMSFields(FieldList $fields) {
		$fields->insertAfter($field = new UploadField('Images', 'Images'), 'Image');
		$field->setAllowedMaxFileNumber(3);

		$attributes = $this->owner->Product()->VariationAttributeTypes();
		if($attributes->exists()) {
			$fields->insertAfter(new LiteralField('ImagesNote', 
				'<p>Note: Variation images are grouped by color so if you have multiple variations using'
				. ' the same color value these will group for the front end.</p>'
			), 'Images');
		}
	}
	
}