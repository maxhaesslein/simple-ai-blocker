<?php

namespace MH\AIBlocker;

if( ! defined('ABSPATH') ) exit;


 function add_additional_cron_schedules( $schedules ) {
	$schedules['monthly'] = array(
		'interval' => 2628000,
		'display' => __( 'Once Monthly' )
	);
	return $schedules;
}
add_filter( 'cron_schedules', 'MH\AIBlocker\add_additional_cron_schedules' );


function get_allowed_json_cron_schedules() {
	$allowed_values = [
		'monthly' => __('Once Monthly'),
		'weekly' => __('Once Weekly'),
		'daily' => __('Once Daily'),
	];

	return $allowed_values;
}


function update_cron( $new_value ) {
	// this gets called after a schedule setting was changed

	$allowed_values = get_allowed_json_cron_schedules();

	$next_timestamp = wp_next_scheduled( 'mh_aiblocker_json_cronjob' );
	if( $next_timestamp ) {
		// we already have an event scheduled; remove it:
		wp_unschedule_event( $next_timestamp, 'mh_aiblocker_json_cronjob' );
	}

	if( ! array_key_exists($new_value, $allowed_values) ) return false; // set to disabled

	return wp_schedule_event( time(), $new_value, 'mh_aiblocker_json_cronjob' );
}


function execute_cronjob() {
	update_url_ip_ranges();
}
add_action( 'mh_aiblocker_json_cronjob', 'MH\AIBlocker\execute_cronjob' );


function remove_all_cronjobs() {
	// this runs on plugin deactivation and removes all pending scheduled events
	wp_unschedule_hook( 'mh_aiblocker_json_cronjob' );
}
