<?php
namespace Aoe\RestlerExamples\Controller\ExtbaseExamples;

use Aoe\RestlerExamples\Domain\Model\Product;
use Aoe\RestlerExamples\Domain\Repository\ProductRepository;
use Aoe\Restler\System\TYPO3\Loader;
use stdClass;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2016 AOE GmbH <dev@aoe.com>
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * @package RestlerExamples
 * @subpackage Controller
 */
class ProductController
{
    /**
     * @var ProductRepository
     */
    private $productRepository;

    /**
     * @var Loader
     */
    private $loader;

    /**
     * The TYPO3-object-manager will create this controller-object, so
     * you can use the dependency-injection of TYPO3 here :-)
     * @param ProductRepository $productRepository
     * @param Loader $loader
     */
    public function __construct(ProductRepository $productRepository, Loader $loader)
    {
        $this->productRepository = $productRepository;
        $this->loader = $loader;
    }

    /********** The following code will ONLY work when the properties of the extbase-domain-model are PUBLIC! **********/
    /**
     * Get Data from extBase-Objects
     *
     * In order to get data from extBase-objects, you must first create some extBase-Objects of type 'Product' in TYPO3-Backend!
     *
     * @url GET extbase-model-with-public-properties/pages/{pageUid}/products/
     * @param integer $pageUid {@min 1} The page-UID of your root-TYPO3-page
     * @return Array {@type \Aoe\RestlerExamples\Domain\Model\Product}
     */
    public function getProductsAsExtbaseObject($pageUid)
    {
        // initialize the frontend of TYPO3 (this is required when you e.g. want to use
        // extbase-models or if you want to render URL's in your REST-API)
        $this->loader->initializeFrontEndRendering($pageUid);

        return $this->productRepository->findAll();
    }

    /**
     * Get Data from extBase-Objects
     *
     * In order to get data from extBase-objects, you must first create some extBase-Objects of type 'Product' in TYPO3-Backend!
     *
     * @url GET extbase-model-with-public-properties/pages/{pageUid}/products/{productUid}
     * @param integer $pageUid {@min 1} The page-UID of your root-TYPO3-page
     * @param integer $productId {@min 1}
     * @return Product {@type \Aoe\RestlerExamples\Domain\Model\Product}
     */
    public function getProductAsExtbaseObject($pageUid, $productUid)
    {
        // initialize the frontend of TYPO3 (this is required when you e.g. want to use
        // extbase-models or if you want to render URL's in your REST-API)
        $this->loader->initializeFrontEndRendering($pageUid);

        return $this->productRepository->findOne($productUid);
    }



    /********** The following code will ALSO work when the properties of the extbase-domain-model are PROTECTED or PRIVATE! **********/
    /**
     * Get Data from extBase-Objects
     *
     * In order to get data from extBase-objects, you must first create some extBase-Objects of type 'Product' in TYPO3-Backend!
     *
     * @url GET extbase-model-with-none-public-properties/pages/{pageUid}/products
     * @param integer $pageUid {@min 1} The page-UID of your root-TYPO3-page
     * @return array {@type stdClass}
     */
    public function getProductsAsNoneExtbaseObject($pageUid)
    {
        // initialize the frontend of TYPO3 (this is required when you e.g. want to use
        // extbase-models or if you want to render URL's in your REST-API)
        $this->loader->initializeFrontEndRendering($pageUid);

        $restProducts = array();
        foreach ($this->productRepository->findAll() as $extbaseProduct) {
            /* @var $extbaseProduct Product */
            $restProduct = new stdClass();
            $restProduct->uid = $extbaseProduct->getUid();
            $restProduct->name = $extbaseProduct->getName();
            $restProduct->description = $extbaseProduct->getDescription();
            $restProduct->details_page = $extbaseProduct->getDetailsPage();
            $restProducts[] = $restProduct;
        }

        return $restProducts;
    }

    /**
     * Get Data from extBase-Objects
     *
     * In order to get data from extBase-objects, you must first create some extBase-Objects of type 'Product' in TYPO3-Backend!
     *
     * @url GET extbase-model-with-none-public-properties/pages/{pageUid}/products/{productUid}
     * @param integer $pageUid {@min 1} The page-UID of your root-TYPO3-page
     * @param integer $productId {@min 1}
     * @return stdClass
     */
    public function getProductAsNoneExtbaseObject($pageUid, $productUid)
    {
        // initialize the frontend of TYPO3 (this is required when you e.g. want to use
        // extbase-models or if you want to render URL's in your REST-API)
        $this->loader->initializeFrontEndRendering($pageUid);

        $extbaseProduct = $this->productRepository->findOne($productUid);
        $restProduct = new stdClass();
        $restProduct->uid = $extbaseProduct->getUid();
        $restProduct->name = $extbaseProduct->getName();
        $restProduct->description = $extbaseProduct->getDescription();
        $restProduct->details_page = $extbaseProduct->getDetailsPage();
        return $restProduct;
    }
}
