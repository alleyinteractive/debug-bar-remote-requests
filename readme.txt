=== Debug Bar Remote Requests ===
Contributors: mboynes, alleyinteractive
Tags: debug bar, debug, http api, remote requests, api, curl
Requires at least: 3.5
Tested up to: 3.9
Stable tag: 0.1.2

An add-on for the Debug Bar plugin that will log and profile remote requests made through the HTTP API

== Description ==

An add-on for the Debug Bar plugin that will log and profile remote requests made through the HTTP API. This plugin requires the Debug Bar Plugin.

This plugin will add a "Remote Requests" panel to Debug Bar that will display the:

* Request method (GET, POST, etc)
* URL
* Time per request
* Total time for all requests
* Total number of requests

Optionally, you can add ?dbrr_full=1 to your URL to get additional information, including all request parameters and a full dump of the response with headers.


== Installation ==

1. Upload to the /wp-content/plugins/ directory
2. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= Have any questions been asked of this plugin yet? =

No.

== Screenshots ==

1. Example output


== Changelog ==

= 0.1.2 =
* Improved error handling

= 0.1.1 =
* Escaped all output
* Added i18n functions
* Improved efficiency when ?dbbr_full isn't present in URL

= 0.1.0 =
* Brand new

== Upgrade Notice ==

= 0.1.0 =
How can you be upgrading? This is brand new!
