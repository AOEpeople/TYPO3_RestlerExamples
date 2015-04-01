<?php
$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['extbase_reflection']['backend'] = 't3lib_cache_backend_NullBackend';
$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['extbase_object']['backend'] = 't3lib_cache_backend_NullBackend';

$autoload = PATH_site . 'typo3conf/ext/restler/vendor/autoload.php';
if (file_exists($autoload)) {
    require_once $autoload;
}
