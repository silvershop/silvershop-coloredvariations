<?php

use SilverStripe\CMS\Controllers\ContentController;
use SilverStripe\CMS\Model\SiteTree;

if (!class_exists('Page')) {
    class Page extends SiteTree
    {
    }
}

if (!class_exists('PageController')) {
    /**
     * @extends ContentController<Page>
     */
    class PageController extends ContentController
    {
    }
}

require_once dirname(__DIR__) . '/vendor/silverstripe/framework/tests/bootstrap.php';
