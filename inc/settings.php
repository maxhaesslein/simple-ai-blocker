<?php

namespace MH\AIBlocker;

if( ! defined('ABSPATH') ) exit;


function get_default_ip_ranges() {

	$bots = [

		// https://openai.com/gptbot.json
		'GPTbot' => [
			'52.230.152.0/24',
			'52.233.106.0/24',
		],

		// https://platform.openai.com/docs/plugins/bot
		'ChatGPT-User bot' => [
			'40.84.180.224/28',
			'13.65.240.240/28',
			'23.98.142.176/28',
			'40.84.180.224/28',
			'13.65.240.240/28',
			'20.97.189.96/28',
			'20.161.75.208/28',
			'52.225.75.208/28',
			'52.156.77.144/28',
			'40.84.221.208/28',
			'40.84.221.224/28',
			'40.84.180.64/28',
		],

		// https://www.perplexity.ai/perplexitybot.json
		'Perplexity AI Bot' => [
			'54.90.207.250/32',
			'23.22.208.105/32',
			'54.242.1.13/32',
			'18.208.251.246/32',
			'34.230.5.59/32',
			'18.207.114.171/32',
			'54.221.7.250/32";',
		],

		// https://docs.yourgpt.ai/whitelisting-ai-crawler
		'YourGPT' => [
			'13.53.253.0/24',
		],
		
	];

	$return_string = "";

	foreach( $bots as $name => $ips ) {
		if( $return_string ) $return_string .= "\n\n";
		$return_string .= "# ".$name;
		foreach( $ips as $ip ) {
			$return_string .= "\n".$ip;
		}
	}

	return $return_string;
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
