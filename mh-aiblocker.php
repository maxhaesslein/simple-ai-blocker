<?php
/*
Plugin Name: MH AI Blocker
Description: TODO
Version: 0.1.0-dev1
Author: Max Häßlein
Author URI: https://www.maxhaesslein.de
Requires at least: 5.2
Requires PHP: 8.0
*/

namespace MH\AIBlocker;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


include_once( 'inc/block.php' );
include_once( 'inc/ip.php' );
include_once( 'inc/options-page.php' );
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
