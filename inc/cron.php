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


 function add_additional_cron_schedules( $schedules ) {
	$schedules['monthly'] = array(
		'interval' => 2628000,
		'display' => __( 'Once Monthly', 'simple-ai-blocker' )
	);
	return $schedules;
}
add_filter( 'cron_schedules', 'MH\SimpleAIBlocker\add_additional_cron_schedules' );


function get_allowed_json_cron_schedules() {
	$allowed_values = [
		'monthly' => __( 'Once Monthly', 'simple-ai-blocker' ),
		'weekly' => __( 'Once Weekly', 'simple-ai-blocker' ),
		'daily' => __( 'Once Daily', 'simple-ai-blocker' ),
	];

	return $allowed_values;
}


function update_cron( $new_value ) {
	// this gets called after a schedule setting was changed

	$allowed_values = get_allowed_json_cron_schedules();

	$next_timestamp = wp_next_scheduled( 'simpleaiblocker_json_cronjob' );
	if( $next_timestamp ) {
		// we already have an event scheduled; remove it:
		wp_unschedule_event( $next_timestamp, 'simpleaiblocker_json_cronjob' );
	}

	if( ! array_key_exists($new_value, $allowed_values) ) return false; // set to disabled

	return wp_schedule_event( time(), $new_value, 'simpleaiblocker_json_cronjob' );
}


function execute_cronjob() {
	update_url_ip_ranges();
}
add_action( 'simpleaiblocker_json_cronjob', 'MH\SimpleAIBlocker\execute_cronjob' );


function remove_all_cronjobs() {
	// this runs on plugin deactivation and removes all pending scheduled events
	wp_unschedule_hook( 'simpleaiblocker_json_cronjob' );
}
