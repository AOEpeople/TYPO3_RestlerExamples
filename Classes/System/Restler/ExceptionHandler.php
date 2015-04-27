<?php
namespace Aoe\RestlerExamples\System\Restler;

use Aoe\Restler\System\Restler\AbstractExceptionHandler;
use Luracast\Restler\RestException;
use Luracast\Restler\Restler;
use TYPO3\CMS\Core\Utility\GeneralUtility;

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
 *
 * @package RestlerExamples
 */
class ExceptionHandler extends AbstractExceptionHandler
{
    /**
     * This is the right place, where we can handle exceptions, which were thrown in REST-API's!
     *
     * The return value (boolean) describes, if restler should display an error as output:
     * TRUE means:  restler should NOT display an error as output (so, we must do that)
     * FALSE means: restler should dislay an error as output
     *
     * @param RestException $exception
     * @param Restler $restler
     * @return boolean
     */
    protected function handleException(RestException $exception, Restler $restler)
    {
        $previousException = $exception->getPrevious();
        if ($previousException instanceof \Exception) {
            $exception = $previousException;
        }

        $message = "Exception occurred in file ".$exception->getFile()." on line " . $exception->getLine().":\n";
        $message.= "Message:\n" . $exception->getMessage() ."\n\n";
        $message.= "Stack-Trace:\n" . $exception->getTraceAsString();

        /**
         * We use the new TYPO3-logger-framework.
         *
         * If you need information, how to configure the framework, take a look at the documentation:
         * http://docs.typo3.org/typo3cms/CoreApiReference/ApiOverview/Logging/Configuration/Index.html#logging-configuration-writer
         *
         * Per default, it seems, that TYPO3 logs the data in this file:
         * htdocs/typo3temp/logs/typo3.log
         */
        /** @var $logger \TYPO3\CMS\Core\Log\Logger */
        $logger = GeneralUtility::makeInstance('TYPO3\CMS\Core\Log\LogManager')->getLogger(__CLASS__);
        $logger->warning($message);

        // restler should dislay an error as output
        return false;
    }
}
