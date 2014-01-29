# SilverStripe Shop Colored Variations

Choose colors for variation attributes, allowing for display of color swatches.

The module also introduces the ability to add many images to a product, and assign each image to a specific color.

## Installation

```
composer require burnbright/silverstripe-shop-coloredvariations
```

Add the color swatches template to your `templates/Layout/Product.ss` template:

```
<% include ColorSwatches %>
```

## Caveats

It is tested / not known how this add-on will behave when there are multiple `ColoredProductAttributeType`s assigned to a product. How often would you independently choose different colors per product though?

## License

BSD-2
