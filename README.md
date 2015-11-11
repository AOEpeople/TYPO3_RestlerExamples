# TYPO3_RestlerExamples
This is a TYPO3-Extension, which contains some examples, how to use/configure the restler-Framework (PHP REST-framework to create REST-API's, https://github.com/Luracast/Restler) in TYPO3 via the TYPO3-Extension 'restler' (https://github.com/AOEpeople/TYPO3_Restler)

## Installation
Download the latest version from github or clone the repository:
```bash
git clone https://github.com/AOEpeople/TYPO3_RestlerExamples.git
```

Use composer to download all dev-dependencies to run behat tests and other stuff
```bash
COMPOSER=_composer.json composer install
```

Activate the restler_example extension in the Extension Manager.

Adapt the settings for the restler extension in the Extension Manager and add the extension key restler_example at the end of the "TYPO3-Extensions with required ext_localconf.php-files" input-field.

## Usage
Open http://www.example.local/api_explorer/ to see all REST-API endpoints.