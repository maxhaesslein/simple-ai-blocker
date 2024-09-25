<?php

// This file is part of Simple AI Blocker
// Copyright (C) 2024 maxhaesslein
// This program is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
// See the file LICENSE.md for more details.

namespace MH\SimpleAIBlocker;

if( ! defined('ABSPATH') ) exit;


function get_default_origin() {
	return 'REMOTE_ADDR';
}


function get_default_useragents() {

	$useragents = [
		'AdsBot-Google',
		'Amazonbot',
		'anthropic-ai',
		'Applebot-Extended',
		'Bytedance',
		'Bytespider',
		'CCBot',
		'ChatGPT-User',
		'Claude-Web',
		'ClaudeBot',
		'cohere-ai',
		'Diffbot',
		'facebook.com/externalhit',
		'FacebookBot',
		'FriendlyCrawler',
		'Google-Extended',
		'GoogleOther',
		'GPTBot',
		'ImagesiftBot',
		'img2dataset',
		'mj12bot',
		'omgili',
		'omgilibot',
		'PerplexityBot',
		'PiplBot',
		'semrushbot',
		'YouBot',
	];

	return implode( "\n", $useragents );
}


function get_default_json() {

	$urls = [
		'GPTbot' => 'https://openai.com/gptbot.json',
		'Perplexity AI Bot' => 'https://www.perplexity.ai/perplexitybot.json',
	];


	$return_string = "";

	foreach( $urls as $name => $url ) {
		if( $return_string ) $return_string .= "\n\n";
		$return_string .= "# ".$name;
		$return_string .= "\n".$url;
	}

	return $return_string;
}


function get_default_ip_ranges() {

	$bots = [

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

		// https://docs.yourgpt.ai/whitelisting-ai-crawler
		'YourGPT' => [
			'13.53.253.0/24',
		],

		'Bytespider' => [
			'220.243.188.0/23 ',
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
	return get_option( 'simpleaiblocker_settings_active' );
}


function get_json_urls( $string  = false ) {

	if( ! $string ) $string = get_option( 'simpleaiblocker_settings_json' );

	if( ! $string ) return [];

	$urls = [];

	$list = explode("\n", $string);
	
	$list = array_map('trim', $list);

	foreach( $list as $line ) {

		if( empty($line) ) continue;
		if( str_starts_with($line, '#') ) continue;

		$urls[] = $line;

	}

	return $urls;
}


function get_server_ip_origin(){

	$origins = get_option( 'simpleaiblocker_settings_origin' );

	if( ! $origins ) return [];

	$origins = explode(',', $origins);

	$origins = array_map('trim', $origins);

	return $origins;
}


function get_user_agents() {

	$useragents = get_option( 'simpleaiblocker_settings_useragents' );

	if( ! $useragents ) return [];

	$useragents = explode( "\n", $useragents );

	$useragents = array_map( 'trim', $useragents );

	$useragents = array_unique($useragents);
	$useragents = array_filter($useragents); // remove empty lines

	return $useragents;
}


function get_ip_ranges() {

	$settings = get_option( 'simpleaiblocker_settings_ipranges' );

	if( ! $settings ) return [];

	$ranges = [];

	$settings = explode("\n", $settings);

	foreach( $settings as $setting ) {

		$setting = trim($setting);

		if( empty($setting) ) continue;
		if( str_starts_with($setting, '#') ) continue;

		$setting = validate_cidr($setting);

		if( ! $setting ) return;

		$ranges[] = $setting;

	}

	return $ranges;
}


function get_all_ip_ranges() {

	$ip_ranges = get_ip_ranges();

	$json_ip_ranges = get_option( 'simpleaiblocker_settings_json_ipranges' );

	if( is_array($json_ip_ranges) && count($json_ip_ranges) ) {
		foreach( $json_ip_ranges as $json_ip_range ) {

			$ip_ranges = array_merge($ip_ranges, $json_ip_range['ip_ranges']);

		}
	}

	return $ip_ranges;
}
