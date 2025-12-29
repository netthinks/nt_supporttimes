.. include:: /Includes.rst.txt

.. _configuration:

=============
Configuration
=============

Extension Configuration
=======================

Global extension settings are configured in **Admin Tools → Settings → Extension Configuration → nt_supporttimes**.

Supported Versions
------------------

:Type: string
:Default: ``9,10,11,12,13,14``

Comma-separated list of TYPO3 major versions to display.

Example::

   12,13,14

This will show only TYPO3 12, 13, and 14 in the dashboard widget and make them available for the frontend plugin.

Show ELTS
---------

:Type: boolean
:Default: ``1`` (enabled)

Enable or disable Extended Long Term Support (ELTS) information display.

* **Enabled (1):** Shows ELTS column in dashboard widget and ELTS phases in roadmap chart
* **Disabled (0):** Hides all ELTS-related information

Cache Lifetime
--------------

:Type: integer
:Default: ``86400`` (24 hours)

Duration in seconds to cache API data from get.typo3.org.

Recommended values:

* **86400** (24 hours) - Default, good balance
* **43200** (12 hours) - More frequent updates
* **3600** (1 hour) - Development/testing

.. note::
   Lower values increase API requests. The TYPO3 API data typically updates once per release.

Plugin Configuration (FlexForm)
================================

Per-plugin settings are configured when adding the roadmap plugin to a page.

General Settings
----------------

Display Versions
~~~~~~~~~~~~~~~~

:Type: select (checkboxes)
:Default: All configured versions

Select which TYPO3 versions to show in this specific roadmap chart.

Options:

* TYPO3 14
* TYPO3 13
* TYPO3 12
* TYPO3 11
* TYPO3 10
* TYPO3 9

.. tip::
   Use this to create focused roadmaps, e.g., only showing LTS versions (12, 13) or current versions (13, 14).

Chart Height
~~~~~~~~~~~~

:Type: integer
:Default: ``350``

Height of the roadmap chart in pixels.

Recommended values:

* **250-300:** Compact display
* **350:** Default, balanced
* **400-600:** Detailed view with more vertical space

TypoScript Configuration
========================

The extension works without TypoScript configuration. However, you can override settings if needed.

Plugin Settings
---------------

Override plugin settings via TypoScript::

   plugin.tx_ntsupporttimes_pi1 {
       settings {
           chartHeight = 400
       }
   }

Constants
---------

No constants are currently defined. All configuration is done via Extension Configuration and FlexForms.

Cache Configuration
===================

The extension uses a dedicated cache for API data.

Cache Identifier
----------------

:Name: ``nt_supporttimes_cache``
:Frontend: ``VariableFrontend``
:Backend: ``FileBackend``
:Groups: ``system``

Clearing Cache
--------------

To clear the extension cache:

1. **Backend:** Admin Tools → Maintenance → Flush Caches → System Caches
2. **CLI:** ``vendor/bin/typo3 cache:flush``

The cache is automatically cleared when:

* Extension is installed/updated
* Cache lifetime expires
* Manual cache flush is triggered

Permissions
===========

Dashboard Widget
----------------

The dashboard widget is available to all backend users who have access to the Dashboard module.

No special permissions are required.

Frontend Plugin
---------------

The frontend plugin can be added by editors with content editing permissions.

No special permissions are required for viewing the roadmap.

Advanced Configuration
======================

Planned Releases
----------------

TYPO3 14 planned releases are hardcoded in the controller. To update them:

1. Edit ``Classes/Controller/SupportTimesController.php``
2. Locate the ``$plannedReleases`` array
3. Update dates as needed

Example::

   $plannedReleases = [
       '14.0.0' => '2025-11-25T00:00:00+01:00',
       '14.1.0' => '2026-01-20T00:00:00+01:00',
       '14.2.0' => '2026-03-31T00:00:00+02:00',
       '14.3.0' => '2026-04-21T00:00:00+02:00',
   ];

.. note::
   Actual releases from the API automatically override planned dates.

Custom Styling
--------------

To customize the roadmap chart appearance, you can add custom CSS::

   .nt-supporttimes-roadmap {
       /* Custom styles */
   }

   #typo3-roadmap-chart {
       /* Chart container styles */
   }

API Endpoints
=============

The extension fetches data from these official TYPO3 endpoints:

* **Releases:** ``https://get.typo3.org/json``
* **Metadata:** ``https://get.typo3.org/api/v1/major/{version}``

Ensure your server can access these URLs (no firewall blocking).
