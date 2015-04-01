<?php
namespace Aoe\RestlerExamples\Domain\Model;

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
 * This class represents a manufacturer of cars.
 *
 * This class is used in this cases:
 *  - If it's an GET-request, we use this class in API-class to create the response
 *  - If it's an POST-request, we use this class in API-class as method-parameter (so the API-method has only one param)
 *  - Inside the online-documentation, we see the structure of the response, which the API will return
 *  - Creating swagger spec model (json schema)
 *
 * @package RestlerExamples
 * @subpackage Domain/Model
 */
class Manufacturer
{
    /**
     * @var integer {@required true} {@min 1}
     */
    public $id;
    /**
     * @var string {@required true} {@min 2}
     */
    public $name;
}
