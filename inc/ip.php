<?php

namespace MH\AIBlocker;

if( ! defined('ABSPATH') ) exit;


function get_user_agent() {

	if( empty($_SERVER['HTTP_USER_AGENT']) ) return false;

	return $_SERVER['HTTP_USER_AGENT'];
}


function get_user_ip(){

	$origins = get_server_ip_origin();

	foreach( $origins as $origin ) {
		if( ! empty($_SERVER[$origin]) ) return $_SERVER[$origin];
	}

	return $_SERVER[get_default_origin()]; // default fallback
}


function validate_cidr( $cidr ) {
	
	// TODO: also check IPv6 addresses

	$explode = explode( '/', $cidr );

	if( count($explode) < 1 ) return false;

	$ip = $explode[0];

	if( count($explode) > 1 ) {
		$mask = (int) $explode[1];
	} else {
		$mask = 32; // default to one ip address, if nothing is provided
	}

	if( ! preg_match( '/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $ip, $matches ) ) {
		return false;
	}

	if( ! filter_var( $ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 ) ) {
		return false;
	}

	if( $mask < 0 || $mask > 32 ) {
		return false;
	}

	// return sanitized cidr
	return $ip.'/'.$mask;
}

function is_ip_in_cidr_ranges($user_ip, $cidr_ranges) {

	// TODO: also check IPv6 addresses

	$user_ip_long = ip2long($user_ip);
	if ($user_ip_long === false) {
		return false;
	}

	foreach ($cidr_ranges as $cidr) {
		list($subnet, $mask) = explode('/', $cidr);

		$subnet_long = ip2long($subnet);
		$mask_long = -1 << (32 - $mask);
		$subnet_long &= $mask_long;

		if( ($user_ip_long & $mask_long) == ($subnet_long & $mask_long) ) {
			return true;
		}
	}

	return false;
}


function update_url_ip_ranges( $urls = false ) {

	if( ! $urls ) $urls = get_json_urls();

	if( ! is_array($urls) || ! count($urls) ) return;

	$ip_ranges = [];

	foreach( $urls as $url ) {

		$additional_ip_ranges = get_remote_ip_ranges( $url );

		if( ! is_array($additional_ip_ranges) || ! count($additional_ip_ranges) ) continue;

		$ip_ranges[] = [
			'url' => $url,
			'ip_ranges' => $additional_ip_ranges,
		];

	}

	update_option( 'mh_aiblocker_settings_json_ipranges', $ip_ranges, true );
}


function get_remote_ip_ranges( $url ) {

	$request = wp_remote_get( $url );

	if( is_wp_error( $request ) ) return false;

	$body = wp_remote_retrieve_body( $request );

	$data = json_decode( $body, true );

	if( empty( $data ) ) return false;
	
	if( empty($data['prefixes']) ) return false; // TODO: check, if there are any other formats we want to support

	$ip_ranges = [];

	foreach( $data['prefixes'] as $prefix ) {
		
		// TODO: support ipv6 range

		$ip_range = $prefix['ipv4Prefix'];

		$ip_range = validate_cidr($ip_range);

		if( ! $ip_range ) continue;

		$ip_ranges[] = $ip_range;

	}

	return $ip_ranges;
}
