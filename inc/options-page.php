<?php

// This file is part of MH AI Blocker
// Copyright (C) 2024 maxhaesslein
// This program is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
// See the file LICENSE.md for more details.

namespace MH\AIBlocker;

if( ! defined('ABSPATH') ) exit;


function add_settings_link( $links ) {
	$settings_link = '<a href="'.esc_url(admin_url('options-general.php?page=mh_aiblocker_settings')).'">Settings</a>';
	array_unshift( $links, $settings_link );
	return $links;
}
add_filter( 'plugin_action_links_'.get_plugin_basename(), 'MH\AIBlocker\add_settings_link' );


function menu_item() {

	add_submenu_page(
		'options-general.php',
		'AI Blocker Settings',
		'AI Blocker Settings',
		'manage_options',
		'mh_aiblocker_settings',
		'MH\AIBlocker\options_page',
	);

}
add_action( 'admin_menu', 'MH\AIBlocker\menu_item' );


function register_settings() {

	add_settings_section(
		'mh_aiblocker_settings',
		'Settings',
		function(){ echo '<p>This plugins blocks known AI crawlers directly via their IP addresses. You can additionally use a plugin that blocks AI crawlers via a robots.txt to get more protection.</p>'; },
		'mh_aiblocker_settings'
	);

	add_settings_field(
		'mh_aiblocker_settings_active',
		'Status',
		function(){

			$active = get_option('mh_aiblocker_settings_active');

			?>
			<label><input type="checkbox" name="mh_aiblocker_settings_active"<?php if( $active ) echo ' checked'; ?>> Blocking active</label>
			<?php
		},
		'mh_aiblocker_settings',
		'mh_aiblocker_settings',
	);
	register_setting( 'mh_aiblocker_settings', 'mh_aiblocker_settings_active', [
		'default' => false
	] );

	add_settings_field(
		'mh_aiblocker_settings_json',
		'JSON Links with IP Ranges',
		function(){
			?>
			<label><textarea name="mh_aiblocker_settings_json" autocomplete="off" autocorrect="off" cols="40" rows="10" spellcheck="false" wrap="off"><?php echo esc_attr( get_option('mh_aiblocker_settings_json') ); ?></textarea></label>
			<p><small>one link to an json endpoint per line. the format of the json endpoint should be an IP prefixes list / network configuration JSON</small></p>
			<p><a href="#" onclick="mh_aiblocker_settings_json_reset()">Reset to default</a></p>
			<script>
			function mh_aiblocker_settings_json_reset(){
				var textarea = document.querySelector('textarea[name="mh_aiblocker_settings_json"]');
				textarea.value = '<?= str_replace("\n", '\n', get_default_json()) ?>';
			};
			</script>
			<?php
		},
		'mh_aiblocker_settings',
		'mh_aiblocker_settings',
	);
	register_setting( 'mh_aiblocker_settings', 'mh_aiblocker_settings_json', [
		'default' => get_default_json(),
		'sanitize_callback' => 'MH\AIBlocker\json_save',
	] );

	add_settings_field(
		'mh_aiblocker_settings_json_schedule',
		'JSON Update Schedule',
		function(){
			$schedule = get_option('mh_aiblocker_settings_json_schedule');
			$allowed_schedules = get_allowed_json_cron_schedules();
			?>
			<label><select name="mh_aiblocker_settings_json_schedule">
				<option value=""><?= __('disabled') ?></option>
				<?php
				foreach( $allowed_schedules as $allowed_schedule => $allowed_schedule_title ) {
					?>
					<option value="<?= $allowed_schedule ?>"<?php if( $schedule == $allowed_schedule ) echo ' selected'; ?>><?= $allowed_schedule_title ?></option>
					<?php
				}
				?>
			</select></label>
			<p><small>automatically refresh the ip ranges from JSON links</small></p>
			<?php
		},
		'mh_aiblocker_settings',
		'mh_aiblocker_settings'
	);
	register_setting( 'mh_aiblocker_settings', 'mh_aiblocker_settings_json_schedule', [
		'default' => 'weekly',
		'sanitize_callback' => 'MH\AIBlocker\json_schedule_update'
	] );

	add_settings_field(
		'mh_aiblocker_settings_ipranges',
		'IP Ranges to Block',
		function(){
			?>
			<label><textarea name="mh_aiblocker_settings_ipranges" autocomplete="off" autocorrect="off" cols="40" rows="10" spellcheck="false" wrap="off"><?php echo esc_attr( get_option('mh_aiblocker_settings_ipranges') ); ?></textarea></label>
			<p><small>one IP address per line; can be a single IP address, or a IP range in CIDR notation with suffix. IPv4 only.</small></p>
			<p><a href="#" onclick="mh_aiblocker_settings_ipranges_reset()">Reset to default</a></p>
			<script>
			function mh_aiblocker_settings_ipranges_reset(){
				var textarea = document.querySelector('textarea[name="mh_aiblocker_settings_ipranges"]');
				textarea.value = '<?= str_replace("\n", '\n', get_default_ip_ranges()) ?>';
			};
			</script>
			<?php
		},
		'mh_aiblocker_settings',
		'mh_aiblocker_settings',
	);
	register_setting( 'mh_aiblocker_settings', 'mh_aiblocker_settings_ipranges', [
		'default' => get_default_ip_ranges(),
		'sanitize_callback' => 'MH\AIBlocker\ipranges_update'
	] );

	add_settings_field(
		'mh_aiblocker_settings_useragents',
		'User-Agents to Block',
		function(){
			?>
			<label><textarea name="mh_aiblocker_settings_useragents" autocomplete="off" autocorrect="off" cols="40" rows="10" spellcheck="false" wrap="off"><?php echo esc_attr( get_option('mh_aiblocker_settings_useragents') ); ?></textarea></label>
			<p><small>one user agent per line; case-insensitive</small></p>
			<p><a href="#" onclick="mh_aiblocker_settings_useragents_reset()">Reset to default</a></p>
			<script>
			function mh_aiblocker_settings_useragents_reset(){
				var textarea = document.querySelector('textarea[name="mh_aiblocker_settings_useragents"]');
				textarea.value = '<?= str_replace("\n", '\n', get_default_useragents()) ?>';
			};
			</script>
			<?php
		},
		'mh_aiblocker_settings',
		'mh_aiblocker_settings',
	);
	register_setting( 'mh_aiblocker_settings', 'mh_aiblocker_settings_useragents', [
		'default' => get_default_useragents(),
	] );

	add_settings_field(
		'mh_aiblocker_settings_origin',
		'IP Address Server Variable',
		function(){
			?>
			<label><input type="text" name="mh_aiblocker_settings_origin" spellcheck="false" autocomplete="off" autocorrect="off" value="<?php echo esc_attr( get_option('mh_aiblocker_settings_origin') ); ?>"></label>
			<p><small>Specify the origins you trust in order of priority, separated by commas. We strongly recommend that you do not use anything other than <code>REMOTE_ADDR</code> since other origins can be easily faked. Examples: <code>HTTP_X_FORWARDED_FOR</code>, <code>HTTP_CF_CONNECTING_IP</code>, <code>HTTP_X_SUCURI_CLIENTIP</code>. Default: <code>REMOTE_ADDR</code></small></p>
			<p><a href="#" onclick="mh_aiblocker_settings_origin_reset()">Reset to default</a></p>
			<script>
			function mh_aiblocker_settings_origin_reset(){
				var input = document.querySelector('input[name="mh_aiblocker_settings_origin"]');
				input.value = '<?= get_default_origin() ?>';
			};
			</script>
			<?php
		},
		'mh_aiblocker_settings',
		'mh_aiblocker_settings',
	);
	register_setting( 'mh_aiblocker_settings', 'mh_aiblocker_settings_origin', [
		'default' => get_default_origin(),
	] );

	add_settings_field(
		'mh_aiblocker_settings_deleteall',
		'Cleanup',
		function(){

			$active = get_option('mh_aiblocker_settings_deleteall');

			?>
			<label><input type="checkbox" name="mh_aiblocker_settings_deleteall"<?php if( $active ) echo ' checked'; ?>> delete all plugin data on deactivation</label>
			<?php
		},
		'mh_aiblocker_settings',
		'mh_aiblocker_settings',
	);
	register_setting( 'mh_aiblocker_settings', 'mh_aiblocker_settings_deleteall', [
		'default' => false
	] );

}
add_action( 'admin_init', 'MH\AIBlocker\register_settings' );


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

		<h1>MH AI Blocker Settings</h1>

		<form method="post" action="<?= esc_url( admin_url('options.php') ) ?>">
			<?php

			settings_fields( 'mh_aiblocker_settings' );

			do_settings_sections( 'mh_aiblocker_settings' );
			
			submit_button();

			?>
		</form>

	</div>
	<?php

}


function show_activation_message() {
	set_transient( 'mh_aiblocker_activation_message', true, 30*MINUTE_IN_SECONDS );
}


// Hook to add admin notice
add_action( 'admin_notices', 'MH\AIBlocker\activation_message' );

function activation_message() {
	if( ! get_transient( 'mh_aiblocker_activation_message' ) ) return;

	?>
	<div class="notice notice-success">
		<p>AI Blocker is successfully installed! Go to the <a href="<?= esc_url(admin_url('options-general.php?page=mh_aiblocker_settings')) ?>">settings page</a> to activate blocking.</p>
	</div>
	<?php

	delete_transient( 'mh_aiblocker_activation_message' );
}


function reset_settings(){

	$delete_all = get_option( 'mh_aiblocker_settings_deleteall' );

	if( ! $delete_all ) return;

	delete_option( 'mh_aiblocker_settings_active' );
	delete_option( 'mh_aiblocker_settings_json' );
	delete_option( 'mh_aiblocker_settings_json_schedule' );
	delete_option( 'mh_aiblocker_settings_ipranges' );
	delete_option( 'mh_aiblocker_settings_useragents' );
	delete_option( 'mh_aiblocker_settings_origin' );
	delete_option( 'mh_aiblocker_settings_deleteall' );

}
