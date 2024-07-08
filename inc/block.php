<?php

namespace MH\AIBlocker;

if( ! defined('ABSPATH') ) exit;


function block() {

	$blocking_active = get_blocking_state();

	if( ! $blocking_active ) return;

	$user_ip = get_user_ip();
	$cidr_ranges = get_all_ip_ranges();

	if( ! is_ip_in_cidr_ranges($user_ip, $cidr_ranges) ) return;

	status_header(403);
	nocache_headers();
	wp_die( "Forbidden - You don't have permission to access this file.", 'Forbidden', [
		'response' => 403,
		'code' => 403
	] );

}
add_action( 'plugins_loaded', 'MH\AIBlocker\block' );
