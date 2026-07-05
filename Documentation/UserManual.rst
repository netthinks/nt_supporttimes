.. include:: /Includes.rst.txt

.. _user-manual:

===========
User Manual
===========

This extension provides two main components for displaying TYPO3 support information.

Dashboard Widget
================

The dashboard widget provides a quick overview of TYPO3 support status.

Adding the Widget
-----------------

1. Navigate to the **Dashboard** module in the TYPO3 backend
2. Click the **+ Add Widget** button
3. Select **TYPO3 Support Status** from the widget list
4. The widget is immediately added to your dashboard

Widget Features
---------------

The widget displays:

* **Version:** TYPO3 major version number
* **Latest Release:** Current version with release date
* **Official Support End:** Date when regular support ends
* **ELTS Support End:** Extended Long Term Support end date (if enabled)
* **Status Badge:** Color-coded status indicator

  * **Green (Active):** Currently supported
  * **Blue (Expiring):** Support ends within 6 months
  * **Yellow (ELTS):** In Extended Long Term Support phase
  * **Red (Expired):** No longer supported

Scrolling
~~~~~~~~~

The widget table is scrollable, allowing you to view all TYPO3 versions including legacy releases.

Backend Update Notification
===========================

The extension automatically checks whether the installed TYPO3 version is up to
date and warns you directly in the backend if it is not.

How It Works
------------

On every backend request the system information toolbar (the info icon in the top
toolbar, next to the TYPO3 version) is enriched with an update check:

1. The installed TYPO3 version is determined.
2. The latest patch release for the same major branch is fetched from
   get.typo3.org (using the same cached data as the widget and roadmap).
3. If the installed version is older than the latest patch release, a warning is
   added to the toolbar.

What You See
------------

When an update is available:

* A **warning badge** appears on the system information toolbar item.
* Opening the dropdown shows a message such as
  *TYPO3 update available: 14.3.4 (installed: 14.3.1)*.
* The version number links to the release notes on get.typo3.org.

When the installation is up to date, no badge and no message are shown.

.. note::

   The check reuses the cached API data (default cache lifetime: 24 hours), so a
   freshly published release may take up to the cache lifetime to appear. If the
   TYPO3 API cannot be reached, the check is skipped silently — no badge and no
   error are produced.

Frontend Roadmap Plugin
=======================

The roadmap plugin displays an interactive timeline chart on your website.

Adding the Plugin
-----------------

1. Create or edit a page in the TYPO3 backend
2. Add a new **Content Element**
3. Select **Plugins → TYPO3 Support Times**
4. Configure the plugin settings (see below)
5. Save and view the page

Plugin Configuration
--------------------

The plugin offers two configuration options in the **Plugin** tab:

Display Versions
~~~~~~~~~~~~~~~~

Select which TYPO3 versions to show in the chart:

* Check the versions you want to display (9, 10, 11, 12, 13, 14)
* Leave empty to show all configured versions
* Useful for focusing on specific version ranges

Chart Height
~~~~~~~~~~~~

Set the chart height in pixels:

* Default: 350px
* Recommended range: 250-600px
* Adjust based on your page layout

Understanding the Chart
-----------------------

The roadmap chart shows different support phases with color coding:

Sprint Releases (Gray)
~~~~~~~~~~~~~~~~~~~~~~~

* Pre-LTS development releases
* Alternating gray tones for visual distinction
* Shows minor version numbers (e.g., 13.0, 13.1, 13.2)

Regular Maintenance (Green)
~~~~~~~~~~~~~~~~~~~~~~~~~~~~

* Full feature and bugfix support
* Typically ~18 months from LTS release
* Includes LTS version number (e.g., "13.4 LTS")

Priority/Security Support (Orange)
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

* Security fixes and critical bugfixes only
* Follows regular maintenance phase
* Extends to official support end date

ELTS Support (Light Orange)
~~~~~~~~~~~~~~~~~~~~~~~~~~~~

* Extended Long Term Support by TYPO3 GmbH
* Available for legacy versions
* Requires commercial license

Extended Partner Support (Beige)
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

* Additional 2 years of support
* Provided by certified partners
* Follows ELTS or official support

Planned Releases
~~~~~~~~~~~~~~~~

For TYPO3 14, the chart shows planned future sprint releases with estimated dates. These are automatically replaced with actual dates once releases are published.

Interacting with the Chart
---------------------------

* **Hover:** Display detailed information about each phase
* **Responsive:** Chart adapts to screen size
* **Timeline:** X-axis shows dates, Y-axis shows TYPO3 versions

Multilingual Support
====================

All labels, tooltips, and chart phases are available in:

* **English** (default)
* **German** (Deutsch)

The language is automatically selected based on the backend/frontend language setting.

Troubleshooting
===============

Widget Not Showing Data
-----------------------

* Check extension configuration (Admin Tools → Settings)
* Verify API connectivity to get.typo3.org
* Clear TYPO3 caches
* Check cache lifetime setting

Chart Not Displaying
--------------------

* Ensure JavaScript is enabled
* Check browser console for errors
* Verify ApexCharts library is loading
* Clear browser cache

Incorrect Data
--------------

* Clear extension cache
* Check configured version list
* Verify API data is current
* Wait for cache to refresh (default: 24 hours)
