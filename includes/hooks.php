<?php
/**
 * This file contain available actions hooks to extend or customize Invoice or Packing Slip file info
 *
 * @package Woo_Invo_Ice
 */


// ################## Language Switch  ######################
add_action('woo_invo_ice_switch_language','woo_invo_ice_switch_language_callback');
add_action('woo_invo_ice_restore_language','woo_invo_ice_restore_language_callback');
add_action( 'change_locale', 'woo_invo_ice_reload_text_domain' );
/**
 * Set Data After the customer/shipping notes
 *
 * @param string $template_type Value: invoiceor packing_slip.
 *
 * @return string
 */

if ( ! function_exists( 'woo_invo_ice_custom_style' ) ) {
	function woo_invo_ice_custom_style( $template_type ) {
		ob_start();
		do_action( 'woo_invo_ice_custom_style', $template_type );
		return ob_get_clean();
	}
}

/**
 * Set Data Before all content on the document
 *
 * @param WC_Order $order Order Object.
 * @param string   $template_type Value: invoiceor packing_slip.
 *
 * @return string
 */
if ( ! function_exists( 'woo_invo_ice_before_document' ) ) {
	function woo_invo_ice_before_document( $order, $template_type ) {
		ob_start();
		do_action( 'woo_invo_ice_before_document', $order, $template_type );
		return ob_get_clean();
	}
}

/**
 * Set Data After all content on the document
 *
 * @param WC_Order $order Order Object.
 * @param string   $template_type Value: invoiceor packing_slip.
 *
 * @return string
 */
if ( ! function_exists( 'woo_invo_ice_after_document' ) ) {
	function woo_invo_ice_after_document( $order, $template_type ) {
		ob_start();
		do_action( 'woo_invo_ice_after_document', $order, $template_type );
		return ob_get_clean();
	}
}

/**
 * Set Data Before Seller Info
 *
 * @param WC_Order $order Order Object.
 * @param string   $template_type Value: invoiceor packing_slip.
 *
 * @return string
 */
if ( ! function_exists( 'woo_invo_ice_before_seller_info' ) ) {
	function woo_invo_ice_before_seller_info( $order, $template_type ) {
		ob_start();
		do_action( 'woo_invo_ice_before_seller_info', $order, $template_type );
		return ob_get_clean();
	}
}

/**
 * Set Data After Seller Info
 *
 * @param WC_Order $order Order Object.
 * @param string   $template_type Value: invoiceor packing_slip.
 *
 * @return string
 */
if ( ! function_exists( 'woo_invo_ice_after_seller_info' ) ) {
	function woo_invo_ice_after_seller_info( $order, $template_type ) {
		ob_start();
		do_action( 'woo_invo_ice_after_seller_info', $order, $template_type );

		return ob_get_clean();
	}
}

/**
 * Set Data Before the billing address
 *
 * @param WC_Order $order Order Object.
 * @param string   $template_type Value: invoiceor packing_slip.
 *
 * @return string
 */
if ( ! function_exists( 'woo_invo_ice_before_billing_address' ) ) {
	function woo_invo_ice_before_billing_address( $order, $template_type ) {
		ob_start();
		do_action( 'woo_invo_ice_before_billing_address', $order, $template_type , 'before_billing_address');
		return ob_get_clean();
	}
}

/**
 * Set Data After the billing address
 *
 * @param WC_Order $order Order Object.
 * @param string   $template_type Value: invoiceor packing_slip.
 *
 * @return string
 */
if ( ! function_exists( 'woo_invo_ice_after_billing_address' ) ) {
	function woo_invo_ice_after_billing_address( $order, $template_type ) {
		ob_start();
		do_action( 'woo_invo_ice_after_billing_address', $order, $template_type, 'after_billing_address' );
		return ob_get_clean();
	}
}

/**
 * Set Data Before the shipping address
 *
 * @param WC_Order $order Order Object.
 * @param string   $template_type Value: invoiceor packing_slip.
 *
 * @return string
 */
if ( ! function_exists( 'woo_invo_ice_before_shipping_address' ) ) {
	function woo_invo_ice_before_shipping_address( $order, $template_type ) {
		ob_start();
		do_action( 'woo_invo_ice_before_shipping_address', $order, $template_type, 'before_shipping_address' );
		return ob_get_clean();
	}
}

/**
 * Set Data After the shipping address
 *
 * @param WC_Order $order Order Object.
 * @param string   $template_type Value: invoiceor packing_slip.
 *
 * @return string
 */
if ( ! function_exists( 'woo_invo_ice_after_shipping_address' ) ) {
	function woo_invo_ice_after_shipping_address( $order, $template_type ) {
		ob_start();
		do_action( 'woo_invo_ice_after_shipping_address', $order, $template_type, 'after_shipping_address' );
		return ob_get_clean();
	}
}

/**
 * Set Data Before the order data (invoice number, order date, etc.)
 *
 * @param WC_Order $order Order Object.
 * @param string   $template_type Value: invoiceor packing_slip.
 *
 * @return string
 */
if ( ! function_exists( 'woo_invo_ice_before_order_data' ) ) {
	function woo_invo_ice_before_order_data( $order, $template_type ) {
		ob_start();
		do_action( 'woo_invo_ice_before_order_data', $order, $template_type, 'before_order_data' );
		return ob_get_clean();
	}
}

/**
 * Set Data After the order data
 *
 * @param WC_Order $order Order Object.
 * @param string   $template_type Value: invoiceor packing_slip.
 *
 * @return string
 */
if ( ! function_exists( 'woo_invo_ice_after_order_data' ) ) {
	function woo_invo_ice_after_order_data( $order, $template_type ) {
		ob_start();
		do_action( 'woo_invo_ice_after_order_data', $order, $template_type , 'after_order_data');
		return ob_get_clean();
	}
}

/**
 * Set Data Before the order details table with all items
 *
 * @param WC_Order $order Order Object.
 * @param string   $template_type Value: invoiceor packing_slip.
 *
 * @return string
 */
if ( ! function_exists( 'woo_invo_ice_before_product_list' ) ) {
	function woo_invo_ice_before_product_list( $order, $template_type ) {
		ob_start();
		do_action( 'woo_invo_ice_before_product_list', $order, $template_type );
		return ob_get_clean();
	}
}

/**
 * Set Data After the order details table
 *
 * @param WC_Order $order Order Object.
 * @param string   $template_type Value: invoice or packing_slip.
 *
 * @return string
 */
if ( ! function_exists( 'woo_invo_ice_after_product_list' ) ) {
	function woo_invo_ice_after_product_list( $order, $template_type ) {
		ob_start();
		do_action( 'woo_invo_ice_after_product_list', $order, $template_type );
		return ob_get_clean();
	}
}

/**
 * Set Data Before the item meta (for each item in the order details table)
 *
 * @param WC_Product $product Product Object.
 * @param WC_Order   $order Order Object.
 * @param string     $template_type Value: invoice or packing_slip.
 *
 * @return string
 */
if ( ! function_exists( 'woo_invo_ice_before_item_meta' ) ) {
	function woo_invo_ice_before_item_meta( $product, $order, $template_type ) {
		ob_start();
		do_action( 'woo_invo_ice_before_item_meta', $product, $order, $template_type );
		return ob_get_clean();
	}
}

/**
 * Set Data After the item meta (for each item in the order details table)
 *
 * @param WC_Product $product Product Object.
 * @param WC_Order   $order Order Object.
 * @param string     $template_type Value: invoice or packing_slip.
 *
 * @return string
 */
if ( ! function_exists( 'woo_invo_ice_after_item_meta' ) ) {
	function woo_invo_ice_after_item_meta( $product, $order, $template_type ) {
		ob_start();
		do_action( 'woo_invo_ice_after_item_meta', $product, $order, $template_type );
		return ob_get_clean();
	}
}


/**
 * Set Data Before the customer/shipping notes
 *
 * @param WC_Order $order Order Object.
 * @param string   $template_type Value: invoice or packing_slip.
 *
 * @return string
 */
if ( ! function_exists( 'woo_invo_ice_before_customer_notes' ) ) {
	function woo_invo_ice_before_customer_notes( $order, $template_type ) {
		ob_start();
		do_action( 'woo_invo_ice_before_customer_notes', $order, $template_type );
		return ob_get_clean();
	}
}

/**
 * Set Data After the customer/shipping notes
 *
 * @param WC_Order $order Order Object.
 * @param string   $template_type Value: invoice or packing_slip.
 *
 * @return string
 */
if ( ! function_exists( 'woo_invo_ice_after_customer_notes' ) ) {
	function woo_invo_ice_after_customer_notes( $order, $template_type ) {
		ob_start();
		do_action( 'woo_invo_ice_after_customer_notes', $order, $template_type );
		return ob_get_clean();
	}
}

/**
 * Get formatted order date according to plugin settings
 *
 * @param WC_Order $order Order Object.
 *
 * @return mixed
 */
function woo_invo_ice_get_formatted_date( $order ) {
    // Set formatted order date.
    $format = '';
    $get_format = get_option('wpifw_date_format') ? get_option('wpifw_date_format') : 'd/m/Y';
    if ( ! empty($get_format) ) {
        $format = $get_format;
    }
    if ( 'wpifw_pdf_order_language' === get_option('wpifw_pdf_document_language') ) {
        $rtl_languages = array( 'ar', 'he', 'ur', 'he_IL' );
        $is_rtl = $order->get_meta('wpifw_invoice_rtl_' . $order->get_id());
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
 * Get order meta if set.
 * @param $order
 * @param $template
 * @param $current_action
 */
function woo_invo_ice_order_section_meta_data_callback( $order, $template , $current_action ) {

    if ( 'invoice' === $template || 'credit_note' == $template ) {
        $order_meta_label = get_option( '_winvoice_order_meta_label' );
        $order_meta_name = get_option( '_winvoice_order_meta_name' );
        $order_meta_place = get_option( '_winvoice_order_meta_name_position' );
        foreach ( $order_meta_label as $key => $value ) {
            if ( $current_action == $order_meta_place[ $key ] ) {
                $order_meta = get_post_meta($order->get_id(), $order_meta_name[ $key ] , true);

                if ( strpos($order_meta_name[ $key ], 'date') ) {
					$wpifw_date = get_post_meta($order->get_id(), $order_meta_name[ $key ], true);
					$order_meta = date(get_option( 'wpifw_date_format' ), strtotime($wpifw_date));
                }
				
                if ( ! empty($order_meta) && ! is_array($order_meta) ) {
                    if ( 'after_order_data' == $current_action || 'before_order_data' == $current_action ) {
                            echo '<tr><td class="order-data-label">'.$value.'</td><td class="order-data-value">'.': '.$order_meta.'</td></tr>';//phpcs:ignore
                    }else {
                        echo "<p>$value : $order_meta </p>"; //phpcs:ignore
                    }
                }
            }
        }
    }elseif ( 'packing_slip' === $template ) {
        $order_meta_label_ps = get_option( '_winvoice_order_meta_label_ps' );
        $order_meta_name_ps = get_option( '_winvoice_order_meta_name_ps' );
        $order_meta_place_ps = get_option( '_winvoice_order_meta_name_position_ps' );
        foreach ( $order_meta_label_ps as $key => $value ) {
            if ( $current_action == $order_meta_place_ps[ $key ] ) {
                $order_meta = get_post_meta($order->get_id(), $order_meta_name_ps[ $key ] , true);
                if ( strpos($order_meta_name_ps[ $key ], 'date') ) {
                    $order_meta = woo_invo_ice_get_formatted_date($order);
                }
                if ( ! empty($order_meta) && ! is_array($order_meta) ) {
                    if ( 'after_order_data' == $current_action || 'before_order_data' == $current_action ) {
                        echo '<tr><td class="order-data-label">'.$value.'</td><td class="order-data-value">'.': '.$order_meta.'</td></tr>';//phpcs:ignore
                    }else {
                        echo "<p>$value : $order_meta </p>"; //phpcs:ignore
                    }
                }
            }
        }
    }
}
//Adding Invoice Order Meta
$order_meta_place = ! empty( get_option( '_winvoice_order_meta_name_position' ) ) ? get_option( '_winvoice_order_meta_name_position' ) : array();
if ( (isset($order_meta_place) && is_array($order_meta_place) && count($order_meta_place) > 0) ) {
    foreach ( $order_meta_place as $key => $value ) {
        add_action('woo_invo_ice_'.$value, 'woo_invo_ice_order_section_meta_data_callback', 10, 3);
    }
}
//Adding Packing Slip Order Meta
$order_meta_place = ! empty( get_option( '_winvoice_order_meta_name_position_ps' ) ) ? get_option( '_winvoice_order_meta_name_position_ps' ) : array();
if ( (isset($order_meta_place) && is_array($order_meta_place) && count($order_meta_place) > 0) ) {
	foreach ( $order_meta_place as $key => $value ) {
		add_action('woo_invo_ice_'.$value, 'woo_invo_ice_order_section_meta_data_callback', 10, 3);
	}
}
