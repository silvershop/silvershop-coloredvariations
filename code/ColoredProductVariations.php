<?php

class ColoredProductVariations extends DataExtension{

	private static $many_many = array(
		"Images" => "Image"
	);

	private static $many_many_extraFields = array(
		'Images' => array(
			'ColorID' => "Int"
		)
	);

	function updateCMSFields(FieldList $fields){
		$fields->insertAfter(
			$gf = GridField::create("Images","Images",$this->owner->Images(),
				GridFieldConfig_RelationEditor::create()
					->removeComponentsByType("GridFieldAddNewButton")
					->removeComponentsByType("GridFieldEditButton")
					->removeComponentsByType("GridFieldDataColumns")
					->removeComponentsByType("GridFieldDeleteAction")
					->addComponent(
						$cols = new GridFieldEditableColumns()
					)
					->addComponent(new GridFieldDeleteAction(true))
			),
			"Image"
		);
		$displayfields = array(
			'Title' => array(
				'title' => 'Title',
				'field' => new ReadonlyField("Name")
			)				 
		);
		//add drop-down color selection
		$colors = $this->owner->getColors();
		if($colors->exists()){
			$displayfields['ColorID'] = function($record, $col, $grid) use ($colors){
				return DropdownField::create($col,"Color",
					$colors->map('ID','Value')->toArray()
				)->setHasEmptyDefault(true);
			};
		}
		$cols->setDisplayFields($displayfields);
	}

	/**
	 * Get all the colors for this product
	 * @return DataList colored attribute values
	 */
	function getColors(){
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
	function Colors(){
		$colors = $this->getColors();
		$images = $this->owner->Images();
		if(!$images->exists()){
			return $colors;
		}
		//add images to output
		$output = new ArrayList();
		foreach($colors as $color){
			$output->push($color->customise(array(
				'Images' => $images->filter('ColorID',$color->ID)
			)));
		}

		return $output;
	}
	
}