<?php
if (!defined('TYPO3_MODE')) {
    die ('Access denied.');
}

// add restler-configuration-class
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['restler']['restlerConfigurationClasses'][] = 'Aoe\\RestlerExamples\\System\\Restler\\Configuration';
