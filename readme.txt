=== Simple AI Blocker ===
Tags: ai, blocking
Requires at least: 5.2
Tested up to: 6.5
Requires PHP: 8.0
License: GPL v3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Block AI Crawlers directly via their IP addresses or user-agents. The blocking happens directly on the server, and not via robots.txt, so it should also block crawlers that do not respect the robots.txt.

After installation you need to activate blocking on the plugin settings page. The default settings should suffice, but you can add additional IP addresses, user agents or even JSON endpoints with lists of ip ranges.
The visitor ip address server variable can also be customized, if your site is behind a reverse proxy and REMOTE_ADDR is not available.

If you want to delete all data the plugin saves in your database, activate the 'delete all plugin data on uninstall' option before uninstalling.

== Changelog ==

= 0.1.2 =
* added translation options, so the plugin should now be translateable
* added additional user agents to block
* updated texts on options page
* code cleanup and enhancements

= 0.1.1 =
* blocking now defaults to 'inactive'
* after plugin activation, a admin notice with a link to the settings page is shown
* option to delete all plugin data on deactivation
* bug fixes and enhancements

= 0.1.0 =
First pre-release. This plugin is in development; there may still be bugs. Use for testing only, not in production yet.

* completely block AI crawlers via their IP addresses or User-Agents
* use JSON endpoints to automatically get currently used IP ranges
* preconfigured to block ChatGPT and Perplexity AI, and a handfull of other AI Crawlers
* use 'Settings - AI Blocker Settings' to configure this plugin
* blocking happens as early as possible, to use almost no ressources if an AI crawler is detected
* this plugin may not yet be compatible with caching plugins

== Screenshots ==

1. The Settings Page
