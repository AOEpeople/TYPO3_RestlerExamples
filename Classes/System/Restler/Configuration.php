<?php
namespace Aoe\RestlerExamples\System\Restler;
use Aoe\Restler\System\Restler\ConfigurationInterface;
use Luracast\Restler\Restler;

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
 * Configure restler:
 *  - add API-classes
 *
 * @package RestlerExamples
 */
class Configuration implements ConfigurationInterface
{
    /**
     * @param Restler $restler
     * @return void
     */
    public function configureRestler(Restler $restler)
    {
        $restler->addAPIClass('Aoe\\RestlerExamples\\Controller\\CarController', 'api/motorsport');
        $restler->addAPIClass('Aoe\\RestlerExamples\\Controller\\ContentController', 'api/shop');
        $restler->addAPIClass('Aoe\\RestlerExamples\\Controller\\FeUserController', 'api/shop');
        $restler->addAPIClass('Aoe\\RestlerExamples\\Controller\\HttpStatusCodeController', 'api/http-status-codes');
    }
}
