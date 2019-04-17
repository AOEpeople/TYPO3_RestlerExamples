.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt


.. _developer-manual:

Developer Manual
====================

This is a TYPO3-Extension, which contain some Examples, how to use/configure the `Restler Framework
<https://github.com/Luracast/Restler>`_ in TYPO3 via the `TYPO3-Extension 'restler'
<https://github.com/AOEpeople/TYPO3_Restler>`_.

Read more details in the TYPO3 restler extension documentation.

Have a look at the /api_explorer/swagger endpoint to see all available routes.

Target group: **Developers**


Installation
------------

The dependencies inside _composer.json are used just for the tests examples (functional with PHPUnit and Guzzle and
 behavioural with Behat).

 If you want to run the tests as well, just install the dependencies with:

.. code-block:: bash

    composer install
