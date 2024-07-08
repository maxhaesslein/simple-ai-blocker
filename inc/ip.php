<?php

namespace MH\AIBlocker;

if( ! defined('ABSPATH') ) exit;


function get_user_ip(){

	$origins = get_server_ip_origin();

	foreach( $origins as $origin ) {
		if( ! empty($_SERVER[$origin]) ) return $_SERVER[$origin];
	}

	return $_SERVER[get_default_origin()]; // default fallback
}


function validate_cidr( $cidr ) {
	
	// TODO: also check IPv6 addresses
	// TODO: also allow single IP addresses, without mask

	// Regular expression to check the basic format of CIDR notation
	if( ! preg_match( '/^([0-9]{1,3}\.){3}[0-9]{1,3}\/([0-9]|[1-2][0-9]|3[0-2])$/', $cidr, $matches ) ) {
		return false;
	}

	// Split the CIDR into IP address and subnet mask
	list( $ip, $mask ) = explode( '/', $cidr );

	// Validate the IP address
	if( ! filter_var( $ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 ) ) {
		return false;
	}

	// Validate the subnet mask (it should be an integer between 0 and 32)
	$mask = (int)$mask;
	if( $mask < 0 || $mask > 32 ) {
		return false;
	}

	return true;
}

function is_ip_in_cidr_ranges($user_ip, $cidr_ranges) {

	// TODO: also check IPv6 addresses
	// TODO: also allow single IP addresses, without mask

	// Convert user's IP address to long integer
	$user_ip_long = ip2long($user_ip);
	if ($user_ip_long === false) {
		return false; // Invalid IP address
	}

	// Iterate through each CIDR range
	foreach ($cidr_ranges as $cidr) {
		list($subnet, $mask) = explode('/', $cidr);

		// Convert subnet to long integer and calculate network address
		$subnet_long = ip2long($subnet);
		$mask_long = -1 << (32 - $mask);
		$subnet_long &= $mask_long; // Calculate network address

		// Check if user's IP is within the CIDR range
		if (($user_ip_long & $mask_long) == ($subnet_long & $mask_long)) {
			return true; // IP is within the CIDR range
		}
	}

	return false; // IP is not within any of the CIDR ranges
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
		// TODO: validate ipv4 range
		// TODO: support ipv6 range
		$ip_ranges[] = $prefix['ipv4Prefix'];
	}

	return $ip_ranges;
}
