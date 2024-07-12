<?php
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) die;

$delete_all = get_option( 'mh_aiblocker_settings_deleteall' );

if( $delete_all ) {
	delete_option( 'mh_aiblocker_settings_active' );
	delete_option( 'mh_aiblocker_settings_json' );
	delete_option( 'mh_aiblocker_settings_json_schedule' );
	delete_option( 'mh_aiblocker_settings_ipranges' );
	delete_option( 'mh_aiblocker_settings_useragents' );
	delete_option( 'mh_aiblocker_settings_origin' );
	delete_option( 'mh_aiblocker_settings_deleteall' );
}
