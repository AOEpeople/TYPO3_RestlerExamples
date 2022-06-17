# TYPO3_RestlerExamples

[![Tests](https://github.com/AOEpeople/TYPO3_RestlerExamples/actions/workflows/tests.yml/badge.svg?branch=main)](https://github.com/AOEpeople/TYPO3_RestlerExamples/actions/workflows/tests.yml)

This is a TYPO3-Extension, which contains some examples, how to use/configure the [Restler Framework][LuracastRestler] (PHP framework to create REST-APIs)
in TYPO3 via the TYPO3-Extension [Restler][TYPO3_Restler]

## Versions and Support

| Release  | TYPO3     | PHP   | Fixes will contain
|---|-----------|---|---|
| 10.x.y | 10.4-11.5 | 7.2 - 7.4 | Features, Bugfixes, Security Updates
| 9.x.y  | 9.5       | 7.2 - 7.4 | Security Updates
| 8.x.y  | 8.6       | 7.0 - 7.4 | End of life
| 1.x.y  | 7.6       | 5.3 - 5.6 | End of life
| 0.x.y  | 6.2       | 5.3 - 5.6 | End of life

## Download / Installation

You can download and install this extension from the [TER (TYPO3 Extension Repository)][RestlerExamples_TER] or use composer.

```shell script
composer require aoe/restler-examples
```

### Using git

Download the latest version from GitHub by cloning the repository:

```bash
git clone https://github.com/AOEpeople/TYPO3_RestlerExamples.git restler_examples
```

Use composer to download all dev-dependencies to run behat tests and other stuff
```bash
composer install
```

Activate the restler_example extension in the Extension Manager.

## Usage
Open http://www.example.local/api_explorer/ to see all REST-API endpoints.

## Documentation

The documentation is available online at [docs.typo3.org][RestlerExamples_Documentation].

## Copyright / License

Copyright: (c) 2015 - 2021, AOE GmbH
License: GPLv3, <http://www.gnu.org/licenses/gpl-3.0.en.html>

[LuracastRestler]: https://github.com/Luracast/Restler
[TYPO3_Restler]: https://github.com/AOEpeople/TYPO3_Restler
[RestlerExamples_TER]: https://extensions.typo3.org/extension/restler_examples
[RestlerExamples_Documentation]: https://docs.typo3.org/typo3cms/extensions/restler_examples/stable/
