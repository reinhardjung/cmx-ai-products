<?php defined( 'ABSPATH' ) or die( 'Oxytocin!' );


add_filter('woocommerce_settings_tabs_array', 'wc_add_ai_settings_tab', 50);
function wc_add_ai_settings_tab($settings_tabs) {
	$settings_tabs['ai_settings'] = __('KI Produkttexte', 'woocommerce');
	return $settings_tabs;
}



add_action('woocommerce_settings_ai_settings', 'wc_ai_settings_tab_content');
function wc_ai_settings_tab_content() {
	woocommerce_admin_fields(get_wc_ai_settings());
}



add_action('woocommerce_update_options_ai_settings', 'wc_save_ai_settings');
function wc_save_ai_settings() {
	woocommerce_update_options(get_wc_ai_settings());
}



function get_wc_ai_settings() {
	$settings = array(
		'section_title' => array(
			'name'     => __('Alles Einstellungssache', 'woocommerce'),
			'type'     => 'title',
			'desc'     => '',
			'id'       => 'wc_ai_api_section_title'
		),
		'settings_explanation' => array(
			'name' => '',
			'type' => 'title',
			'desc' => __('Bitte in WP Einstellungen eine Standardsprache wählen: Also "Deutsch", statt "Deutsch (Sie)"', 'woocommerce'),
			'id'   => 'wc_ai_new_yes_no_explanation',
		),
		'api_key' => array(
			'name' => __('API Key', 'woocommerce'),
			'type' => 'text',
			'desc' => __('Geben Sie Ihren API-Schlüssel für die AI-Integration ein.', 'woocommerce'),
			'id'   => 'wc_ai_api_key',
		),
		'multi_select' => array(
			'name'     => __('Ausdrucksweise', 'woocommerce'),
			'type'     => 'multiselect',
			'desc'     => __('Wählen Sie eine oder mehrere Tonarten', 'woocommerce'),
			'id'       => 'wc_ai_multiselect',
			'class'    => 'wc-enhanced-select',
			'css'      => 'min-width:300px;',
			'default'  => 'persuasive',
			'options'  => array(
				'professional' => 'Professionell',
				'friendly' => 'Freundlich',
				'persuasive' => 'Verkaufend',
				'inspirational' => 'Inspirierend',
				'humorous' => 'Humorvoll',
				'luxurious' => 'Luxuriös',
				'technical' => 'Technisch',
				'nostalgic' => 'Nostalgisch',
				'authoritative' => 'Autoritär',
				'simple_clear' => 'Einfach und klar',
				'urgent' => 'Dringlich',
				'passionate' => 'Leidenschaftlich',
				'informative' => 'Informativ'
			),
		),
		'section_end' => array(
			'type' => 'sectionend',
			'id'   => 'wc_ai_api_section_end'
		),
	);
	return $settings;
}
