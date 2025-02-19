<?php defined( 'ABSPATH' ) or die( 'Oxytocin!' );


add_filter( 'unzip_file_use_ziparchive', '__return_false' );



function get_wp_time() {
	date_default_timezone_set(get_option('timezone_string'));
	return 'debug <br><strong><i>' .date(get_option('date_format'). ' - ' .get_option('time_format')). '</i></strong>'; // d.m.Y - H:i:s
}



function get_language_from_wp() {
	$locale = get_locale();
	$locale_mapping = [
		'de_DE' => 'Deutsch (Deutschland)',
		'de_AT' => 'Deutsch (Österreich)',
		'de_CH' => 'Deutsch (Schweiz)',
		'fr_FR' => 'Französisch (Frankreich)',
		'fr_CH' => 'Französisch (Schweiz)',
		'it_IT' => 'Italienisch (Italien)',
		'it_CH' => 'Italienisch (Schweiz)',
		'rm_CH' => 'Rätoromanisch (Schweiz)',
		'en_US' => 'Englisch (USA)',
		'en_GB' => 'Englisch (UK)',
		'es_ES' => 'Spanisch (Spanien)',
	];

	if (array_key_exists($locale, $locale_mapping)) {
		return explode(' ', $locale_mapping[$locale])[0];
	} else return 'Unknown Language! Please choose another Language within the WordPress Settings.';
}



function update_plugin_version($plugin_name) {
	if (!defined('WP_ENVIRONMENT_TYPE') || WP_ENVIRONMENT_TYPE !== 'local') return;

	date_default_timezone_set('Europe/Zurich');

	$filePath = plugin_dir_path(__FILE__).$plugin_name;
	file_put_contents($filePath, preg_replace('/(\* Version: ).*/', '$1' . ' ' .date("Y.md.Hi", filemtime($filePath)), file_get_contents($filePath)));
}
