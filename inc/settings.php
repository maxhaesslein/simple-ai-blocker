<?php

namespace MH\AIBlocker;

if( ! defined('ABSPATH') ) exit;


# GBT Bot ranges: https://openai.com/gptbot-ranges.txt and https://platform.openai.com/docs/plugins/bot
# Perplexity Bot: https://web.archive.org/web/20240616105916/https://docs.perplexity.ai/docs/perplexitybot and https://web.archive.org/web/20240615195252/https://www.perplexity.ai/perplexitybot.json
function get_default_ip_ranges() {
	return "# GPTbot\n52.230.152.0/24\n52.233.106.0/24\n\n# ChatGPT-User bot\n40.84.180.224/28\n13.65.240.240/28\n23.98.142.176/28\n40.84.180.224/28\n13.65.240.240/28\n20.97.189.96/28\n20.161.75.208/28\n52.225.75.208/28\n52.156.77.144/28\n40.84.221.208/28\n40.84.221.224/28\n40.84.180.64/28\n\n# Perplexity AI Bot\n54.90.207.250/32\n23.22.208.105/32\n54.242.1.13/32\n18.208.251.246/32\n34.230.5.59/32\n18.207.114.171/32\n54.221.7.250/32";
}


function get_blocking_state(){
	return get_option('mh_aiblocker_settings_active');
}


function get_server_ip_origin(){
	$origins = get_option('mh_aiblocker_settings_origin');

	if( ! $origins ) return [];

	$origins = explode(',', $origins);

	$origins = array_map('trim', $origins);

	return $origins;
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
