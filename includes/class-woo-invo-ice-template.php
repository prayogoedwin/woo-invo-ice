<?php
/**
 * Used to generate template according to template type.
 *
 * @link  https://astamatechnology.com
 * @since 1.0.0
 *
 * @package    Woo_Invo_Ice_Helper
 * @subpackage Woo_Invo_Ice_Helper/includes
 */

class Woo_Invo_Ice_Template {

	/**
	 * Helper class variable
	 *
	 * @var Woo_Invo_Ice_Helper
	 */
	public $helper;
	/**
	 * Orders Class variable
	 *
	 * @var Woo_Invo_Ice_Orders
	 */
	public $orders;
	/**
	 * Order Object
	 *
	 * @var WC_Order
	 */
	private $order;
	/**
	 * Template Type
	 *
	 * @var string
	 */
	private $template_type;
	/**
	 * RTL for arabic language.
	 *
	 * @var $rtl
	 */
	private $rtl;


	/**
	 * Woo_Invo_Ice_Template constructor.
	 *
	 * @param array $order_ids Order Id or Ids.
	 * @param string $template_type Template Type.
	 * @param int $vendor Vendor Id.
	 */
	public function __construct( $order_ids, $template_type = 'invoice' ) {
		$this->helper        = woo_invo_ice_helper();
		$this->orders        = $order_ids;
		$this->template_type = $template_type;
	}

	/**
	 * Set RTL status for arabic languages.
	 *
	 * @param WC_Order $order Order Info.
	 *
	 * @return string
	 */
	private function set_rtl( $order ) {
		$this->rtl         = '';
		$document_language = '' != get_option( 'wiopt_pdf_document_language' ) ? get_option( 'wiopt_pdf_document_language' ) : 'wiopt_pdf_site_language';
		$rtl_languages     = array( 'ar', 'ary', 'fa', 'fa_IR', 'he', 'ur', 'he_IL' );
		if ( 'wiopt_pdf_site_language' == $document_language ) {
			global $locale;
			if ( in_array( $locale, $rtl_languages ) ) {
				$this->rtl = 'rtl';
			}
		} elseif ( 'wiopt_pdf_order_language' == $document_language ) {

			$is_rtl = $this->order->get_meta( 'woo_invo_ice_order_lang' );

			if ( in_array( $is_rtl, $rtl_languages ) ) {
				$this->rtl = 'rtl';
			}
		} else {

			if ( in_array( $document_language, $rtl_languages ) ) {
				$this->rtl = 'rtl';
			}
		}
		$this->rtl = apply_filters( 'woo_invo_ice_set_rtl', $this->rtl, $order );

		return $this->rtl;
	}


	/**
	 * Get Invoice Template
	 *
	 * @return string
	 */
	public function get_invoice_template() {
		$template         = '';
		$page_break       = 'pageBreak';
		$product_per_page = ( get_option( 'wiopt_invoice_product_per_page' ) ) ? get_option( 'wiopt_invoice_product_per_page' ) : 6;
		$orders           = $this->orders;
		$total_page       = count( $orders );
		$total_orders     = count( $orders );
		$page_loop        = 0;
		$template         .= $this->get_css();
		$count_order      = 1;

		foreach ( $orders as $o_key => $order ) {
			$this->order = wc_get_order( $order );
			// TODO Condition for Site Lang or Order Lang.
			$language_code     = $this->order->get_meta( 'woo_invo_ice_order_lang' );
			$document_language = '' != get_option( 'wiopt_pdf_document_language' ) ? get_option( 'wiopt_pdf_document_language' ) : 'wiopt_pdf_site_language';
			global $locale;
			update_option( 'wiopt_site_default_language', $locale );
			// Switch Language Action Hook
			if ( 'wiopt_pdf_order_language' === get_option( 'wiopt_pdf_document_language' ) ) {
				do_action( 'woo_invo_ice_switch_language', $language_code );
			} elseif ( 'wiopt_pdf_site_language' !== get_option( 'wiopt_pdf_document_language' ) ) {
				do_action( 'woo_invo_ice_switch_language', $document_language );
			}

			$order = woo_invo_ice_orders( array( $order ), 'invoice', 1 );
			$order = $order[0];

			$this->set_rtl( $this->order );

			$product_chunk = array_chunk( $order['items'], $product_per_page );
			$total_chunk   = count( $product_chunk );
			if ( $total_chunk > 1 ) {
				$total_page += ( $total_chunk - 1 );
			}
			$chunk = 0;
			$page_loop ++;
			$product_loop = 1;
			// If "Stop repeating header and footer" is enable.
			if ( ! empty( get_option( 'wiopt_stop_repeating_header_footer' ) ) ) {
				$page_break = 'pageBreak';
				if ( $page_loop == $total_page || 1 == $page_loop ) {
					$template .= $this->get_html_start();
				} else {
					$template .= $this->get_html_start( $page_break );
				}
				$template .= $this->get_header_section();
				// Get "Credit Note" text in invoice if order is refunded.
				if ( ( isset( $order['status'] ) && 'refunded' === $order['status'] )
				     && ( isset( $_REQUEST['template'] ) && 'credit_note' == $_REQUEST['template'] )
				) {
					$template .= $this->get_credit_note_text();
				} elseif ( 'credit_note' === $this->template_type ) {
					$template .= $this->get_credit_note_text();
				}
				$template .= $this->get_order_section( $order, $this->template_type );
			}
			foreach ( $product_chunk as $p_key => $page ) {
				// If "Stop repeating header and footer" is enable.
				if ( ! empty( get_option( 'wiopt_stop_repeating_header_footer' ) ) ) {

					if ( $chunk > 0 ) {
						$page_loop ++;
					}
					if ( $total_page == $page_loop
					     || ( 1 == $total_chunk && $total_chunk == $product_loop )
					     || ( $total_chunk > 1 && $total_chunk == $product_loop )

					) {
						$page_break = '';
					} else {
						$page_break = 'pageBreak';
					}
					$template .= $this->get_product_section( $page, $page_break );
					if ( $product_loop == $total_chunk ) {
						$template .= $this->get_product_total_section( $order );
						$template .= $this->get_order_note_section( $order['order_note'], $order['ID'], ! empty( $order['order_note'] ) ? true : false );
						$template .= $this->get_bank_accounts_section( $order['bank_accounts'] );
						$template .= $this->get_footer_section();
					}
					$product_loop ++;
					$chunk ++;
				} else {
					// Calculate Page Break.
					if ( $chunk > 0 ) {
						$page_loop ++;
					}
					if ( $total_page == $page_loop ) {
						$page_break = '';
					}
					$template .= $this->get_html_start( $page_break );
					$template .= $this->get_header_section();
					// Get "Credit Note" text in invoice if order is redunded.
					if ( ( isset( $order['status'] ) && 'refunded' === $order['status'] )
					     && ( isset( $_REQUEST['template'] ) && 'credit_note' == $_REQUEST['template'] )
					) {
						$template .= $this->get_credit_note_text();
					} elseif ( 'credit_note' === $this->template_type ) {
						$template .= $this->get_credit_note_text();
					}
					$template .= $this->get_order_section( $order, $this->template_type );
					$template .= $this->get_product_section( $page, '' );
					if ( $product_loop == $total_chunk ) {
						$template .= $this->get_product_total_section( $order );
						$template .= $this->get_order_note_section( $order['order_note'], $order['ID'], ! empty( $order['order_note'] ) ? true : false );
						$template .= $this->get_bank_accounts_section( $order['bank_accounts'] );
					}
					$template .= $this->get_footer_section();
					$product_loop ++;
					$chunk ++;
				}
			}

			// Revert Language Action Hook
			if ( 'wiopt_pdf_order_language' === get_option( 'wiopt_pdf_document_language' ) ) {
				do_action( 'woo_invo_ice_restore_language' );
			} elseif ( 'wiopt_pdf_site_language' !== get_option( 'wiopt_pdf_document_language' ) ) {
				do_action( 'woo_invo_ice_restore_language' );
			}
			$count_order ++;
		}
		$template .= $this->get_html_end();

		return $template;
	}


	/**
	 * Get "Credit Note" text in invoice if order is redunded.
	 * @return string
	 */
	public function get_credit_note_text() {
		$html = '';
		$html .= "<table><tr ><td style='text-align: center;font-size: 17px;padding-bottom: 10px;'>";
		$html .= "<h1>" . woo_invo_ice_filter_label( 'Credit Note', $this->order, $this->template_type ) . "</h1></td></tr></table>";

		return $html;
	}

	/**
	 * Get Packing Slip Template
	 *
	 * @return string
	 */
	public function get_packing_template() {

		$template         = '';
		$page_break       = 'pageBreak';
		$product_per_page = ( get_option( 'wiopt_packingslip_product_per_page' ) ) ? get_option( 'wiopt_packingslip_product_per_page' ) : 6;
		$orders           = $this->orders;
		$total_page       = count( $orders );
		$page_loop        = 0;
		$template         .= $this->get_css();
		$count_order      = 1;
		foreach ( $orders as $o_key => $order ) {
			$this->order = wc_get_order( $order );
			// TODO Condition for Site Lang or Order Lang.
			$language_code     = $this->order->get_meta( 'woo_invo_ice_order_lang' );
			$document_language = '' != get_option( 'wiopt_pdf_document_language' ) ? get_option( 'wiopt_pdf_document_language' ) : 'wiopt_pdf_site_language';
			global $locale;
			update_option( 'wiopt_site_default_language', $locale );
			// Switch Language Action Hook
			if ( 'wiopt_pdf_order_language' === get_option( 'wiopt_pdf_document_language' ) ) {
				do_action( 'woo_invo_ice_switch_language', $language_code );
			} elseif ( 'wiopt_pdf_site_language' !== get_option( 'wiopt_pdf_document_language' ) ) {
				do_action( 'woo_invo_ice_switch_language', $document_language );
			}
			$order = woo_invo_ice_orders( array( $order ), $this->template_type, 1 );
			$order = $order[0];

			$this->set_rtl( $this->order );

			$product_chunk = array_chunk( $order['items'], $product_per_page );
			$total_chunk   = count( $product_chunk );
			if ( $total_chunk > 1 ) {
				$total_page += ( $total_chunk - 1 );
			}
			$chunk = 0;
			$page_loop ++;
			$product_loop = 1;
			// If "Stop repeating header and footer" is enable.
			if ( 1 == get_option( 'wiopt_stop_repeating_header_footer' ) ) {
				$break = '';
				if ( isset( $_GET['action'] ) && 'wiopt_generate_invoice_packing_slip_bulk' == $_GET['action'] ) {
					if ( 1 == $total_chunk ) {
						$break = 'pageBreak';
					}
					if ( ( $page_loop + 1 ) == $total_page ) {
						$break = '';
					}
				} elseif ( isset( $_GET['action'] ) && 'wiopt_generate_invoice_packing_slip' == $_GET['action'] ) {
					if ( 1 != $count_order ) {
						$break = 'pageBreak';
					}
					if ( ( $page_loop + 1 ) == $total_page ) {
						$break = '';
					}
				}
				$template .= $this->get_html_start( $break );
				$template .= $this->get_header_section();
				$template .= $this->get_order_section( $order, $this->template_type );
			}
			foreach ( $product_chunk as $p_key => $page ) {
				// If "Stop repeating header and footer" is enable.
				if ( 1 == get_option( 'wiopt_stop_repeating_header_footer' ) ) {
					$chunk ++;
					if ( count( $product_chunk ) == $chunk ) {
						$page_break = '';
					} else {
						$page_break = "pageBreak";
					}
					$template .= $this->get_product_section( $page, $page_break );
					if ( count( $product_chunk ) == $chunk ) {
						$template .= $this->get_packing_total_section( $order );
						$template .= $this->get_order_note_section( $order['order_note'], $order['ID'], ! empty( $order['order_note'] ) ? true : false );
					}
				} else {
					// Calculate Page Break.
					if ( $chunk > 0 ) {
						$page_loop ++;
					}
					if ( $total_page == $page_loop ) {
						$page_break = '';
					}
					// $page_break = $page_break . " " . $page_loop . " " . $total_page;
					// $product_loop = $page_break . " " . $product_loop . " " . $total_chunk;
					$template .= $this->get_html_start( $page_break );
					$template .= $this->get_header_section();
					$template .= $this->get_order_section( $order, $this->template_type );
					$template .= $this->get_product_section( $page, '' );
					if ( $product_loop == $total_chunk ) {
						$template .= $this->get_packing_total_section( $order );
						$template .= $this->get_order_note_section( $order['order_note'], $order['ID'], ! empty( $order['order_note'] ) ? true : false );
					}
					$template .= $this->get_footer_section();
					$product_loop ++;
					$chunk ++;
				}
			}
			// If "Stop repeating header and footer" is enable.
			if ( 1 == get_option( 'wiopt_stop_repeating_header_footer' ) ) {
				$template .= $this->get_footer_section();
			}

			// Revert Language Action Hook
			if ( 'wiopt_pdf_order_language' === get_option( 'wiopt_pdf_document_language' ) ) {
				do_action( 'woo_invo_ice_restore_language' );
			} elseif ( 'wiopt_pdf_site_language' !== get_option( 'wiopt_pdf_document_language' ) ) {
				do_action( 'woo_invo_ice_restore_language' );
			}


			$count_order ++;
		}
		$template .= $this->get_html_end();

		return $template;
	}

	/**
	 * Get Shipping Label Template
	 *
	 * @param int $column Page Column.
	 * @param int $row Label per page.
	 * @param int $font_size Font Size.
	 *
	 * @return string
	 */

	public function get_shipping_label_template( $column = 1, $row = 1, $font_size = 20 ) {
		$content = '';
		$content .= $this->get_css();
		$orders  = $this->orders;
		//  get single shipping lable.
		if ( 1 === count( $orders ) ) {
			$this->order = wc_get_order( $orders[0] );
			// TODO Condition for Site Lang or Order Lang.
			$language_code     = $this->order->get_meta( 'woo_invo_ice_order_lang' );
			$document_language = '' != get_option( 'wiopt_pdf_document_language' ) ? get_option( 'wiopt_pdf_document_language' ) : 'wiopt_pdf_site_language';
			global $locale;
			update_option( 'wiopt_site_default_language', $locale );
			// Switch Language Action Hook
			if ( 'wiopt_pdf_order_language' === get_option( 'wiopt_pdf_document_language' ) ) {
				do_action( 'woo_invo_ice_switch_language', $language_code );
			} elseif ( 'wiopt_pdf_site_language' !== get_option( 'wiopt_pdf_document_language' ) ) {
				do_action( 'woo_invo_ice_switch_language', $document_language );
			}
			$rtl     = $this->set_rtl( $this->order );
			$content .= "<div dir='$rtl'>";
			$row     = 1;
			$column  = 1;
			$index   = 0;
			$content .= "<div dir='$rtl'>";
			$content .= "<table style='font-size:$font_size'>";
			for ( $r = 1; $r <= $row; $r ++ ) {
				// Apply custom shipping label.
				if ( has_filter( 'woo_invo_ice_custom_shipping_label' ) ) {
					$single_shipping_label = apply_filters( 'woo_invo_ice_custom_shipping_label', $this->order, $this->helper );
					if ( is_array( $single_shipping_label ) && ! empty( $single_shipping_label[0] ) ) {
						$content              .= $single_shipping_label[0];
						$padding              = ( ! empty( $single_shipping_label[1] ) ) ? $single_shipping_label[1] : '';
						$height_and_font_size = ( ! empty( $single_shipping_label[2] ) ) ? $single_shipping_label[2] : '';
					}
				} else {
					$padding              = '';
					$height_and_font_size = '';
				}

				$content .= "<tr>";
				for ( $c = 1; $c <= $column; $c ++ ) {
					if ( $index < count( $orders ) ) {
						// Get shipping address with or without padding based on row no.
						$content .= $this->get_shipping_label_content( $orders, $index, $column, $r = '', $padding, $height_and_font_size );
					}
					$index ++;
				}
				$content .= "</tr>";
				break;
			}
			$content .= "</table>";
			$content .= "</div>";

			// Revert Language Action Hook
			if ( 'wiopt_pdf_order_language' === get_option( 'wiopt_pdf_document_language' ) ) {
				do_action( 'woo_invo_ice_restore_language' );
			} elseif ( 'wiopt_pdf_site_language' !== get_option( 'wiopt_pdf_document_language' ) ) {
				do_action( 'woo_invo_ice_restore_language' );
			}

			return $content;

			// Get bulk shipping lablel.
		} elseif ( count( $orders ) >= $row ) {
			$order_chunk = array_chunk( $orders, $row * $column );
			$total_chunk = count( $order_chunk );
			$page_break  = 'page-break-after: always;';
		}


		$index = 0;
		for ( $ch = 0; $ch < $total_chunk; $ch ++ ) {
			$min = $total_chunk - $ch;
			if ( 1 === $min ) {
				$content .= "<table style=' font-size:$font_size'>";
				for ( $r = 1; $r <= $row; $r ++ ) {
					// Apply custom shipping label.
					if ( has_filter( 'woo_invo_ice_custom_shipping_label' ) ) {
						$bulk_shipping_label = apply_filters( 'woo_invo_ice_custom_shipping_label', $this->order, $this->helper );
						if ( is_array( $bulk_shipping_label ) && ! empty( $bulk_shipping_label[0] ) ) {
							$content              .= $bulk_shipping_label[0];
							$padding              = ( ! empty( $bulk_shipping_label[1] ) ) ? $bulk_shipping_label[1] : '';
							$height_and_font_size = ( ! empty( $bulk_shipping_label[2] ) ) ? $bulk_shipping_label[2] : '';
						}
					} else {
						$padding              = '';
						$height_and_font_size = '';
					}

					$content .= "<tr>";
					for ( $c = 1; $c <= $column; $c ++ ) {
						if ( $index < count( $orders ) ) {
							// Get shipping address with or without padding based on row no.
							$content .= $this->get_shipping_label_content( $orders, $index, $column, $row, $padding, $height_and_font_size );

						}
						$index ++;
					}
					$content .= "</tr>";
				}
				$content .= "</table>";
			} else {
				$content .= "<table style='$page_break font-size:$font_size'>";
				for ( $r = 1; $r <= $row; $r ++ ) {
					// Apply custom shipping label.
					if ( has_filter( 'woo_invo_ice_custom_shipping_label' ) ) {
						$bulk_shipping_label = apply_filters( 'woo_invo_ice_custom_shipping_label', $this->order, $this->helper );
						if ( is_array( $bulk_shipping_label ) && ! empty( $bulk_shipping_label[0] ) ) {
							$content              .= $bulk_shipping_label[0];
							$padding              = ( ! empty( $bulk_shipping_label[1] ) ) ? $bulk_shipping_label[1] : '';
							$height_and_font_size = ( ! empty( $bulk_shipping_label[2] ) ) ? $bulk_shipping_label[2] : '';
						}
					} else {
						$padding              = '';
						$height_and_font_size = '';
					}
					$content .= "<tr>";
					for ( $c = 1; $c <= $column; $c ++ ) {
						if ( $index < count( $orders ) ) {
							// Get shipping address with or without padding based on row no.

							$content .= $this->get_shipping_label_content( $orders, $index, $column, $row, $padding, $height_and_font_size );

						}
						$index ++;
					}
					$content .= "</tr>";
				}
				$content .= "</table>";
			}
		}

		return $content;
	}

	/**
	 * @param $orders
	 * @param $index
	 * @param $column
	 * @param $r
	 *
	 * @return string
	 */
	private function get_shipping_label_content( $orders, $index, $column, $r, $padding, $height_and_font_size ) {
		$address = '';
		if ( 1 === $r ) {

			$rtl = $this->set_rtl( $orders[0] );
			if ( '' != $padding ) {
				if ( 'rtl' === $rtl ) {
					$padding_right_left = "padding-right: $padding.px;$height_and_font_size";
				} else {
					$padding_right_left = "padding-left: $padding.px;$height_and_font_size";
				}
			} else {
				$padding_right_left = "padding-left: 10px;";
			}
			$address     .= "<td style='$padding_right_left'>";
			$address     .= "<div dir='$rtl'>";
			$this->order = ( $orders[0] ) ? wc_get_order( $orders[0] ) : null;
			// TODO Condition for Site Lang or Order Lang.
			$language_code     = $this->order->get_meta( 'woo_invo_ice_order_lang' );
			$document_language = '' != get_option( 'wiopt_pdf_document_language' ) ? get_option( 'wiopt_pdf_document_language' ) : 'wiopt_pdf_site_language';
			global $locale;
			update_option( 'wiopt_site_default_language', $locale );
			// Switch Language Action Hook
			if ( 'wiopt_pdf_order_language' === get_option( 'wiopt_pdf_document_language' ) ) {
				do_action( 'woo_invo_ice_switch_language', $language_code );
			} elseif ( 'wiopt_pdf_site_language' !== get_option( 'wiopt_pdf_document_language' ) ) {
				do_action( 'woo_invo_ice_switch_language', $document_language );
			}
			// Actions Before Shipping Address.
			$address .= woo_invo_ice_before_shipping_address( $this->order, $this->template_type );
			// Get Shipping Address.
			$address .= $this->helper->get_address( $this->order, 'label', $this->template_type, $column );
			// Get Order Metas.
			$address .= $this->helper->get_shipping_label_metas( $orders[0] );
			// Actions Before SHipping Address.
			$address .= woo_invo_ice_after_shipping_address( $this->order, $this->template_type );
			$address .= '</div>';
			$address .= "</td>";
			// Revert Language Action Hook
			if ( 'wiopt_pdf_order_language' === get_option( 'wiopt_pdf_document_language' ) ) {
				do_action( 'woo_invo_ice_restore_language' );
			} elseif ( 'wiopt_pdf_site_language' !== get_option( 'wiopt_pdf_document_language' ) ) {
				do_action( 'woo_invo_ice_restore_language' );
			}
		} else {

			$this->order = ( $orders[0] ) ? wc_get_order( $orders[0] ) : null;
			// TODO Condition for Site Lang or Order Lang.
			$language_code     = $this->order->get_meta( 'woo_invo_ice_order_lang' );
			$document_language = '' != get_option( 'wiopt_pdf_document_language' ) ? get_option( 'wiopt_pdf_document_language' ) : 'wiopt_pdf_site_language';
			global $locale;
			update_option( 'wiopt_site_default_language', $locale );
			// Switch Language Action Hook
			if ( 'wiopt_pdf_order_language' === get_option( 'wiopt_pdf_document_language' ) ) {
				do_action( 'woo_invo_ice_switch_language', $language_code );
			} elseif ( 'wiopt_pdf_site_language' !== get_option( 'wiopt_pdf_document_language' ) ) {
				do_action( 'woo_invo_ice_switch_language', $document_language );
			}
			$rtl = $this->set_rtl( $orders[ $index ] );
			if ( '' != $padding ) {
				if ( 'rtl' === $rtl ) {
					$padding_right_left = "padding-right: $padding.px;$height_and_font_size";
				} else {
					$padding_right_left = "padding-left: $padding.px;$height_and_font_size";
				}
			} else {
				$padding_right_left = "padding-left: 10px;";
			}
			$address     .= "<td style='$padding_right_left'>";
			$address     .= "<div  dir='$rtl'>";
			$this->order = ( $orders[0] ) ? wc_get_order( $orders[0] ) : null;
			// Actions Before Shipping Address.
			$address .= woo_invo_ice_before_shipping_address( $this->order, $this->template_type );
			// Get Shipping Address.
			$address .= $this->helper->get_address( $this->order, 'label', $this->template_type, $column );
			// Get Order Metas.
			$address .= $this->helper->get_shipping_label_metas( $orders[0] );
			// Actions Before SHipping Address.
			$address .= woo_invo_ice_after_shipping_address( $this->order, $this->template_type );
			$address .= "</td>";
			// Revert Language Action Hook
			if ( 'wiopt_pdf_order_language' === get_option( 'wiopt_pdf_document_language' ) ) {
				do_action( 'woo_invo_ice_restore_language' );
			} elseif ( 'wiopt_pdf_site_language' !== get_option( 'wiopt_pdf_document_language' ) ) {
				do_action( 'woo_invo_ice_restore_language' );
			}
		}

		return $address;
	}

	/**
	 * Load Style for Template
	 *
	 * @return false|string
	 */
	public function get_css() {
		$font_size = ( get_option( 'wiopt_invoice_font_size' ) ) ? get_option( 'wiopt_invoice_font_size' ) : '11';
		ob_start();
		// Load CSS File for template.
		$file  = '';
		$title = '';
		if ( 'invoice' === $this->template_type || 'credit_note' === $this->template_type ) {

			$template = ( get_option( 'wiopt_templateid' ) ) ? get_option( 'wiopt_templateid' ) : 'invoice-1';
			$file     = plugin_dir_path( __FILE__ ) . "templates/$template.css";
			( 'credit_note' === $this->template_type ) ? $title = "Credit Note" : $title = "Invoice";
		} elseif ( 'packing_slip' === $this->template_type ) {
			$file  = plugin_dir_path( __FILE__ ) . 'templates/packing_slip.css';
			$title = 'Packing Slip';
		} elseif ( 'label' === $this->template_type ) {
			$file  = plugin_dir_path( __FILE__ ) . 'templates/shipping_label.css';
			$title = 'Shipping Label';
		}

		$title = apply_filters( 'woo_invo_ice_pdf_title', $title, $this->template_type );

		// Default css.
		$default_css      = '';
		$default_css_file = apply_filters( 'woo_invo_ice_default_css_file', plugin_dir_path( __FILE__ ) . 'templates/default.css' );
		if ( file_exists( $default_css_file ) ) {
			$default_css = file_get_contents( $default_css_file ); //phpcs:ignore
		}
		// template css.
		$template_css = '';
		$file         = apply_filters( 'woo_invo_ice_template_css_file', $file );
		if ( file_exists( $file ) ) {
			$template_css = file_get_contents( $file );//phpcs:ignore
		}

		?>
        <html>
        <head>
			<title><?php echo esc_attr__( $title, 'webappick-pdf-invoice-for-woocommerce' ); //phpcs:ignore
			?></title>
            <style>
                <?php echo $default_css; //phpcs:ignore?>
            </style>
            <style>
                <?php echo $template_css; //phpcs:ignore

                if ( 'invoice' === $this->template_type ) {
                  echo( ! empty( get_option( 'wiopt_custom_css' ) ) ? get_option( 'wiopt_custom_css' ) : '' ); //phpcs:ignore
                }else {
                  echo( ! empty( get_option( 'wiopt_packing_slip_css' ) ) ? get_option( 'wiopt_packing_slip_css' ) : '' ); //phpcs:ignore
                }
                ?>
            </style>
			<?php $custom_css = woo_invo_ice_custom_style( $this->template_type );//phpcs:ignore
			?>
			<?php if ( strpos( $custom_css, '<style>' ) === false ) { ?>
                <style>/*Load custom css from action hook.*/
                    <?php echo $custom_css; //phpcs:ignore?>
                </style>
			<?php } else { ?>
				<?php echo $custom_css; //phpcs:ignore ?>
			<?php } ?>
        </head>
        <body  style="font-size:<?php echo esc_attr( $font_size ) . 'px'; ?>">
		<?php
		$html = ob_get_contents();
		ob_end_clean();

		return $html;
	}

	/**
	 * Start Template Body
	 *
	 * @param string $page_break Page break tag.
	 *
	 * @return false|string
	 */
public function get_html_start( $page_break = '' ) {
	ob_start();
	$rtl_css = '';
	if ( 'rtl' === $this->rtl && file_exists( plugin_dir_path( __FILE__ ) . 'templates/invoice-rtl.css' ) ) {
		$rtl_css = file_get_contents( plugin_dir_path( __FILE__ ) . 'templates/invoice-rtl.css' );
	}
	?>
    <style><?php echo $rtl_css; //phpcs:ignore
		?></style>
<div class='invoice-box <?php echo esc_html( $page_break ); ?>' dir="<?php echo esc_html( $this->rtl ); ?>">
	<div class='invoice-box-inner'>
	<?php
	echo woo_invo_ice_before_document( $this->order, $this->template_type ); //phpcs:ignore
	$html = ob_get_contents();
	ob_end_clean();

	return $html;
}

	/**
	 * Load Header
	 */
	public function get_header_section() {
		$logo   = $this->helper->get_invoice_logo();
		$seller = $this->helper->get_seller_info();
		$seller = apply_filters( 'woo_invo_ice_seller_info', $seller, $this->template_type );

		ob_start();
		// Action Before the seller address.
		$seller_before = woo_invo_ice_before_seller_info( $this->order, $this->template_type );
		// Action After the seller address.
		$seller_after = woo_invo_ice_after_seller_info( $this->order, $this->template_type );

		$qr_code_string = $this->helper->get_qr_code( $this->order );
		$qr_code        = apply_filters( 'woo_invo_ice_qr_code_attributes', [
			'size'  => '1.5',
			'style' => '',
		], $this->order, $this->template_type );
		$bar_code       = apply_filters( 'woo_invo_ice_bar_code_attributes', [
			'size'  => '1.6',
			'style' => 'margin-left:-14px;margin-top:10px;',
		], $this->order, $this->template_type );

		// Change header item positions by filter.
		$header_position_customization = apply_filters( 'woo_invo_ice_header_position_customization', [
			'logo',
			'bar_qr_code',
			'seller'
		], $this->order, $this->template_type );

		?>
        <script>

        </script>

	<?php if ( 'invoice' === $this->template_type ) {
		do_action('wiopt_before_header_table_body');
	} // Add a hook for title in the invoice ?>

        <table class="header-table" border="0">
            <tbody>
            <tr>
				<?php
				foreach ( $header_position_customization as $value ) {
					if ( 'logo' == $value ) { ?>
                        <td class="site-logo">
							<?php echo $logo; //phpcs:ignore?>
                        </td>
						<?php
					} elseif ( 'bar_qr_code' == $value ) { ?>
                        <td>
                            <!-- Enable Bar Code --->
							<?php
							if ( ! empty( get_option( 'wiopt_enable_qrcode' ) ) && ( 1 == get_option( 'wiopt_enable_qrcode' ) ) ) :
								?>
                                <div class="invoice_qr_code">
                                    <barcode code="<?php echo esc_html( $qr_code_string ); ?>" type="QR" class="qrcode"
                                             disableborder="1" size="<?php echo esc_html( $qr_code['size'] ); ?>"
                                             style="<?php echo esc_html( $qr_code['style'] ) ?>"/>
                                </div>
							<?php endif; ?>
                            <!-- Enable QR Code --->
							<?php if ( ! empty( get_option( 'wiopt_enable_barcode' ) ) && ( 1 == get_option( 'wiopt_enable_barcode' ) ) ) : ?>
                                <div class="invoice_bar_code">
                                    <barcode code="<?php echo esc_html( $this->order->get_id() ); ?>" type="I25"
                                             class="barcode" size="<?php echo esc_html( $bar_code['size'] ); ?>"
                                             style="<?php echo esc_html( $bar_code['style'] ); ?>"/>
                                </div>
							<?php endif; ?>
                        </td>
					<?php } else { ?>
                        <td class="seller">
							<?php echo "<span class='seller-info-before'>$seller_before</span>"; //phpcs:ignore?>
							<?php echo "<span class='seller-info'>$seller</span>"; //phpcs:ignore?>
							<?php echo "<span class='seller-info-after'>$seller_after</span>"; //phpcs:ignore?>
                        </td>
					<?php }
				}
				?>
            </tr>
            </tbody>
        </table>
        <br/><br/>
		<?php
		$html = '';
		$html = ob_get_contents();
		ob_end_clean();

		return $html;
	}

	/**
	 * Load Order info section
	 *
	 * @param array $order Order Info.
	 *
	 * @return mixed
	 */
	public function get_order_section( $order, $template_type = '' ) {
		$billing    = apply_filters( 'woo_invo_ice_billing_info', $order['billing_info'], $this->order, $this->template_type );
		$shipping   = apply_filters( 'woo_invo_ice_shipping_info', $order['shipping_info'], $this->order, $this->template_type );
		$order_data = $order['order_info'];
		$order_status = wc_get_order_status_name( wc_get_order( $order['ID'] )->get_status() );

		ob_start();
		// Hide Shipping address if disabled for invoice.
		if ( 'invoice' === $this->template_type && ! get_option( 'wiopt_display_shipping_address', true ) ) {
			$shipping = '';
		}

		// Action Before the billing address.
		$billing_before = woo_invo_ice_before_billing_address( $this->order, $this->template_type );
		// Action After the billing address.
		$billing_after = woo_invo_ice_after_billing_address( $this->order, $this->template_type );
		// Action Before the shipping address.
		$shipping_before = woo_invo_ice_before_shipping_address( $this->order, $this->template_type );
		// Action After the shipping address.
		$shipping_after = woo_invo_ice_after_shipping_address( $this->order, $this->template_type );
		// Action Before the order data.
		$before_order_data = woo_invo_ice_before_order_data( $this->order, $this->template_type );
		// Action After the order data.
		$after_order_data = woo_invo_ice_after_order_data( $this->order, $this->template_type );

		$billing  = ! empty( $billing ) ? '<b>' . woo_invo_ice_filter_label( 'Billing', $this->order, $this->template_type ) . '</b><br/>' . $billing_before . $billing : '';//phpcs:ignore
		$shipping = ! empty( $shipping ) ? '<b>' . woo_invo_ice_filter_label( 'Shipping', $this->order, $this->template_type ) . '</b><br/>' . $shipping_before . $shipping : '';//phpcs:ignore
		// Change billing, shipping, order_details position by filter.
		$address_position_customization = apply_filters( 'woo_invo_ice_address_position_customization', [
			'billing',
			'shipping',
			'order_data'
		], $this->order, $this->template_type );
		?>
        <div class="order-table-wrap">
            <table border="" class="order-table">
                <tbody>
                <tr>
					<?php
					foreach ( $address_position_customization as $value ) {
						if ( 'billing' == $value ) { ?>
                            <td class="billing-td" colspan="<?php echo empty( $shipping ) ? '2' : '0'; ?>">
								<?php echo "<span class='billing-address'>$billing</span>"; //phpcs:ignore?>
								<?php echo "<span class='billing-address-after'>$billing_after</span>"; //phpcs:ignore?>
                            </td>
						<?php } elseif ( 'shipping' == $value ) {
							if ( ! empty( $shipping ) ) : ?>
                                <td class="shipping-td">
									<?php echo "<span class='shipping-address'>$shipping</span>"; //phpcs:ignore?>
									<?php echo "<span class='shipping-address-after'>$shipping_after</span>"; //phpcs:ignore?>
                                </td>
							<?php endif;
						} else { ?>
                            <td class="order-data-td">
                                <table class="order-data-table">
                                    <tbody>
									<?php
									$invoice_number  = apply_filters( 'wiopt_invoice_number', '#' . $order_data['invoice_number'], $order );
									$bold_text_class = 'order-invoice-number';
									if ( '0' !== get_option( 'wiopt_display_invoice_number', 1 ) ) : ?>
                                        <tr class="<?php echo esc_attr( $bold_text_class );
										?>">
                                            <td class="order-data-label "><?php echo woo_invo_ice_filter_label( 'Invoice Number', $this->order, $this->template_type ); //phpcs:ignore?></td>
                                            <td class="order-data-value"><?php echo $invoice_number; //phpcs:ignore?></td>
                                        </tr>
										<?php
										$bold_text_class = '';
									endif;
									?>
									<?php
									// String before order data.
									if ( ! empty( $before_order_data ) ) {
										echo "<tr>$before_order_data</tr>";//phpcs:ignore
									}
									if ( '0' != get_option( 'wiopt_display_order_number', 1 ) ) :
										?>
                                        <tr class="<?php echo esc_attr( $bold_text_class ); ?>">
                                            <td class="order-data-label">
												<?php echo woo_invo_ice_filter_label( "Order Number", $this->order, $this->template_type ); //phpcs:ignore
												?>
                                            </td>
                                            <td class="order-data-value">
												<?php echo ': ' . $order_data['order_number']; //phpcs:ignore
												?>
                                            </td>
                                        </tr>
									<?php endif; ?>
                                    <tr>
                                        <td class="order-data-label"><?php echo woo_invo_ice_filter_label( "Order Date", $this->order, $this->template_type ); //phpcs:ignore?></td>
                                        <td class="order-data-value"><?php echo ': ' . $order_data['order_date']; //phpcs:ignore?></td>
                                    </tr>
									<?php if ( get_option( 'wiopt_show_order_status', true ) ) : ?>
                                        <tr>
                                            <td class="order-status-label"><?php echo woo_invo_ice_filter_label( "Order Status", $this->order, $this->template_type ); //phpcs:ignore?></td>
                                            <td class="order-status-value"><?php echo ': ' . $order_status; //phpcs:ignore?></td>
                                        </tr>
									<?php endif; ?>
									<?php if ( ! empty( $order_data['payment_method'] ) ) { ?>
                                        <tr>
                                            <td class="order-data-label"><?php echo woo_invo_ice_filter_label( "Payment Method", $this->order, $this->template_type ); //phpcs:ignore?></td>
                                            <td class="order-data-value"><?php echo ': ' . $order_data['payment_method']; //phpcs:ignore?></td>
                                        </tr>
									<?php } ?>
									<?php if ( '' !== $order_data['shipping_method'] && get_option( 'wiopt_display_shipping_address', true ) ) : ?>
                                        <tr>
                                            <td class="order-data-label"><?php echo woo_invo_ice_filter_label( "Shipping Method", $this->order, $this->template_type ); //phpcs:ignore?></td>
                                            <td class="order-data-value"><?php echo ': ' . esc_html( $order_data['shipping_method'] ); //phpcs:ignore?></td>
                                        </tr>
									<?php endif; ?>
                                    <!-- Get order delivery data if delivery plugin is active.-->
									<?php if ( ! empty( $order_data['delivery_date'] ) ) : ?>
                                        <tr>
                                            <td class="order-data-label"><?php echo woo_invo_ice_filter_label( "Delivery Date", $this->order, $this->template_type ); //phpcs:ignore?></td>
                                            <td class="order-data-value"><?php echo ': ' . esc_html( $order_data['delivery_date'] ); //phpcs:ignore?></td>
                                        </tr>
									<?php endif; ?>
									<?php if ( ! empty( $order_data['time_slot'] ) ) : ?>
                                        <tr>
                                            <td class="order-data-label"><?php echo woo_invo_ice_filter_label( "Time Slot", $this->order, $this->template_type ); //phpcs:ignore?></td>
                                            <td class="order-data-value"><?php echo ': ' . esc_html( $order_data['time_slot'] ); //phpcs:ignore?></td>
                                        </tr>
									<?php endif; ?>
                                    <!-- end. Get order delivery data if delivery plugin is active.-->
									<?php
									unset( $order_data['payment_method'] );
									unset( $order_data['order_number'] );
									unset( $order_data['order_date'] );
									unset( $order_data['invoice_number'] );
									unset( $order_data['shipping_method'] );
									unset( $order_data['delivery_date'] );
									unset( $order_data['time_slot'] );

									if ( ! empty( $order_data ) ) :
										foreach ( $order_data as $key => $value ) : ?>
                                            <tr>
                                                <td class="order-data-label"><?php echo woo_invo_ice_filter_label( $key, $this->order, $this->template_type ); //phpcs:ignore
													?></td>
                                                <td class="order-data-value"><?php echo ': ' . $value; //phpcs:ignore
													?></td>
                                            </tr>
										<?php
										endforeach;
									endif;
									// String After order data.
									if ( ! empty( $after_order_data ) ) {
										echo "<tr>$after_order_data</tr>";//phpcs:ignore
									}
									?>
                                    </tbody>
                                </table>
                            </td>
						<?php }
					}
					?>
                </tr>
                </tbody>
            </table>
        </div>
        <br>
		<?php
		$html = ob_get_contents();
		ob_end_clean();

		return $html;
	}


	/**
	 * Load Products table
	 *
	 * @param array $products Product list.
	 *
	 * @return false|string
	 */
	public function get_product_section( $products, $page_break = '' ) {
		$wiopt_display_product_img = ( '' != get_option( 'wiopt_product_image_show' ) ) ? get_option( 'wiopt_product_image_show' ) : '0';
		// Remove Product Image column if disabled.
		$image = '0';
		if ( 'invoice' === $this->template_type && $wiopt_display_product_img ) {
			$image = '2';
		} elseif ( 'credit_note' === $this->template_type && $wiopt_display_product_img ) {
			$image = '2';
		} elseif ( 'packing_slip' === $this->template_type && get_option( 'wiopt_packingslip_product_image_show' ) ) {
			$image = '2';
		}

		ob_start();
		// Action Before the product table.
		echo woo_invo_ice_before_product_list( $this->order, $this->template_type );//phpcs:ignore

		?>
        <table border="" class="product-table <?php echo esc_html( $page_break ) ?>">
            <thead>
            <tr class="product-list-header">
                <th class="product-column"
                    colspan="<?php echo esc_attr( $image ); ?>"><?php echo woo_invo_ice_filter_label( "Item", $this->order, $this->template_type ); //phpcs:ignore
					?></th>

				<?php if ( 'invoice' === $this->template_type || 'credit_note' === $this->template_type ) : ?>

					<?php
					$invoice_header_and_data = $this->get_invoice_header_and_data();
					$selected_header = $invoice_header_and_data['header'];
					$selected_invoice_data = $invoice_header_and_data['data'];

					foreach ( $selected_header as $key => $value ) { ?>
                        <th class="price-column"><?php echo esc_html_e( $value ); //phpcs:ignore ?></th>
					<?php } ?>
				<?php elseif ( 'packing_slip' === $this->template_type ) : ?>
					<?php
					$packing_slip_header_and_data = $this->get_packing_slip_header_and_data();
					$selected_packing_slip_header = $packing_slip_header_and_data['header'];
					$selected_packing_slip_data = $packing_slip_header_and_data['data'];
					foreach ($selected_packing_slip_header as $key => $value) { ?>
                        <th class="packing-slip-column"><?php echo esc_html($value); ?></th>
					<?php } ?>

				<?php endif; ?>


            </tr>
            </thead>
            <tbody class="product-list-tbody">
			<?php
			foreach ( $products as $key => $product ) :

				unset( $product['id'] );
				unset( $product['raw_price'] );
				unset( $product['raw_total'] );
				unset( $product['raw_title'] );
				unset( $product['raw_quantity'] );
				unset( $product['raw_weight'] );
				unset( $product['product_meta'] );

				?>
                <tr class="product-list">
                    <!-- Available classes for td row -->
                    <!-- [ .product-img, .product, .price .quantity, .total, .tax] -->
					<?php
					if ( 'invoice' === $this->template_type || 'credit_note' === $this->template_type ) {
						// Code for the invoice template
						foreach ( $selected_invoice_data as $key ) {
							if ( in_array( $key, $selected_invoice_data ) ) {
								if ( $key === 'tax_rate' && $product[ $key ] === '' ) {
									$product[ $key ] = '-';
								}

								?>
                                    <td class="<?php echo esc_html( $key ); ?>">
                                        <?php echo $product[$key] ?? ''; // Display product details in invoice ?>
                                        <br>
                                        <?php
                                        // Check if the conditions for displaying the regular price are met with discount price
                                        $display_regular_price = !in_array('discount', $selected_invoice_data) &&
                                                                 apply_filters('wiopt_display_regular_price', $key === 'price' && $product[$key] != $product['regular_price'], $key, $product);
                                        if ($display_regular_price) : ?>
                                            <del class="wiopt-regular-price">
                                                (<?php echo $product['regular_price']; ?>)
                                            </del>
                                        <?php endif; ?>
                                    </td>
                                    <?php
							}
						}
					} elseif ( 'packing_slip' === $this->template_type ) {

						// Code for the packing slip template
						foreach ( $selected_packing_slip_data as $key  ) {
							?>
                            <td class="<?php echo esc_html( $key ); ?>">
								<?php
								if( isset( $product[ $key ] ) && $product[ $key ] ) {
									echo $product[ $key ]; // Display product details in packing slip
								}
								else {
									echo 'N/A';
								}
								?>
                            </td>
							<?php
						}
					}

					?>
                </tr>
			<?php
			endforeach;
			?>
            </tbody>
			<?php

			if ( 'pageBreak' == $page_break ) {
				echo $this->get_page_number(); //phpcs:ignore
			}
			?>
        </table>

		<?php


		// Action After the product table.
		echo woo_invo_ice_after_product_list( $this->order, $this->template_type );//phpcs:ignore
		$html = ob_get_contents();
		ob_end_clean();

		return $html;
	}

	/**
	 * Invoice product section header and data based on selection from Invoice dashboard menu.
	 *
	 * @return array
	 */
	private function get_invoice_header_and_data(){
		$tax_label = 'Tax';
		if ( ! empty( $this->order->get_taxes() ) ) :
			foreach ( $this->order->get_taxes() as $tax_id => $tax_item ) :
				$tax_label = ! empty( $tax_item['label'] ) ? $tax_item['label'] : __( 'Tax', 'woocommerce' );
				break;
			endforeach;
		endif;
		if ( $tax_label === '' ) {
			$tax_label = 'Tax';
		}
		$tax_label       = woo_invo_ice_filter_label( $tax_label, $this->order, $this->template_type );
		$options         = [
			'price'                => woo_invo_ice_filter_label( 'Cost', $this->order, $this->template_type ),
			'quantity'             => woo_invo_ice_filter_label( 'Qty', $this->order, $this->template_type ),
			'tax'                  => $tax_label,
			'tax_inc_discounted'   => woo_invo_ice_filter_label( $tax_label . ' Inc. Discount', $this->order, $this->template_type ),
			'tax_ex_discounted'    => woo_invo_ice_filter_label( $tax_label . ' Ex. Discount', $this->order, $this->template_type ),
			'total'                => woo_invo_ice_filter_label( 'Total', $this->order, $this->template_type ),
			'total_inc_discounted' => woo_invo_ice_filter_label( 'Total Inc. Discount', $this->order, $this->template_type ),
			'total_ex_discounted'  => woo_invo_ice_filter_label( 'Total Ex. Discount', $this->order, $this->template_type ),
			'tax_rate'             => woo_invo_ice_filter_label( $tax_label . ' %', $this->order, $this->template_type ),
			'regular_price'        => woo_invo_ice_filter_label( 'Regular Price', $this->order, $this->template_type ),
			'regular_price_with_tax'     => woo_invo_ice_filter_label( 'Regular Price with Tax', $this->order, $this->template_type ),
			'sale_price_with_tax'        => woo_invo_ice_filter_label( 'Sale Price with Tax', $this->order, $this->template_type ),
			'price_with_tax'        => woo_invo_ice_filter_label( 'Price with Tax', $this->order, $this->template_type ),
			'sale_price'           => woo_invo_ice_filter_label( 'Sale Price', $this->order, $this->template_type ),
			'discount'             => woo_invo_ice_filter_label( 'Discount', $this->order, $this->template_type ),
			'total_inc_tax'        => woo_invo_ice_filter_label( 'Total Inc. ' . $tax_label, $this->order, $this->template_type ),
			'total_ex_tax'         => woo_invo_ice_filter_label( 'Total Ex. ' . $tax_label, $this->order, $this->template_type ),
		];
		$selected_invoice_data = get_option( 'wiopt_select_product_column' );
		if ( ! $selected_invoice_data ) {
			$selected_invoice_data = [ 'price', 'quantity', 'tax', 'tax_rate', 'total' ];
		}
		array_unshift( $selected_invoice_data, 'product' );
		if ( get_option( 'wiopt_product_image_show' ) ) {
			array_unshift( $selected_invoice_data, 'product-img' );
		}

		$common_keys  = array_intersect( $selected_invoice_data, array_keys( $options ) );
		$common_value = [];
		foreach ( $selected_invoice_data as $key => $value ) {
			if ( array_key_exists( $value, $options ) ) {
				$common_value[] = $options[ $value ];
			}
		}
		$selected_header = array_combine( $common_keys, $common_value );
		return apply_filters( 'woo_invo_ice_packing_slip_product_header_and_data', [
			'header' => $selected_header,
			'data' => $selected_invoice_data,
		] , $this->order, $this->template_type );
	}


	/**
	 * Packing slip product section header and data based on selection from packing slip dashboard menu.
	 *
	 * @return array
	 */
	private function get_packing_slip_header_and_data() {
		$packing_slip_options = [
			'quantity'   => woo_invo_ice_filter_label( 'Qty', $this->order, $this->template_type ),
			'weight'     => woo_invo_ice_filter_label( 'Weight', $this->order, $this->template_type ),
			'dimension'  => woo_invo_ice_filter_label( 'Dimension', $this->order, $this->template_type ),
		];

		// packing slip data
		$selected_packing_slip_data = get_option( 'wiopt_packingslip_product_table_header' );
		if ( ! $selected_packing_slip_data ) {
			$selected_packing_slip_data = [ 'weight', 'dimension', 'quantity' ];
		}
		array_unshift( $selected_packing_slip_data, 'product' );
		if ( get_option( 'wiopt_packingslip_product_image_show' ) ) {
			array_unshift( $selected_packing_slip_data, 'product-img' );
		}

		// packing slip header
		$common_keys = array_intersect($selected_packing_slip_data, array_keys($packing_slip_options));
		$common_value = [];
		foreach ($selected_packing_slip_data as $key => $value) {
			if (array_key_exists($value, $packing_slip_options)) {
				$common_value[] = $packing_slip_options[$value];
			}
		}
		$selected_packing_slip_header = array_combine($common_keys, $common_value);


		return apply_filters( 'woo_invo_ice_packing_slip_product_header_and_data', [
			'header' => $selected_packing_slip_header,
			'data' => $selected_packing_slip_data,
		] , $this->order, $this->template_type );

	}

	/**
	 * Load Order total table
	 *
	 * @param array $order Order Info.
	 *
	 * @return string
	 */
	public function get_product_total_section( $order ) {
		$total = $order['totals'];

		$paid_stamp = $this->get_paid_stamp_section( $order['status'] );

		$row_span = count( $total ) + 1;

		if ( ! $total['discount_total'] ) {
			-- $row_span;
		}

		if ( ! $total['tax_total'] || empty( $total['tax_total'] ) ) {
			-- $row_span;
		}

		if ( ! $total['shipping_total'] || empty( $total['shipping_total'] ) ) {
			-- $row_span;
		}

		if ( ! empty( $total['total_refund'] ) ) {
			$row_span -= 2;
		}

		if ( ! get_option( 'wiopt_display_total_without_tax' ) ) {
			-- $row_span;
		}
		if ( ! get_option( 'wiopt_total_fees' ) || empty( $total['fees'] ) ) {
			-- $row_span;
		}

		if ( wc_tax_enabled() ) {
			$row_span += count( $this->order->get_tax_totals() );
		}
		ob_start();
		?>
        <!-- WooCommerce Product Add-Ons Ultimate data add . -->
        <table class="fee-table">
            <tbody>
			<?php
			$line_items_fee = $this->order->get_items( 'fee' );

			if ( is_array( $line_items_fee )
			     && ! empty( $line_items_fee )
			     && ( 'invoice' === $this->template_type || 'credit_note' === $this->template_type ) ) :
				foreach ( $line_items_fee as $item_id => $item ) {
					do_action( 'woocommerce_before_order_item_' . $item->get_type() . '_html', $item_id, $item, $this->order );

					// include __DIR__ . '/html-order-item.php';
					?>

                    <tr class="fee <?php echo ( ! empty( $class ) ) ? esc_attr( $class ) : ''; ?>"
                        data-order_item_id="<?php echo esc_attr( $item_id ); ?>">

                        <td class="name">
                            <div class="view">
								<?php echo esc_html( $item->get_name() ? $item->get_name() : __( 'Fee', 'woocommerce' ) ); ?>
                            </div>

							<?php do_action( 'woocommerce_after_order_fee_item_name', $item_id, $item, null ); ?>
                        </td>

						<?php do_action( 'woocommerce_admin_order_item_values', null, $item, absint( $item_id ) ); ?>

                        <td class="line_cost" style="text-align:right">
                            <div class="view">
								<?php
								echo wc_price( $item->get_total(), array( 'currency' => $this->order->get_currency() ) );//phpcs:ignore

								if ( $refunded = $this->order->get_total_refunded_for_item( $item_id, 'fee' ) ) {
									echo '<small class="refunded">-' . wc_price( $refunded, array( 'currency' => $this->order->get_currency() ) ) . '</small>';//phpcs:ignore;
								}
								?>
                            </div>

                        </td>
                    </tr>
					<?php
					do_action( 'woocommerce_order_item_' . $item->get_type() . '_html', $item_id, $item, $this->order );
				}
			endif;
			do_action( 'woocommerce_admin_order_items_after_fees', $this->order->get_id() );

			?>
            </tbody>
        </table>
		<?php

		$fees = ob_get_contents();
		ob_end_clean();

		$product_total = apply_filters( 'woo_invo_ice_fee_table', $fees, $this->template_type, $this->order );

		// Get refund details.
		if ( ! empty( $total['refunds'] ) ) {
			$product_total .= '<table class="refund-table"><tbody>';
			foreach ( $total['refunds'] as $order_refunds ) :
				$product_total .= "<tr>";
				$product_total .= "<td><strong>";
				$product_total .= woo_invo_ice_filter_label( 'Refund', $this->order, $this->template_type );
				$product_total .= " #" . $order_refunds['id'] . " - " . $order_refunds['date'];
				$product_total .= "</strong><p style='font-size: 10px'>";
				$product_total .= esc_html__( $order_refunds['reason'], 'astama-pdf-invoice-for-woocommerce' );//phpcs:ignore
				$product_total .= "</p> </td>";
				$product_total .= "<td>" . $order_refunds['total'] . "</td>";
				$product_total .= "<tr>";
			endforeach;
			$product_total .= '</tbody></table>';
			$product_total .= '<table border="" class="order-total-table"><tbody>';
		} else {
			$product_total .= '<table border="" class="order-total-table"><tbody>';
		}


		// End refund table.


		$product_total .= "<tr><td rowspan='$row_span' class='paid-stamp'> $paid_stamp</td> <td class='order-total-label subtotal-label'>" . woo_invo_ice_filter_label( 'Items Subtotal', $this->order, $this->template_type ) . ":&nbsp;&nbsp;</td><td class='order-total-value subtotal-value'>" . $total['subtotal'] . '</td></tr>'; //phpcs:ignore

		if ( $total['discount_total'] ) {
			$product_total .= "<tr><td class='order-total-label discount-label'>" . woo_invo_ice_filter_label( 'Coupon(s)', $this->order, $this->template_type ) . " :&nbsp;&nbsp;</td><td class='order-total-value discount-value'>-&nbsp;" . $total['discount_total'] . '</td></tr>'; //phpcs:ignore
		}

		if ( ! empty( $total['total_fees'] ) && '0' !== get_option( 'wiopt_total_fees' ) ) {
			// Get order fees.
			if ( isset( $total['fees'] ) && ! empty( $total['fees'] ) && count( $total['fees'] ) > 1 ) {
				foreach ( $total['fees'] as $key => $value ) {
					if ( ! empty( $value ) && is_array( $value ) && count( $value ) > 1 ) {
						$fees          = $this->helper->format_price( wc_get_order( $order['ID'] ), $value['total'] );
						$product_total .= "<tr><td class='order-total-label fees-label'>" . esc_html__( $value['name'] . " :", 'woocommerce' ) . "&nbsp;&nbsp;</td><td class='order-total-value fees-value'>" . $fees . '</td></tr>'; //phpcs:ignore
					}
				}
			} else {
				if ( ! empty( $total['total_fees'] ) && '0' !== get_option( 'wiopt_total_fees' ) ) {
					$product_total .= "<tr><td class='order-total-label fees-label'>" . woo_invo_ice_filter_label( 'Fees', $this->order, $this->template_type ) . " :&nbsp;&nbsp;</td><td class='order-total-value fees-value'>" . $total['total_fees'] . '</td></tr>'; //phpcs:ignore
				}
			}
		}

		if ( ! empty( $total['shipping_total'] ) ) {
			if ( 'wiopt_invoice_display_shipping_total_with_tax' === get_option( 'wiopt_invoice_display_shipping_total' ) ) {
				$product_total .= "<tr><td class='order-total-label shipping-label'>" . woo_invo_ice_filter_label( 'Shipping', $this->order, $this->template_type ) . ' ' . WC()->countries->inc_tax_or_vat() . " :&nbsp;&nbsp;</td><td class='order-total-value shipping-value'>" . $total['shipping_total'] . '</td></tr>'; //phpcs:ignore
			} else {
				$product_total .= "<tr><td class='order-total-label shipping-label'>" . woo_invo_ice_filter_label( 'Shipping', $this->order, $this->template_type ) . ' ' . WC()->countries->ex_tax_or_vat() . " :&nbsp;&nbsp;</td><td class='order-total-value shipping-value'>" . $total['shipping_total'] . '</td></tr>'; //phpcs:ignore
			}
		}

		if ( wc_tax_enabled() ) :
			if ( ! empty( $this->order->get_tax_totals() ) ) {
				foreach ( $this->order->get_tax_totals() as $code => $tax_total ) :
					$product_total .= "<tr><td class='order-total-label tax-label'>" . woo_invo_ice_filter_label( $tax_total->label, $this->order, $this->template_type ) . " :&nbsp;&nbsp;</td><td class='order-total-value tax-value'>" . $this->helper->format_price( $this->order, $tax_total->amount ) . '</td></tr>'; //phpcs:ignore
				endforeach;
			} else {
				$product_total .= "<tr><td class='order-total-label tax-label'>" . woo_invo_ice_filter_label( 'Tax', $this->order, $this->template_type ) . " :&nbsp;&nbsp;</td><td class='order-total-value tax-value'>" . $this->helper->format_price( $this->order, 0 ) . '</td></tr>'; //phpcs:ignore
			}
		endif;

		if ( get_option( 'wiopt_display_total_without_tax' ) ) {
			$product_total .= "<tr><td class='order-total-label without-tax-label'>" . woo_invo_ice_filter_label( 'Order Total Without Tax', $this->order, $this->template_type ) . " :&nbsp;&nbsp;</td><td class='order-total-value without-tax-value'>" . $total['total_without_tax'] . '</td></tr>';
		}
		$order_total = $total['grand_total'];
		if ( ! empty( $total['total_refund'] ) ) {
			$refund    = $total['total_refund'];
			$net_total = $total['net_total'];
		}

		// Run loop for filtered value.
		unset( $total['subtotal'] );
		unset( $total['shipping_total'] );
		unset( $total['tax_total'] );
		unset( $total['discount_total'] );
		unset( $total['fees'] );
		unset( $total['total_fees'] );
		unset( $total['total_without_tax'] );
		unset( $total['grand_total'] );
		unset( $total['total_refund'] );
		unset( $total['net_total'] );
		unset( $total['shipping_methods'] );
		unset( $total['refunds'] );
		unset( $total['paid'] );

		if ( ! empty( $total ) ) {
			foreach ( $total as $key => $value ) {
				$product_total .= "<tr><td class='order-total-label'>" . __( $key, 'astama-pdf-invoice-for-woocommerce' ) . " :&nbsp;&nbsp;</td><td class='order-total-value'>$value</td></tr>"; //phpcs:ignore
			}
		}

	   
		$product_total .= "<tr><td class='order-total-label'>" . woo_invo_ice_filter_label( 'Order Total', $this->order, $this->template_type ) . " :&nbsp;&nbsp;</td><td class='order-total-value'>" . $order_total . '</td></tr>'; //phpcs:ignore

		// Show Total Refund & Net Payment.
		if ( isset( $refund ) && isset( $net_total ) ) {
			$product_total .= "<tr><td class='order-total-label '>" . woo_invo_ice_filter_label( 'Refund', $this->order, $this->template_type ) . " :&nbsp;&nbsp;</td><td class='order-total-value  refund'>-&nbsp;" . $refund . '</td></tr>'; //phpcs:ignore
			$product_total .= "<tr><td class='order-total-label '>" . woo_invo_ice_filter_label( 'Net Payment', $this->order, $this->template_type ) . " :&nbsp;&nbsp;</td><td class='order-total-value'>" . $net_total . '</td></tr>'; //phpcs:ignore
		}
		$product_total .= "<tr class='total-last-tr'><td class='order-total-label total-last-td-label'></td><td class='order-total-value total-last-td-value'></td></tr>";
		$product_total .= '</tbody></table>';

		ob_start();

		do_action( 'woocommerce_admin_order_totals_after_total', $this->order->get_id() );

		$after_total = ob_get_contents();
		ob_end_clean();

		$product_total .= apply_filters( 'woo_invo_ice_after_total', $after_total, $this->order->get_id() );

		return $product_total;
	}

	/**
	 * Get packing total info.
	 *
	 * @param string $order Order.
	 *
	 * @return string
	 */
	public function get_packing_total_section( $order ) {

		$vendor_address = '';
		if ( isset( $order['vendor_address'] ) ) {
			$vendor_address = apply_filters( 'woo_invo_ice_vendor_refund_address', $order['vendor_address'] );
		}

		$product_total = '';
		$packing_total = $order['packing_total'];
		$product_total .= '<table border="" class="packing-total-table"><tbody>';
		if ( ! empty( $packing_total ) ) {
			$rowspan                = count( $packing_total );
			$product_total_quantity = $packing_total['quantity'];
			$product_total_weight   = $packing_total['weight'];
			$weight_weight          = $product_total_weight . ' ' . get_option( 'woocommerce_weight_unit' );
			$product_total          .= "<tr><td class='order-total-label'>" . woo_invo_ice_filter_label( 'Total Quantity', $this->order, $this->template_type ) . " :&nbsp;&nbsp;</td><td class='order-total-value'>" . $product_total_quantity . '</td></tr>';
			if ( $product_total_weight > 0 ) {
				$product_total .= "<tr><td class='order-total-label'>" . woo_invo_ice_filter_label( 'Total Weight', $this->order, $this->template_type ) . " :&nbsp;&nbsp;</td><td class='order-total-value'>" . $weight_weight . '</td></tr>';
			}
			// Remove weight and quantity from array.
			unset( $packing_total['weight'] );
			unset( $packing_total['quantity'] );
			foreach ( $packing_total as $key => $value ) {
				$product_total .= "<tr><td class='order-total-label'>" . woo_invo_ice_filter_label( $key, $this->order, $this->template_type ) . " :</td><td class='order-total-value'>" . $value . '</td></tr>'; //phpcs:ignore
			}
		}
		$product_total .= '</table>';

		// Vendor Refund Address.
		if ( '1' == get_option( 'wiopt_display_refund_address' ) ) {
			if ( class_exists( 'WCFM' ) && '' !== $vendor_address ) {

				$product_total .= '<table>';
				$product_total .= '<tr><td><b>' . woo_invo_ice_filter_label( 'Refund Address', $this->order, $this->template_type ) . '</b></td></tr>';

				// Vendor City.
				if ( '' !== $vendor_address['city'] ) {
					$product_total .= '<tr><td> ' . esc_html__( $vendor_address['city'], 'woocommerce' ) . '</td></tr>';//phpcs:ignore

				}
				// Vendor Address.
				if ( '' !== $vendor_address['address'] ) {
					$product_total .= '<tr><td> ' . esc_html__( $vendor_address['address'], 'woocommerce' ) . '</td></tr>';//phpcs:ignore

				}
				// Vendor Post Code.
				if ( '' !== $vendor_address['zip_code'] ) {
					$product_total .= '<tr><td> ' . esc_html__( $vendor_address['zip_code'], 'woocommerce' ) . '</td></tr>';//phpcs:ignore

				}
				// Vendor Country.
				if ( '' !== $vendor_address['country'] ) {
					$product_total .= '<tr><td> ' . esc_html__( $vendor_address['country'], 'woocommerce' ) . '</td></tr>';//phpcs:ignore

				}
				// Vendor Phone.
				if ( '' !== $vendor_address['phone'] ) {
					$product_total .= '<tr><td>' . woo_invo_ice_filter_label( 'Phone', $this->order, $this->template_type ) . ' :  ' . esc_html( $vendor_address['phone'] ) . '</td></tr>';
				}
				// Vendor Email.
				if ( '' !== $vendor_address['email'] ) {
					$product_total .= '<tr><td>' . woo_invo_ice_filter_label( 'Email', $this->order, $this->template_type ) . ' : ' . esc_html( $vendor_address['email'] ) . '</td></tr>';
				}

				$product_total .= '</table>';
			}
		}

		return $product_total;

	}

	/**
	 * Load Paid Stamp
	 *
	 * @param string $order_status Order Status.
	 *
	 * @return string
	 */
	public function get_paid_stamp_section( $order_status ) {
		return $this->helper->get_paid_stamp( $order_status );
	}

	/**
	 * Load order note
	 *
	 * @param string $order_note Customer Note.
	 *
	 * @return false|string
	 */
	public function get_order_note_section( $order_note, $order_id, $has_note = false ) {
		$order_note = $has_note ? $order_note : '-';
		// Action before order note.
		$before_order_note = woo_invo_ice_before_customer_notes( $this->order, $this->template_type );
		// Action after order note.
		$after_order_note = woo_invo_ice_after_customer_notes( $this->order, $this->template_type );
		$order_note = '<b>' . woo_invo_ice_filter_label( 'Customer Note', $this->order, $this->template_type ) . ':</b> ' . $order_note;

		if ( 'invoice' === $this->template_type && get_option( 'wiopt_show_order_note' ) ) {
			if ( has_filter( 'woo_invo_ice_customer_notes' ) ) {
				$order_note = apply_filters( 'woo_invo_ice_customer_notes', $order_note, $this->template_type, $this->order );
			}
		} elseif ( 'packing_slip' === $this->template_type && get_option( 'wiopt_show_order_note_ps' ) ) {
			if ( has_filter( 'woo_invo_ice_customer_notes' ) ) {
				$order_note = apply_filters( 'woo_invo_ice_customer_notes', $order_note, $this->template_type, $this->order );
			}
		}

		ob_start();
		?>
		<table border="" dir="<?php echo esc_html( $this->rtl ); ?>" class="order-note-table">
            <tbody>
			<tr><td>Sudah termasuk PPN 11%</td></tr>
            </tbody>
        </table>
        <table border="" dir="<?php echo esc_html( $this->rtl ); ?>" class="order-note-table">
            <tbody>
			<?php echo ( ! empty( $before_order_note ) ) ? "<tr><td class='order-note-before'>" . $before_order_note . '</td></tr>' : ''; //phpcs:ignore
			?>

			<?php echo "<tr><td class='order-note'>" . $order_note . '</td></tr>'; //phpcs:ignore
			?>

			<?php echo ( ! empty( $after_order_note ) ) ? "<tr><td class='order-note-after'>" . $after_order_note . '</td></tr>' : ''; //phpcs:ignore
			?>
            </tbody>
        </table>
		<?php

		$html = ob_get_contents();
		ob_end_clean();

		return $html;
	}


	/**
	 * Load Bank Accounts
	 *
	 * @param $accounts
	 *
	 * @return false|string
	 */
	public function get_bank_accounts_section( $accounts ) {
		if ( empty( $accounts ) ) {
			return '';
		}

		ob_start();
		?>

        <table border="" class="bank-accounts-table">
            <thead>
            <tr class="bank-account-list-header">
                <th class=""><?php echo esc_html( woo_invo_ice_filter_label( 'Bank Name', $this->order, $this->template_type ) ); ?></th>
                <th class=""><?php echo esc_html( woo_invo_ice_filter_label( 'Account Name', $this->order, $this->template_type ) ); ?></th>
                <th class=""><?php echo esc_html( woo_invo_ice_filter_label( 'Account No', $this->order, $this->template_type ) ); ?></th>
                <!-- <th class=""><?php echo esc_html( woo_invo_ice_filter_label( 'Sort Code', $this->order, $this->template_type ) ); ?></th>
                <th class=""><?php echo esc_html( woo_invo_ice_filter_label( 'IBAN', $this->order, $this->template_type ) ); ?></th>
                <th class=""><?php echo esc_html( woo_invo_ice_filter_label( 'BIC/Swift', $this->order, $this->template_type ) ); ?></th> -->
            </tr>

            </thead>
            <tbody>
			<?php foreach ( $accounts as $key => $value ) : ?>
                <tr class="bank-account-list">
                    <td class=""><?php echo esc_html( $value['bank_name'] ); ?></td>
                    <td class=""><?php echo esc_html( $value['account_name'] ); ?></td>
                    <td class=""><?php echo esc_html( $value['account_number'] ); ?></td>
                    <!-- <td class=""><?php echo esc_html( $value['sort_code'] ); ?></td>
                    <td class=""><?php echo esc_html( $value['iban'] ); ?></td>
                    <td class=""><?php echo esc_html( $value['bic'] ); ?></td> -->
                </tr>
			<?php endforeach; ?>
            </tbody>
        </table>
        <br>
		<?php
		$html = ob_get_contents();
		ob_end_clean();

		return $html;
	}

	/**
	 * Load Store Signature
	 *
	 * @return string
	 */
	public function get_signature_section() {
		$image_url = $this->helper->get_signature();
		if ( ! empty( $image_url ) ) {
			$signature_text = ( get_option( 'wiopt_signature_text' ) ) ? get_option( 'wiopt_signature_text' ) : 'Authorized Signature';
			$signature      = '<td>';
			$signature      .= '<img class="signature" src="' . $image_url . '" alt="signature" ><br/>';
			$signature      .= '<p class="signature-text">' . woo_invo_ice_filter_label( 'Authorized Signature', $this->order, $this->template_type ) . '</p>';
			$signature      .= '</td>';

			return $signature;
		}

		return '';
	}

	/**
	 * Load Footer Section
	 *
	 * @return false|string
	 */
public function get_footer_section() {

if ( 'invoice' === $this->template_type || 'credit_note' === $this->template_type ) {
	ob_start();
	$terms_and_conditions = stripslashes( get_option( 'wiopt_terms_and_condition' ) );
	if ( has_filter( 'woo_invo_ice_footer_1' ) ) {
		$terms_and_conditions = apply_filters( 'woo_invo_ice_footer_1', $terms_and_conditions, $this->template_type, $this->order );
	}
	// Footer 2.
	$other_information = stripslashes( get_option( 'wiopt_other_information' ) );
	if ( has_filter( 'woo_invo_ice_footer_2' ) ) {
		$other_information = apply_filters( 'woo_invo_ice_footer_2', $other_information, $this->template_type, $this->order );
	}

	$footer_font_size = ( get_option( 'wiopt_invoice_footer_font_size' ) ) ? get_option( 'wiopt_invoice_footer_font_size' ) : '9';
	$footer_line      = '';
	if ( get_option( 'wiopt_display_footer_line' ) ) {
		$footer_line = "<hr class='invoice-footer-hr'>";
	}
	?>
    <htmlpagefooter name="invoiceFooter">
		<?php echo $footer_line; //phpcs:ignore
		?>
        <div class="invoice-footer">
            <table border="0" class="invoice-footer-table">
                <tbody>
					<tr>
						<td class="order-term-condition">
							<?php if ( ! empty( $terms_and_conditions ) ) : ?>
								<p style="font-size:<?php echo esc_html( $footer_font_size ) . 'px'; ?>"><?php echo $terms_and_conditions; ?></p>
								<br>
							<?php endif;
							if ( ! empty( $other_information ) ) : ?>
								<p style="font-size:<?php echo esc_html( $footer_font_size ) . 'px'; ?>"><?php echo $other_information; ?></p>
							<?php endif; ?>
						</td>

						<?php echo $this->get_signature_section(); //phpcs:ignore
						?>
                </tr>
                </tbody>
            </table>
			<?php
			if ( ! isset( $_GET['order_ids'] ) ) {
				$template_type = get_option( 'wiopt_add_page_number' );
				if ( 1 == get_option( 'wiopt_enable_page_number' ) && count( $template_type ) > 0 ) {
					if ( ( in_array( 'invoice', $template_type ) && 'invoice' === $this->template_type )
					     || ( in_array( 'credit_note', $template_type ) && 'credit_note' === $this->template_type ) ) { ?>
                        <p>
                        <div align="center">{PAGENO}</div></p>
						<?php
					}
				}
			}
			// end. Enable page number option.
			?>
			<?php echo woo_invo_ice_after_document( $this->order, $this->template_type ); //phpcs:ignore
			?>
        </div>
    </htmlpagefooter>
    <sethtmlpagefooter name="invoiceFooter" value="1"/>

</div>
	<?php
	$html = '';
	$html = ob_get_contents();
	ob_end_clean();
	return $html;
} else {

	$html = '';
	ob_start();
	// Enable page number option.
	if ( ! isset( $_GET['order_ids'] ) ) {
		$template_type = get_option( 'wiopt_add_page_number' );
		if ( 1 == get_option( 'wiopt_enable_page_number' ) && count( $template_type ) > 0 ) {
			if ( in_array( 'packing_slip', $template_type ) && 'packing_slip' === $this->template_type ) {
				?>
                <htmlpagefooter name="invoiceFooter">
                    <div align="center">{PAGENO}</div>
                </htmlpagefooter>
                <sethtmlpagefooter name="invoiceFooter" value="1"/>
				<?php
			}
		}
	}
	echo '</div></div>';
	$html = ob_get_contents();
	ob_end_clean();

	return $html;
}

}
	/**
	 * Get page number, If header and footer is non repeatable.
	 */
	public function get_page_number() {
		ob_start();
		// Enable page number option.
		if ( ! isset( $_GET['order_ids'] ) ) {
			$template_type = get_option( 'wiopt_add_page_number' );
			if ( 1 == get_option( 'wiopt_enable_page_number' ) && count( $template_type ) > 0 ) {
				if ( ( in_array( 'invoice', $template_type ) && 'invoice' === $this->template_type )
				     || ( in_array( 'packing_slip', $template_type ) && 'packing_slip' === $this->template_type )
				     || ( in_array( 'credit_note', $template_type ) && 'credit_note' === $this->template_type ) ) {
					?>
                    <htmlpagefooter name="invoiceFooter1">
                        <div align="center">{PAGENO}</div>
                    </htmlpagefooter>
                    <sethtmlpagefooter name="invoiceFooter1" value="1"/>
					<?php

				}
			}
		}

		$html = ob_get_contents();
		ob_end_clean();

		return $html;
	}

	/**
	 * Close HTML Tags
	 *
	 * @return false|string
	 */
	public function get_html_end() {
		ob_start();
		?>
        </body>
        </html>
		<?php
		$html = ob_get_contents();
		ob_end_clean();

		return $html;
	}
}


/**
 * Load Woo_Invo_Ice_Template class within function.
 *
 * @param array $order_ids Order Id or Ids.
 * @param string $template Template Type.
 * @param int $vendor Vendor Id.
 *
 * @return object
 */
function woo_invo_ice_template( $order_ids, $template, $vendor = null ) {
	return new Woo_Invo_Ice_Template( $order_ids, $template, $vendor );

}
