<?php

namespace SilverShop\ColoredVariations;

use SilverShop\Admin\ProductCatalogAdmin;
use SilverShop\Model\Variation\AttributeType;
use SilverStripe\Core\Extension;
use SilverStripe\Forms\GridField\GridFieldAddNewButton;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\Form;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Core\ClassInfo;
use Symbiote\GridFieldExtensions\GridFieldAddNewMultiClass;

/**
 * @extends Extension<ProductCatalogAdmin>
 */
class ColoredProductAttributeAdmin extends Extension
{
    public function updateEditForm(Form $form): void
    {
        $this->updateCMSFields($form->Fields());
    }

    public function updateCMSFields(FieldList $fields): void
    {
        $attributes = $fields->fieldByName('SilverShop-Model-Variation-AttributeType');
        if (!$attributes instanceof GridField) {
            return;
        }

        $attributes->getConfig()
            ->removeComponentsByType(GridFieldAddNewButton::class)
            ->addComponent(
                $multiclass = GridFieldAddNewMultiClass::create()
            );

        $multiclass->setClasses(
            array_values(ClassInfo::subclassesFor(AttributeType::class))
        );
    }

}
