<?php
namespace Aoe\RestlerExamples\Controller;

use Luracast\Restler\RestException;

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
class FeUserController
{
    /**
     * Test GET-Method with protected REST-API-endpoint
     *
     * API-Endpoint is only callable, when TYPO3-Frontend-user is logged-in
     *
     * @url GET customer/self
     * @access protected
     * @class Aoe\Restler\Controller\FeUserAuthenticationController {@checkAuthentication true}
     *
     * @return array
     * @throws RestException 401 frontend-user is not logged-in
     */
    public function getDataOfLoggedInFeUser()
    {
        return $GLOBALS['TSFE']->fe_user->user;
    }
}
