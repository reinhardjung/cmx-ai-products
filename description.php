<?php defined( 'ABSPATH' ) or die( 'Oxytocin!' );


class WCAIAutoDescription {

	public function __construct() {
		add_action('woocommerce_before_product_object_save', [$this, 'get_all_productinfos']);
	}



	public function get_all_productinfos($product) {
		$myProduct										= new stdClass();
		$myProduct->id								= $product->get_id();
		$myProduct->name							= $product->get_name();
		$myProduct->description				= $product->get_description();
		$myProduct->short_description	= $product->get_short_description();

		$myProduct->attributes				= $product->get_attributes();
		$myProduct->meta_data					= $product->get_meta_data();

		$myProduct->prompt_engine			= '';
		$myProduct->add_short					= '';

		$myProduct->categories				= $this->get_all_categories($product);
		$myProduct->prompt_categories	= ' Mit den Kategorien: ' .implode(',', $myProduct->categories);

		$myProduct->tags							= $this->get_all_tags($product);
		$myProduct->prompt_tags				= ' Mit den Produkt Eigenschaften: ' .implode(',', $myProduct->tags);

		$myProduct->phrasings					= $this->get_all_phrasings($product);
		$myProduct->prompt_phrasings	= $myProduct->phrasings;

		$myProduct->cart_sort					= "Write a short, concise product description for  ' .$myProduct->name. 'This short description should be 5 words  maximum and highlight the most important benefits.";

		$myProduct->lang							= get_language_from_wp();


		if(!empty($myProduct->short_description)) {
			$myProduct->add_short = ' und der zusätzlichen Beschreibung: "' .$myProduct->short_description .'".';

			if(empty($myProduct->description)) {
				$myProduct->prompt_engine		= "Schreibe eine ansprechende ausführliche Produktbeschreibung für ein Produkt Namens ' .$myProduct->name. .$myProduct->add_short. .$myProduct->prompt_categories. .$myProduct->prompt_tags. ' Mit folgenden Attributen:'' Betone dabei die Vorteile, Qualität und Alleinstellungsmerkmale.";
				if(empty(get_option('wc_ai_api_key'))) {
					$product->set_description('API-Key is missing: [WooCommerce/Settings/AI Product Description]');
				} else { $product->set_description($this->get_ai_description($myProduct)); };
			}
		} else {
			$myProduct->prompt_engine		= "Write a short, concise product description for  ' .$myProduct->name. 'This short description should be 2-3 sentences maximum and highlight the most important benefits.";
			if(empty(get_option('wc_ai_api_key'))) {
				$product->set_short_description('API-Key is missing: [WooCommerce/Settings/AI Product Description]');
			} else { $product->set_short_description($this->get_ai_description($myProduct)); };
		}
	}



	private function get_ai_description($myProduct) {
		$myAI					= new stdClass();
		$myAI->key		= get_option('wc_ai_api_key');
		$myAI->lang		= get_language_from_wp();
		$myAI->prompt	= $myProduct->prompt_engine;

		$response = wp_remote_post('https://api.openai.com/v1/chat/completions', array(
			'body' => json_encode(array(
				'model' => 'gpt-3.5-turbo',
				'messages' => array(
					array(
						'role' => 'system',
						'content' => 'You should always reply in ' .$myAI->lang,
					),
					array(
						'role' => 'user',
						'content' => 'Du bist eine seo marketing assistentin, welche sich immer wie folgt ausdrückt: ' .$myProduct->prompt_phrasings,
					),
					array(
						'role' => 'assistant',
						'content' => $myAI->prompt,
					)
				),
				// 'max_tokens' => 150,
			)),
			'headers' => array(
				'Content-Type' => 'application/json',
				'Authorization' => 'Bearer ' . $myAI->key,
			)
		));

		if (is_wp_error($response)) {
			$error_message = $response->get_error_message();
			if (strpos($error_message, 'exceeded your current quota') !== false) {
				echo 'You have exceeded your API usage quota. Please upgrade your plan or reduce usage.';
			}
			return '';
		}

		$result = json_decode(wp_remote_retrieve_body($response), true);

		return isset($result['choices'][0]['message']['content']) ? trim($result['choices'][0]['message']['content']) : '';  // docu rju 2025-01-28: Sicherstellen, dass die API-Antwort gültig ist und die erwarteten Felder enthält'
	}



	private function get_all_categories($product) {
		$categories		= [];
		$category_ids = $product->get_category_ids();
		foreach ($category_ids as $id) {
			$category = get_term($id);
			if (!is_wp_error($category) && $category) {
				$categories[] = $category->name;
			}
		}
		return $categories;
	}



	private function get_all_tags($product) {
		$tags			= [];
		$tag_ids	= $product->get_tag_ids();
		foreach ($tag_ids as $id) {
			$tagy = get_term($id);
			if (!is_wp_error($tagy) && $tagy) {
				$tags[] = $tagy->name;
			}
		}
		return $tags;
	}



	private function get_all_phrasings($product) {
		$myKey			= new stdClass();
		$myKey->key = get_option('wc_ai_multiselect');
		return $myKey->key;
	}
}


new WCAIAutoDescription();
