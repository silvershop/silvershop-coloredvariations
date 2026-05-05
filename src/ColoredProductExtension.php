<?php

namespace SilverShop\ColoredVariations;

use SilverShop\Page\Product;
use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Assets\Image;
use SilverStripe\Forms\LiteralField;
use SilverStripe\Forms\SingleSelectField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\GridField\GridFieldDataColumns;
use SilverStripe\Forms\GridField\GridFieldDeleteAction;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldAddNewButton;
use SilverStripe\Forms\GridField\GridFieldConfig_RelationEditor;
use SilverStripe\Forms\GridField\GridFieldEditButton;
use Symbiote\GridFieldExtensions\GridFieldEditableColumns;
use Symbiote\GridFieldExtensions\GridFieldOrderableRows;
use SilverStripe\Forms\ReadonlyField;
use SilverStripe\Forms\Tab;
use SilverStripe\Forms\TabSet;
use SilverStripe\Model\List\ArrayList;
use SilverStripe\Model\List\SS_List;
use SilverStripe\Core\Extension;

/**
 * @extends Extension<Product>
 */
class ColoredProductExtension extends Extension
{
    /**
     * @var array<string, string>
     */
    private static $many_many = [
        "ColorImages" => Image::class
    ];

    /**
     * @var array<int, string>
     */
    private static $owns = [
        'ColorImages'
    ];

    /**
     * @var array<string, array<string, string>>
     */
    private static $many_many_extraFields = [
        'ColorImages' => [
            'ColorID' => "Int",
            'Sort' => "Int"
        ]
    ];

    public function updateCMSFields(FieldList $fields): void
    {
        $cols = null;
        $firstCreationNote = 'Note: The product must be saved before attributes can be assigned to images.';

        $fields->insertAfter('Image', $tabset = TabSet::create('ColoredImages'));
        $tabset->push($uploadtab = Tab::create('UploadImages'));
        $tabset->push($attributetab = Tab::create('AssignAttribute'));

        $uploadtab->push($uf = UploadField::create('ColorImages', 'Color Images'));
        $uf->setDescription($firstCreationNote);

        if ($this->owner->exists()) {
            $attributetab->push(
                $gf = GridField::create("ImageAttributes", "Color Images", $this->owner->ColorImages(),
                    GridFieldConfig_RelationEditor::create()
                        ->removeComponentsByType(GridFieldAddNewButton::class)
                        ->removeComponentsByType(GridFieldEditButton::class)
                        ->removeComponentsByType(GridFieldDataColumns::class)
                        ->removeComponentsByType(GridFieldDeleteAction::class)
                        ->addComponent(
                            $cols = GridFieldEditableColumns::create()
                        )
                        ->addComponent(
                            GridFieldOrderableRows::create('Sort')
                        )
                )
            );
        } else {
            $attributetab->push(LiteralField::create('ImageAttributesNote', $firstCreationNote));
        }

        $displayfields = [
            'Title' => [
                'title' => 'Title',
                'field' => ReadonlyField::create('Name')
            ]
        ];

        $colors = $this->owner->getColors();

        if ($colors->exists()) {
            $displayfields['ColorID'] = function ($record, string $col, GridField $grid) use ($colors): SingleSelectField {
                return DropdownField::create(
                    $col,
                    'Color',
                    $colors->map('ID', 'Value')->toArray()
                )->setHasEmptyDefault(true);
            };
        }

        if ($cols) {
            $cols->setDisplayFields($displayfields);
        }
    }

    /**
     * @return mixed
     */
    public function getColors()
    {
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
            ->filter('ProductID', $this->owner->ID);
    }

    /**
     * Add image lists to colors
     *
     * @return SS_List<mixed>
     */
    public function Colors(): SS_List
    {
        $colors = $this->getColors();
        $images = $this->owner->ColorImages();

        $output = ArrayList::create();

        foreach ($colors as $color) {
            $output->push($color->customise([
                'ColorImages' => $images->filter('ColorID', $color->ID)
            ]));
        }

        return $output;
    }

}
