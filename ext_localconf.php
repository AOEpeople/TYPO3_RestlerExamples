<?php
defined('TYPO3') or die();

// add restler-configuration-class
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['restler']['restlerConfigurationClasses'][] = \Aoe\RestlerExamples\System\Restler\Configuration::class;
