<?php

declare(strict_types=1);

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
 * @IgnoreAnnotation("url")
 * @IgnoreAnnotation("expires")
 * @IgnoreAnnotation("status")
 */
class HttpStatusCodeController
{
    /**
     * Test with HTTP-Status-Codes and Exceptions - Part 1
     *
     * Return always an array with some data
     *
     * @url GET objects
     */
    public function getObjects(): array
    {
        return [
            ['id' => 1, 'name' => 'object1'],
            ['id' => 2, 'name' => 'object2'],
            ['id' => 3, 'name' => 'object3'],
            ['id' => 4, 'name' => 'object4'],
        ];
    }

    /**
     * Test with HTTP-Status-Codes and Exceptions - Part 2
     *
     * Throw Exception (with detailed informations), when variable $id has NOT the value 1
     *
     * @url GET objects/{id}
     */
    public function getObject(int $id): array
    {
        if ($id === 1) {
            return ['id' => $id, 'name' => 'tester'];
        }

        $details = [
            'errors' => [
                ['code' => 1024, 'field' => 'id', 'message' => 'bliblablub'],
                ['code' => 1024, 'field' => 'id', 'message' => 'bliblablub'],
            ],
        ];

        throw new RestException('404', 'id does not exists', $details);
    }

    /**
     * Test with HTTP-Status-Codes and Exceptions - Part 3
     *
     * Throw Exception, when variable $parentId or $childId has NOT the value 1
     *
     * @url GET objects/{parentId}/subobjects/{childId}
     */
    public function getSubObject(int $parentId, int $childId): array
    {
        if ($parentId !== 1) {
            throw new RestException('404', 'parentId does not exists');
        }

        if ($childId !== 1) {
            throw new RestException('404', 'childId does not exists');
        }

        return ['parentId' => $parentId, 'childId' => $childId, 'name' => 'tester'];
    }

    /**
     * Test with HTTP-Status-Codes and Exceptions - Part 4
     *
     * Throw Exception, when variable $name has value 'Felix' or 'Thomas'
     *
     * @url POST objects/
     * @status 201
     */
    public function createObject(string $name): array
    {
        if ($name === 'Felix') {
            throw new RestException('400', 'invalid name', ['error_code' => 12002]);
        }

        if ($name === 'Thomas') {
            throw new RestException('500', 'could not create resource', ['error_code' => 12004]);
        }

        return ['id' => 10000];
    }

    /**
     * Test with HTTP-Status-Codes and Exceptions - Part 5
     *
     * Update object via REST-endpoint. Throw Exception, when variable $id has NOT the value 1
     *
     * @url PUT objects/{id}
     */
    public function updateObject(int $id, string $name): array
    {
        if ($id !== 1) {
            throw new RestException('500');
        }

        return ['id' => $id, 'name' => $name];
    }

    /**
     * Test with HTTP-Status-Codes and Exceptions - Part 6
     *
     * Delete object via REST-endpoint (and return HTTP-Status-Code 204).
     * Throw Exception, when variable $id has NOT the value 1
     *
     * @url DELETE objects/{id}
     * @status 204
     */
    public function deleteObject(int $id): void
    {
        if ($id !== 1) {
            throw new RestException('404', 'Could not delete resource');
        }
    }
}
