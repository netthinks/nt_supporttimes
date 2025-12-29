.. include:: /Includes.rst.txt

.. _privacy:

=======
Privacy
=======

External Requests
-----------------

This extension performs HTTP requests to `https://get.typo3.org` to fetch the latest release data.

*   **Data Sent:** No personal data is sent. Only a standard HTTP GET request.
*   **Purpose:** Retrieve public lifecycle information.
*   **Frequency:** Requests are cached according to `cacheLifetime` (default 24h).

GDPR / DSGVO
------------

Since no user-specific data is transmitted or stored, this extension is GDPR compliant by default regarding its own operations.

Cookies
-------

This extension does not set any cookies.

assets
------

All assets (CSS/JS) needed for the widget are loaded from the local TYPO3 installation. No 3rd party CDNs are used.
