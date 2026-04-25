<?php

use SilverStripe\CMS\Controllers\ContentController;
use SilverStripe\CMS\Model\SiteTree;

if (!class_exists('Page')) {
    class Page extends SiteTree
    {
    }
}

if (!class_exists('PageController')) {
    class PageController extends ContentController
    {
    }
}

require_once dirname(__DIR__) . '/vendor/silverstripe/framework/tests/bootstrap.php';
