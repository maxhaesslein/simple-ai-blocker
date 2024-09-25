<?php
/*

Simple AI Blocker - WordPress Plugin to block AI Crawlers directly via their IP addresses or user agents.
Copyright (C) 2024 maxhaesslein (https://www.maxhaesslein.de)

Plugin Name: Simple AI Blocker
Plugin URI: https://github.com/maxhaesslein/simple-ai-blocker
Description: Block AI Crawlers directly via their IP addresses or user agents.
Version: 0.2.2
Author: maxhaesslein
Author URI: https://www.maxhaesslein.de
License: GPL v3
License URI: https://www.gnu.org/licenses/gpl-3.0.html
Requires at least: 5.2
Tested up to: 6.6
Requires PHP: 8.0
Text Domain: simple-ai-blocker


This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version. 

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program. If not, see <https://www.gnu.org/licenses/>.

*/


namespace MH\SimpleAIBlocker;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


include_once( 'inc/block.php' );
include_once( 'inc/cron.php' );
include_once( 'inc/ip.php' );
include_once( 'inc/options-page.php' );
include_once( 'inc/scripts.php' );
include_once( 'inc/settings.php' );


function get_plugin_version(){

	if( ! function_exists('get_plugin_data') ){
		require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	}

	$plugin_data = get_plugin_data( __FILE__ );

	if( empty($plugin_data['Version']) ) return false;

	$version = $plugin_data['Version'];

	return $version;
}

function get_plugin_path( $append_path = false ){

	$path = plugin_dir_path(__FILE__);

	if( $append_path ) $path = trailingslashit($path).$append_path;

	return $path;
}

function get_plugin_url( $append_path = false ){

	$url = plugin_dir_url(__FILE__);

	if( $append_path ) $url = trailingslashit($url).$append_path;

	return $url;
}


function get_plugin_basename() {
	return plugin_basename(__FILE__);
}


register_activation_hook( __FILE__, 'MH\SimpleAIBlocker\show_activation_message' );

register_deactivation_hook( __FILE__, 'MH\SimpleAIBlocker\remove_all_cronjobs' );
register_deactivation_hook( __FILE__, 'MH\SimpleAIBlocker\deactivate_blocking' );
