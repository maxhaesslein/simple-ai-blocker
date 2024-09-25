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


function enqueue_admin_js( $hook ){

	if( $hook != 'settings_page_simpleaiblocker_settings' ) return;

	wp_enqueue_script( 'simpleaiblocker-options-page', get_plugin_url('assets/js/options-page.js'), array(), '1.0', array('in_footer' => true) );

}
add_action( 'admin_enqueue_scripts', 'MH\SimpleAIBlocker\enqueue_admin_js' );
