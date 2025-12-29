.. include:: /Includes.rst.txt

.. _start:

=======================
TYPO3 Support Roadmap
=======================

:Extension Key:
    nt_supporttimes

:Version:
    1.0.0

:Language:
    en

:Description:
    Displays TYPO3 support times, lifecycle information, and interactive roadmap charts in backend and frontend.

:Keywords:
    dashboard, support, elts, lifecycle, roadmap, timeline, widget, plugin

:Copyright:
    2023-2025

:Author:
    NET.THINKS

:License:
    This document is published under the Open Content License available from http://www.opencontent.org/opl.shtml

**Table of Contents**

.. toctree::
   :maxdepth: 2
   :titlesonly:

   Installation
   Configuration
   UserManual
   Privacy
   Targets

Introduction
============

This extension provides comprehensive TYPO3 support lifecycle visualization through:

* **Dashboard Widget:** Overview of all TYPO3 versions with support status
* **Frontend Roadmap Plugin:** Interactive timeline chart showing support phases
* **Live Data:** Fetches from official TYPO3 API (get.typo3.org)
* **Sprint Releases:** Displays pre-LTS sprint releases and stabilization phases
* **Multilingual:** Full English and German translations

Features
--------

Dashboard Widget
~~~~~~~~~~~~~~~~

* Color-coded status badges (Active, Expiring, ELTS, Expired)
* Scrollable table for all TYPO3 versions
* Latest release information with dates
* Support end dates for official and ELTS support

Frontend Roadmap
~~~~~~~~~~~~~~~~

* Interactive ApexCharts timeline
* Visual support phases:
  
  * Sprint Releases (gray)
  * Regular Maintenance (green)
  * Priority/Security Support (orange)
  * ELTS Support (light orange)
  * Extended Partner Support (beige)

* Configurable chart height via FlexForm
* Version filter to show specific TYPO3 versions
* Planned future releases (TYPO3 14)
* Responsive design

Configuration
~~~~~~~~~~~~~

* Select which TYPO3 versions to display
* Enable/disable ELTS information
* Configurable cache lifetime
* Per-plugin version filtering

Screenshots
-----------

.. image:: ../Resources/Public/Screenshots/widget.png
   :alt: Dashboard Widget Screenshot
   :class: with-shadow

.. image:: ../Resources/Public/Screenshots/roadmap.png
   :alt: Frontend Roadmap Chart
   :class: with-shadow
