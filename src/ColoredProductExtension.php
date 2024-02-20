<?php

namespace SilverShop\ColoredVariations;

use SilverStripe\Forms\LiteralField;
use SilverStripe\ORM\DataExtension;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TabSet;
use SilverStripe\Forms\Tab;
use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Assets\Image;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_RelationEditor;
use Symbiote\GridFieldExtensions\GridFieldEditableColumns;
use Symbiote\GridFieldExtensions\GridFieldOrderableRows;
use SilverStripe\Forms\ReadonlyField;
use SilverStripe\Forms\DropdownField;
use SilverStripe\ORM\ArrayList;

class ColoredProductExtension extends DataExtension
{
    private static $many_many = [
        "Images" => Image::class
    ];

    private static $owns = [
        'Images'
    ];

    private static $many_many_extraFields = [
        'Images' => [
            'ColorID' => "Int",
            'Sort' => "Int"
        ]
    ];

    public function updateCMSFields(FieldList $fields) {
        $cols = null;
        $firstCreationNote = 'Note: The product must be saved before attributes can be assigned to images.';

        $fields->insertAfter('Image', $tabset = new TabSet('ColoredImages'));
        $tabset->push($uploadtab = new Tab('UploadImages'));
        $tabset->push($attributetab = new Tab('AssignAttribute'));

        $uploadtab->push($uf = new UploadField('Images', 'Images'));
        $uf->setDescription($firstCreationNote);

        if ($this->owner->exists()) {
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
        } else {
            $attributetab->push(LiteralField::create('ImageAttributesNote', $firstCreationNote));
        }

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

        if ($cols) {
            $cols->setDisplayFields($displayfields);
        }
    }

    /**
     * Get all the colors for this product
     *
     * @return DataList
     */
    public function getColors() {
        return ColoredProductAttributeValue::get()
            ->innerJoin(
                "SilverShop_Variation_AttributeValues",
                "\"SilverShop_Variation_AttributeValues\".\"SilverShop_AttributeValueID\" = ".
                "\"SilverShop_ColoredProductAttributeValue\".\"ID\""
            )
            ->innerJoin(
                "SilverShop_Variation",
                "\"SilverShop_Variation_AttributeValues\".\"SilverShop_VariationID\" = ".
                "\"SilverShop_Variation\".\"ID\""
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
