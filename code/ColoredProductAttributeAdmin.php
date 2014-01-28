<?php

class ColoredProductAttributeAdmin extends Extension{

	function updateEditForm($form){
		$this->updateCMSFields($form->Fields());
	}
	
	function updateCMSFields(FieldList $fields){
		if($attributes = $fields->fieldByName("ProductAttributeType")){
			$attributes->getConfig()
				->removeComponentsByType("GridFieldAddNewButton")
				->addComponent(
					new GridFieldAddNewMultiClass()
				);
		}
	}

}