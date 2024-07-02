<?php

namespace MH\AIBlocker;

if( ! defined('ABSPATH') ) exit;


function validate_cidr( $cidr ) {
	
	// TODO: also check IPv6 addresses

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
