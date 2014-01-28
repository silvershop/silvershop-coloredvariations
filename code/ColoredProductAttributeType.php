<?php

class ColoredProductAttributeType extends ProductAttributeType{
	
	private static $singular_name = "Colored Attribute";
	private static $plural_name = "Colored Attributes";

	private static $defaults = array(
		'Name' => 'Color',
		'Label' => 'Color'
	);

	function getCMSFields(){
		$fields = parent::getCMSFields();
		$values = $fields->fieldByname("Values");
		if($values instanceof GridField){
			$values->setModelClass("ColoredProductAttributeValue");
		}
		return $fields;
	}

}