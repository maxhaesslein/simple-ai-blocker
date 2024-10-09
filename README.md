# 'Simple AI Blocker' WordPress Plugin

Block AI Crawlers directly via their IP addresses or user-agents. The blocking happens directly on the server, and not via robots.txt, so it should also block crawlers that do not respect the robots.txt.

This plugin may not work if you use a caching plugin that hooks before the 'plugins_loaded' hook. I may add compatibility in a future update.

After installation you need to activate blocking on the plugin settings page. The default settings should suffice, but you can add additional IP addresses, user agents or even JSON endpoints with lists of ip ranges.
The visitor ip address server variable can also be customized, if your site is behind a reverse proxy and REMOTE_ADDR is not available.

If you want to delete all data the plugin saves in your database, activate the 'delete all plugin data on uninstall' option before uninstalling.

## Installation

1. Install and activate the plugin through the `Plugins` menu in WordPress
2. Once installed, go to the plugins setting page, activate the `blocking active` option and save the options
3. The `blocking active` options gets automatically disabled when you deactivate the plugin, however, by default, all other options will persist
4. If you want to delete all data the plugin saves in your database, activate the `delete all plugin data on uninstall` option before uninstalling