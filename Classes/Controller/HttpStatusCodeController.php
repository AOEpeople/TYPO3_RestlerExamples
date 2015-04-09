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
class HttpStatusCodeController
{
    /**
     * Test with HTTP-Status-Codes and Exceptions - Part 1
     *
     * Return always an array with some data
     *
     * @url GET objects
     *
     * @return array
     */
    public function getObjects()
    {
        return array(
            array('id' => 1, 'name' => 'object1'),
            array('id' => 2, 'name' => 'object2'),
            array('id' => 3, 'name' => 'object3'),
            array('id' => 4, 'name' => 'object4')
        );
    }

    /**
     * Test with HTTP-Status-Codes and Exceptions - Part 2
     *
     * Throw Exception (with detailed informations), when variable $id has NOT the value 1
     *
     * @url GET objects/{id}
     * @throws RestException 404 Resource does not exists
     *
     * @param integer $id
     * @return array
     */
    public function getObject($id)
    {
        if ($id === 1) {
            return array('id' => $id, 'name' => 'tester');
        }

        $details = array(
            'errors' => array(
                array('code' => 1024, 'field' => 'id', 'message' => 'bliblablub'),
                array('code' => 1024, 'field' => 'id', 'message' => 'bliblablub')
            )
        );

        throw new RestException(404, 'id does not exists', $details);
    }

    /**
     * Test with HTTP-Status-Codes and Exceptions - Part 3
     *
     * Throw Exception, when variable $parentId or $childId has NOT the value 1
     *
     * @url GET objects/{parentId}/subobjects/{childId}
     * @throws RestException 404 ParentId does not exists
     * @throws RestException 404 ChildId does not exists
     *
     * @param integer $parentId
     * @param integer $childId
     * @return array
     */
    public function getSubObject($parentId, $childId)
    {
        if ($parentId !== 1) {
            throw new RestException(404, 'parentId does not exists');
        } elseif ($childId !== 1) {
            throw new RestException(404, 'childId does not exists');
        }

        return array('parentId' => $parentId, 'childId' => $childId, 'name' => 'tester');
    }

    /**
     * Test with HTTP-Status-Codes and Exceptions - Part 4
     *
     * Throw Exception, when variable $name has value 'Felix' or 'Thomas'
     *
     * @url POST objects/
     * @status 201
     *
     * @param string $name Name {@example Arul Kumaran}
     * @throws RestException 400 Invalid name
     * @throws RestException 500 Could not create resource
     * @return integer
     */
    public function createObject($name)
    {
        if ($name === 'Felix') {
            throw new RestException(400, 'invalid name', array('error_code' => 12002));
        } elseif ($name === 'Thomas') {
            throw new RestException(500, 'could not create resource', array('error_code' => 12004));
        } else {
            return array('id' => 10000);
        }
    }

    /**
     * Test with HTTP-Status-Codes and Exceptions - Part 5
     *
     * Update object via REST-endpoint. Throw Exception, when variable $id has NOT the value 1
     *
     * @url PUT objects/{id}
     * @throws RestException 500 Could not update resource
     *
     * @param integer $id
     * @param string $name
     * @return array
     */
    public function updateObject($id, $name)
    {
        if ($id !== 1) {
            throw new RestException(500);
        }
        return array('id' => $id, 'name' => $name);
    }

    /**
     * Test with HTTP-Status-Codes and Exceptions - Part 6
     *
     * Delete object via REST-endpoint (and return HTTP-Status-Code 204).
     * Throw Exception, when variable $id has NOT the value 1
     *
     * @url DELETE objects/{id}
     * @status 204
     *
     * @param integer $id
     * @throws RestException 404 Could not delete resource
     * @return void
     */
    public function deleteObject($id)
    {
        if ($id !== 1) {
            throw new RestException(404, 'Could not delete resource');
        }
    }
}
