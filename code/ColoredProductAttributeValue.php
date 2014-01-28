<?php

class ColoredProductAttributeValue extends ProductAttributeValue{
	
	private static $db = array(
		'Color' => 'Varchar(6)'
	);

	private static $singular_name = "Color";
	private static $plural_name = "Colors";

	private static $summary_fields = array(
		"Value", "Color"
	);

	//TODO: texture image

}