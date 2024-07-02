<?php

namespace MH\AIBlocker;

if( ! defined('ABSPATH') ) exit;


function get_default_ip_ranges() {
	return "# GPTbot\n52.230.152.0/24\n52.233.106.0/24\n\n# ChatGPT-User bot\n23.98.142.176/28\n40.84.180.224/28\n13.65.240.240/28";
}


function get_ip_ranges() {

	$settings = get_option('mh_aiblocker_settings_ipranges');

	if( ! $settings ) return [];

	$ranges = [];

	$settings = explode("\n", $settings);

	foreach( $settings as $setting ) {

		$setting = trim($setting);

		if( empty($setting) ) continue;
		if( str_starts_with($setting, '#') ) continue;

		if( ! validate_cidr($setting) ) continue;

		$ranges[] = $setting;

	}

	return $ranges;
}
