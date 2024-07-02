<?php

namespace MH\AIBlocker;

if( ! defined('ABSPATH') ) exit;


function get_user_ip(){
	return $_SERVER['REMOTE_ADDR'];
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

