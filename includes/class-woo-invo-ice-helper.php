<?php

use Woo_Invo_Ice\GenerateQrCode;
use Woo_Invo_Ice\Tag;

class Woo_Invo_Ice_Helper {

	/**
	 * Get WooCommerce Country Object.
	 *
	 * @var WC_Countries
	 */
	private $countries;


	/**
	 * Woo_Invo_Ice_Helper constructor.
	 */
	public function __construct() {
		$this->countries = new WC_Countries();
	}

	/**
	 * Get formatted order date according to plugin settings
	 *
	 * @param WC_Order $order Order Object.
	 *
	 * @return mixed
	 */
	public function woo_invo_ice_get_formatted_date( $order ) {
		// Set formatted order date.
		$format = '';
		$get_format = get_option('wiopt_date_format') ? get_option('wiopt_date_format') : 'd/m/Y';
		if ( ! empty($get_format) ) {
			$format = $get_format;
		}
		if ( 'wiopt_pdf_order_language' === get_option('wiopt_pdf_document_language') ) {
			$rtl_languages = array( 'ar', 'he', 'ur', 'he_IL' );
			$is_rtl = $order->get_meta('wiopt_invoice_rtl_' . $order->get_id());
			if ( in_array($is_rtl, $rtl_languages) && get_locale() == $is_rtl ) {
				return $order->get_date_created()->date_i18n($format);
			} else {
				return gmdate($format, strtotime($order->get_date_created()));
			}
		} else {
			return $order->get_date_created()->date_i18n($format);
		}
	}

	/**
	 * Get Order Number
	 *
	 * @param WC_Order $order Order Object.
	 *
	 * @return mixed|string
	 */
	public function woo_invo_ice_get_order_number( $order ) {

		$order_no = '';
		// Order Number Type.
		$get_number_type = get_option('wiopt_invoice_order_number_type');
		if ( ! $get_number_type || 'order_number' === $get_number_type ) {
			$order_no = $order->get_order_number();
		}

		if ( ! $get_number_type || 'order_id' === $get_number_type ) {
			$order_no = $order->get_id();
		}

		// Check for any sequential order number plugin installed or not and then get the sequential number if found.
		if ( class_exists('Alg_WC_Custom_Order_Numbers') ) {
			if ( '_woo_invo_ice_custom_order_numbers_for_woocommerce' === $get_number_type ) {
				$order_no = get_post_meta($order->get_id(), '_alg_wc_custom_order_number', true);
			}
		}

		if ( class_exists('Wt_Advanced_Order_Number') ) {
			if ( '_woo_invo_ice_wt_woocommerce_sequential_order_numbers' === $get_number_type ) {
				$order_no = $order->get_order_number();
			}
		}

		if ( class_exists('Wt_Advanced_Order_Number') ) {
			if ( '_woo_invo_ice_woocommerce_sequential_order_numbers' === $get_number_type ) {
				$order_no = $order->get_order_number();
			}
		}

		if ( class_exists('WCSON_INIT') ) {
			if ( '_woo_invo_ice_woo_custom_and_sequential_order_number' === $get_number_type ) {
				$order_no = get_post_meta($order->get_id(), '_wcson_order_number', true);
			}
		}

		if ( class_exists('BeRocket_Order_Numbers') ) {
			if ( '_woo_invo_ice_sequential_order_numbers_for_wooCommerce' === $get_number_type ) {
				$order_no = get_post_meta($order->get_id(), '_sequential_order_number', true);
			}
		}

		if ( class_exists('OpenToolsOrdernumbersBasic') ) {
			if ( '_woo_invo_ice_woocommerce_basic_ordernumbers' === $get_number_type ) {
				$order_no = get_post_meta($order->get_id(), '_oton_number_ordernumber', true);
			}
		}


		if ( empty($order_no) ) {
			$order_no = $order->get_order_number();
		}

		//Add Prefix & Suffix to order number  Order Number.

		// Get Prefix.
		$get_prefix = get_option('wiopt_order_no_prefix');
		$prefix = ! empty($get_prefix) ? $get_prefix : '';

		// Get Suffix.
		$get_suffix = get_option('wiopt_order_no_suffix');
		$suffix = ! empty($get_suffix) ? $get_suffix : '';

		$order_no = $prefix . $order_no . $suffix;

		$order_no = woo_invo_ice_process_date_macros( $order->get_id(), $order_no );



		return $order_no;
	}

	/**
	 * Get Billing Address
	 *
	 * @param WC_Order $order Order Object.
	 * @param string $type Value: billing or shipping.
	 * @param string $template Value: invoice or packing_slip.
	 * @param string $column Used for Shipping Label.
	 *
	 * @return string
	 */
	public function get_address( $order, $type, $template, $column = null ) {
		if ( 'label' === $template ) {
			return $this->get_custom_address($order, $type, $template, $column = null);
		}

		if ( 'billing' === $type ) { // Get Billing Address.
			$billing_data = '';
			// unload text domains
			$billing_data .= $order->get_formatted_billing_address();

			if ( empty(get_option('wiopt_display_phone')) && 1 != get_option('wiopt_display_phone') ) {
				$billing_data .= '<p>' . woo_invo_ice_filter_label('Phone', $order, $template) . ' : ' . $order->get_billing_phone() . '<p>';
			}
			if ( empty(get_option('wiopt_display_email')) && 1 != get_option('wiopt_display_email') ) {
				$billing_data .= '<p>' . woo_invo_ice_filter_label('Email', $order, $template) . ' : ' . $order->get_billing_email() . '<p>';
			}
			return $billing_data;
		} elseif ( 'shipping' === $type ) { // Get SHipping Address.
			// Return empty if Billing and Shipping Address Same.
			if ( get_option('wiopt_display_shipping_address') ) {
				if ( get_option('wiopt_hide_for_same_address') ) {
					if ( $order->get_billing_address_1() === $order->get_shipping_address_1() ) {
						return '';
					}
				}
			}
			if ( ! empty(get_option('wiopt_display_shipping_address')) ) {
				return $order->get_formatted_shipping_address();
			}
		}
	}


	/**
	 * Get Custom Formatted Billing/Shipping Address
	 *
	 * @param WC_Order $order Order Object.
	 * @param string $type Value: billing or shipping.
	 * @param string $template Value: invoice or packing_slip.
	 * @param string $column Used for Shipping Label.
	 *
	 * @return string|bool
	 */
	private function get_custom_address( $order, $type, $template, $column = null ) {
		$order_id = $order->get_id();
		$details = '';
		if ( 'billing' === $type ) {
			$details = get_option('wiopt_buyer');
		} elseif ( 'shipping' === $type ) {
			$details = get_option('wiopt_buyer_shipping_address');
		} elseif ( 'label' === $type ) {
			$default_tag = "{{billing_first_name}}	{{billing_last_name}}
{{billing_company}}	{{billing_address_1}}
{{billing_address_2}}	{{billing_city}}
{{billing_state}}	{{billing_postcode}}
{{billing_country}}	{{billing_phone}}
{{billing_email}}
{{shipping_first_name}}	{{shipping_last_name}}
{{shipping_company}}	{{shipping_address_1}}
{{shipping_address_2}}	{{shipping_city}}
{{shipping_state}}	{{shipping_postcode}}
{{shipping_country}}	{{shipping_phone}}
{{shipping_email}}";
			$details = '' != get_option('wiopt_shipping_lebel_buyer') ? get_option('wiopt_shipping_lebel_buyer') : $default_tag;

		}


		if ( ! empty($details) ) {
			preg_match_all('/{{(.*?)}}/', $details, $matches);
			$to_replace = $matches[0];
			$replace_with = array();
			
			if ( 'shipping' === $type ) {
				$country_code = get_post_meta($order_id, '_shipping_country', true);
			} elseif ( 'billing' === $type ) {
				$country_code = get_post_meta($order_id, '_billing_country', true);
			} else {
				$country_code = get_post_meta($order_id, '_shipping_country', true);
				if ( empty($country_code) ) {
					$country_code = get_post_meta($order_id, '_billing_country', true);
				}
			}

			foreach ( $matches[1] as $key => $meta_key ) {
				$is_type_meta = substr("$meta_key", 0, 1);
				$get_meta = get_post_meta($order_id, $meta_key, true);
				
				// If meta not found then add underscore and try again.
				if ( empty($get_meta) ) {
					if ( '_' !== $is_type_meta ) {
						$meta_key = '_' . $meta_key;
					}
					$get_meta = get_post_meta($order_id, $meta_key, true);
				}


				if ( is_array($get_meta) ) {
					$get_meta = implode('-', $get_meta);
				}

				if ( strpos($meta_key, 'billing_state') !== false || strpos($meta_key, 'shipping_state') !== false ) {
					$get_meta = $this->get_state_label( $country_code, $get_meta );
				}

				if ( strpos($meta_key, 'shipping_country') !== false || strpos($meta_key, 'billing_country') !== false ) {
					$get_meta = $this->get_country_label( $get_meta );
				}

				$get_meta = ! empty($get_meta) ? $get_meta : '';
				array_push($replace_with, $get_meta);
			}
			// Replace Billing information according to customers settings.
			$address = str_replace($to_replace, $replace_with, $details);

			// Remove Empty Line.
			$address = preg_replace("/\n\n/", "\n", $address);
			$address = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", '<br>', $address);
			$address = str_replace('<br><br>', '<br>', $address);

			if ( 'label' === $template && ! empty($from) ) {
				$address = '<div style="border:2px solid black;float:left;width:' . $column . '%"><p><b>' . $from . '</b><br>' . $address . '</p></div>';
			}

//            print_r($details);exit;


			return $address;
		}
		return false;
	}

	/**
	 * Get tax rate by product id
	 *
	 * @param int $id Product Id.
	 *
	 * @return float|mixed
	 */
	public function product_tax_rate( $id ) {
		$tax_rate = [];
		$product = wc_get_product($id);
		$tax = new WC_Tax();
		$tax_rate_class = $product->get_tax_class();
		if ( ! empty($tax_rate_class) ) {
			$tax_rate = $tax->get_rates($tax_rate_class);
			$tax_rate = reset($tax_rate);
		} else {
			$rates = WC_Tax::get_rates();
			if ( ! empty($rates) ) {
				$tax_rate = round(reset($rates)['rate']);
			}
		}

		return $tax_rate;
	}

	/**
	 * Get Country label by country code
	 *
	 * @param string $country_code Country Code.
	 *
	 * @return mixed
	 */
	private function get_country_label( $country_code ) {
		if ( empty($country_code) ) {
			return false;
		}

		$countries = $this->countries->get_countries();

		return $countries[ $country_code ];
	}

	/**
	 * Get State label by Country code and State code
	 *
	 * @param string $country_code Country Code.
	 * @param string $state_code State Code.
	 *
	 * @return mixed
	 */
	private function get_state_label( $country_code, $state_code ) {
		if ( empty($country_code) || empty($state_code) ) {
			return false;
		}

		$states = $this->countries->get_states($country_code);

		return $states[ $state_code ];
	}

	/**
	 * Format price with WooCommerce number format and order currency
	 *
	 * @param WC_Order $order Order Object.
	 * @param integer $price Product Price.
	 *
	 * @return mixed|string
	 */
	public function format_price( $order, $price ) {
		$missing_currencies = array(
			'BDT' => '&#2547;&nbsp;',
			'BTC' => '&#3647;',
			'CRC' => '&#x20a1;',
			'GEL' => '&#x20be;',
			'ILS' => '&#8362;',
			'KPW' => '&#x20a9;',
			'KRW' => '&#8361;',
			'LAK' => '&#8365;',
			'MNT' => '&#x20ae;',
			'MUR' => '&#x20a8;',
			'MVR' => '.&#x783;',
			'NPR' => '&#8360;',
			'PKR' => '&#8360;',
			'PYG' => '&#8370;',
			'RUB' => '&#8381;',
			'SCR' => '&#x20a8;',
			'THB' => '&#3647;',
			'TRY' => '&#8378;',
			'VND' => '&#8363;',
		);


		$price = apply_filters('wiopt_incl_or_exl_tax__price', $price, $order  );

		if ( '1' == get_option('wiopt_currency_code') ) {
			$price = number_format(
				$price,
				wc_get_price_decimals(),
				wc_get_price_decimal_separator(),
				wc_get_price_thousand_separator()
			);
			if ( 'left' === get_option('woocommerce_currency_pos') || 'left_space' === get_option('woocommerce_currency_pos') ) {
				$price = $order->get_currency() . ' ' . $price;
			}
			if ( 'right' === get_option('woocommerce_currency_pos') || 'right_space' === get_option('woocommerce_currency_pos') ) {
				$price = $price . ' ' . $order->get_currency();
			}
		} else {
			$price = wc_price($price, array( 'currency' => $order->get_currency() ));

			if ( empty(get_option('wiopt_currency_code')) ) {
				if ( array_key_exists($order->get_currency(), $missing_currencies) ) {
					$font_size = get_option("wiopt_invoice_font_size") ? get_option("wiopt_invoice_font_size") + 1 : 12;
					?>
					<style>
                        .woocommerce-Price-currencySymbol {
                            font-family: currencies;
                            font-size: <?php echo esc_html( $font_size );?>px;
                        }
					</style>
					<?php
					$price = str_replace('woocommerce-Price-currencySymbol', 'woocommerce-Price-currencySymbol', $price);
				}
			}
		}

		return $price;
	}

	/**
	 * Resize & Get Invoice logo according to plugin settings
	 *
	 * @return string
	 */
	public function get_invoice_logo() {
		$logo_url = false;

		// Get original logo image.
		if ( false !== get_option('wiopt_logo_attachment_id', false) ) {
			if ( substr(get_option('wiopt_logo_attachment_id'), 0, 7) === 'http:// ' || substr(get_option('wiopt_logo_attachment_id'), 0, 8) === 'https://' ) {
				$image_id = attachment_url_to_postid(get_option('wiopt_logo_attachment_id'));
				$full_size_path = get_attached_file($image_id);
				update_option('wiopt_logo_attachment_id', $full_size_path);
				update_option('wiopt_logo_attachment_image_id', $image_id);
			}
			$logo_url = get_option('wiopt_logo_attachment_id');
		} elseif ( has_custom_logo() ) { // Get custom logo from theme customization.
			$custom_logo_id = get_theme_mod('custom_logo');
			$custom_logo_url = wp_get_attachment_image_url($custom_logo_id, 'full');
			$logo_url = $custom_logo_url;
		} else {
			// use site name
			return get_bloginfo( 'name');
		}

		$logo_url = apply_filters('woo_invo_ice_store_logo', $logo_url);

		// Set Logo Width.
		$logo_width = get_option('wiopt_logo_width');
		$logo_width = ! empty($logo_width) ? "style='width:$logo_width'" : '';

		// Final Logo.
		$logo = "<img class='logo' width='150' src='$logo_url' $logo_width >";

		if ( ! empty($logo_url) ) {
			return $logo;
		}

		return '';
	}

	/**
	 * Seller Info according to plugin settings
	 *
	 * @return string
	 */
	public function get_seller_info() {
		$store_address = '';
		if ( ! empty(get_option('wiopt_cdetails')) ) {
			$company = '<b>' . get_option('wiopt_cname') . '</b><br>';
			$address = str_replace("\n", '<br>', stripslashes(get_option('wiopt_cdetails')));
			$store_address = $company . $address;
		} else {

			if ( ! empty(get_option('wiopt_cname')) ) {
				$company = get_option('wiopt_cname');
			} else {
				$company = get_bloginfo('name');
			}
			$store_address .= '<b>' . $company . '</b>';
			$store = $this->countries;
			if ( ! empty($store->get_base_address()) ) {
				$store_address .= "<p>". $store->get_base_address() ."</p>";
			}
			if ( ! empty($store->get_base_address_2()) ) {
				$store_address .= "<p>" . $store->get_base_address_2() . "<p>";
			}
			if ( ! empty($store->get_base_city()) ) {
				$store_address .= "<p>" . $store->get_base_city();
			}
			if ( ! empty($store->get_base_city()) && ! empty($store->get_base_postcode()) ) {
				$store_address .= ' - ' . $store->get_base_postcode() . "</p>";
			}
			if ( ! empty($store->get_base_country()) ) {
				$store_address .= "<p>" . $this->get_country_label($store->get_base_country()) . "</p>";
			}
		}

		return $store_address;
	}

	/**
	 * Get paid stamp according to plugin settings for invoice
	 *
	 * @param string $order_status Status.
	 *
	 * @return string
	 */
	public function get_paid_stamp( $order_status ) {
		if ( 'completed' === $order_status && get_option('wiopt_paid_stamp') && ! empty(get_option('wiopt_paid_stamp_image')) ) {
			$selected_paid_stamp = get_option('wiopt_paid_stamp_image');

			if ( get_option('wiopt_custom_stamp_attachment_id') != false && ! empty(get_option('wiopt_custom_stamp_attachment_id')) ) {
				$paid_stamp = wp_get_attachment_url(esc_html(get_option('wiopt_custom_stamp_attachment_id')));
			} else {
				$paid_stamp = WP_PLUGIN_URL . '/astama-pdf-invoice-for-woocommerce-pro/admin/images/paid-stamp/' . $selected_paid_stamp . '.png';
			}

			$paid_stamp = apply_filters('woo_invo_ice_paid_stamp', $paid_stamp);
			$opacity = get_option('wiopt_paid_stamp_opacity') != false ? get_option('wiopt_paid_stamp_opacity') : '1.0';

			// Define a filter for the inline style
			$inline_style = apply_filters('woo_invo_ice_paid_stamp_inline_style', 'margin-left:60px;margin-top:5px;width:12%;opacity:' . esc_attr($opacity.';'));
			return '<img src="' . esc_url($paid_stamp) . '" alt="" style="' . esc_attr($inline_style) . '">';
		} else {
			return '';
		}
	}

	/**
	 * Get Direct Bank Transfer accounts info
	 *
	 * @param WC_Order $order Order Object.
	 *
	 * @return array|bool
	 */
	public function get_bank_accounts( $order ) {
		if ( get_option('wiopt_display_bank_account', true) && 'bacs' === $order->get_payment_method() ) {
			$bank_accounts = get_option('woocommerce_bacs_accounts');
			if ( ! empty($bank_accounts) ) {
				return $bank_accounts;
			}
		}

		return false;
	}

	/**
	 * Get Signature
	 *
	 * @return mixed|string
	 */
	public function get_signature() {
		if ( get_option('wiopt_enable_signature') != false && ! empty(get_option('wiopt_signature_attachment_id')) ) {
			if ( substr(get_option('wiopt_signature_attachment_id'), 0, 7) === 'http://' || substr(get_option('wiopt_signature_attachment_id'), 0, 8) === 'https://' ) {
				$image_id = attachment_url_to_postid(get_option('wiopt_signature_attachment_id'));
				$full_size_path = get_attached_file($image_id);
				update_option('wiopt_signature_attachment_id', $full_size_path);
				update_option('wiopt_signature_attachment_image_id', $image_id);
			}

			return get_option('wiopt_signature_attachment_id');
		}

		return '';
	}

	/**
	 * Get Shipping label Metas
	 *
	 * @param integer $order_id Order Id.
	 *
	 * @return string
	 */
	public function get_shipping_label_metas( $order_id ) {
		$get_meta_labels = get_option('wiopt_shipping_label_meta_labels');
		$get_metas = ! empty(get_option('wiopt_shipping_label_metas')) ? get_option('wiopt_shipping_label_metas') : [];

		$id = $order_id;
		$metas = '';
		foreach ( $get_metas as $key => $value ) {
			if ( ! empty($value) && ! empty($get_meta_labels[ $key ]) ) {
				$meta_value = get_post_meta($id, $value, true);
				$label = $get_meta_labels[ $key ];
				$metas .= '<p>' . $label . ' : ' . $meta_value . '</p>';
			} elseif ( empty($value) && ! empty($get_meta_labels[ $key ]) ) {
				$label = $get_meta_labels[ $key ];
				$metas .= '<p>' . $label . ' : </p>';
			}
		}

		return $metas;
	}

	/**
	 * Get Order Tax Information.
	 *
	 * @param WC_Order $order Order Object.
	 * @return array
	 */
	public function order_tax_info( $order ) {
		$order_taxes = $order->get_taxes();
		$tax_info = array();
		foreach ( $order_taxes as $key => $tax ) {
			$rate_id = $tax->get_rate_id();
			$tax_info[ $rate_id ]['tax_id'] = $tax->get_id();
			$tax_info[ $rate_id ]['rate_id'] = $tax->get_rate_id();
			$tax_info[ $rate_id ]['rate_code'] = $tax->get_rate_code();
			$tax_info[ $rate_id ]['rate'] = $tax->get_rate_percent();
			$tax_info[ $rate_id ]['label'] = $tax->get_label();
		}
		return $tax_info;
	}

    /**
     * Generate qr code according to Saudi law ZATCA.
     * for other users generate QR code string which gives an url of order received page's.
     *
     * @return mixed
     */
    public function get_qr_code( $order ) {


        /***********************************************************************
         * Saudi law for QR code.
         * After scanning QR code these data should be shown [Company name, VAT number, Date and time, Total order amount, VAT amount, and Order number]
         */


        if ( get_option( 'wiopt_enable_zatca' ) ) {

            date_default_timezone_set('Asia/Riyadh');

            $company_name = get_option('wiopt_cname');
            $order_total = $order->get_total();
            $date_time = $order->get_date_created()->date_i18n('Y-m-d')."T".$order->get_date_created()->date_i18n('H:i:s')."Z";
            $vat_amount = $order->get_total_tax();
            $vat_reg_number = get_option('wiopt_seller_vat_number') ? get_option('wiopt_seller_vat_number') : '';
            $qr_code_data_array = [
				'company_name'   => $company_name,
                'vat_reg_number' => $vat_reg_number,
                'date_time'      => $date_time,
				'order_total'    => $order_total,
				'vat_amount'     => $vat_amount,
            ];
            $qr_code_data_array = apply_filters('woo_invo_ice_qr_code',$qr_code_data_array,$order);

            $qr_code = GenerateQrCode::fromArray([
                new Tag(1, $qr_code_data_array['company_name']),
                new Tag(2, $qr_code_data_array['vat_reg_number']),
                new Tag(3, $qr_code_data_array['date_time']),
                new Tag(4, $qr_code_data_array['order_total']),
                new Tag(5, $qr_code_data_array['vat_amount']),
                new Tag(6, 66),
            ])->toBase64();


        }else {

            $qr_code = $order->get_checkout_order_received_url();

            $qr_code = apply_filters('woo_invo_ice_qr_code',$qr_code,$order);
        }

        return $qr_code;

    }

}

/**
 * Woo_Invo_Ice_Helper constructor.
 */
function woo_invo_ice_helper() {
	return new Woo_Invo_Ice_Helper();
}
