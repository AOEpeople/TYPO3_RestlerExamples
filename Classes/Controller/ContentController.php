<?php
namespace Aoe\RestlerExamples\Controller;

use Aoe\Restler\System\TYPO3\Loader as TYPO3Loader;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2015 AOE GmbH <dev@aoe.com>
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
class ContentController
{
    /**
     * @var ContentObjectRenderer
     */
    private $cObject;
    /**
     * @var TYPO3Loader
     */
    private $typo3Loader;

    /**
     * @param ContentObjectRenderer $cObject
     * @param TYPO3Loader $typo3Loader
     */
    public function __construct(ContentObjectRenderer $cObject, TYPO3Loader $typo3Loader)
    {
        $this->cObject = $cObject;
        $this->typo3Loader = $typo3Loader;
    }

    /**
     * Render tt_content-Record (for any FE-user)
     *
     * Render the tt_content-Element with given pageId and content-element-UID - This method is always callable
     *
     * @url GET pages/{pageId}/tt_content/{contentElementUid}
     *
     * @param integer $pageId
     * @param integer $contentElementUid
     * @return string
     */
    public function getContentElementByUidForAnyFeUser($pageId, $contentElementUid)
    {
        $this->typo3Loader->initializeFrontEndRendering($pageId);

        $cConf = array(
            'tables' => 'tt_content',
            'source' => $contentElementUid,
            'dontCheckPid' => 1,
        );
        return $this->cObject->RECORDS($cConf);
    }

    /**
     * Render tt_content-Record (for logged-in FE-user)
     *
     * Render the tt_content-Element with given pageId and content-element-UID
     * This method is only callable, when FE-user is logged-in
     *
     * @url GET customer/self/pages/{pageId}/tt_content/{contentElementUid}
     * @access protected
     * @class Aoe\Restler\Controller\FeUserAuthenticationController {@checkAuthentication true}
     * @class Aoe\Restler\Controller\FeUserAuthenticationController {@argumentNameOfPageId pageId}
     *
     * @param integer $pageId
     * @param integer $contentElementUid
     * @return string
     */
    public function getContentElementByUidForLoggedInFeUser($pageId, $contentElementUid)
    {
        $this->typo3Loader->initializeFrontEndRendering($pageId);

        $cConf = array(
            'tables' => 'tt_content',
            'source' => $contentElementUid,
            'dontCheckPid' => 1,
        );
        return $this->cObject->RECORDS($cConf);
    }
}
