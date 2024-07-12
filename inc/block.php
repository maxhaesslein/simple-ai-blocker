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


function block_ipranges() {

	if( is_admin() ) return;

	$blocking_active = get_blocking_state();

	if( ! $blocking_active ) return;

	$user_ip = get_user_ip();
	$cidr_ranges = get_all_ip_ranges();

	if( ! is_ip_in_cidr_ranges($user_ip, $cidr_ranges) ) return;

	block_access();
}
add_action( 'plugins_loaded', 'MH\SimpleAIBlocker\block_ipranges' );


function block_useragents() {

	if( is_admin() ) return;

	$blocking_active = get_blocking_state();

	if( ! $blocking_active ) return;

	$user_agent = get_user_agent();

	$user_agents_to_block = get_user_agents();

	$block = false;
	foreach( $user_agents_to_block as $user_agent_to_block ) {

		if( stripos( $user_agent, $user_agent_to_block ) === false ) continue;

		$block = true;
		break;
	}

	if( ! $block ) return;

	block_access();
}
add_action( 'plugins_loaded', 'MH\SimpleAIBlocker\block_useragents' );


function block_access(){
	status_header(403);
	nocache_headers();
	wp_die( esc_html__("Forbidden - You don't have permission to access this file.", 'simple-ai-blocker'), esc_html__('Forbidden', 'simple-ai-blocker'), [
		'response' => 403,
		'code' => 403
	] );
}
