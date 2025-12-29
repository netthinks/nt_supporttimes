.. include:: /Includes.rst.txt

.. _installation:

============
Installation
============

Via Composer
------------

The recommended way to install this extension is using Composer:

.. code-block:: bash

   composer require netthinks/nt-supporttimes

Via Extension Manager
---------------------

1.  Login to the TYPO3 Backend.
2.  Go to **Admin Tools > Extensions**.
3.  Activate the extension `nt_supporttimes`.

.. _configuration:

=============
Configuration
=============

You can configure the extension in **Admin Tools > Settings > Extension Configuration**.

Properties
----------

supportedVersions
    *   **Type:** string (comma separated integers)
    *   **Default:** 9,10,11,12,13,14
    *   **Description:** List of TYPO3 Major versions to display in the widget.

showElts
    *   **Type:** boolean
    *   **Default:** 1
    *   **Description:** If enabled, ELTS support expiration dates are shown.

cacheLifetime
    *   **Type:** integer
    *   **Default:** 86400
    *   **Description:** Cache lifetime for the remote data in seconds.
