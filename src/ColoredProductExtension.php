<?php

namespace SilverShop\ColoredVariations;

use SilverStripe\ORM\DataExtension;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TabSet;
use SilverStripe\Forms\Tab;
use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Assets\Image;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_RelationEditor;
use GridFieldEditableColumns;
use GridFieldOrderableRows;
use SilverStripe\Forms\ReadonlyField;
use SilverStripe\Forms\DropdownField;
use SilverStripe\ORM\ArrayList;

class ColoredProductExtension extends DataExtension
{
    private static $many_many = [
        "Images" => Image::class
    ];

    private static $many_many_extraFields = [
        'Images' => [
            'ColorID' => "Int",
            'Sort' => "Int"
        ]
    ];

    public function updateCMSFields(FieldList $fields) {
        $fields->insertAfter($tabset = new TabSet('ColoredImages'), 'Image');
        $tabset->push($uploadtab = new Tab('UploadImages'));
        $tabset->push($attributetab = new Tab('AssignAttribute'));

        $uploadtab->push($uf = new UploadField('Images', 'Images'));
        $uf->setDescription('Note: The product must be saved before attributes can be assigned to new uploaded images.');

        $attributetab->push(
            $gf = GridField::create("ImageAttributes", "Images", $this->owner->Images(),
                GridFieldConfig_RelationEditor::create()
                    ->removeComponentsByType("GridFieldAddNewButton")
                    ->removeComponentsByType("GridFieldEditButton")
                    ->removeComponentsByType("GridFieldDataColumns")
                    ->removeComponentsByType("GridFieldDeleteAction")
                    ->addComponent(
                        $cols = new GridFieldEditableColumns()
                    )
                    ->addComponent(
                        new GridFieldOrderableRows('Sort')
                    )
            )
        );
        $displayfields = array(
            'Title' => array(
                'title' => 'Title',
                'field' => new ReadonlyField("Name")
            )
        );

        $colors = $this->owner->getColors();

        if ($colors->exists()) {
            $displayfields['ColorID'] = function($record, $col, $grid) use ($colors) {
                return DropdownField::create($col,"Color",
                    $colors->map('ID','Value')->toArray()
                )->setHasEmptyDefault(true);
            };
        }

        $cols->setDisplayFields($displayfields);
    }

    /**
     * Get all the colors for this product
     *
     * @return DataList
     */
    public function getColors() {
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
     * Add image lists to colors
     *
     * @return DataList
     */
    public function Colors() {
        $colors = $this->getColors();
        $images = $this->owner->Images();

        if (!$images->exists()) {
            return $colors;
        }

        $output = new ArrayList();

        foreach ($colors as $color) {
            $output->push($color->customise(array(
                'Images' => $images->filter('ColorID',$color->ID)
            )));
        }

        return $output;
    }

}
