=== MH AI Blocker ===
Tags: ai, blocking
Requires at least: 5.2
Tested up to: 6.5
Requires PHP: 8.0
License: GPL v3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Block AI Crawlers directly via their IP addresses. Currently in development, more information will follow.

== Changelog ==

= 0.1.1 =
* blocking now defaults to inactive
* after plugin activation, a admin notice with a link to the settings page is shown
* option to delete all plugin data deactivation
* bug fixes and enhancements

= 0.1.0 =
First pre-release. This plugin is in development; there may still be bugs. Use for testing only, not in production yet.

* completely block AI crawlers via their IP addresses or User-Agents
* use JSON endpoints to automatically get currently used IP ranges
* preconfigured to block ChatGPT and Perplexity AI, and a handfull of other AI Crawlers
* use 'Settings - AI Blocker Settings' to configure this plugin
* blocking happens as early as possible, to use almost no ressources if an AI crawler is detected
* this plugin may not yet be compatible with caching plugins
