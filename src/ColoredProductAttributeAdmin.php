<?php

namespace SilverShop\ColoredVariations;

use SilverStripe\Core\Extension;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\GridField\GridFieldAddNewButton;
use SilverShop\Model\Variation\AttributeType;
use Symbiote\GridFieldExtensions\GridFieldAddNewMultiClass;
use SilverStripe\Core\ClassInfo;


class ColoredProductAttributeAdmin extends Extension
{
    public function updateEditForm($form) {
        $this->updateCMSFields($form->Fields());
    }

    public function updateCMSFields(FieldList $fields) {
        if ($attributes = $fields->fieldByName("SilverShop-Model-Variation-AttributeType")) {
            $attributes->getConfig()
                ->removeComponentsByType(GridFieldAddNewButton::class)
                ->addComponent(
                    $multiclass = new GridFieldAddNewMultiClass()
                );

            $multiclass->setClasses(
                array_values(ClassInfo::subclassesFor(AttributeType::class))
            );
        }
    }

}
