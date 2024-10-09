=== Simple AI Blocker ===
Contributors: maxhaesslein
Tags: ai, blocking, ai-crawler
Requires at least: 5.2
Tested up to: 6.6
Requires PHP: 8.0
License: GPL v3
License URI: https://www.gnu.org/licenses/gpl-3.0.html
Stable Tag: 0.2.2

Block AI Crawlers directly via their IP addresses or user-agents.

== Description ==

Block AI Crawlers directly via their IP addresses or user-agents. The blocking happens directly on the server, and not via robots.txt, so it should also block crawlers that do not respect the robots.txt.

This plugin may not work if you use a caching plugin that hooks before the 'plugins_loaded' hook. I may add compatibility in a future update.

After installation you need to activate blocking on the plugin settings page. The default settings should suffice, but you can add additional IP addresses, user agents or even JSON endpoints with lists of ip ranges.
The visitor ip address server variable can also be customized, if your site is behind a reverse proxy and REMOTE_ADDR is not available.

If you want to delete all data the plugin saves in your database, activate the 'delete all plugin data on uninstall' option before uninstalling.

== Changelog ==

= 0.2.2 =
* moved admin JavaScript to own file
* code enhancements and fixes

= 0.2.1 =
* tested up to WordPress v.6.6

= 0.2.0 =
* first stable release
* prepared plugin for release in WP Plugin Directory
* code enhancements and fixes

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

== Installation ==
1. Install and activate the plugin through the ‘Plugins’ menu in WordPress
2. Once installed, go to the plugins setting page, activate the 'blocking active' option and save the options
3. The 'blocking active' options gets automatically disabled when you deactivate the plugin, however, by default, all other options will persist
4. If you want to delete all data the plugin saves in your database, activate the 'delete all plugin data on uninstall' option before uninstalling

== Screenshots ==

1. The Settings Page
