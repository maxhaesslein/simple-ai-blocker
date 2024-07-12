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


function add_settings_link( $links ) {
	$settings_link = '<a href="'.esc_url(admin_url('options-general.php?page=simpleaiblocker_settings')).'">'.__( 'Settings', 'simple-ai-blocker' ).'</a>';
	array_unshift( $links, $settings_link );
	return $links;
}
add_filter( 'plugin_action_links_'.get_plugin_basename(), 'MH\SimpleAIBlocker\add_settings_link' );


function menu_item() {

	add_submenu_page(
		'options-general.php',
		__( 'Simple AI Blocker Settings', 'simple-ai-blocker' ),
		__( 'Simple AI Blocker', 'simple-ai-blocker' ),
		'manage_options',
		'simpleaiblocker_settings',
		'MH\SimpleAIBlocker\options_page',
	);

}
add_action( 'admin_menu', 'MH\SimpleAIBlocker\menu_item' );


function register_settings() {

	add_settings_section(
		'simpleaiblocker_settings',
		__( 'Settings', 'simple-ai-blocker' ),
		function(){ echo '<p>'.__( 'This plugins blocks known AI crawlers directly via their IP addresses or user agents, without a robots.txt.', 'simple-ai-blocker' ).'</p>'; },
		'simpleaiblocker_settings'
	);

	add_settings_field(
		'simpleaiblocker_settings_active',
		__( 'Status', 'simple-ai-blocker' ),
		function(){

			$active = get_option('simpleaiblocker_settings_active');

			?>
			<label><input type="checkbox" name="simpleaiblocker_settings_active"<?php if( $active ) echo ' checked'; ?>> <?= __( 'Blocking active', 'simple-ai-blocker' ) ?></label>
			<?php
		},
		'simpleaiblocker_settings',
		'simpleaiblocker_settings',
	);
	register_setting( 'simpleaiblocker_settings', 'simpleaiblocker_settings_active', [
		'default' => false
	] );

	add_settings_field(
		'simpleaiblocker_settings_json',
		__( 'JSON API Endpoints', 'simple-ai-blocker' ),
		function(){
			?>
			<label><textarea name="simpleaiblocker_settings_json" autocomplete="off" autocorrect="off" cols="40" rows="10" spellcheck="false" wrap="off"><?php echo esc_attr( get_option('simpleaiblocker_settings_json') ); ?></textarea></label>
			<p><small><?= __( 'one link to an json api endpoint per line; the format of the json endpoint should be an IP prefixes list', 'simple-ai-blocker' ) ?></small></p>
			<p><a href="#" onclick="simpleaiblocker_settings_json_reset()"><?= __( 'Reset to default', 'simple-ai-blocker' ) ?></a></p>
			<script>
			function simpleaiblocker_settings_json_reset(){
				var textarea = document.querySelector('textarea[name="simpleaiblocker_settings_json"]');
				textarea.value = '<?= str_replace("\n", '\n', get_default_json()) ?>';
			};
			</script>
			<?php
		},
		'simpleaiblocker_settings',
		'simpleaiblocker_settings',
	);
	register_setting( 'simpleaiblocker_settings', 'simpleaiblocker_settings_json', [
		'default' => get_default_json(),
		'sanitize_callback' => 'MH\SimpleAIBlocker\json_save',
	] );

	add_settings_field(
		'simpleaiblocker_settings_json_schedule',
		__( 'JSON Update Schedule', 'simple-ai-blocker' ),
		function(){
			$schedule = get_option('simpleaiblocker_settings_json_schedule');
			$allowed_schedules = get_allowed_json_cron_schedules();
			?>
			<label><select name="simpleaiblocker_settings_json_schedule">
				<option value=""><?= __('disabled') ?></option>
				<?php
				foreach( $allowed_schedules as $allowed_schedule => $allowed_schedule_title ) {
					?>
					<option value="<?= $allowed_schedule ?>"<?php if( $schedule == $allowed_schedule ) echo ' selected'; ?>><?= $allowed_schedule_title ?></option>
					<?php
				}
				?>
			</select></label>
			<p><small><?= __( 'automatically refresh the ip ranges from JSON links', 'simple-ai-blocker' ) ?></small></p>
			<?php
		},
		'simpleaiblocker_settings',
		'simpleaiblocker_settings'
	);
	register_setting( 'simpleaiblocker_settings', 'simpleaiblocker_settings_json_schedule', [
		'default' => 'weekly',
		'sanitize_callback' => 'MH\SimpleAIBlocker\json_schedule_update'
	] );

	add_settings_field(
		'simpleaiblocker_settings_ipranges',
		__( 'IP Ranges to Block', 'simple-ai-blocker' ),
		function(){
			?>
			<label><textarea name="simpleaiblocker_settings_ipranges" autocomplete="off" autocorrect="off" cols="40" rows="10" spellcheck="false" wrap="off"><?php echo esc_attr( get_option('simpleaiblocker_settings_ipranges') ); ?></textarea></label>
			<p><small><?= __( 'one IP address per line; can be a single IP address, or a IP range in CIDR notation with suffix. IPv4 only for now.', 'simple-ai-blocker' ) ?></small></p>
			<p><a href="#" onclick="simpleaiblocker_settings_ipranges_reset()"><?= __( 'Reset to default', 'simple-ai-blocker' ) ?></a></p>
			<script>
			function simpleaiblocker_settings_ipranges_reset(){
				var textarea = document.querySelector('textarea[name="simpleaiblocker_settings_ipranges"]');
				textarea.value = '<?= str_replace("\n", '\n', get_default_ip_ranges()) ?>';
			};
			</script>
			<?php
		},
		'simpleaiblocker_settings',
		'simpleaiblocker_settings',
	);
	register_setting( 'simpleaiblocker_settings', 'simpleaiblocker_settings_ipranges', [
		'default' => get_default_ip_ranges(),
		'sanitize_callback' => 'MH\SimpleAIBlocker\ipranges_update'
	] );

	add_settings_field(
		'simpleaiblocker_settings_useragents',
		__( 'User-Agents to Block', 'simple-ai-blocker' ),
		function(){
			?>
			<label><textarea name="simpleaiblocker_settings_useragents" autocomplete="off" autocorrect="off" cols="40" rows="10" spellcheck="false" wrap="off"><?php echo esc_attr( get_option('simpleaiblocker_settings_useragents') ); ?></textarea></label>
			<p><small><?= __( 'one user agent per line; case-insensitive', 'simple-ai-blocker' ) ?></small></p>
			<p><a href="#" onclick="simpleaiblocker_settings_useragents_reset()"><?= __( 'Reset to default', 'simple-ai-blocker' ) ?></a></p>
			<script>
			function simpleaiblocker_settings_useragents_reset(){
				var textarea = document.querySelector('textarea[name="simpleaiblocker_settings_useragents"]');
				textarea.value = '<?= str_replace("\n", '\n', get_default_useragents()) ?>';
			};
			</script>
			<?php
		},
		'simpleaiblocker_settings',
		'simpleaiblocker_settings',
	);
	register_setting( 'simpleaiblocker_settings', 'simpleaiblocker_settings_useragents', [
		'default' => get_default_useragents(),
	] );

	add_settings_field(
		'simpleaiblocker_settings_origin',
		__( 'IP Address Server Variable', 'simple-ai-blocker' ),
		function(){
			?>
			<label><input type="text" name="simpleaiblocker_settings_origin" spellcheck="false" autocomplete="off" autocorrect="off" value="<?php echo esc_attr( get_option('simpleaiblocker_settings_origin') ); ?>"></label>
			<p><small><?php printf( __( 'Specify the origins you trust in order of priority, separated by commas. You should use %1$s, because other origins can be easily faked. Examples: %2$s.', 'simple-ai-blocker' ), '<code>REMOTE_ADDR</code>', '<code>HTTP_X_FORWARDED_FOR</code>, <code>HTTP_CF_CONNECTING_IP</code>, <code>HTTP_X_SUCURI_CLIENTIP</code>'  ); ?></small></p>
			<p><a href="#" onclick="simpleaiblocker_settings_origin_reset()"><?= __( 'Reset to default', 'simple-ai-blocker' ) ?></a></p>
			<script>
			function simpleaiblocker_settings_origin_reset(){
				var input = document.querySelector('input[name="simpleaiblocker_settings_origin"]');
				input.value = '<?= get_default_origin() ?>';
			};
			</script>
			<?php
		},
		'simpleaiblocker_settings',
		'simpleaiblocker_settings',
	);
	register_setting( 'simpleaiblocker_settings', 'simpleaiblocker_settings_origin', [
		'default' => get_default_origin(),
	] );

	add_settings_field(
		'simpleaiblocker_settings_deleteall',
		__( 'Cleanup', 'simple-ai-blocker' ),
		function(){

			$active = get_option('simpleaiblocker_settings_deleteall');

			?>
			<label><input type="checkbox" name="simpleaiblocker_settings_deleteall"<?php if( $active ) echo ' checked'; ?>> <?= __( 'delete all plugin data on uninstall', 'simple-ai-blocker' ) ?></label>
			<?php
		},
		'simpleaiblocker_settings',
		'simpleaiblocker_settings',
	);
	register_setting( 'simpleaiblocker_settings', 'simpleaiblocker_settings_deleteall', [
		'default' => false
	] );

}
add_action( 'admin_init', 'MH\SimpleAIBlocker\register_settings' );


function json_save( $value ) {

	// TODO: validate urls on save

	$urls = get_json_urls($value);

	update_url_ip_ranges($urls);

	return $value;
}


function json_schedule_update( $input ) {

	$success = update_cron( $input );

	if( ! $success ) return '';

	return $input;
}


function ipranges_update( $input ) {

	$input = explode("\n", $input);

	for( $i = 0; $i < count($input); $i++ ) {

		$line = $input[$i];

		if( ! empty($line) && ! str_starts_with($line, '#') ) {
			$line = validate_cidr($line);
		}

		$input[$i] = $line;
	}

	$input = implode("\n", $input);
	return $input;
}


function options_page(){

	?>
	<div class="wrap">

		<h1><?= __( 'Simple AI Blocker', 'simple-ai-blocker' ) ?></h1>

		<form method="post" action="<?= esc_url( admin_url('options.php') ) ?>">
			<?php

			settings_fields( 'simpleaiblocker_settings' );

			do_settings_sections( 'simpleaiblocker_settings' );
			
			submit_button();

			?>
		</form>

	</div>
	<?php

}


function deactivate_blocking(){
	delete_option( 'simpleaiblocker_settings_active' );
}


function show_activation_message() {
	set_transient( 'simpleaiblocker_activation_message', true, 30*MINUTE_IN_SECONDS );
}


function activation_message() {

	if( ! get_transient( 'simpleaiblocker_activation_message' ) ) return;

	$text_with_placeholder = __( 'AI Blocker is successfully installed! Go to the %s to activate blocking.', 'simple-ai-blocker' );
	$link = '<a href="'.esc_url(admin_url('options-general.php?page=simpleaiblocker_settings')).'">'.__( 'settings page', 'simple-ai-blocker' ).'</a>';
	$text = sprintf($text_with_placeholder, $link);

	?>
	<div class="notice notice-success">
		<p><?= $text ?></p>
	</div>
	<?php

	delete_transient( 'simpleaiblocker_activation_message' );
}
add_action( 'admin_notices', 'MH\SimpleAIBlocker\activation_message' );
