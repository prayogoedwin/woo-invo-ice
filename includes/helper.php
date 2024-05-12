<?php
/**
 *
 */
if ( ! function_exists('woo_invo_ice_filter_label') ) {

    function woo_invo_ice_filter_label( $label, $order, $template ) {
        return apply_filters(
            'woo_invo_ice_filter_template_label',
            __($label, 'webappick-pdf-invoice-for-woocommerce'),//phpcs:ignore
            $order,
            $template
        );
    }
}

if ( ! function_exists('woo_invo_ice_switch_language_callback') ) {
    /**
     * @param WC_Order $order Order Object.
     * @param $template
     */
    function woo_invo_ice_switch_language_callback( $language_code, $cookie_lang = true ) {
        if ( ! empty($language_code) ) {
            if ( class_exists('SitePress', false) ) {
                // WPML Switch Language.
                global $sitepress;
                if ( $sitepress->get_current_language() !== $language_code ) {
                    $sitepress->switch_lang($language_code, $cookie_lang);
                }
            }
            // when polylang plugin is activated.
            if ( defined('POLYLANG_BASENAME') || function_exists('PLL') ) {
                if ( pll_current_language() !== $language_code ) {
                    PLL()->curlang = PLL()->model->get_language($language_code);
                }
            }

            if ( 'wpifw_pdf_site_language' !== get_option('wpifw_pdf_document_language') ) {
                switch_to_locale($language_code);
            }
}
    }
}

if ( ! function_exists('woo_invo_ice_restore_language_callback') ) {
    /**
     * restore previous language.
     */
    function woo_invo_ice_restore_language_callback() {
        $language_code = '';
        if ( class_exists('SitePress', false) ) {
            // WPML restore Language.
            global $sitepress;
            $language_code = $sitepress->get_default_language();
        }

        // when polylang plugin is activated
        if ( defined('POLYLANG_BASENAME') || function_exists('PLL') ) {
            $language_code = pll_default_language();
        }
        /**
         * Filter to hijack Default Language code before restore.
         *
         * @param string $language_code
         */

        if ( ! empty($language_code) ) {
            woo_invo_ice_switch_language_callback($language_code);
        } else {
            $defaul_lang = get_option('wpifw_site_default_language');

            woo_invo_ice_switch_language_callback($defaul_lang);
        }
    }
}

if ( ! function_exists('woo_invo_ice_reload_text_domain') ) {
    /**
     * Load plugin textdomain during generating invoice.
     */
    function woo_invo_ice_reload_text_domain() {
        load_plugin_textdomain(
            'webappick-pdf-invoice-for-woocommerce',
            false,
            dirname(WOO_INVO_ICE_BASE_NAME) . '/languages/'
        );
    }
}


if ( ! function_exists('woo_invo_ice_product_meta_query') ) {
    /**
     * @return array|object|null
     */
    function woo_invo_ice_product_meta_query(){
        global $wpdb;
        // $meta_query = $wpdb->get_results( "SELECT * FROM $wpdb->postmeta GROUP BY meta_key" );
		if ( class_exists( 'WooCommerce' ) ) {
			$product_meta_query = $wpdb->get_results(
				"SELECT $wpdb->postmeta.meta_key, $wpdb->postmeta.meta_id FROM $wpdb->postmeta
                                    LEFT JOIN $wpdb->posts ON $wpdb->postmeta.post_id = $wpdb->posts.id
                                    WHERE $wpdb->posts.post_type = 'product'
                                    GROUP BY $wpdb->postmeta.meta_key"
			);
		} else {
			$product_meta_query = array();
		}

        return $product_meta_query;
    }
}

if ( ! function_exists('woo_invo_ice_order_meta_query') ) {
    /**
     * @return array|object|null
     */
    function woo_invo_ice_order_meta_query(){
        global $wpdb;
		if ( class_exists( 'WooCommerce' ) ) {
			$order_meta_query_arr = $wpdb->get_results(
				"SELECT $wpdb->postmeta.meta_key FROM $wpdb->postmeta
                                    LEFT JOIN $wpdb->posts ON $wpdb->postmeta.post_id = $wpdb->posts.id
                                    WHERE $wpdb->posts.post_type = 'shop_order'
                                    GROUP BY $wpdb->postmeta.meta_key"
			);
		} else {
			$order_meta_query_arr = array();
		}
        $order_meta_query = array();
        $order_meta_query = $order_meta_query_arr;

        return $order_meta_query;
    }
}

if ( ! function_exists('woo_invo_ice_item_meta_query') ) {
    /**
     * @return array|object|null
     */
    function woo_invo_ice_item_meta_query(){
        global $wpdb;
		if ( class_exists( 'WooCommerce' ) ) {
			$item_meta = $wpdb->get_results(
				"SELECT  DISTINCT  $wpdb->order_itemmeta.meta_key
        FROM $wpdb->order_itemmeta");
		} else {
			$item_meta = array();
		}

        return $item_meta;
    }
}



if ( ! function_exists('woo_invo_ice_order_meta_data_position') ) {
    /**
     * @return string[].
     */
    function woo_invo_ice_order_meta_data_position(){
        $order_meta_position_arr = array(
            'after_order_data'        => 'After order data',
            'before_order_data'       => 'Before order data',
            'before_billing_address'  => 'Before billing address',
            'after_billing_address'   => 'After billing address',
            'before_shipping_address' => 'Before shipping address',
            'after_shipping_address'  => 'After shipping address',
        );

        return $order_meta_position_arr;
    }
}

if ( ! function_exists('woo_invo_ice_fonts') ) {
    /**
     *
     * @return string[].
     */
    function woo_invo_ice_fonts(){
        // MPDF font list.
        $woo_invo_ice_fonts = array(
            'https://challan-assets.s3.ap-southeast-1.amazonaws.com/fonts/AboriginalSansREGULAR.ttf',
            'https://challan-assets.s3.ap-southeast-1.amazonaws.com/fonts/Abyssinica_SIL.ttf',
            'https://challan-assets.s3.ap-southeast-1.amazonaws.com/fonts/Aegean.otf',
            'https://challan-assets.s3.ap-southeast-1.amazonaws.com/fonts/Aegyptus.otf',
            'https://challan-assets.s3.ap-southeast-1.amazonaws.com/fonts/Akkadian.otf',
            'https://challan-assets.s3.ap-southeast-1.amazonaws.com/fonts/DBSILBR.ttf',
            'https://challan-assets.s3.ap-southeast-1.amazonaws.com/fonts/Dhyana-Bold.ttf',
            'https://challan-assets.s3.ap-southeast-1.amazonaws.com/fonts/Dhyana-Regular.ttf',
            'https://challan-assets.s3.ap-southeast-1.amazonaws.com/fonts/FreeMono.ttf',
            'https://challan-assets.s3.ap-southeast-1.amazonaws.com/fonts/FreeMonoBold.ttf',
            'https://challan-assets.s3.ap-southeast-1.amazonaws.com/fonts/FreeMonoBoldOblique.ttf',
            'https://challan-assets.s3.ap-southeast-1.amazonaws.com/fonts/FreeMonoOblique.ttf',
            'https://challan-assets.s3.ap-southeast-1.amazonaws.com/fonts/FreeSans.ttf',
            'https://challan-assets.s3.ap-southeast-1.amazonaws.com/fonts/FreeSansBold.ttf',
            'https://challan-assets.s3.ap-southeast-1.amazonaws.com/fonts/FreeSansBoldOblique.ttf',
            'https://challan-assets.s3.ap-southeast-1.amazonaws.com/fonts/FreeSansOblique.ttf',
            'https://challan-assets.s3.ap-southeast-1.amazonaws.com/fonts/FreeSerif.ttf',
            'https://challan-assets.s3.ap-southeast-1.amazonaws.com/fonts/FreeSerifBold.ttf',
            'https://challan-assets.s3.ap-southeast-1.amazonaws.com/fonts/FreeSerifBoldItalic.ttf',
            'https://challan-assets.s3.ap-southeast-1.amazonaws.com/fonts/FreeSerifItalic.ttf',
            'https://challan-assets.s3.ap-southeast-1.amazonaws.com/fonts/Garuda-BoldOblique.ttf',
            'https://challan-assets.s3.ap-southeast-1.amazonaws.com/fonts/Garuda-Oblique.ttf',
            'https://challan-assets.s3.ap-southeast-1.amazonaws.com/fonts/Jomolhari.ttf',
            'https://challan-assets.s3.ap-southeast-1.amazonaws.com/fonts/KhmerOS.ttf',
            'https://challan-assets.s3.ap-southeast-1.amazonaws.com/fonts/LateefRegOT.ttf',
            'https://challan-assets.s3.ap-southeast-1.amazonaws.com/fonts/Padauk-book.ttf',
            'https://challan-assets.s3.ap-southeast-1.amazonaws.com/fonts/Quivira.otf',
            'https://challan-assets.s3.ap-southeast-1.amazonaws.com/fonts/Sun-ExtA.ttf',
            'https://challan-assets.s3.ap-southeast-1.amazonaws.com/fonts/Sun-ExtB.ttf',
            'https://challan-assets.s3.ap-southeast-1.amazonaws.com/fonts/TaameyDavidCLM-Medium.ttf',
            'https://challan-assets.s3.ap-southeast-1.amazonaws.com/fonts/TaiHeritagePro.ttf',
            'https://challan-assets.s3.ap-southeast-1.amazonaws.com/fonts/Tharlon-Regular.ttf',
            'https://challan-assets.s3.ap-southeast-1.amazonaws.com/fonts/UnBatang_0613.ttf',
            'https://challan-assets.s3.ap-southeast-1.amazonaws.com/fonts/XB+Riyaz.ttf',
            'https://challan-assets.s3.ap-southeast-1.amazonaws.com/fonts/XB+RiyazBd.ttf',
            'https://challan-assets.s3.ap-southeast-1.amazonaws.com/fonts/XB+RiyazBdIt.ttf',
            'https://challan-assets.s3.ap-southeast-1.amazonaws.com/fonts/XB+RiyazIt.ttf',
            'https://challan-assets.s3.ap-southeast-1.amazonaws.com/fonts/ZawgyiOne.ttf',
            'https://challan-assets.s3.ap-southeast-1.amazonaws.com/fonts/ayar.ttf',
            'https://challan-assets.s3.ap-southeast-1.amazonaws.com/fonts/damase_v.2.ttf',
            'https://challan-assets.s3.ap-southeast-1.amazonaws.com/fonts/lannaalif-v1-03.ttf',
            'https://challan-assets.s3.ap-southeast-1.amazonaws.com/fonts/ocrb10.ttf',
        );

        return $woo_invo_ice_fonts;
    }
}
/**
 * Get all WordPress registered language for generating pdf invoice.
 * @return string[]
 */
function woo_invo_ice_get_default_languages(){
    return array(
        'af'             => 'Afrikaans',
        'ar'             => 'العربية',
        'ary'            => 'العربية المغربية',
        'as'             => 'অসমীয়া',
        'azb'            => 'گؤنئی آذربایجان',
        'az'             => 'Azərbaycan dili',
        'bel'            => 'Беларуская мова',
        'bg_BG'          => 'Български',
        'bn_BD'          => 'বাংলা',
        'bo'             => 'བོད་ཡིག',
        'bs_BA'          => 'Bosanski',
        'ca'             => 'Català',
        'ceb'            => 'Cebuano',
        'cs_CZ'          => 'Čeština',
        'cy'             => 'Cymraeg',
        'da_DK'          => 'Dansk',
        'de_DE_formal'   => 'Deutsch (Sie)',
        'de_DE'          => 'Deutsch',
        'de_CH_informal' => 'Deutsch (Schweiz, Du)',
        'de_CH'          => 'Deutsch (Schweiz)',
        'de_AT'          => 'Deutsch (Österreich)',
        'dsb'            => 'Dolnoserbšćina',
        'dzo'            => 'རྫོང་ཁ',
        'el'             => 'Ελληνικά',
        'en_CA'          => 'English (Canada)',
        'en_NZ'          => 'English (New Zealand)',
        'en_ZA'          => 'English (South Africa)',
        'en_GB'          => 'English (UK)',
        'en_AU'          => 'English (Australia)',
        'eo'             => 'Esperanto',
        'es_DO'          => 'Español de República Dominicana',
        'es_CR'          => 'Español de Costa Rica',
        'es_VE'          => 'Español de Venezuela',
        'es_CO'          => 'Español de Colombia',
        'es_CL'          => 'Español de Chile',
        'es_UY'          => 'Español de Uruguay',
        'es_PR'          => 'Español de Puerto Rico',
        'es_ES'          => 'Español',
        'es_GT'          => 'Español de Guatemala',
        'es_PE'          => 'Español de Perú',
        'es_MX'          => 'Español de México',
        'es_EC'          => 'Español de Ecuador',
        'es_AR'          => 'Español de Argentina',
        'et'             => 'Eesti',
        'eu'             => 'Euskara',
        'fa_AF'          => '(فارسی (افغانستان',
        'fa_IR'          => 'فارسی',
        'fi'             => 'Suomi',
        'fr_FR'          => 'Français',
        'fr_CA'          => 'Français du Canada',
        'fr_BE'          => 'Français de Belgique',
        'fur'            => 'Friulian',
        'gd'             => 'Gàidhlig',
        'gl_ES'          => 'Galego',
        'gu'             => 'ગુજરાતી',
        'haz'            => 'هزاره گی',
        'he_IL'          => 'עִבְרִית',
        'hi_IN'          => 'हिन्दी',
        'hr'             => 'Hrvatski',
        'hsb'            => 'Hornjoserbšćina',
        'hu_HU'          => 'Magyar',
        'hy'             => 'Հայերեն',
        'id_ID'          => 'Bahasa Indonesia',
        'is_IS'          => 'Íslenska',
        'it_IT'          => 'Italiano',
        'ja'             => '日本語',
        'jv_ID'          => 'Basa Jawa',
        'ka_GE'          => 'ქართული',
        'kab'            => 'Taqbaylit',
        'kk'             => 'Қазақ тілі',
        'km'             => 'ភាសាខ្មែរ',
        'kn'             => 'ಕನ್ನಡ',
        'ko_KR'          => '한국어',
        'ckb'            => 'كوردی‎',
        'lo'             => 'ພາສາລາວ',
        'lt_LT'          => 'Lietuvių kalba',
        'lv'             => 'Latviešu valoda',
        'mk_MK'          => 'Македонски јазик',
        'ml_IN'          => 'മലയാളം',
        'mn'             => 'Монгол',
        'mr'             => 'मराठी',
        'ms_MY'          => 'Bahasa Melayu',
        'my_MM'          => 'ဗမာစာ',
        'nb_NO'          => 'Norsk bokmål',
        'ne_NP'          => 'नेपाली',
        'nl_NL_formal'   => 'Nederlands (Formeel)',
        'nl_BE'          => 'Nederlands (België)',
        'nl_NL'          => 'Nederlands',
        'nn_NO'          => 'Norsk nynorsk',
        'oci'            => 'Occitan',
        'pa_IN'          => 'ਪੰਜਾਬੀ',
        'pl_PL'          => 'Polski',
        'ps'             => 'پښتو',
        'pt_PT'          => 'Português',
        'pt_PT_ao90'     => 'Português (AO90)',
        'pt_AO'          => 'Português de Angola',
        'pt_BR'          => 'Português do Brasil',
        'rhg'            => 'Ruáinga',
        'ro_RO'          => 'Română',
        'ru_RU'          => 'Русский',
        'sah'            => 'Сахалыы',
        'snd'            => 'سنڌي',
        'si_LK'          => 'සිංහල',
        'sk_SK'          => 'Slovenčina',
        'skr'            => 'سرائیکی',
        'sl_SI'          => 'Slovenščina',
        'sq'             => 'Shqip',
        'sr_RS'          => 'Српски језик',
        'sv_SE'          => 'Svenska',
        'sw'             => 'Kiswahili',
        'szl'            => 'Ślōnskŏ gŏdka',
        'ta_IN'          => 'தமிழ்',
        'ta_LK'          => 'தமிழ்',
        'te'             => 'తెలుగు',
        'th'             => 'ไทย',
        'tl'             => 'Tagalog',
        'tr_TR'          => 'Türkçe',
        'tt_RU'          => 'Татар теле',
        'tah'            => 'Reo Tahiti',
        'ug_CN'          => 'ئۇيغۇرچە',
        'uk'             => 'Українська',
        'ur'             => 'اردو',
        'uz_UZ'          => 'O‘zbekcha',
        'vi'             => 'Tiếng Việt',
        'zh_TW'          => '繁體中文',
        'zh_HK'          => '香港中文版	',
        'zh_CN'          => '简体中文',
    );
}

/**
 * Check if current status is checked for showing button to my account page and during email attachment.
 * @param $status
 * @param $checked_array
 * @return bool
 */
function woo_invo_ice_is_current_status_checked( $status, $checked_array ) {
    if ( 'always_allow' == $status ) {
        return true;
    }
    if ( in_array( $status, $checked_array ) ) {
        return  true;
    }
    foreach ( $checked_array as &$value ) {
        $value = str_replace( '_', '-', $value );
        if ( strpos( $value, $status) !== false ) {
            return true;
        }
    }

    return false;
}

// End of file helper.php