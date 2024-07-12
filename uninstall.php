<?php

// This file is part of Simple AI Blocker
// Copyright (C) 2024 maxhaesslein
// This program is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
// See the file LICENSE.md for more details.

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) die;

$delete_all = get_option( 'simpleaiblocker_settings_deleteall' );

if( $delete_all ) {
	delete_option( 'simpleaiblocker_settings_active' );
	delete_option( 'simpleaiblocker_settings_json' );
	delete_option( 'simpleaiblocker_settings_json_schedule' );
	delete_option( 'simpleaiblocker_settings_ipranges' );
	delete_option( 'simpleaiblocker_settings_useragents' );
	delete_option( 'simpleaiblocker_settings_origin' );
	delete_option( 'simpleaiblocker_settings_deleteall' );
}
