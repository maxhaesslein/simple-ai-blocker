<?php

namespace MH\AIBlocker;

if( ! defined('ABSPATH') ) exit;


function add_settings_link( $links ) {
	$settings_link = '<a href="options-general.php?page=mh_aiblocker_settings">Settings</a>';
	array_unshift( $links, $settings_link );
	return $links;
}
add_filter( 'plugin_action_links_mh-aiblocker/mh-aiblocker.php', 'MH\AIBlocker\add_settings_link' );


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
		false,
		'mh_aiblocker_settings'
	);

	add_settings_field(
		'mh_aiblocker_settings_active',
		'IP Ranges to Block',
		function(){

			$active = get_option('mh_aiblocker_settings_active');

			?>
			<label><input type="checkbox" name="mh_aiblocker_settings_active"<?php if( $active ) echo ' checked'; ?>> activate blocking</label>
			<?php
		},
		'mh_aiblocker_settings',
		'mh_aiblocker_settings',
	);
	register_setting( 'mh_aiblocker_settings', 'mh_aiblocker_settings_active', [
		'default' => true
	] );

	add_settings_field(
		'mh_aiblocker_settings_ipranges',
		'IP Ranges to Block',
		function(){
			?>
			<label><textarea name="mh_aiblocker_settings_ipranges" autocomplete="off" autocorrect="off" cols="40" rows="28" spellcheck="false" wrap="off"><?php echo esc_attr( get_option('mh_aiblocker_settings_ipranges') ); ?></textarea></label>
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
		// TODO: validate ip ranges on save
	] );

}
add_action( 'admin_init', 'MH\AIBlocker\register_settings' );


function options_page(){

	?>
	<div class="wrap">

		<h1>MH AI Blocker Settings</h1>

		<form method="post" action="<?= esc_url( admin_url('options.php') ) ?>">
			<?php

			settings_fields( 'mh_aiblocker_settings' );

			do_settings_sections( 'mh_aiblocker_settings' );
			
			submit_button();

			// DEBUG:
			echo '<hr>';
			echo '<h2>Debug-Information:</h2>';
			echo '<strong>User IP:</strong>';
			echo '<pre>'; var_dump(get_user_ip()); echo '</pre>'; // DEBUG
			echo '<strong>IP Ranges:</strong>';
			echo '<pre>'; var_dump(get_ip_ranges()); echo '</pre>'; // DEBUG

			?>
		</form>

	</div>
	<?php

}
