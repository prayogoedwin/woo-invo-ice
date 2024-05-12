<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

/**
 * Used to get formatted order information
 *
 * @link  https://astamatechnology.com
 * @since 1.0.0
 *
 * @package    Woo_Invo_Ice_Orders
 * @subpackage Woo_Invo_Ice_Orders/includes
 */

class Woo_Invo_Ice_Orders {
	/**
	 *  Init Helper Class
	 *
	 * @var Woo_Invo_Ice_Helper  Helper Class.
	 */
	private $helper;
	/**
	 * Hold Order Ids
	 *
	 * @var array $order_ids Contain order Ids.
	 */
	private $order_ids;
	/**
	 * Template Type
	 *
	 * @var string $template
	 */
	private $template;
	/**
	 * Vendor Id
	 *
	 * @var int|null
	 */
	private $vendor;

	/**
	 * Woo_Invo_Ice_Orders constructor.
	 *
	 * @param array $order_ids Order Ids.
	 * @param string $template Template Type.
	 * @param int $vendor Vendor Id.
	 */
	public function __construct( $order_ids, $template = 'invoice', $vendor = null ) {
		$this->order_ids = $order_ids;
		$this->template  = $template;
		$this->helper    = woo_invo_ice_helper();
		$this->vendor    = $vendor;
	}

	/**
	 * Get Orders info
	 *
	 * @return mixed
	 */
	public function get_orders_info() {
		if ( empty( $this->order_ids ) ) {
			return false;
		}
		global $locale;
		$r      = 0;
		$orders = array();
		foreach ( $this->order_ids as $key => $order_id ) {
			$order                  = wc_get_order( $order_id );
			$orders[ $r ]['status'] = '';
			if ( $order->is_paid() || 'completed' === $order->get_status() ) {
				$orders[ $r ]['status'] = 'completed';
			} elseif ( 'refunded' === $order->get_status() ) {
				$orders[ $r ]['status'] = 'refunded';
			}
			$orders[ $r ]['ID']              = $order_id;
			$orders[ $r ]['order_info']      = $this->get_order_info( $order );
			$orders[ $r ]['wpml_language']   = $order->get_meta( 'wpml_language' );
			$orders[ $r ]['site_language']   = $locale;
			$orders[ $r ]['billing_info']    = $this->get_address( $order, 'billing', $this->template );
			$orders[ $r ]['shipping_info']   = $this->get_address( $order, 'shipping', $this->template );
			$orders[ $r ]['order_note']      = $order->get_customer_note();
			$orders[ $r ]['items']           = $this->get_order_items( $order );
			$orders[ $r ]['bank_accounts']   = $this->helper->get_bank_accounts( $order );
			$orders[ $r ]['shipping_method'] = $order->get_shipping_method();

			if ( class_exists( 'WCFM' ) ) {
				$orders[ $r ]['vendor_address'] = $this->get_product_vendor_address( $order );
			}


			$totals = array(
				'subtotal'          => $this->get_subtotal( $order ),
				'discount_total'    => $this->get_discount_total( $order ),
				'tax_total'         => $this->get_tax_total( $order ),
				'shipping_total'    => $this->get_shipping_total( $order ),
				'total_without_tax' => $this->get_total_with_or_without_tax( $order ),
				'total_fees'        => $this->get_order_total_fees( $order ),
				'fees'              => $this->get_fees( $order ),
				'grand_total'       => $this->get_order_total( $order ),
				'total_refund'      => $this->get_refunded_total( $order ),
				'net_total'         => $this->get_net_total( $order ),
				'shipping_methods'  => $this->get_shipping_methods( $order ),
				'refunds'           => $this->get_order_refunds( $order ),
				'paid'              => $this->get_total_paid( $order ),
			);


			$packing_total = array(
				'quantity' => $this->get_product_total_quantity( $order ),
				'weight'   => $this->get_product_total_weight( $order ),
			);

			// Total filter.
			if ( has_filter( 'woo_invo_ice_order_total' ) ) {
				$totals = apply_filters( 'woo_invo_ice_order_total', $totals, $this->template, $order );
			}

			// Packing total filter.
			if ( has_filter( 'woo_invo_ice_packing_total' ) ) {
				$packing_total = apply_filters( 'woo_invo_ice_packing_total', $packing_total, $this->template, $order );
			}

			$orders[ $r ]['totals']        = $totals;
			$orders[ $r ]['packing_total'] = $packing_total;

			$r ++;

		}

		return $orders;
	}


	/**
	 * Get Order Items
	 *
	 * @param WC_Order $order Order Object.
	 *
	 * @return array
	 * @see https://stackoverflow.com/questions/40711160/woocommerce-getting-the-order-item-price-and-quantity
	 */
	private function get_order_items( $order ) {
		$items    = $order->get_items();
		$products = array();
		foreach ( $items as $key => $item ) {
			$item_info     = $item->get_data();
			$product_id    = $item_info['product_id'];
			$item_quantity = $item->get_quantity();
			if ( $item_info['variation_id'] ) {
				$product_id = $item_info['variation_id'];
			}

			$product = wc_get_product( $product_id );
//            if ( ! is_null($this->vendor) && get_post_field('post_author', $product->get_id()) !== $this->vendor ) {
//                continue;
//            }

			$products[ $key ]['id']           = $product_id;
			$products[ $key ]['raw_total']    = $item_info['total'];
			$products[ $key ]['raw_price']    = $item_info['subtotal'] / $item_info['quantity'];
			$products[ $key ]['raw_quantity'] = $item->get_quantity();
			$products[ $key ]['raw_title']    = $item->get_name();
			$products[ $key ]['raw_weight']   = $this->get_item_weight( $product, $item_quantity );

			if ( $product ) {
				$products[ $key ]['product-img'] = $this->get_item_image( $product, $item );
			}else {
				unset( $products[ $key ]['product-img'] );
			}

			$products[ $key ]['product'] = $this->get_item_title( $order, $product, $item );

			$products[ $key ]['product_meta'] = $this->get_item_meta( $item );
			if ( 'packing_slip' === $this->template ) { // Packing Slip Item Data.
				$products[ $key ]['dimension'] = wc_format_dimensions( $this->get_item_dimension( $product ) );
				$products[ $key ]['weight']    = wc_format_weight( $this->get_item_weight( $product, $item_quantity ) );
				$products[ $key ]['quantity']  = $item->get_quantity();
			} elseif ( 'invoice' === $this->template || 'credit_note' === $this->template ) { // Invoice Item Data.
				$products[ $key ]['price']                = $this->helper->format_price( $order, $item_info['subtotal'] / $item_info['quantity'] );
				$products[ $key ]['quantity']             = $this->get_item_quantity( $order, $item->get_id(), $item->get_quantity() );
				$products[ $key ]['total']                = $this->get_item_total_price( $order, $item->get_id(), $item_info['total'] );
				$products[ $key ]['total_inc_discounted'] = $this->get_item_total_price( $order, $item->get_id(), $item_info['total'] );
				$products[ $key ]['total_ex_discounted']  = $this->helper->format_price( $order, $item->get_subtotal() ); // Get the item line total non discounted
				$products[ $key ]['tax']                  = $this->get_item_tax( $order, $item, $item_info['total_tax'] );
				$products[ $key ]['tax_inc_discounted']   = $this->get_item_tax( $order, $item, $item_info['total_tax'] );
				$products[ $key ]['tax_ex_discounted']    = $this->helper->format_price( $order, $item->get_subtotal_tax() ); // Get the item line total tax non discounted
				$products[ $key ]['tax_rate']             = $this->get_item_tax_rate( $order, $item );

				/**
				 * Sale price should be always price ( $products[ $key ]['price']). Because $product->get_regular_price() function gives latest price.
				 * Ex. 1 product sale price was $15 during order. Now $product->get_regular_price() function give $15
				 * After few days sale price is changed. i.e $12 Then $product->get_regular_price() function sale price will give
				 * $12 for all order this is not true for all order. That's why it should be price ( $products[ $key ]['price'])
				 */
				$products[ $key ]['sale_price']             = $products[ $key ]['price'];

				if ( is_a( $product, 'WC_Product' ) ) {
					$products[ $key ]['regular_price'] = $this->helper->format_price( $order, $product->get_regular_price() );
				} else {
					// Product doesn't exist, set regular price to price
					$products[ $key ]['regular_price'] = $this->helper->format_price( $order, $item_info['subtotal'] / $item_info['quantity'] );
				}

				$products[ $key ]['regular_price_with_tax'] = $this->get_regular_price_with_tax($order, $product,$item);
				$products[ $key ]['sale_price_with_tax']    = $this->get_sale_price_with_tax($order, $product,$item);
				$products[ $key ]['price_with_tax']         = $this->get_price_with_tax($order, $product,$item);
				$products[ $key ]['total_inc_tax']          = $this->helper->format_price( $order, $this->total_with_tax( $item_info['total'], $item_info['total_tax'] ) );
				$products[ $key ]['total_ex_tax']           = $this->helper->format_price( $order, $item_info['total'] );
				$products[ $key ]['discount']               = $this->helper->format_price($order, $item->get_subtotal() - $item->get_total());
			}
		}

		if ( has_filter( 'woo_invo_ice_product_data' ) ) {
			$products = apply_filters( 'woo_invo_ice_product_data', $products, $this->template, $order );
		}

		return $products;
	}

	/**
	 * Get woocommerce booking info.
	 *
	 * @param WC_Order_Item $item Order Item Object.
	 *
	 * @return string
	 */
	protected function get_booking_info( $item ) {
		// Get booking information.
		$booking_ids  = WC_Booking_Data_Store::get_booking_ids_from_order_item_id( $item->get_id() );
		$booking_data = '';
		foreach ( $booking_ids as $booking_id ) {
			$booking          = get_wc_booking( $booking_id );
			$product          = $booking->get_product();
			$resource         = $booking->get_resource();
			$label            = $product && is_callable( array(
				$product,
				'get_resource_label',
			) ) && $product->get_resource_label() ? $product->get_resource_label() : __( 'Type', 'woocommerce-bookings' ); //phpcs:ignore
			$booking_timezone = str_replace( '_', ' ', $booking->get_local_timezone() );
			// Get booking number.
			$booking_number = sprintf( __( 'Booking #%s', 'woocommerce-bookings' ), (string) $booking->get_id() ); //phpcs:ignore
			$booking_data   .= '<br/><b><i>' . $booking_number . '</i></b><br/>';
			// Get booking time.
			$get_local_time = wc_should_convert_timezone( $booking );
			if ( strtotime( 'midnight', $booking->get_start() ) === strtotime( 'midnight', $booking->get_end() ) ) {
				$booking_date = sprintf( __( '%1$s', 'woocommerce-bookings' ), $booking->get_start_date( null, null, $get_local_time ) );//phpcs:ignore
			} else {
				$booking_date = sprintf( __( '%1$s - %2$s', 'woocommerce-bookings' ), $booking->get_start_date( null, null, $get_local_time ), $booking->get_end_date( null, null, $get_local_time ) );//phpcs:ignore
			}
			$booking_data .= $booking_date . '<br/>';
			// Get booking timezone.
			if ( wc_should_convert_timezone( $booking ) ) :
				/* translators: %s: timezone name */
				$booking_data .= ucwords( sprintf( __( 'in timezone: %s', 'woocommerce-bookings' ), $booking_timezone ) ); //phpcs:ignore
				$booking_data .= '<br/>';
			endif;
			// Booking Type.
			if ( $resource ) :
				$booking_data .= sprintf( __( '%1$s: %2$s', 'woocommerce-bookings' ), $label, $resource->get_name() ); //phpcs:ignore
				$booking_data .= '<br/>';
			endif;
			// Booking Person.
			if ( $product && $product->has_persons() ) {
				if ( $product->has_person_types() ) {
					$person_types  = $product->get_person_types();
					$person_counts = $booking->get_person_counts();
					if ( ! empty( $person_types ) && is_array( $person_types ) ) {
						foreach ( $person_types as $person_type ) {
							if ( empty( $person_counts[ $person_type->get_id() ] ) ) {
								continue;
							}
							$booking_data .= sprintf( __( '%1$s: %2$d', 'woocommerce-bookings' ), $person_type->get_name(), $person_counts[ $person_type->get_id() ] );//phpcs:ignore
						}
					}
				} else {
					/* translators: 1: person count */
					$booking_data .= sprintf( __( '%d Persons', 'woocommerce-bookings' ), array_sum( $booking->get_person_counts() ) ); //phpcs:ignore
				}
			}
		}

		return $booking_data;
	}

	/**
	 * Get Product information
	 *
	 * @param WC_Order $order Order Object.
	 * @param integer $product_id Product id.
	 * @param WC_Order_Item $item Order Item Object.
	 * @param string $template Template Type.
	 * @param int|null $vendor Vendor Id.
	 *
	 * @return string
	 */
	private function get_product_title( $order, $product_id, $item, $template, $vendor ) {

		$name    = '';
		$title   = $item->get_name();
		$title   = apply_filters( 'woo_invo_ice_item_title', $title, $order, $product_id, $item->get_id() );
		$product = wc_get_product( $product_id );
		$sku     = $product->get_sku();
		// Product Title Length Setting.
		if ( 'packing_slip' === $template ) {
			$product_title_length = get_option( 'wiopt_packingslip_product_title_length' );
		} else {
			$product_title_length = get_option( 'wiopt_invoice_product_title_length' );
		}

		if ( strlen( $title ) > $product_title_length && false !== $product_title_length && '' !== $product_title_length ) {
			$name .= '<b>' . substr( $title, 0, $product_title_length ) . '</b>...';
		} else {
			$name .= '<b>' . $title . '</b>';
		}

		// If Woocommerce booking plugin is active.
		if ( class_exists( 'WC_Booking_Data_Store' ) ) {
			$name .= $this->get_booking_info( $item );
		}

		// If YITH Booking plugin is active then get product title.
		if ( class_exists( 'YITH_WCBK' ) ) {
			$name .= $this->yith_booking_info( $item, $order->get_id(), $product_id );
		}

		$name .= "<span class='product-meta'>";
		// Action Before the item meta.
		$name .= woo_invo_ice_before_item_meta( $product, $order, $template );

		// Show SKU or ID.
		if ( 'packing_slip' === $template ) {
			$display_info = get_option( 'wiopt_packingslip_disid', true );
		} else {
			$display_info = get_option( 'wiopt_disid', true );
		}

		if ( ! empty( $display_info ) ) {
			if ( 'ID' == $display_info ) {
				$name .= "<br/><span class='product-meta'>" . woo_invo_ice_filter_label( 'ID', $order, $template ) . ":" . $product_id . '</span>';
			} elseif ( 'SKU' == $display_info ) {
				if ( ! empty( $sku ) ) {
					$name .= "<br/><span class='product-meta'>" . woo_invo_ice_filter_label( 'SKU', $order, $template ) . ":" . $sku . '</span>';
				}
			}
		}

		// Show Order Item metas.
		// if ( ! empty($meta = $this->get_item_meta($item)) ) {
		//     $name .= $meta;
		// }

		// Show Product Categories.
		if ( 'packing_slip' === $template ) {
			$display_category = get_option( 'wiopt_packingslip_product_category_show', true );
		} else {
			$display_category = get_option( 'wiopt_product_category_show', true );
		}

		if ( $display_category ) {
			$terms = get_the_terms( $product_id, 'product_cat' );
			if ( $terms && ! is_wp_error( $terms ) ) {
				$categories = array_column( $terms, 'name' );
				// $categories = implode( $categories, ', ' ); deprecated
				$categories = implode( ', ', $categories );
				if ( ! empty( $categories ) ) {
					$name .= "<br/><span class='product-meta'>" . woo_invo_ice_filter_label( 'Category', $order, $template ) . ": " . $categories . '</span>';
				}
			}
		}

		// Show Product Description.
		if ( 'packing_slip' === $template ) {
			$display_description = get_option( 'wiopt_packingslip_product_description_show', true );
			$description_length  = get_option( 'wiopt_packingslip_description_limit' );
		} else {
			$display_description = get_option( 'wiopt_product_description_show', true );
			$description_length  = get_option( 'wiopt_invoice_description_limit' );
		}


		if ( ! empty( $display_description ) && 'none' !== $display_description ) {
			if ( 'short' === $display_description ) {
				$s_description = strip_tags( $product->get_short_description() );
				if ( '' !== $s_description ) {
					if ( strlen( $s_description ) > $description_length ) {
						$s_description = wp_trim_words( $s_description, $description_length, '...' );
					}
					$name .= "<br/><span class='product-meta'>" . woo_invo_ice_filter_label( 'Description', $order, $template ) . ": " . $s_description . '</span>';
				}
			} elseif ( 'long' === $display_description ) {
				$l_description = wp_strip_all_tags( apply_filters( 'the_content', $product->post->post_content ) );//phpcs:ignore
				if ( '' !== $l_description ) {
					if ( strlen( $l_description ) > $description_length ) {
						$l_description = wp_trim_words( $l_description, $description_length, '...' );
					}
					$name .= "<br/><span class='product-meta'>" . woo_invo_ice_filter_label( 'Description', $order, $template ) . ": " . $l_description . '</span>';
				}
			}
		}

		// Show Product Attributes.
		if ( 'invoice' === $template || 'credit_note' === $template ) {
			$attributes = get_option( 'wiopt_product_attribute_show', true );
			if ( ! empty( $attributes ) && is_array( $attributes ) ) {
				$attr_info = array();
				foreach ( $attributes as $key => $attribute ) {

					$attribute_value = $product->get_attribute( $attribute );
					if ( $attribute_value ) {
						$attr_info[] = ucwords( $attribute ) . ': ' . $attribute_value;
					}
				}
				foreach ( $attr_info as $key => $value ) {
					if ( ! empty( $attr_info ) ) {
						if ( version_compare( PHP_VERSION, '7.4', '<' ) ) {
							$name .= "<br/><span class='product-meta'>" . $value . '</span>';//TODO
						} else {
							$name .= "<br/><span class='product-meta'>" . $value . '</span>';//TODO
						}
					}
				}
			}
		}

		// Show Product Dimensions.
		if ( 'invoice' === $template || 'credit_note' === $template ) {
			$dimensions = get_option( 'wiopt_invoice_product_dimension_show', true );
			if ( ! empty( $dimensions ) ) {
				$dimensions = wc_format_dimensions( array(
					'width'  => $product->get_width(),
					'height' => $product->get_height(),
					'length' => $product->get_length(),
				) );
				$weight     = wc_format_weight( $product->get_weight() );
				if ( 'N/A' !== $dimensions ) {
					$name .= "<br><span class='product-meta'> " . woo_invo_ice_filter_label( 'Dimensions', $order, $template ) . ': ' . $dimensions . '</span></br>'; //phpcs:ignore
				}
				if ( 'N/A' !== $weight ) {
					$name .= "<br><span class='product-meta'> " . woo_invo_ice_filter_label( 'Weight', $order, $template ) . ': ' . $weight . '</span></br>'; //phpcs:ignore
				}
			}
		}
		// Show product meta.
		if ( 'invoice' === $template || 'credit_note' === $template ) {
			$post_meta = get_option( 'wiopt_custom_post_meta' );

		} else {
			$post_meta = get_option( 'wiopt_custom_post_meta_ps' );
		}
		if ( ! empty( $post_meta ) ) {
			foreach ( $post_meta as $meta => $label ) {
				$meta_value = apply_filters( 'woo_invo_ice_meta_value_filter', get_post_meta( $item->get_product_id(), $meta, true ), $meta );
				if ( '' !== $meta_value && ! is_array( $meta_value ) ) {
					$name .= "<br><span class='product-meta'>" . woo_invo_ice_filter_label( $label, $order, $template ) . ': ' . $meta_value . '</span></br>';
				}
			}
		}

		// Show order item meta.
		if ( 'invoice' === $template || 'credit_note' === $template ) {
			$order_item_meta = get_option( 'wiopt_order_item_meta' );
		} elseif ( 'packing_slip' === $template ) {
			$order_item_meta = get_option( 'wiopt_order_item_meta_ps' );
		}

		if ( ! empty( $order_item_meta ) ) {
			$item_id = $item->get_id();
			foreach ( $order_item_meta as $meta => $label ) {
				$meta_value = wc_get_order_item_meta( $item_id, $meta, true );
				if ( '' !== $meta_value && ! is_array( $meta_value ) ) {
					$name .= "<br><span class='product-meta'>" . woo_invo_ice_filter_label( $label, $order, $template ) . ': ' . $meta_value . '</span></br>';
				}
			}
		}

		if ( 'packing_slip' === $template && ! is_null( $vendor ) ) {
			if ( get_option( 'wiopt_packingslip_product_display_vendor', true ) ) {
				$vendor_name = get_the_author_meta( 'display_name', $vendor );
				$name        .= "<br><span class='product-meta'>" . woo_invo_ice_filter_label( 'Vendor', $order, $template ) . ": " . $vendor_name . '</span>';
			}
		}

		//  WooCommerce Product Add-Ons Ultimate data add .
		$hidden_order_itemmeta = apply_filters(
			'woocommerce_hidden_order_itemmeta',
			array(
				'_qty',
				'_tax_class',
				'_product_id',
				'_variation_id',
				'_line_subtotal',
				'_line_subtotal_tax',
				'_line_total',
				'_line_tax',
				'method_id',
				'cost',
				'_reduced_stock',
			)
		);

		if ( $meta_data = $item->get_formatted_meta_data( '' ) ) : ?>
			<?php
			$formatted_meta_data = '';
			foreach ( $meta_data as $meta_id => $meta ) :
				if ( in_array( $meta->key, $hidden_order_itemmeta, true ) ) {
					continue;
				}

				$meta_data = strip_tags( $meta->display_value );
				$met_key   = str_replace( '_', ' ', $meta->display_key );
				// remove meta data from YITH PDF plugin.
				if ( preg_match( "/ywpi/i", $met_key ) ) {
					$met_key = str_replace( 'ywpi', '', $met_key );
					break;
				}

				// if( '' !== $meta_data[0] && null !== $meta_data[0] && ! empty($meta_data[0])){
				$formatted_meta_data .= "<br><span class='product-meta'>" . wp_kses_post( $met_key ) . " : " . $meta_data . '</span>';
				// }

			endforeach;
			$name .= apply_filters( 'woo_invo_ice_display_formatted_meta_data', $formatted_meta_data, $this->template );
		endif;

		// Action After the item meta.
		$name .= woo_invo_ice_after_item_meta( $product, $order, $template );

		return $name;

	}

	/**
	 * Get booking info from YITH Booking plugin.
	 *
	 * @param WC_Order_Item $item .
	 *
	 * @return mixed|string
	 */

	private function yith_booking_info( $item, $order_id, $product_id ) {

		$booking_detail = '';
		$booking_id     = wc_get_order_item_meta( $item->get_id(), '_booking_id', true );

		if ( isset( $booking_id ) && '' != $booking_id ) {
			$booking        = new YITH_WCBK_Booking( $booking_id );
			$sent_to_admin  = false;
			$booking_status = __( $booking->get_status_text(), 'yith-booking-for-woocommerce' ); //phpcs:ignore

			$booking_duration = __( $booking->get_duration_html(), 'yith-booking-for-woocommerce' ) . '<br/>'; //phpcs:ignore

			$booking_from = __( $booking->woo_invo_ice_get_formatted_date( 'from' ), 'yith-booking-for-woocommerce' ) . '<br/>'; //phpcs:ignore

			$booking_to = __( $booking->woo_invo_ice_get_formatted_date( 'to' ), 'yith-booking-for-woocommerce' ); //phpcs:ignore

			if ( $booking->has_persons() && ! $booking->has_person_types() ) {
				$booking_persons = sprintf( __( '<br/><span class="product-meta"><b>%1$s : </b> %2$u</span>', 'yith-booking-for-woocommerce' ), yith_wcbk_get_label( 'people' ), $booking->persons ); //phpcs:ignore
			}
			// Get additional service.
			if ( $services = $booking->get_service_names( $sent_to_admin, 'additional' ) ) {
				$booking_aditional_services = sprintf( __( '<br/><span class="product-meta"><b>%1$s : </b> %2$s</span>', 'yith-booking-for-woocommerce' ), yith_wcbk_get_label( 'additional-services' ), yith_wcbk_booking_services_html( $services ) ); //phpcs:ignore
			} else {
				$booking_aditional_services = '';
			}
			// Get included service.
			if ( $services = $booking->get_service_names( $sent_to_admin, 'included' ) ) {
				$booking_included_services = sprintf( __( '<br/><span class="product-meta"><b>%1$s : </b>  %2$s</span>', 'yith-booking-for-woocommerce' ), yith_wcbk_get_label( 'included-services' ), yith_wcbk_booking_services_html( $services ) ); //phpcs:ignore
			} else {
				$booking_included_services = '';
			}
			// Get booking persons with type.
			if ( $booking->has_persons() && $booking->has_person_types() ) {
				$i                   = 0;
				$booking_person_type = '<br/><span class="product-meta"><b>' . yith_wcbk_get_label( 'people' ) . ':</b> </span>';
				foreach ( $booking->person_types as $person_type ) {
					if ( ! $person_type['number'] ) {
						continue;
					}
					$person_type_id     = absint( $person_type['id'] );
					$person_type_title  = YITH_WCBK()->person_type_helper->get_person_type_title( $person_type_id );
					$person_type_title  = ! ( $person_type_title ) ? $person_type_title : $person_type['title'];
					$person_type_number = absint( $person_type['number'] );
					$i ++;
					if ( count( $booking->person_types ) == $i ) {
						$booking_person_type .= $person_type_title . ' : ' . $person_type_number;
					} else {
						$booking_person_type .= $person_type_title . ' : ' . $person_type_number . ', ';
					}
				}

				// Get total person.
				$booking_total_person = sprintf( '<br/><span class="product-meta"><b>%s : </b> %u</span>', yith_wcbk_get_label( 'total-people' ), $booking->persons );

			} else {
				$booking_person_type  = '';
				$booking_total_person = '';
			}
			$order = wc_get_order( $order_id );

			// put all value in one variable and return.
			$booking_detail .= '<br/><span class="product-meta">';
			$booking_detail .= woo_invo_ice_filter_label( 'Duration', $order, $this->template );
			$booking_detail .= ' : ' . $booking_duration . '</span>';
			$booking_detail .= '<span class="product-meta">';
			$booking_detail .= woo_invo_ice_filter_label( 'From', $order, $this->template );
			$booking_detail .= ' : ' . $booking_from . '</span>';
			$booking_detail .= '<span class="product-meta">';
			$booking_detail .= woo_invo_ice_filter_label( 'To', $order, $this->template );
			$booking_detail .= ' : ' . $booking_to . '</span>';

			$booking_detail .= ( isset( $booking_persons ) && ( '' != $booking_persons ) );
			$booking_detail .= $booking_aditional_services;
			$booking_detail .= $booking_included_services;
			$booking_detail .= $booking_person_type;
			$booking_detail .= $booking_total_person;

			return $booking_detail;
		}

		return '';
	}

	/**
	 * Get Order Fees.
	 *
	 * @param WC_Order $order Order Object.
	 *
	 * @return array
	 */
	private function get_fees( $order ) {
		$fees = $order->get_fees();

		$data = array();
		foreach ( $fees as $key => $fee ) {
			$data[ $key ]['name']      = $fee->get_name();
			$data[ $key ]['total']     = $fee->get_total();
			$data[ $key ]['total_tax'] = ( $fee->get_total_tax() > 0 ) ? $fee->get_total_tax() : '&ndash;';
			$data[ $key ]['tax_rate']  = $this->get_item_tax_rate( $order, $fee );
		}


		return $data;
	}

	/**
	 * Get Order Shipping Methods Info.
	 *
	 * @param WC_Order $order Order Object.
	 *
	 * @return array
	 */
	private function get_shipping_methods( $order ) {
		$shipping_methods = $order->get_shipping_methods();
		$data             = array();
		if ( ! empty( $shipping_methods ) ) {
			foreach ( $shipping_methods as $key => $method ) {
				$data[ $key ]['name']     = $method->get_method_title();
				$data[ $key ]['total']    = $method->get_total();
				$data[ $key ]['tax']      = ( $method->get_total_tax() > 0 ) ? $method->get_total_tax() : '&ndash;';
				$data[ $key ]['tax_rate'] = $this->get_item_tax_rate( $order, $method );
				$data[ $key ]['items']    = $this->shipping_method_meta( $method );
			}
		}

		return $data;
	}

	/**
	 * Get Shipping Metas.
	 *
	 * @param WC_Order_Item_Shipping $method WooCommerce Meta Data Object.
	 *
	 * @return array
	 */
	private function shipping_method_meta( $method ) {
		$shipping_metas = $method->get_formatted_meta_data();
		$data           = array();
		if ( ! empty( $shipping_metas ) ) {
			foreach ( $shipping_metas as $key => $value ) {
				$data[ $value->display_key ] = $value->display_value;
			}
		}

		return $data;
	}

	/**
	 * Get Order Refunds info.
	 *
	 * @param WC_Order $order Order Object.
	 *
	 * @return array
	 */
	private function get_order_refunds( $order ) {
		$refunds = $order->get_refunds();
		$data    = array();
		if ( ! empty( $refunds ) ) {
			foreach ( $refunds as $key => $refund ) {
				$data[ $key ]['id']        = $refund->get_id();
				$data[ $key ]['total']     = $this->helper->format_price( $order, $refund->get_total() );
				$data[ $key ]['tax_total'] = $refund->get_total_tax();
				$data[ $key ]['date']      = $this->helper->woo_invo_ice_get_formatted_date( $order );
				$data[ $key ]['reason']    = $refund->get_reason();
			}
		}

		return $data;
	}


	/**
	 * Get Product total quantity.
	 *
	 * @param WC_Order $order Order Object.
	 *
	 * @return array
	 */
	private function get_product_total_quantity( $order ) {
		$item_quantity = 0;
		foreach ( $order->get_items() as $item_id => $item_data ) {
			$item_quantity += $item_data->get_quantity(); // Get the item quantity.
		}

		return $item_quantity;
	}

	/**
	 * Get product total weight
	 *
	 * @param WC_Order $order Order Object.
	 *
	 * @return float
	 */
	private function get_product_total_weight( $order ) {
		$items                = $order->get_items();
		$products             = array();
		$product_total_weight = 0;
		foreach ( $items as $key => $item ) {
			$item_info  = $item->get_data();
			$product_id = $item_info['product_id'];
			$product    = wc_get_product( $product_id );
			if ( 'packing_slip' === $this->template ) { // Packing Slip Item Data.
				$quantity                   = $item->get_quantity();
				$products[ $key ]['weight'] = wc_format_weight( $this->get_item_weight( $product, $quantity ) );
			}
			if ( ! empty( $products[ $key ]['weight'] ) ) {
				$product_total_weight += (float) $products[ $key ]['weight'];
			}
		}

		return apply_filters('woo_invo_ice_packing_total_weight', $product_total_weight, $items, $order, $this->template );
	}

	/**
	 * Get Vendor Address as Product Refund Address.
	 *
	 * @param WC_Order $order Order Object.
	 *
	 * @return string
	 */
	private function get_product_vendor_address( $order ) {
		foreach ( $order->get_items() as $item ) {
			$item_info  = $item->get_data();
			$product_id = $item_info['product_id'];
		}

		global $WCFM;//phpcs:ignore
		$vendor_id = $WCFM->wcfm_vendor_support->wcfm_get_vendor_id_from_product( $product_id );//phpcs:ignore
		if ( $vendor_id ) {
			$store_user     = wcfmmp_get_store( $vendor_id );
			$get_store_info = $store_user->get_shop_info();
			$store_info     = isset( $get_store_info['address'] ) ? $get_store_info['address'] : '';
			$street_1       = isset( $store_info['street_1'] ) ? $store_info['street_1'] : '';
			$street_2       = isset( $store_info['street_2'] ) ? $store_info['street_2'] : '';
			$vendor_country = isset( WC()->countries->countries[ $store_info['country'] ] ) ? WC()->countries->countries[ $store_info['country'] ] : '';

			return array(
				'city'     => isset( $store_info['city'] ) ? $store_info['city'] : '',
				'zip_code' => isset( $store_info['zip'] ) ? $store_info['zip'] : '',
				'address'  => $street_1 . '' . $street_2,
				'country'  => $vendor_country,
				'phone'    => isset( $get_store_info['phone'] ) ? $get_store_info['phone'] : '',
				'email'    => isset( $get_store_info['store_email'] ) ? $get_store_info['store_email'] : '',
			);
		}

	}

	/**
	 * Get Order Item Metas
	 *
	 * @param WC_Order_Item $item Item Object.
	 *
	 * @return mixed
	 */
	private function get_item_meta( $item ) {

		$data                  = '';
		$hidden_order_itemmeta = apply_filters(
			'woocommerce_hidden_order_itemmeta',
			array(
				'_qty',
				'_tax_class',
				'_product_id',
				'_variation_id',
				'_line_subtotal',
				'_line_subtotal_tax',
				'_line_total',
				'_line_tax',
				'method_id',
				'cost',
				'_reduced_stock',
			)
		);
		$metas                 = $item->get_formatted_meta_data();
		if ( ! empty( $metas ) ) {
			foreach ( $metas as $key => $meta ) {
				if ( in_array( $meta->key, $hidden_order_itemmeta, true ) ) {
					continue;
				}
				/* translators: %s: Item Meta */
				$data .= "<br/><span class='product-meta'>" . esc_html__( sprintf( '%s', wp_kses_post( $meta->display_key ) ), 'woocommerce' ) . " : " . wp_kses_post( force_balance_tags( $meta->value ) ) . '</span>';//phpcs:ignore
			}
		}
		$product = $item->get_product();
		if ( function_exists( 'wc_display_item_meta' ) ) { // WC3.0+
			$data .= wc_display_item_meta( $item, array(
				'echo' => false,
			) );
		} else {
			if ( version_compare( WOOCOMMERCE_VERSION, '2.4', '<' ) ) {
				$meta = new \WC_Order_Item_Meta( $item['item_meta'], $product );
			} else {
				$meta = new \WC_Order_Item_Meta( $item, $product );
			}
			$data .= $meta->display( false, true );
		}

		return $data;
	}

	/**
	 * Get Order Item Price
	 *
	 * @param WC_Order $order Order Object.
	 * @param int $item_id Item Id.
	 * @param float $price Item Price.
	 *
	 * @return mixed|string
	 */
	private function get_item_total_price( $order, $item_id, $price ) {
		// Get the refunded amount for a line item.
		$item_total_refunded = $order->get_total_refunded_for_item( $item_id );
		$price               = $this->helper->format_price( $order, $price );
		if ( $item_total_refunded > 0 ) {
			$item_total_refunded = $this->helper->format_price( $order, $item_total_refunded );
			$price               = $price . "<br/><span class='refund small'>-&nbsp;" . $item_total_refunded . '</span>';
		}

		return $price;
	}

	/**
	 * Get Order Item Quantity
	 *
	 * @param WC_Order $order Order Object.
	 * @param int $item_id Item Id.
	 * @param int $qty Item Quantity.
	 *
	 * @return mixed|string
	 */
	private function get_item_quantity( $order, $item_id, $qty ) {
		// Get the refunded quantity for a line item.
		$item_qty_refunded = $order->get_qty_refunded_for_item( $item_id );
		if ( $item_qty_refunded < 0 ) {
			$qty = $qty . "<br/><span class='refund small'>" . $item_qty_refunded . '</span>';
		}

		return '<small class="times">&times;</small> ' . $qty;
	}


	/**
	 * Get Order Item Tax
	 *
	 * @param WC_Order $order Order Object.
	 * @param WC_Order_Item $item Order Item Object.
	 * @param float $tax $item tax.
	 *
	 * @return mixed|string
	 */
	private function get_item_tax( $order, $item, $tax ) {
		// Get the refunded tax amount for a line item.
		$rate_id           = $this->tax_rate_id( $order, $item );
		$item_tax_refunded = $order->get_tax_refunded_for_item( $item->get_id(), $rate_id );
		$tax               = $this->helper->format_price( $order, $tax );
		if ( $item_tax_refunded > 0 ) {
			$item_tax_refunded = $this->helper->format_price( $order, $item_tax_refunded );
			$tax               = $tax . "<br/><span class='refund small'>-&nbsp;" . $item_tax_refunded . '</span>';
		}

		return $tax;
	}

	/**
	 * Get Order total with Tax
	 *
	 * @param WC_Order $sale_price
	 * @param WC_Order $tax
	 *
	 * @return float
	 */
	private function total_with_tax( $sale_price, $tax ) {
		return ( floatval( $sale_price ) + floatval( $tax ) );
	}

	/**
	 * Get Regular Price with Tax for a Single Unit
	 *
	 * @param WC_Order $order Order Object.
	 * @param WC_Product $product Product Object.
	 * @param WC_Order_Item $item Order Item Object.
	 *
	 * @return mixed|string
	 */
	private function get_regular_price_with_tax($order, $product, $item) {
		// If the product has tax, get the regular price with tax, otherwise, get the regular price.
		if (is_a($product, 'WC_Product') && $product->is_taxable()) {
			$regular_price_with_tax = $this->helper->format_price($order, wc_get_price_including_tax($product, ['price' => $product->get_regular_price()]));
		} elseif (is_a($product, 'WC_Product')) {
			$regular_price_with_tax = $this->helper->format_price($order, $product->get_regular_price());
		} else {
			// Product doesn't exist, set regular price with tax to "Product doesn't exist"
			$regular_price_with_tax = apply_filters('wiopt_regular_price_does_not_exist_message', "Product doesn't exist");
		}

		return $regular_price_with_tax;
	}

	/**
	 * Get Sale Price with Tax for a Single Unit
	 *
	 * @param WC_Order $order Order Object.
	 * @param WC_Product $product Product Object.
	 * @param WC_Order_Item $item Order Item Object.
	 *
	 * @return mixed|string
	 */
	private function get_sale_price_with_tax($order, $product, $item) {
		// If the product has tax, get the sale price with tax, otherwise, get the sale price.
		if (is_a($product, 'WC_Product') && $product->is_taxable()) {
			$sale_price_with_tax = $this->helper->format_price($order, wc_get_price_including_tax($product, ['price' => $product->get_sale_price()]));
		} elseif (is_a($product, 'WC_Product')) {
			$sale_price_with_tax = $this->helper->format_price($order, $product->get_sale_price());
		} else {
			// Product doesn't exist, set sale price with tax to "Product doesn't exist"
			$sale_price_with_tax = apply_filters('wiopt_sale_price_does_not_exist_message', "Product doesn't exist");
		}

		return $sale_price_with_tax;
	}

	/**
	 * Get Price with Tax for a Single Unit
	 *
	 * @param WC_Order $order Order Object.
	 * @param WC_Product $product Product Object.
	 * @param WC_Order_Item $item Order Item Object.
	 *
	 * @return mixed|string
	 */
	private function get_price_with_tax($order, $product, $item) {
		// If the product exists and has tax, get the price with tax, otherwise, get the price.
		if (is_a($product, 'WC_Product') && $product->is_taxable()) {
			$price_with_tax = $this->helper->format_price($order, wc_get_price_including_tax($product));
		} elseif (is_a($product, 'WC_Product')) {
			$price_with_tax = $this->helper->format_price($order, $product->get_price());
		} else {
			// Product doesn't exist, set price with tax to "Product doesn't exist on your store"
			$price_with_tax = apply_filters('wiopt_price_with_tax_does_not_exist_message', "Product doesn't exist");
		}

		return $price_with_tax;
	}

	/**
	 * Get Order Item Tax Rate
	 *
	 * @param WC_Order $order Order Item Object.
	 * @param WC_Order_Item $item Order Item Object.
	 *
	 * @return mixed|string
	 */
	private function get_item_tax_rate( $order, $item ) {
		$taxes      = $item->get_taxes();
		$tax_detail = $this->helper->order_tax_info( $order );
		if ( isset( $taxes['total'] ) && ! empty( $taxes['total'] ) ) {
			foreach ( $taxes['total'] as $rate_id => $tax ) {
				if ( ! empty( $tax ) ) {
					return ! empty( $tax_detail[ $rate_id ]['rate'] ) ? $tax_detail[ $rate_id ]['rate'] . '%' : '&ndash;';
				}
			}
		}

		return '&ndash;';
	}


	/**
	 * Get Tax rate id
	 *
	 * @param WC_Order $order Order Object.
	 * @param WC_Order_Item $item Order Item Object.
	 *
	 * @return mixed
	 */
	private function tax_rate_id( $order, $item ) {
		$taxes      = $item->get_taxes();
		$tax_detail = $this->helper->order_tax_info( $order );
		if ( isset( $taxes['total'] ) && ! empty( $taxes['total'] ) ) {
			foreach ( $taxes['total'] as $rate_id => $tax ) {
				if ( ! empty( $tax ) ) {
					return $rate_id;
				}
			}
		}

		return false;
	}

	/**
	 * Get items subtotal without tax
	 *
	 * @param WC_Order $order Order Object.
	 *
	 * @return mixed|string
	 */
	private function get_subtotal( $order ) {
		return $this->helper->format_price( $order, $order->get_subtotal() );
	}

	/**
	 * Get Order data for order info section
	 *
	 * @param WC_Order $order Order Object.
	 *
	 * @return array
	 */
	private function get_order_info( $order ) {
		$order_info = array();

		if ( '1' === get_option( 'wiopt_payment_method_show' ) ) {
			$order_info['payment_method'] = $order->get_payment_method_title();
		}

		$order_no                      = $this->helper->woo_invo_ice_get_order_number( $order );
		$order_date                    = $this->helper->woo_invo_ice_get_formatted_date( $order );
		$order_id                      = $order->get_id();
		$invoice_no                    = ( ! empty( get_post_meta( $order_id, 'wiopt_invoice_no_' . $order_id, true ) ) ) ? get_post_meta( $order_id, 'wiopt_invoice_no_' . $order_id, true ) : woo_invo_ice_get_invoice_number( $order_id );
		$order_info['order_number']    = apply_filters( 'woo_invo_ice_order_number', $order_no, $this->template, $order );
		$order_info['order_date']      = apply_filters( 'woo_invo_ice_order_date', $order_date, $this->template, $order );
		$order_info['invoice_number']  = apply_filters( 'woo_invo_ice_invoice_number', $invoice_no, $this->template, $order );
		$order_info['shipping_method'] = apply_filters( 'woo_invo_ice_shipping_method', $order->get_shipping_method() );

		// Add Order metas according to settings.
		$get_order_metas = $this->get_order_post_meta( $order->get_id() );


		if ( ! empty( $get_order_metas ) ) {
			$order_info = $order_info + $get_order_metas;
		}
		// Get order delivery information.
		$delivery_date = $this->get_delivery_date( $order );
		if ( isset( $delivery_date ) && ! empty( $delivery_date ) && is_array( $delivery_date ) && count( $delivery_date ) > 0 ) {
			foreach ( $delivery_date as $key => $value ) {
				$order_info[ $value['label'] ] = $value['value'];
			}
		} elseif ( is_plugin_active( 'order-delivery-date-for-woocommerce/order_delivery_date.php' ) ) {
			$delivery_date_formatted = Orddd_Lite_Common::orddd_lite_get_order_delivery_date( $order->get_id() );
			$order_page_time_slot    = Orddd_Lite_Common::orddd_get_order_timeslot( $order->get_id() );

			if ( '' !== $delivery_date_formatted ) {
				$order_info['delivery_date'] = $delivery_date_formatted;
			}

			if ( '' !== $order_page_time_slot ) {
				$order_info['time_slot'] = $order_page_time_slot;
			}
		}

		if ( has_filter( 'woo_invo_ice_order_data' ) ) {
			$order_info = apply_filters( 'woo_invo_ice_order_data', $order_info, $this->template, $order );
		}

		return $order_info;
	}

	/**
	 * Get order delivery date info.
	 *
	 * @param $order
	 *
	 * @return array|string
	 */

	private function get_delivery_date( $order ) {
		$delivery      = [];
		$delivery_data = array(
			'delivery_time',
			'pickup_time',
			'pickup_date',
			'delivery_date',
			'delivery_type',
		);
		$arr           = $order->get_order_item_totals();
		$keys          = array_keys( $arr );
		$common_keys   = array_intersect( $keys, $delivery_data );
		if ( ! empty( $common_keys ) && count( $common_keys ) > 0 ) {
			foreach ( $common_keys as $data ) {
				array_push( $delivery, $arr[ $data ] );
			}
		}

		return $delivery;
	}

	/**
	 * Get Order Post Meta Value
	 *
	 * @param int $order_id Order Id.
	 *
	 * @return array|string
	 */

	private function get_order_post_meta( $order_id ) {
		if ( 'invoice' === $this->template && get_option( 'wiopt_custom_order_meta' ) && ! empty( get_option( 'wiopt_custom_order_meta' ) ) ) {
			$post_meta = array();
			foreach ( get_option( 'wiopt_custom_order_meta' ) as $key => $value ) {
				if ( ! is_array( $value ) ) {
					if ( '' !== get_post_meta( $order_id, $key, true ) ) {
						$post_meta[ $value ] = get_post_meta( $order_id, $key, true );
					}
				}
			}

			return $post_meta;
		} elseif ( 'packing_slip' === $this->template && get_option( 'wiopt_custom_order_meta_ps' ) && ! empty( get_option( 'wiopt_custom_order_meta_ps' ) ) ) {
			$post_meta = array();
			foreach ( get_option( 'wiopt_custom_order_meta_ps' ) as $key => $value ) {
				if ( ! is_array( $value ) ) {
					if ( '' !== get_post_meta( $order_id, $key, true ) ) {
						$post_meta[ $value ] = get_post_meta( $order_id, $key, true );
					}
				}
			}

			// print_r( $post_meta ); exit();

			return $post_meta;
		}

		return '';
	}

	/**
	 * Get total tax
	 *
	 * @param WC_Order $order Order Object.
	 *
	 * @return mixed
	 */
	private function get_tax_total( $order ) {
		return ( $order->get_total_tax() > 0 ) ? $this->helper->format_price( $order, $order->get_total_tax() ) : '';
	}

	/**
	 * Get total without tax
	 *
	 * @param WC_Order $order Order Object.
	 *
	 * @return mixed|string
	 */
	private function get_total_with_or_without_tax( $order ) {
		$total_without_tax = (float) $order->get_total() - (float) $order->get_total_tax();

		return $this->helper->format_price( $order, $total_without_tax );
	}

	/**
	 * Get grand total without tax
	 *
	 * @param WC_Order $order Order Object.
	 *
	 * @return mixed|string
	 */
	private function get_order_total( $order ) {
		return $this->helper->format_price( $order, $order->get_total() );
	}

	/**
	 * Get order fees
	 *
	 * @param WC_Order $order Order Object.
	 *
	 * @return mixed|string
	 */
	private function get_order_total_fees( $order ) {
		if ( method_exists( $order, 'get_total_fees' ) ) {
			$raw_fees = $order->get_total_fees();
			$fees     = $this->helper->format_price( $order, $raw_fees );

			return ! empty( $raw_fees ) ? $fees : '';
		}

		return false;
	}

	/**
	 * Get Shipping total
	 *
	 * @param WC_Order $order Order Object.
	 *
	 * @return mixed|string
	 */

	private function get_shipping_total( $order ) {
		if ( $order->get_shipping_total() > 0 ) {
			if ( $order->get_shipping_tax() > 0 ) {
				if ( 'wiopt_invoice_display_shipping_total_without_tax' === get_option( 'wiopt_invoice_display_shipping_total' ) ) {
					$shipping_without_tax = $order->get_shipping_total();
				} elseif ( 'wiopt_invoice_display_shipping_total_with_tax' === get_option( 'wiopt_invoice_display_shipping_total' ) ) {
					$shipping_without_tax = $order->get_shipping_total() + $order->get_shipping_tax();
				}
			} else {
				$shipping_without_tax = $order->get_shipping_total();
			}

			return $this->helper->format_price( $order, $shipping_without_tax );
		}

		return '';
	}

	/**
	 * Get Discount total
	 *
	 * @param WC_Order $order Order Object.
	 *
	 * @return mixed|string
	 */
	private function get_discount_total( $order ) {
		if ( ! $order->get_discount_total() ) {
			return false;
		}

		return $this->helper->format_price( $order, $order->get_discount_total() );
	}

	/**
	 * Get Discount total
	 *
	 * @param WC_Order $order Order Object.
	 *
	 * @return mixed|string
	 */
	private function get_refunded_total( $order ) {
		if ( ! $order->get_total_refunded() ) {
			return false;
		}

		return $this->helper->format_price( $order, $order->get_total_refunded() );
	}

	/**
	 * Get Total Paid Amount.
	 *
	 * @param WC_Order $order Order Object.
	 *
	 * @return mixed|string
	 */
	private function get_total_paid( $order ) {
		if ( ! $order->is_paid() ) {
			return false;
		}

		return $this->helper->format_price( $order, $order->get_total() );
	}

	/**
	 * Get Discount total
	 *
	 * @param WC_Order $order Order Object.
	 *
	 * @return mixed|string
	 */
	private function get_net_total( $order ) {
		if ( ! $order->get_total_refunded() ) {
			return false;
		}

		$net_total = $order->get_total() - $order->get_total_refunded();

		return $this->helper->format_price( $order, $net_total );
	}

	/**
	 * Get Product Title
	 *
	 * @param WC_Order $order Order Object.
	 * @param WC_Product $product Product Object.
	 * @param WC_Order_Item $item Order Item Object.
	 *
	 * @return mixed|string
	 */
	private function get_item_title( $order, $product, $item ) {
		if ( $product && $product instanceof WC_Product ) {
			return $this->get_product_title( $order, $product->get_id(), $item, $this->template, $this->vendor );
		} elseif ( $item && $item instanceof WC_Order_Item ) {
			$name    = '';
			$item_id = $item->get_id();

			// Show order item meta.
			if ( 'invoice' === $this->template || 'credit_note' === $this->template ) {
				$order_item_meta = get_option( 'wiopt_order_item_meta' );
			} elseif ( 'packing_slip' === $this->template ) {
				$order_item_meta = get_option( 'wiopt_order_item_meta_ps' );
			}
			if ( ! empty( $order_item_meta ) ) {
				foreach ( $order_item_meta as $meta => $label ) {
					$meta_value = wc_get_order_item_meta( $item_id, $meta, true );
					if ( '' !== $meta_value && ! is_array( $meta_value ) ) {
						$name .= "<br><span class='product-meta'>" . $label . ': ' . $meta_value . '</span></br>';
					}
				}
			}
		}
		$item_name = $item->get_name();
		$item_name .= $name;


		return '<b>'.$item_name.'</b>';
	}

	/**
	 * Get Product Weight
	 *
	 * @param WC_Product $product Product Object.
	 *
	 * @return mixed|string
	 */
	private function get_item_weight( $product, $quantity ) {
		if ( $product instanceof WC_Product ) {
			$product_weight = $product->get_weight();
			if ( $product_weight ) {
				$product_weight = ( $quantity > 0 ) ? $product_weight * $quantity : $product_weight;

				return $product_weight;
			}
		}

		return '';
	}

	/**
	 * Get Product Dimension
	 *
	 * @param WC_Product $product Product Object.
	 *
	 * @return mixed|string
	 */
	private function get_item_dimension( $product ) {
		if ( $product && $product instanceof WC_Product ) {
			return array(
				( $product->get_width() ) ? $product->get_width() : '',
				( $product->get_height() ) ? $product->get_height() : '',
				( $product->get_length() ) ? $product->get_length() : '',
			);
		}

		return array();
	}

	/**
	 * Get Product Image
	 *
	 * @param WC_Product $product Product Object.
	 * @param WC_Order_Item $item Order Item Object.
	 *
	 * @return mixed|string
	 */
	private function get_item_image( $product, $item ) {
		if ( $product && $product instanceof WC_Product ) {
			$height_width = apply_filters( 'woo_invo_ice_order_item_image_filter', array(
				'width'  => '50',
				'height' => '50',
			), $product, $item );
			$image        = $product->get_image( 'thumbnail', array(
				'title'  => '',
				'width'  => $height_width['width'],
				'height' => $height_width['height'],
				'crop'   => 0,
			), false );

			return apply_filters( 'woocommerce_admin_order_item_thumbnail',
				$image,
				$item->get_id(), $item );
		}

		return '';
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
	private function get_address( $order, $type, $template, $column = null ) {
		if ( 'label' === $template ) {
			return $this->get_custom_address( $order, $type, $template, $column = null );
		}

		if ( 'billing' === $type ) { // Get Billing Address.
			$billing_data = '';
			// unload text domains
			$billing_data .= $order->get_formatted_billing_address();

			if ( empty( get_option( 'wiopt_display_phone' ) ) && 1 != get_option( 'wiopt_display_phone' ) ) {
				$billing_data .= '<p>' . woo_invo_ice_filter_label( 'Phone', $order, $template ) . ' : ' . $order->get_billing_phone() . '<p>';
			}
			if ( empty( get_option( 'wiopt_display_email' ) ) && 1 != get_option( 'wiopt_display_email' ) ) {
				$billing_data .= '<p>' . woo_invo_ice_filter_label( 'Email', $order, $template ) . ' : ' . $order->get_billing_email() . '<p>';
			}

			return $billing_data;
		} elseif ( 'shipping' === $type ) { // Get SHipping Address.
			// Return empty if Billing and Shipping Address Same.
			if ( get_option( 'wiopt_display_shipping_address', true ) ) {
				if ( get_option( 'wiopt_hide_for_same_address', false ) ) {
					if ( $order->get_billing_address_1() === $order->get_shipping_address_1() ) {
						return '';
					}
				}
			}

			if ( ! empty( get_option( 'wiopt_display_shipping_address', true ) ) ) {
				return $order->get_formatted_shipping_address();
			}
		}
	}
}

/**
 * Initialize Woo_Invo_Ice_Orders class.
 *
 * @param array $order_ids Order Ids.
 * @param string $template Template Type.
 * @param int $vendor Vendor Id.
 *
 * @return Woo_Invo_Ice_Orders
 */
function woo_invo_ice_orders( $order_ids, $template, $vendor ) {
	$orders = new Woo_Invo_Ice_Orders( $order_ids, $template, $vendor );

	return $orders->get_orders_info();
}
