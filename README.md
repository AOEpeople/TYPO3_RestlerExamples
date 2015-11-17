# TYPO3_RestlerExamples
This is a TYPO3-Extension, which contains some examples, how to use/configure the [Restler Framework][LuracastRestler] (PHP ramework to create REST-APIs)
in TYPO3 via the TYPO3-Extension [Restler][TYPO3_Restler]

## Brief installation

### Using the TYPO3 Extension Repository
You can download and install this extension from the [TER (TYPO3 Extension Repository)][RestlerExamples_TER]

### Using git

Download the latest version from Github by clone the repository:

```bash
git clone https://github.com/AOEpeople/TYPO3_RestlerExamples.git restler_examples
```

Use composer to download all dev-dependencies to run behat tests and other stuff
```bash
COMPOSER=_composer.json composer install
```

Activate the restler_example extension in the Extension Manager.

Adapt the settings for the restler extension in the Extension Manager.
Add the extension key restler_example at the end of the "TYPO3-Extensions with required ext_localconf.php-files" input field.

## Usage
Open http://www.example.local/api_explorer/ to see all REST-API endpoints.

## Documentation

The documentation is available online at [docs.typo3.org][RestlerExamples_Documentation].

## Copyright / License

Copyright: (c) 2015, AOE GmbH <dev@aoe.com>
License: GPLv3, <http://www.gnu.org/licenses/gpl-3.0.en.html>


[LuracastRestler]: https://github.com/Luracast/Restler
[TYPO3_Restler]: https://github.com/AOEpeople/TYPO3_Restler
[RestlerExamples_TER]: http://typo3.org/extensions/repository/view/restler_examples
[RestlerExamples_Documentation]: https://docs.typo3.org/typo3cms/extensions/restler_examples/
