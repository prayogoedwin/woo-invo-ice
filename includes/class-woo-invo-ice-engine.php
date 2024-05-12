<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link  https://astamatechnology.com
 * @since 1.0.0
 *
 * @package    Woo_Invo_Ice
 * @subpackage Woo_Invo_Ice/includes
 */

/**
 * The core plugin class that generate PDF Invoice.
 *
 * This is used to generate PDF Invoice
 *
 * @since      1.0.0
 * @package    Woo_Invo_Ice
 * @subpackage Woo_Invo_Ice/includes
 * @author     Md Ohidul Islam <wahid@astama.com>
 */
class Woo_Invo_Ice_Engine  {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    Woo_Invo_Ice_Engine $orderId store all order ids.
	 */

	public $orderIds;
	public $orderId;
	public $products;
	public $orderInfo;
	public $column;
	public $row;
	public $shipping_lebel_paper;
	public $currencySymbol = '';
	public $countries;
	public $paperSizes = array( 'A3', 'A4', 'A5', 'Letter' );
	public $fontSize;
    public $invoice_lang;
	// TODO Save plugin settings options into a variable

	/**
	 * Woo_Invo_Ice_Engine constructor.
	 *
	 * @param $orderIds
	 * @param null     $column
	 * @param null     $paper_size
	 * @param null     $row
	 */
	public function __construct( $orderIds, $column = null, $paper_size = null, $row = null, $font_size = null ) {

		$this->orderIds = $orderIds;
		$this->column    = $column;
		$this->row       = $row;
		$this->fontSize  = $font_size;
		$this->countries = new WC_Countries();
		$this->products  = array();
		$this->orderInfo = array();
		$this->shipping_lebel_paper = $paper_size;
		$this->extractOrdersInfo();
		if ( ! empty( get_option( 'wiopt_currency_code' ) ) ) {
			$this->currencySymbol = ' (' . get_woocommerce_currency() . ')';
		}

	}

	public function generateInvoice( $orderId ) {
        $template = isset($_GET['template']) ? sanitize_text_field( wp_unslash( $_GET['template'] ) ) : "";
		$ids      = explode( ',', $this->orderIds );
        if ( 'credit_note' == $template ) {
            $template_type = 'credit_note';
        }else {
            $template_type = 'invoice';
        }

		$template = woo_invo_ice_template( $ids, $template_type );

		// Output type html.
		$get_url = isset($_SERVER['REQUEST_URI']) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : ''; //phpcs:ignore
		$url_components = wp_parse_url( $get_url );
		$url_components = parse_str( $url_components['query'], $params );
		if ( isset( $params['output'] ) && 'html' === $params['output'] ) {
			echo $template->get_invoice_template(); //phpcs:ignore
            die;
		}

		// DEBUG HERE INVOICE
		echo $template->get_invoice_template();
		wp_die();
	}

	/**
	 * Generate Bulk or Single PDF Invoice Packing Slip
	 *
	 * @param int    $orderId
	 * @param string $download_type
	 * @param string $bulkEmailID
	 *
	 * @throws \Mpdf\MpdfException
	 */
	public function generatePackingSlip( $orderId = null, $download_type = null, $bulkEmailID = null, $vendor = null ) {
		$ids      = explode( ',', $this->orderIds );
		$template = woo_invo_ice_template( $ids, 'packing_slip', $vendor );

		// Output type html.
		$get_url = isset($_SERVER['REQUEST_URI']) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : ''; //phpcs:ignore
		$url_components = wp_parse_url( $get_url );
		$url_components = parse_str( $url_components['query'], $params );
		if ( isset( $params['output'] ) && 'html' === $params['output'] ) {
			echo $template->get_packing_template(); //phpcs:ignore
			die;
		}

		// DEBUG HERE INVOICE.
		echo $template->get_invoice_template();
		wp_die();
	}

	/**
	 * Generate Shipping Lebel.
	 */
	public function generateShippingLabel( $orderId ) {
		$ids      = explode( ',', $this->orderIds );
		$template = woo_invo_ice_template( $ids, 'label' );
		// Output type html.
		$get_url = isset($_SERVER['REQUEST_URI']) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : ''; //phpcs:ignore
		$url_components = wp_parse_url( $get_url );
		$url_components = parse_str( $url_components['query'], $params );
		if ( isset( $params['output'] ) && 'html' === $params['output'] ) {
			echo $template->get_shipping_label_template( $this->column, $this->row, $this->fontSize ); //phpcs:ignore
			die;
		}

		// DEBUG HERE INVOICE.
		echo $template->get_shipping_label_template($this->column, $this->row, $this->fontSize);
		wp_die();
	}

	/**
	 * Generate Order List
	 */
	public function generateOrderList() {
		$baseDir = WOO_INVO_ICE_DIR;

		ob_start();
		$orders = $this->orderInfo;

		// Get Template Content

		if ( $this->orderIds ) {
			echo $this->getTemplateContent( 'orderlist', $orders ); //phpcs:ignore
		} else {
			include plugin_dir_path( __FILE__ ) . 'templates/empty.php';
		}
		$html = ob_get_contents();
		ob_end_clean();
        $defaultConfig = ( new Mpdf\Config\ConfigVariables() )->getDefaults();
		$fontDirs      = $defaultConfig['fontDir'];

        // Add custom font dir for extra fonts.
        if ( file_exists( wp_upload_dir()['basedir'] . '/woo-invo-ice/woo-invo-ice-fonts' ) ) {
            $fontDirs = array_merge(
                $fontDirs,
                array(
                    wp_upload_dir()['basedir'] . '/woo-invo-ice/woo-invo-ice-fonts',
                )
            );
        }
		$defaultFontConfig = ( new Mpdf\Config\FontVariables() )->getDefaults();
		$fontData          = $defaultFontConfig['fontdata'];

		$mpdf              = new \Mpdf\Mpdf(
			array(
				'tempDir'        => $baseDir,
				'autoLangToFont' => false,
				'fontDir'        => $fontDirs,
				// array_merge( $fontDirs, [
				// plugin_dir_path( __FILE__ ) . 'templates/fonts',
				// ] ),
				'fontdata'       => $fontData + array(
					'Lato'  => array(
						'R'  => 'Lato-Regular.ttf',
						'B'  => 'Lato-Bold.ttf',
						'I'  => 'Lato-Italic.ttf',
						'BI' => 'Lato-BoldItalic.ttf',
					),
					// 'currencies' => [
					// 'R' => "Currencies.ttf",
					// ],
					'grota' => array(
						'R' => 'Grota-Sans-Alt-Regular.ttf',
						'B' => 'Grota-Sans-Bold.ttf',
					),
				// 'raleway'    => [
				// 'R'  => "Raleway-Regular.ttf",
				// 'B'  => "Raleway-Bold.ttf",
				// 'I'  => "Raleway-Italic.ttf",
				// 'BI' => "Raleway-BoldItalic.ttf",
				// ]
				),
				'default_font'   => 'frutiger',
				'mode'           => 'utf-8',
				'format'         => 'A4',
			)
		);

		$mpdf->shrink_tables_to_fit = 0;

		$mpdf->WriteHTML( $html );
		$mpdf->Output( 'order-list.pdf', 'I' );

		exit;
	}

	/**
	 * Generate Order List
	 */
	public function generateCSVOrderList() {
		$get_order_id = $this->orderIds;

		$orders = explode(',', $get_order_id);

		if ( $orders ) {
			$csv_fields   = array();
			$csv_fields[] = 'first_column';
			$csv_fields[] = 'second_column';

            $titles = array();
            $leadArray = array();
            $selected_fields = get_option('wiopt_add_fields_csv');
            if ( ! empty($selected_fields) ) {
                foreach ( $selected_fields as $key => $field ) {

//                    array_push($titles, $this->create_column_heading($field));

                    $titles[ $field ]  = $this->create_column_heading($field);
                }
            }

			$output_filename = 'order-list' . '.csv';
			$output_handle   = @fopen( 'php://output', 'w' );

			header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
			header( 'Content-Description: File Transfer' );
			header( 'Content-type: text/csv' );
			header( 'Content-Disposition: attachment; filename=' . $output_filename );
			header( 'Expires: 0' );
			header( 'Pragma: public' );


            // Add csv rows
            foreach ( $orders as $key => $order_id ) {
                $order     = wc_get_order( $order_id );

                if ( ! empty($selected_fields) ) {
                    foreach ( $selected_fields as $field ) {

                        if ( 'get_date_created' == $field ) {
                            $val = $order->$field()->format('F j, Y');
                        } else {
                            $val = $order->$field();
                        }

                        if ( is_array($val) || '' == $val ) {
                            unset($titles[ $field ]);
                        }

                        if ( ! is_array( $val ) && '' != $val ) {
                            array_push($leadArray, $val);
                        }
                    }
                }
                if ( empty($selected_fields) ) {
                    $csv_default_fields = array(
                        $order->get_order_number(),
                        $order->get_billing_first_name() . ' ' . $order->get_billing_last_name(),
                        $order->get_date_created()->format( 'F j, Y' ),
                        $order->get_status(),
                        $order->get_formatted_order_total(),
                    );
                }

//                print_r($titles);exit;

                $csv_fields_val = empty($leadArray) ? $csv_default_fields : $leadArray;
                $array_purify = array_map( 'strip_tags', $csv_fields_val );
                $leadArray = [];


                if ( ! empty( $titles ) && 0 == $key ) {
                    fputcsv( $output_handle, $titles );
                }elseif ( 0 == $key ) {
                    // Add csv headers.
                    $titles = array(
                        'Order No',
                        'Customer Name',
                        'Date',
                        'Status',
                        'Total',
                    );
                    fputcsv( $output_handle, $titles );
                }

                fputcsv( $output_handle, $array_purify );
            }

			// Close output file stream
			fclose( $output_handle );
			die();
		}
	}

	/**
	 * Extract all order information and store as array
	 */
	public function extractOrdersInfo() {
		$orderIds = explode( ',', $this->orderIds );
		foreach ( $orderIds as $order_key => $order_id ) {

			// Initialize order information into array
			$order = wc_get_order( $order_id );

			if ( ! is_a( $order, 'WC_Order_Refund' ) ) {

				$shipping_method = $order->get_shipping_method();
				$payment_method  = $order->get_payment_method_title();

				$order_data = $order->get_data();
				foreach ( $order_data as $order_key => $order_value ) {
					$this->orderInfo[ $order_id ][ $order_key ] = $order_value;
				}

				// Get Products by Order Id
				foreach ( $order->get_items() as $item_key => $item_values ) :

					$item_id      = $item_values->get_id();
					$product      = $item_values->get_product();
					if ( $product ) {
						$product_data = $product->get_data();
					}
					$item_data    = $item_values->get_data();

					foreach ( $item_data as $key => $value ) {
						$this->products[ $order_id ][ $item_id ][ $key ] = $value;
					}
					if ( $product ) {
						$this->products[ $order_id ][ $item_id ]['pid']          = $product_data['id'];
						$this->products[ $order_id ][ $item_id ]['sku']          = $product_data['sku'];
						$this->products[ $order_id ][ $item_id ]['product_info'] = wc_get_product( $product_data['id'] );
					}

				endforeach;

				$this->orderInfo[ $order_id ]['wiopt_products']       = $this->products[ $order_id ];
				$this->orderInfo[ $order_id ]['shipping_method']      = $shipping_method;
				$this->orderInfo[ $order_id ]['payment_method_title'] = $payment_method;

			}
		}
	}


	/**
	 *
	 * Make Invoice / Packing Slip Content from Template
	 *
	 * @param $type
	 * @param $orders
	 * @param $column
	 * @param $row
	 *
	 * @return bool|mixed|string
	 */

	public function getTemplateContent( $type, $orders, $column = null, $row = null ) {

		$template       = '';
		$content        = '';
		$productPerPage = 6;
		if ( 'invoice' == $type ) {

			// Get Invoice Content
			$selected_invoice_template = get_option( 'wiopt_templateid' );
			if ( $selected_invoice_template && ! empty( $selected_invoice_template ) ) {
				$template = file_get_contents( plugin_dir_path( dirname( __FILE__ ) ) . 'includes/templates/' . $selected_invoice_template . '.php' );
			} else {
				$template = file_get_contents( plugin_dir_path( dirname( __FILE__ ) ) . 'includes/templates/invoice-1.php' );
			}
			$productPerPage = ( get_option( 'wiopt_invoice_product_per_page' ) ) ? get_option( 'wiopt_invoice_product_per_page' ) : 6;
		} elseif ( 'slip' == $type ) {
			// Get Invoice Slip Content
			$template       = file_get_contents( plugin_dir_path( dirname( __FILE__ ) ) . 'includes/templates/invoice_packing_slip.php' );
			$productPerPage = ( get_option( 'wiopt_packingslip_product_per_page' ) ) ? get_option( 'wiopt_packingslip_product_per_page' ) : 6;
		} elseif ( 'shippinglebel' == $type ) {
			// $template=file_get_contents(plugin_dir_path(dirname(__FILE__)) .'includes/templates/shipping_lebel.php');
			$all_shipping = '';
			$column       = ( 100 / (int) $column );
			$content     .= "<div style='page: label;page-break-after: always;'>";
			$r            = 1;
			$c            = 1;

			$orderChunk = array_chunk( $orders, $row );
			$totalChunk = count( $orderChunk );
			$pageBreak  = 'page: label;page-break-after: always;';
			foreach ( $orders as $order_id => $order ) {
				$single_shipping = $this->processBillingAddressShippingLabel( $order, $column, $row );
				$content        .= $single_shipping;
				$metas           = $this->getShippingLabelMetas( $order );
				$content        .= $metas;

				// Calculate Page Break
				if ( $r == $row ) {
					$content .= '</div>';
					if ( $c == $totalChunk - 1 ) {
						$pageBreak = '';
					}
					$content .= "<div style='$pageBreak'>";
					$r        = 1;
					$c ++;
				} else {
					$r ++;
				}
			}

			return $content;
		} elseif ( 'orderlist' == $type ) {

		    $order_list_filter = apply_filters('woo_invo_ice_shop_order__orderlist', array(
		        'get_order_number',
                'get_formatted_billing_full_name',
                'get_date_created',
                'get_status',
                'get_formatted_order_total',
            ),  $orders);

			$order_list  = '<table cellpadding="0" cellspacing="0"><tr style="width:100%;">';
            if ( count($order_list_filter) ) {
                foreach ( $order_list_filter as $function ) {
                    $order_list .= '<td style="width:15%;text-align:left;padding:10px 20px;border-bottom:1px solid #ccc"><b>'.$this->create_column_heading($function).'</b></td>';
                }
            }
			$order_list .= '</tr>';
			foreach ( $orders as $order_id => $order ) {
				$order_list .= '<tr style="width:100%">';
				$order       = wc_get_order( $order_id );
                if ( count($order_list_filter) ) {
                    foreach ( $order_list_filter as $function ) {
                        if ( 'get_date_created' == $function ) {
                             $order_list .= '<td style="width:15%;text-align:left;padding:10px 20px;border-bottom:1px solid #ccc">' . $order->$function()->format( 'F j, Y' ) . '</td>';
                        }elseif ( 'get_order_number' == $function ) {
                            $order_list .= '<td style="width:15%;text-align:left;padding:10px 20px;border-bottom:1px solid #ccc">#' . $order->$function() . '</td>';
                        }elseif ( 'get_formatted_order_total' == $function ) {
                            $helper = woo_invo_ice_helper();
                            $order_list .= '<td style="width:15%;text-align:left;padding:10px 20px;border-bottom:1px solid #ccc">#' . $helper->format_price( $order, $order->get_total() ) . '</td>';
                        }else {
                            $order_list .= '<td style="width:15%;text-align:left;padding:10px 20px;border-bottom:1px solid #ccc">' . $order->$function() . '</td>';
                        }
                    }
                }
                $order_list .= '</tr>';
			}
			$order_list .= '</table>';
			$content    .= $order_list;
			return $content;
		}

		$pageBreak = 'pageBreak';

		// Get Template Header
		$content = $this->get_string_between(
			$template,
			'<=head-start=>',
			'<=head-end=>',
			array(
				'{{PACKING_SLIP_TEXT}}',
				'{{INVOICE}}',
			),
			array(
				( ! empty( $PACKING_SLIP_TEXT ) ? $PACKING_SLIP_TEXT : 'PACKING SLIP' ),
				( ! empty( get_option( 'wiopt_invoice_title' ) ) ? get_option( 'wiopt_invoice_title' ) : 'INVOICE' ),
			)
		);

		foreach ( $orders as $order_id => $order ) {
			$productChunk = array_chunk( $order['wiopt_products'], $productPerPage );
			$totalChunk   = count( $productChunk );
			foreach ( $productChunk as $cKey => $chunk ) {
				// Calculate Page Break
				if ( 1 == $totalChunk ) {
					$pageBreak = '';
				} elseif ( ( $totalChunk - 1 ) != $cKey ) {
					$pageBreak = '';
				}

				// Get Template Body
				$body = $this->get_string_between(
					$template,
					'<=body-top-start=>',
					'<=body-top-end=>',
					array(
						'{{pagebreak}}',
						'$order_id',
					),
					array( $pageBreak, $order_id )
				);

				// Get Products
				foreach ( $chunk as $pKey => $value ) {
					   $id    = ( $value['variation_id'] > 0 ) ? $value['variation_id'] . '_' . $value['id'] : $value['product_id'] . '_' . $value['id'];
					   $body .= $this->get_string_between( $template, '<=product-loop-start=>', '<=product-loop-end=>', '$id', $id );
					if ( ! get_option( 'wiopt_show_tax' ) || is_null( get_option( 'wiopt_show_tax' ) ) || 0.00 == $order['total_tax'] ) {
						$body = $this->remove_string_between( $body, '<=body-remove-tax-column-start=>', '<=body-remove-tax-column-end=>' );
					}

					if ( ! get_option( 'wiopt_tax_percentage' ) || is_null( get_option( 'wiopt_tax_percentage' ) ) || 0.00 == $order['total_tax'] ) {
						$body = $this->remove_string_between( $body, '<=body-remove-tax-precentage-column-start=>', '<=body-remove-tax-precentage-column-end=>' );
					}

					if ( ! get_option( 'wiopt_product_image_show' ) || is_null( get_option( 'wiopt_product_image_show' ) ) ) {
									   $body = $this->remove_string_between( $body, '<=body-remove-product-image-column-start=>', '<=body-remove-product-image-column-end=>' );
					}

					if ( get_option( 'wiopt_packingslip_product_image_show' ) != '1' ) {
						$body = $this->remove_string_between( $body, '<=body-remove-packingslip-image-column-start=>', '<=body-remove-packingslip-image-column-end=>' );
					}

					if ( get_option( 'wiopt_packingslip_product_width_show' ) != '1' ) {
						$body = $this->remove_string_between( $body, '<=body-remove-product-width-column-start=>', '<=body-remove-product-width-column-end=>' );
					}

					if ( get_option( 'wiopt_packingslip_product_height_show' ) != '1' ) {
							  $body = $this->remove_string_between( $body, '<=body-remove-product-height-column-start=>', '<=body-remove-product-height-column-end=>' );
					}

					if ( get_option( 'wiopt_packingslip_product_weight_show' ) != '1' ) {
									  $body = $this->remove_string_between( $body, '<=body-remove-product-weight-column-start=>', '<=body-remove-product-weight-column-end=>' );
					}
				}

				// Get Template Body Bottom
				$body .= $this->get_string_between( $template, '<=body-bottom-start=>', '<=body-bottom-end=>', '$order_id', $order_id );

				if ( ( $totalChunk - 1 ) != $cKey ) {
					// Remove Total Info
					$body = $this->remove_string_between( $body, '<=body-remove-sub-total-start=>', '<=body-remove-sub-total-end=>' );
					$body = $this->remove_string_between( $body, '<=body-remove-tax-start=>', '<=body-remove-tax-end=>' );
					$body = $this->remove_string_between( $body, '<=body-remove-discount-start=>', '<=body-remove-discount-end=>' );
					$body = $this->remove_string_between( $body, '<=body-remove-total-without-discount-start=>', '<=body-remove-total-without-discount-end=>' );
					$body = $this->remove_string_between( $body, '<=body-remove-shipping-start=>', '<=body-remove-shipping-end=>' );
					$body = $this->remove_string_between( $body, '<=body-remove-total-without-tax-start=>', '<=body-remove-total-without-tax-end=>' );
					$body = $this->remove_string_between( $body, '<=body-remove-refund-start=>', '<=body-remove-refund-end=>' );
					$body = $this->remove_string_between( $body, '<=body-remove-total-start=>', '<=body-remove-total-end=>' );
					$body = $this->remove_string_between( $body, '<=body-remove-footer-1-start=>', '<=body-remove-footer-1-end=>' );
					$body = $this->remove_string_between( $body, '<=body-remove-footer-2-start=>', '<=body-remove-footer-2-end=>' );
					/*$body = $this->remove_string_between($body, "<=body-remove-tax-column-start=>", "<=body-remove-tax-column-end=>");*/

				} else {
					if ( ! $order['total_tax'] ) {
						$body = $this->remove_string_between( $body, '<=body-remove-tax-start=>', '<=body-remove-tax-end=>' );
					}
					if ( ! $order['discount_total'] || get_option( 'wiopt_display_discounted_amount' ) != '1' ) {
						$body = $this->remove_string_between( $body, '<=body-remove-discount-start=>', '<=body-remove-discount-end=>' );
					}
					if ( ! $order['discount_total'] || get_option( 'wiopt_display_total_without_discount' ) != '1' ) {
						$body = $this->remove_string_between( $body, '<=body-remove-total-without-discount-start=>', '<=body-remove-total-without-discount-end=>' );
					}
					if ( ! $order['shipping_total'] || 0.00 == $order['shipping_total'] ) {
						$body = $this->remove_string_between( $body, '<=body-remove-shipping-start=>', '<=body-remove-shipping-end=>' );
					}
					if ( empty( (array) wc_get_order( $order_id )->get_refunds() ) ) {
						$body = $this->remove_string_between( $body, '<=body-remove-refund-start=>', '<=body-remove-refund-end=>' );
					}
				}

				if ( empty( (array) wc_get_order( $order_id )->get_refunds() ) ) {
					$body = $this->remove_string_between( $body, '<=body-remove-refund-start=>', '<=body-remove-refund-end=>' );
				}

				if ( ! get_option( 'wiopt_payment_method_show' ) ) {
					$body = $this->remove_string_between( $body, '<=body-top-invoice_payment_start=>', '<=body-top-invoice_payment_end=>' );
				}

				// Header Invoice Payment Method Show/Hide
				if ( ! get_option( 'wiopt_payment_method_show' ) ) {
					$body = $this->remove_string_between( $body, '<!--body-top-invoice_payment_start-->', '<!--body-top-invoice_payment_end-->' );
				}

				// Header Invoice Date Show/Hide
				if ( ! $this->processCustomDateFormat( $order['date_paid'] ) ) {
					$body = $this->remove_string_between( $body, '<!--body-top-invoice_date_check_start-->', '<!--body-top-invoice_date_check_end-->' );
				}

				if ( ! get_option( 'wiopt_show_tax' ) || is_null( get_option( 'wiopt_show_tax' ) ) || 0.00 == $order['total_tax'] ) {
					$body = $this->remove_string_between( $body, '<=body-remove-tax-column-start=>', '<=body-remove-tax-column-end=>' );
				}

				if ( ! get_option( 'wiopt_tax_percentage' ) || is_null( get_option( 'wiopt_tax_percentage' ) ) || 0.00 == $order['total_tax'] ) {
					$body = $this->remove_string_between( $body, '<=body-remove-tax-precentage-column-start=>', '<=body-remove-tax-precentage-column-end=>' );
				}

				if ( ! get_option( 'wiopt_product_image_show' ) || is_null( get_option( 'wiopt_product_image_show' ) ) ) {
					$body = $this->remove_string_between( $body, '<=body-remove-product-image-column-start=>', '<=body-remove-product-image-column-end=>' );
				}

				if ( get_option( 'wiopt_display_total_without_tax' ) != '1' ) {
					$body = $this->remove_string_between( $body, '<=body-remove-total-without-tax-start=>', '<=body-remove-total-without-tax-end=>' );
				}

				if ( get_option( 'wiopt_packingslip_product_image_show' ) != '1' ) {
					$body = $this->remove_string_between( $body, '<=body-remove-packingslip-image-column-start=>', '<=body-remove-packingslip-image-column-end=>' );
				}

				if ( get_option( 'wiopt_packingslip_product_width_show' ) != '1' ) {
					$body = $this->remove_string_between( $body, '<=body-remove-product-width-column-start=>', '<=body-remove-product-width-column-end=>' );
				}

				if ( get_option( 'wiopt_packingslip_product_height_show' ) != '1' ) {
					$body = $this->remove_string_between( $body, '<=body-remove-product-height-column-start=>', '<=body-remove-product-height-column-end=>' );
				}

				if ( get_option( 'wiopt_packingslip_product_weight_show' ) != '1' ) {
					$body = $this->remove_string_between( $body, '<=body-remove-product-weight-column-start=>', '<=body-remove-product-weight-column-end=>' );
				}

				if ( $this->processCustomDateFormat( $order ) == '' ) {
					$body = $this->remove_string_between( $body, '<=body-remove-invoice-date-start=>', '<=body-remove-invoice-date-end=>' );
				}

				// Replace string codes
//				$content .= $this->replaceOrderString( $order, $body );
			}

			$content .= $this->get_string_between( $template, '<=footer-bottom-start=>', '<=footer-bottom-end=>' );

		}

		// Get Template Footer
		$content .= $this->get_string_between( $template, '<=footer-start=>', '<=footer-end=>' );

		return $content;
	}

    /**
     * @param $function_name
     * @return string
     */

	public function create_column_heading( $function_name ) {

	    if ( strpos($function_name, '_') != false ) {
            $string_arr = explode('_', $function_name);
            $string_remove = [ 'get', 'formatted', 'created' ];
            foreach ( $string_remove as $string ) {
                $index = array_search($string, $string_arr);
                unset($string_arr[ $index ]);
            }
            $string_arr = array_values($string_arr);
            $heading = '';
            foreach ( $string_arr as $index => $value ) {
                if ( ! preg_match_all("/[0-9]/i", $value, $matches) ) {
                    if ( 0 == $index ) {
                        $heading .= ucwords($value);
                    } else {
                        $heading .= " ". ucwords($value);
                    }
                }
            }

            return $heading;
        }else {

            return ucwords($function_name);
        }
    }

	/**
	 * Get Template Strings between Template Codes
	 *
	 * @param $string
	 * @param string $start       Template code to start
	 * @param string $end         Template Code to end
	 * @param string $toReplace   Order or Product id to replace
	 * @param string $replaceWith Order or Product id replace with
	 *
	 * @return bool|mixed|string
	 */

	public function get_string_between( $string, $start, $end, $toReplace = '', $replaceWith = '' ) {
		$string = ' ' . $string;
		$ini    = strpos( $string, $start );
		if ( 0 == $ini ) {
			return '';
		}
		$ini        += strlen( $start );
		$len         = strpos( $string, $end, $ini ) - $ini;
		$finalString = substr( $string, $ini, $len );
		$finalString = str_replace( $toReplace, $replaceWith, $finalString );

		return $finalString;
	}

	public function remove_string_between( $string, $start, $end ) {
		$beginningPos = strpos( $string, $start );
		$endPos       = strpos( $string, $end );
		if ( false === $beginningPos || false === $endPos ) {
			return $string;
		}

		$textToDelete = substr( $string, $beginningPos, ( $endPos + strlen( $end ) ) - $beginningPos );
		$string       = str_replace( $textToDelete, '', $string );

		return $string;
	}

	/**
	 * Find and Replace all dynamic string for Invoice Template
	 *
	 * @param $order
	 * @param $string
	 *
	 * @return mixed
	 */
	public function replaceOrderString( $order, $string ) {

		// echo "<pre>";
		// print_r($order);
		// die();
		// Invoice localization variables
		$id                                = $order['id'];
		$FONT_SIZE                         = get_option( 'wiopt_invoice_font_size' );
		$FOOTER_FONT_SIZE                  = get_option( 'wiopt_invoice_footer_font_size' );
		$INVOICE_NUMBER_TEXT               = get_option( 'wiopt_INVOICE_NUMBER_TEXT' );
		$INVOICE_DATE_TEXT                 = get_option( 'wiopt_INVOICE_DATE_TEXT' );
		$invoice_title                     = get_option( 'wiopt_invoice_title' );
		$ORDER_NUMBER_TEXT                 = get_option( 'wiopt_ORDER_NUMBER_TEXT' );
		$ORDER_DATE_TEXT                   = get_option( 'wiopt_ORDER_DATE_TEXT' );
		$SL                                = get_option( 'wiopt_SL' );
		$PRODUCT                           = get_option( 'wiopt_PRODUCT' );
		$PRICE                             = get_option( 'wiopt_PRICE' );
		$QUANTITY                          = get_option( 'wiopt_QUANTITY' );
		$ROW_TOTAL                         = get_option( 'wiopt_ROW_TOTAL' );
		$SUBTOTAL_TEXT                     = get_option( 'wiopt_SUBTOTAL_TEXT' );
		$TAX_TEXT                          = get_option( 'wiopt_TAX_TEXT' );
		$TAX_PERCENTAGE_TEXT               = get_option( 'wiopt_TAX_PERCENTAGE_TEXT' );
		$DISCOUNT_TEXT                     = get_option( 'wiopt_DISCOUNT_TEXT' );
		$SHIPPING_TEXT                     = get_option( 'wiopt_SHIPPING_TEXT' );
		$GRAND_TOTAL_WITHOUT_TAX_TEXT      = get_option( 'wiopt_GRAND_TOTAL_WITHOUT_TAX_TEXT' );
		$GRAND_TOTAL_WITHOUT_DISCOUNT_TEXT = get_option( 'wiopt_GRAND_TOTAL_WITHOUT_DISCOUNT_TEXT' );
		$GRAND_TOTAL_TEXT                  = get_option( 'wiopt_GRAND_TOTAL_TEXT' );
		$LOGO_HEIGHT                       = get_option( 'wiopt_logo_height' );
		$LOGO_WIDTH                        = get_option( 'wiopt_logo_width' );
		$TERMS_AND_CONDITION               = stripslashes( get_option( 'wiopt_terms_and_condition' ) );
		$OTHER_INFORMATION                 = stripslashes( get_option( 'wiopt_other_information' ) );

		// Packing Slip localization variables
		$PACKING_SLIP_TEXT              = get_option( 'wiopt_PACKING_SLIP_TEXT' );
		$PACKING_SLIP_ORDER_NUMBER_TEXT = get_option( 'wiopt_PACKING_SLIP_ORDER_NUMBER_TEXT' );
		$PACKING_SLIP_ORDER_DATE_TEXT   = get_option( 'wiopt_PACKING_SLIP_ORDER_DATE_TEXT' );
		$PACKING_SLIP_ORDER_METHOD_TEXT = get_option( 'wiopt_PACKING_SLIP_ORDER_METHOD_TEXT' );
		$PACKING_SLIP_PRODUCT_TEXT      = get_option( 'wiopt_PACKING_SLIP_PRODUCT_TEXT' );
		$PACKING_SLIP_WIDTH_TEXT        = get_option( 'wiopt_PACKING_SLIP_WIDTH_TEXT' );
		$PACKING_SLIP_HEIGHT_TEXT       = get_option( 'wiopt_PACKING_SLIP_HEIGHT_TEXT' );
		$PACKING_SLIP_WEIGHT_TEXT       = get_option( 'wiopt_PACKING_SLIP_WEIGHT_TEXT' );
		$PACKING_SLIP_QUANTITY_TEXT     = get_option( 'wiopt_PACKING_SLIP_QUANTITY_TEXT' );
		$PAYMENT_METHOD_TEXT            = get_option( 'wiopt_payment_method_text' );

		// TODO Check if refundable product exist or not

		// Get refunded product details
		$refund_total      = 0;
		$line_subtotal_tax = 0;
		$refunded_product  = array();
		$refund_order      = wc_get_order( $order['id'] );
		$order_refunds     = $refund_order->get_refunds();

		foreach ( $order_refunds as $refund ) {
			foreach ( $refund->get_items() as $item_id => $item ) {
				$refunded_quantity                                   = $item->get_quantity();
				$refund_total                                        = $refund_total + $item->get_subtotal();
				$line_subtotal_tax                                   = $line_subtotal_tax + $item->get_subtotal_tax();
				$refunded_product[ $item->get_data()['product_id'] ] = array(
					'quantity'     => $refunded_quantity,
					'refund_total' => $refund_total,
					'refund_tax'   => $line_subtotal_tax,
				);
			}
		}

		// Product Information To Replace
		$pInfoReplace  = array();
		$prodToReplace = array();
		// Product Information Replace With
		$prodReplaceWith = array();
		$subtotal        = 0;
		$sl              = 1;
		foreach ( $order['wiopt_products'] as $p_key => $p_value ) {

			$pid           = ( $p_value['variation_id'] > 0 ) ? $p_value['variation_id'] : $p_value['product_id'];
			$product       = $p_value['product_info'];
			$subtotal      = $subtotal + $p_value['subtotal'];
			$regular_price = $product->get_regular_price();
			$sale_price    = $product->get_sale_price();
			$orderPrice    = $p_value['subtotal'] / $p_value['quantity'];
			$price         = $this->formatPrice( $orderPrice, $order['id'] );
			$subtotal_tax  = $this->formatPrice( $p_value['subtotal_tax'], $order['id'] );
			$quantity      = $p_value['quantity'];

			// Update Refunded Information
			if ( array_key_exists( $pid, $refunded_product ) ) {
				// Update Refunded Price
				$price = $this->formatPrice( $orderPrice, $order['id'] );
				$price = $price . '<br/><br/><span style="color: #f7edee;font-size:9px;"><span style="font-family: fontawesome;">&#xf112;</span> ' . $this->formatPrice( $refunded_product[ $pid ]['refund_total'], $order['id'] ) . '</span>';
				// Update Refunded Subtotal Tax
				$subtotal_tax = $this->formatPrice( $p_value['subtotal_tax'], $order['id'] );
				$subtotal_tax = $subtotal_tax . '<br/><br/><span style="color: #f70202;font-size:9px;"><span style="font-family: fontawesome;">&#xf112;</span> ' . $this->formatPrice( $refunded_product[ $pid ]['refund_tax'], $order['id'] ) . '</span>';
				// Update Refunded quantity
				$quantity = $quantity . '<br/><br/><span style="color: #f70202;font-size:9px;"><span style="font-family: fontawesome;">&#xf112;</span> ' . $refunded_product[ $pid ]['quantity'] . '</span>';
			}

			$product_subtotal = $this->formatPrice( $p_value['subtotal'], $order['id'] );
			if ( get_option( 'wiopt_inc_tax_total' ) == '1' ) {
				$product_subtotal = $this->formatPrice( $p_value['subtotal'] + $p_value['subtotal_tax'], $order['id'] );
			}

			// Calculate Tax Rate
			$_tax           = new WC_Tax();
			$tax_rate_class = $product->get_tax_class();
			$tax_rate       = $_tax->get_rates( $tax_rate_class );
			$tax_rate       = reset( $tax_rate );

			// Product Information to replace
			$pInfoReplace[ '{{P_SL_' . $pid . '_' . $p_value['id'] . '}}' ]                      = $sl;
			$pInfoReplace[ '{{P_DESCRIPTION_' . $pid . '_' . $p_value['id'] . '}}' ]             = $this->processProductInfo( $p_value['name'], $p_value['sku'], $pid, $order['id'] );
			$pInfoReplace[ '{{PACKINGSLIP_P_DESCRIPTION_' . $pid . '_' . $p_value['id'] . '}}' ] = $this->packingslipProductInfo( $p_value['name'], $p_value['sku'], $pid );
			$pInfoReplace[ '{{P_REGULAR_PRICE_' . $pid . '_' . $p_value['id'] . '}}' ]           = $this->getFormattedRegularPrice( $order['id'], $regular_price, $sale_price, $orderPrice );
			$pInfoReplace[ '{{P_PRICE_' . $pid . '_' . $p_value['id'] . '}}' ]                   = $price;
			$pInfoReplace[ '{{P_TAX_' . $pid . '_' . $p_value['id'] . '}}' ]                     = $subtotal_tax;
			$pInfoReplace[ '{{P_QUANTITY_' . $pid . '_' . $p_value['id'] . '}}' ]                = $quantity;
			$pInfoReplace[ '{{P_TOTAL_' . $pid . '_' . $p_value['id'] . '}}' ]                   = $product_subtotal;
			$pInfoReplace[ '{{WIDTH_' . $pid . '_' . $p_value['id'] . '}}' ]                     = $this->getProductWidth( $product );
			$pInfoReplace[ '{{HEIGHT_' . $pid . '_' . $p_value['id'] . '}}' ]                    = $this->getProductHeight( $product );
			$pInfoReplace[ '{{WEIGHT_' . $pid . '_' . $p_value['id'] . '}}' ]                    = $this->getProductWeight( $product );
			$pInfoReplace[ '{{LENGTH_' . $pid . '_' . $p_value['id'] . '}}' ]                    = $this->getProductLength( $product );
			$pInfoReplace[ '{{P_TAX_RATE_' . $pid . '_' . $p_value['id'] . '}}' ]                = ( ! empty( $tax_rate['rate'] ) ? $tax_rate['rate'] : '0' );
			$pInfoReplace[ '{{P_IMAGE_' . $pid . '_' . $p_value['id'] . '}}' ]                   = $this->getProductImage( $pid );

			$sl ++;
		}
		
		// If tax and discount are not available ,the subtotal will be the grandTotal
		$grandTotal = $subtotal;

		// Get Shipping Tax
		$shippingTax = ( isset( $order['shipping_tax'] ) && ! empty( $order['shipping_tax'] ) ) ? $order['shipping_tax'] : 0;

		// Set values to Invoice template patterns
		$textToReplace = array(
			'{{DISPLAY_NONE}}'                            => 'display:none;',
			'{{FONT_SIZE}}'                               => ( ! empty( $FONT_SIZE ) ) ? $FONT_SIZE . 'px' : '11px',
			'{{FOOTER_FONT_SIZE}}'                        => ( ! empty( $FOOTER_FONT_SIZE ) ) ? $FOOTER_FONT_SIZE . 'px' : '9px',
			'{{LOGO}}'                                    => $this->getInvoiceLogo(),
			'{{SIGNATURE}}'                               => $this->getStoreSignature(),
			'{{CIRCLE_INVOICE_IMAGE}}'                    => $this->circleImage(),
			'{{PAID_STAMP}}'                              => $this->getPaidStamp( $order['status'] ),
			"{{TO_$id}}"                                  => $this->getBillingAddress( $order['id'] ),
			'{{FROM}}'                                    => $this->getSellerInfo(),
			'{{INVOICE_NUMBER_TEXT}}'                     => ( ! empty( $INVOICE_NUMBER_TEXT ) ? $INVOICE_NUMBER_TEXT : 'INVOICE NUMBER' ),
			'{{INVOICE_DATE_TEXT}}'                       => ( ! empty( $INVOICE_DATE_TEXT ) ? $INVOICE_DATE_TEXT : 'INVOICE DATE' ),
			'{{ORDER_NUMBER_TEXT}}'                       => ( ! empty( $ORDER_NUMBER_TEXT ) ? $ORDER_NUMBER_TEXT : 'ORDER NUMBER' ),
			'{{ORDER_DATE_TEXT}}'                         => ( ! empty( $ORDER_DATE_TEXT ) ? $ORDER_DATE_TEXT : 'ORDER DATE' ),
			'{{SL}}'                                      => ( ! empty( $SL ) ? $SL : 'SL' ),
			'{{PRODUCT}}'                                 => ( ! empty( $PRODUCT ) ? $PRODUCT : 'PRODUCTS' ),
			'{{PRICE}}'                                   => ( ! empty( $PRICE ) ? $PRICE : 'PRICE' ),
			'{{QUANTITY}}'                                => ( ! empty( $QUANTITY ) ? $QUANTITY : 'QUANTITY' ),
			'{{ROW_TOTAL}}'                               => ( ! empty( $ROW_TOTAL ) ? $ROW_TOTAL : 'TOTAL' ),
			'{{SUBTOTAL_TEXT}}'                           => ( ! empty( $SUBTOTAL_TEXT ) ? $SUBTOTAL_TEXT : 'SUB TOTAL' ),
			'{{REFUND_TEXT}}'                             => ( ! empty( $REFUNDED_TEXT ) ? $REFUNDED_TEXT : 'REFUNDED' ),
			'{{TAX_TEXT}}'                                => ( ! empty( $TAX_TEXT ) ? $TAX_TEXT : 'TAX' ),
			'{{TAX_PERCENTAGE_TEXT}}'                     => ( ! empty( $TAX_PERCENTAGE_TEXT ) ? $TAX_PERCENTAGE_TEXT : 'TAX(%)' ),
			'{{DISCOUNT_TEXT}}'                           => ( ! empty( $DISCOUNT_TEXT ) ? $DISCOUNT_TEXT : 'DISCOUNT' ),
			'{{SHIPPING_TEXT}}'                           => $this->getShippingText( $SHIPPING_TEXT, $shippingTax ),
			'{{GRAND_TOTAL_WITHOUT_TAX_TEXT}}'            => ( ! empty( $GRAND_TOTAL_WITHOUT_TAX_TEXT ) ? $GRAND_TOTAL_WITHOUT_TAX_TEXT : 'TOTAL WITHOUT TAX' ),
			'{{GRAND_TOTAL_WITHOUT_DISCOUNT_TEXT}}'       => ( ! empty( $GRAND_TOTAL_WITHOUT_DISCOUNT_TEXT ) ? $GRAND_TOTAL_WITHOUT_DISCOUNT_TEXT : 'TOTAL WITHOUT DISCOUNT' ),
			'{{GRAND_TOTAL_TEXT}}'                        => ( ! empty( $GRAND_TOTAL_TEXT ) ? $GRAND_TOTAL_TEXT : 'TOTAL' ),
			"{{ORDER_NUMBER_$id}}"                        => $this->processOrderNumber( $order['id'] ),
			"{{ORDER_DATE_$id}}"                          => $this->processCustomDateFormat( $order['date_created'] ),
			"{{INVOICE_NUMBER_$id}}"                      => $this->processInvoiceNumber( $order['id'] ),
			"{{INVOICE_DATE_$id}}"                        => $this->processCustomDateFormat( $order['date_paid'] ),
			"{{SUBTOTAL_$id}}"                            => $this->formatPrice( $subtotal, $order['id'] ),
			"{{REFUND_$id}}"                              => $this->formatPrice( $refund_total, $order['id'] ),
			"{{TAX_$id}}"                                 => ( ! empty( $order['total_tax'] ) ) ? $this->formatPrice( ( $order['total_tax'] + $line_subtotal_tax ), $order['id'] ) : '',
			"{{DISCOUNT_$id}}"                            => ( ! empty( $order['discount_total'] ) ) ? $this->formatPrice( $order['discount_total'], $order['id'] ) : '',
			"{{SHIPPING_$id}}"                            => ( ! empty( $order['shipping_total'] ) ) ? $this->formatPrice( $order['shipping_total'] + $shippingTax, $order['id'] ) : '',
			"{{GRAND_TOTAL_WITHOUT_TAX_$id}}"             => $this->formatPrice( $order['total'] - $order['total_tax'], $order['id'] ),
			"{{GRAND_TOTAL_WITHOUT_DISCOUNT_$id}}"        => $this->formatPrice( ( ( $order['total'] + $refund_total + $line_subtotal_tax ) + $order['discount_total'] ), $order['id'] ),
			"{{GRAND_TOTAL_$id}}"                         => $this->formatPrice( ( $order['total'] + $refund_total + $line_subtotal_tax ), $order['id'] ),
			'{{CURRENCY}}'                                => $this->getCurrencyCode( $order['id'] ),
			"{{ORDER_METHOD_$id}}"                        => $order['shipping_method'],
			'{{WEIGHT}}'                                  => 'WEIGHT',
			"{{WEIGHT_$id}}"                              => "WEIGHT_$id",
			'{{INVOICE}}'                                 => ( ! empty( $invoice_title ) ? $invoice_title : 'INVOICE' ),
			'{{PACKING_SLIP_TEXT}}'                       => ( ! empty( $PACKING_SLIP_TEXT ) ? $PACKING_SLIP_TEXT : 'PACKING SLIP' ),
			'{{PACKING_SLIP_ORDER_NUMBER_TEXT}}'          => ( ! empty( $PACKING_SLIP_ORDER_NUMBER_TEXT ) ? $PACKING_SLIP_ORDER_NUMBER_TEXT : 'ORDER NUMBER' ),
			'{{PACKING_SLIP_ORDER_DATE_TEXT}}'            => ( ! empty( $PACKING_SLIP_ORDER_DATE_TEXT ) ? $PACKING_SLIP_ORDER_DATE_TEXT : 'ORDER DATE' ),
			'{{PACKING_SLIP_ORDER_METHOD_TEXT}}'          => ( ! empty( $PACKING_SLIP_ORDER_METHOD_TEXT ) ? $PACKING_SLIP_ORDER_METHOD_TEXT : 'SHIPPING METHOD' ),
			'{{PACKING_SLIP_PRODUCT_TEXT}}'               => ( ! empty( $PACKING_SLIP_PRODUCT_TEXT ) ? $PACKING_SLIP_PRODUCT_TEXT : 'PRODUCT' ),
			'{{PACKING_SLIP_WIDTH_TEXT}}'                 => ( ! empty( $PACKING_SLIP_WIDTH_TEXT ) ? $PACKING_SLIP_WIDTH_TEXT : 'WIDTH' ),
			'{{PACKING_SLIP_HEIGHT_TEXT}}'                => ( ! empty( $PACKING_SLIP_HEIGHT_TEXT ) ? $PACKING_SLIP_HEIGHT_TEXT : 'HEIGHT' ),
			'{{PACKING_SLIP_WEIGHT_TEXT}}'                => ( ! empty( $PACKING_SLIP_WEIGHT_TEXT ) ? $PACKING_SLIP_WEIGHT_TEXT : 'WEIGHT' ),
			'{{PACKING_SLIP_QUANTITY_TEXT}}'              => ( ! empty( $PACKING_SLIP_QUANTITY_TEXT ) ? $PACKING_SLIP_QUANTITY_TEXT : 'QUANTITY' ),
			'{{LOGO_HEIGHT}}'                             => ( ! empty( $LOGO_HEIGHT ) ) ? $LOGO_HEIGHT . '%' : '',
			'{{LOGO_WIDTH}}'                              => ( ! empty( $LOGO_WIDTH ) ) ? $LOGO_WIDTH . '%' : '',
			'{{TERMS_AND_CONDITION}}'                     => ( ! empty( $TERMS_AND_CONDITION ) ) ? $TERMS_AND_CONDITION : '',
			'{{OTHER_INFORMATION}}'                       => ( ! empty( $OTHER_INFORMATION ) ) ? $OTHER_INFORMATION : '',
			'{{INVOICE_PAYMENT_METHOD_TITLE_TEXT}}'       => ( ! empty( $PAYMENT_METHOD_TEXT ) ) ? $PAYMENT_METHOD_TEXT : 'PAYMENT METHOD',
			"{{INVOICE_PAYMENT_METHOD_TITLE_TEXT_$id}}"   => ( ! empty( $order['payment_method_title'] ) ) ? $order['payment_method_title'] : '',
			'{{TABLE_COLUMN_COLSPAN}}'                    => $this->tableColumnColspan( $order ),
			'{{TABLE_COLUMN_COLSPAN2}}'                   => $this->tableColumnColspan2( $order ),
			'{{TABLE_COLUMN_COLSPAN3}}'                   => $this->tableColumnColspan3( $order ),
			'{{TABLE_COLUMN_COLSPAN4}}'                   => $this->tableColumnColspan4( $order ),
			'{{TABLE_COLUMN_COLSPAN5}}'                   => $this->tableColumnColspan5( $order ),
			'{{INVOICE_MARGIN}}'                          => $this->invoiceMargin( $order ),
			'{{ORDER_NOTE}}'                              => $this->get_order_note( $order['id'] ),
			'{{ORDER_POST_META}}'                         => $this->get_order_post_meta( $order['id'] ),
			'{{SOCIAL_MEDIA_URL}}'                        => $this->SocialMediaIcons(),

			'{{WOO_INVO_ICE_CUSTOM_STYLE}}'                => $this->woo_invo_ice_custom_style(),

			'{{WOO_PACKING_SLIP_CUSTOM_STYLE}}'           => $this->woo_packing_slip_custom_style( $id ),

			// Woo Hook Insert
			'{{WOO_INVO_ICE_BEFORE_DOCUMENT}}'             => $this->woo_invo_ice_before_document( $id ),
			'{{WOO_INVO_ICE_AFTER_DOCUMENT}}'              => $this->woo_invo_ice_after_document( $id ),
			'{{WOO_INVO_ICE_AFTER_DOCUMENT_LABEL}}'        => $this->woo_invo_ice_after_document_label( $id ),
			'{{WOO_INVO_ICE_BEFORE_BILLING_ADDRESS}}'      => $this->woo_invo_ice_before_billing_address( $id ),
			'{{WOO_INVO_ICE_AFTER_BILLING_ADDRESS}}'       => $this->woo_invo_ice_after_billing_address( $id ),
			'{{WOO_INVO_ICE_BEFORE_ORDER_DATA}}'           => $this->woo_invo_ice_before_order_data( $id ),
			'{{WOO_INVO_ICE_AFTER_ORDER_DATA}}'            => $this->woo_invo_ice_after_order_data( $id ),
			'{{WOO_INVO_ICE_BEFORE_ORDER_DETAILS}}'        => $this->woo_invo_ice_before_product_list( $id ),
			'{{WOO_INVO_ICE_AFTER_ORDER_DETAILS}}'         => $this->woo_invo_ice_after_product_list( $id ),
			'{{WOO_INVO_ICE_BEFORE_CUSTOMER_NOTES}}'       => $this->woo_invo_ice_before_customer_notes( $id ),
			'{{WOO_INVO_ICE_AFTER_CUSTOMER_NOTES}}'        => $this->woo_invo_ice_after_customer_notes( $id ),

			'{{WOO_PACKING_SLIP_BEFORE_DOCUMENT}}'        => $this->woo_packing_slip_before_document( $id ),
			'{{WOO_PACKING_SLIP_AFTER_DOCUMENT}}'         => $this->woo_packing_slip_after_document( $id ),
			'{{WOO_PACKING_SLIP_AFTER_DOCUMENT_LABEL}}'   => $this->woo_packing_slip_after_document_label( $id ),
			'{{WOO_PACKING_SLIP_BEFORE_BILLING_ADDRESS}}' => $this->woo_packing_slip_before_billing_address( $id ),
			'{{WOO_PACKING_SLIP_AFTER_BILLING_ADDRESS}}'  => $this->woo_packing_slip_after_billing_address( $id ),
			'{{WOO_PACKING_SLIP_BEFORE_ORDER_DATA}}'      => $this->woo_packing_slip_before_order_data( $id ),
			'{{WOO_PACKING_SLIP_AFTER_ORDER_DATA}}'       => $this->woo_packing_slip_after_order_data( $id ),
			'{{WOO_PACKING_SLIP_BEFORE_ORDER_DETAILS}}'   => $this->woo_packing_slip_before_order_details( $id ),
			'{{WOO_PACKING_SLIP_AFTER_ORDER_DETAILS}}'    => $this->woo_packing_slip_after_order_details( $id ),

		);

		// Assign shipping method for multiple bulk action TODO
		if ( isset( $order['shipping_method'] ) && is_array( $order['shipping_method'] ) ) {
			foreach ( $order['shipping_method'] as $s_key => $s_value ) {
				$pInfoReplace[ '{{ORDER_METHOD' . $s_value['shipping_method'] . '}}' ] = $s_value['shipping_method'];
			}
		}

		// DEBUG
		// echo "<pre>";
		// print_r($pInfoReplace);
		// die();

		// Replace Order Information
		$html = str_replace( array_keys( $textToReplace ), array_values( $textToReplace ), $string );
		// Replace Product Information
		$html = str_replace( array_keys( $pInfoReplace ), array_values( $pInfoReplace ), $html );

		return $html;
	}

	// Social Media Icon  functionality ####
	public function SocialMediaIcons() {
		if ( '1' == get_option( 'wiopt_display_social_media_url' ) ) {
			$social_medias = array( 'facebook', 'twitter', 'linkedin', 'instagram', 'youtube' );
			$social_table  = '';

			foreach ( $social_medias as $social_media ) {
				if ( get_option( 'wiopt_social_media_' . $social_media . '_url' ) != false && get_option( 'wiopt_social_media_' . $social_media . '_url' ) != '' ) {
					$social_table .= '<table border="0" style="margin-top: 0px;margin-bottom: -20px">';
					$social_table .= '<tbody><tr style="height:5px;">';
					$social_table .= '<td style="border-bottom: 0px">';
					$social_table .= '<img style="width:12px;margin-right:5px;" alt="" src="' . plugins_url() . '/astama-pdf-invoice-for-woocommerce-pro/admin/images/' . $social_media . '.png">';
					$social_table .= get_option( 'wiopt_social_media_' . $social_media . '_url' );
					$social_table .= '</td>';
					$social_table .= '</tr></tbody>';
					$social_table .= '</table>';
				}
			}

			return $social_table;
		} else {
			return '';
		}
	}

	/**
	 * Save PDF Invoice to Upload directory
	 *
	 * @param $invoice_id
	 * @param $order_id
	 *
	 * @return string
	 * @throws \Mpdf\MpdfException
	 */
	public function savePdf( $order_id ) {

	    $order = wc_get_order($order_id);
        $invoice_no = ( ! empty(get_post_meta($order_id, 'wiopt_invoice_no_'.$order_id, true))) ? get_post_meta($order_id, 'wiopt_invoice_no_'.$order_id, true) : woo_invo_ice_get_invoice_number($order_id);
        if ( 'refunded' === $order->get_status() ) {
            $template_type = 'credit_note';
        }else {
            $template_type = 'invoice';
        }
		// Download pdf for invoice.
		$template = woo_invo_ice_template( array( $order_id ), $template_type );
		ob_start();
		echo $template->get_invoice_template(); //phpcs:ignore
		$html = ob_get_contents();
		ob_end_clean();
        if ( $order instanceof WC_Order && 'refunded' === $order->get_status() ) {
            $file_name = 'Credit-Note-Of-Invoice-' . $invoice_no;
        }else {
            $file_name = 'Invoice-' . $invoice_no;
        }

		$pdf       = woo_invo_ice_pdf( $html, $file_name, $order_id, 'save' );
		$pdf->generatePDF();

		// Download pdf for packing slip if order is completed.
        if ( ( 'completed' === $order->get_status() ) && ( '1' === get_option( 'wiopt_email_packing_slip' ) ) ) {

            $get_packingslip_template = woo_invo_ice_template( array( $order_id ), 'packing_slip' );
            ob_start();
            echo $get_packingslip_template->get_packing_template(); //phpcs:ignore
            $packing_slip_html = ob_get_contents();
            ob_end_clean();
            $packing_slip_name = 'packing-slip' .'-'.$invoice_no. '.pdf';

            $pdff       = woo_invo_ice_pdf( $packing_slip_html, $packing_slip_name, $order_id, 'save_packing_slip' );
            $pdff->generatePDF();
        }

	}

	// Template Customization Functions Start ####

	public function tableColumnColspan( $order ) {
		$colspan = 6;

		if ( ( '1' == get_option( 'wiopt_show_tax' ) && 0.00 == $order['total_tax'] ) ) {
			$colspan = $colspan - 1;
		} elseif ( empty( get_option( 'wiopt_show_tax' ) ) ) {
			$colspan = $colspan - 1;
		}

		if ( ( '1' == get_option( 'wiopt_tax_percentage' ) && 0.00 == $order['total_tax'] ) ) {
			$colspan = $colspan - 1;
		} elseif ( empty( get_option( 'wiopt_tax_percentage' ) ) ) {
			$colspan = $colspan - 1;
		}

		if ( ! get_option( 'wiopt_product_image_show' ) || is_null( get_option( 'wiopt_product_image_show' ) ) ) {
			$colspan = $colspan - 1;
		}

		return $colspan;
	}

	// Need for invoice-5
	public function tableColumnColspan2( $order ) {
		$column = 4;

		if ( '1' == get_option( 'wiopt_show_tax' ) && 0.00 != $order['total_tax'] ) {
			$column = $column + 1;
		}

		if ( '1' == get_option( 'wiopt_tax_percentage' ) && 0.00 != $order['total_tax'] ) {
			$column = $column + 1;
		}

		if ( '1' == get_option( 'wiopt_product_image_show' ) ) {
			$column = $column + 1;
		}

		if ( 4 == $column ) {
			$colspan = 2;
		} elseif ( 5 == $column ) {
			$colspan = 2;
		} elseif ( 6 == $column ) {
			$colspan = 3;
		} elseif ( 7 == $column ) {
			$colspan = 3;
		}

		return $colspan;
	}

	// Need for invoice-5
	public function tableColumnColspan3( $order ) {

		$column = 4;

		if ( '1' == get_option( 'wiopt_show_tax' ) && 0.00 != $order['total_tax'] ) {
			$column = $column + 1;
		}

		if ( '1' == get_option( 'wiopt_tax_percentage' ) && 0.00 != $order['total_tax'] ) {
			$column = $column + 1;
		}

		if ( '1' == get_option( 'wiopt_product_image_show' ) ) {
			$column = $column + 1;
		}

		if ( 4 == $column ) {
			$colspan = 2;
		} elseif ( 5 == $column ) {
			$colspan = 3;
		} elseif ( 6 == $column ) {
			$colspan = 3;
		} elseif ( 7 == $column ) {
			$colspan = 4;
		}

		return $colspan;
	}

	// Need for invoice-7
	public function tableColumnColspan4( $order ) {
		$column = 4;

		if ( '1' == get_option( 'wiopt_show_tax' ) && 0.00 != $order['total_tax'] ) {
			$column = $column + 1;
		}

		if ( '1' == get_option( 'wiopt_tax_percentage' ) && 0.00 != $order['total_tax'] ) {
			$column = $column + 1;
		}

		if ( '1' == get_option( 'wiopt_product_image_show' ) ) {
			$column = $column + 1;
		}

		if ( 4 == $column ) {
			$colspan = 2;
		} elseif ( 5 == $column ) {
			$colspan = 3;
		} elseif ( 6 == $column ) {
			$colspan = 3;
		} elseif ( 7 == $column ) {
			$colspan = 3;
		}

		return $colspan;
	}

	// Need for invoice-7
	public function tableColumnColspan5( $order ) {

		$column = 4;

		if ( get_option( 'wiopt_show_tax' ) == '1' && 0.00 != $order['total_tax'] ) {
			$column = $column + 1;
		}

		if ( get_option( 'wiopt_tax_percentage' ) == '1' && 0.00 != $order['total_tax'] ) {
			$column = $column + 1;
		}

		if ( '1' == get_option( 'wiopt_product_image_show' ) ) {
			$column = $column + 1;
		}

		if ( 4 == $column ) {
			$colspan = 1;
		} elseif ( 5 == $column ) {
			$colspan = 1;
		} elseif ( 6 == $column ) {
			$colspan = 2;
		} elseif ( 7 == $column ) {
			$colspan = 3;
		}

		return $colspan;
	}

	/**
	 * Need for Invoice Template 3,4,5. fixing Overlapping problem in FROM section
	 *
	 * @param $order
	 *
	 * @return string
	 */
	private function invoiceMargin( $order ) {
		$date = $order['date_completed'];
		if ( ! empty( $date ) ) {
			return '-30px';
		} else {
			return '0px';
		}

	}

	// Template Customization Functions End ####

	/**
	 * Get Individual product length for making invoice/ invoice slip
	 *
	 * @param $product
	 *
	 * @return string
	 */
	private function getProductLength( $product ) {
		$length = ! empty( $product->get_length() ) ? $product->get_length() . ' ' . get_option( 'woocommerce_dimension_unit' ) : 'n/a';

		return $length;
	}

	/**
	 * Get Individual product width for making invoice/ invoice slip
	 *
	 * @param $product
	 *
	 * @return string
	 */
	private function getProductWidth( $product ) {
		$width = ! empty( $product->get_width() ) ? $product->get_width() . ' ' . get_option( 'woocommerce_dimension_unit' ) : 'n/a';

		return $width;
	}

	/**
	 * Get Individual product height for making invoice/ invoice slip
	 *
	 * @param $product
	 *
	 * @return string
	 */
	private function getProductHeight( $product ) {
		$height = ! empty( $product->get_height() ) ? $product->get_height() . ' ' . get_option( 'woocommerce_dimension_unit' ) : 'N/A';

		return $height;
	}


	/**
	 * Get Individual product weight for making invoice/ invoice slip
	 *
	 * @param $product
	 *
	 * @return string
	 */
	private function getProductWeight( $product ) {
		$weight = $product->get_weight();
		$weight = wc_format_weight( $weight );

		return $weight;
	}

	/**
	 * Get Currency code (Ex. USD)
	 *
	 * @param $orderId
	 *
	 * @return string
	 */
	private function getCurrencyCode( $orderId ) {
		if ( ! empty( get_option( 'wiopt_currency_code' ) ) ) {
			return ' (' . wc_get_order( $orderId )->get_currency() . ')';
		} else {
			return '';
		}
	}

	/**
	 * Get Order Note of each order for invoice
	 *
	 * @param $orderId
	 *
	 * @return string
	 */
	private function get_order_note( $orderId ) {
		if ( '1' == get_option( 'wiopt_show_order_note' ) && ! empty( wc_get_order( $orderId )->get_customer_note() ) ) {
			$template = get_option( 'wiopt_templateid' );
			if ( 'invoice-7' == $template ) {
				return "<p style='margin-top:10px;color:#fff'>Order Note: " . wc_get_order( $orderId )->get_customer_note() . '</p>';
			} elseif ( 'invoice-9' == $template ) {
				return "<p style='margin-top:10px;color:#382F57'>Order Note: " . wc_get_order( $orderId )->get_customer_note() . '</p>';
			} else {
				return "<p style='margin-top:10px;'>Order Note: " . wc_get_order( $orderId )->get_customer_note() . '</p>';
			}
		} else {
			return '';
		}
	}

	/**
	 * Get Order's custom post meta for invoice
	 *
	 * @param $orderId
	 *
	 * @return string
	 */
	// TODO Set default order metas after plugin activation or set default metas here
	private function get_order_post_meta( $orderId ) {
		if ( get_option( 'wiopt_custom_order_meta' ) && ! empty( get_option( 'wiopt_custom_order_meta' ) ) ) {
			$post_meta = '';
			foreach ( get_option( 'wiopt_custom_order_meta' ) as $key => $value ) {
				if ( get_post_meta( $orderId, $key, true ) != '' ) {
					'<b>' . $post_meta .= $value . ': </b>' . get_post_meta( $orderId, $key, true ) . '<br>';
				}
			}

			return $post_meta;
		}

		return '';
	}

	/**
	 * Resize & Get Invoice logo according to plugin settings
	 */
	private function getInvoiceLogo() {

		$logo_url = false;

		// Get original logo image
		if ( get_option( 'wiopt_logo_attachment_id' ) != false ) {
			if ( substr( get_option( 'wiopt_logo_attachment_id' ), 0, 7 ) === 'http://' || substr( get_option( 'wiopt_logo_attachment_id' ), 0, 8 ) === 'https://' ) {
				$image_id       = attachment_url_to_postid( get_option( 'wiopt_logo_attachment_id' ) );
				$full_size_path = get_attached_file( $image_id );
				update_option( 'wiopt_logo_attachment_id', $full_size_path );
				update_option( 'wiopt_logo_attachment_image_id', $image_id );
			}
			$logo_url = get_option( 'wiopt_logo_attachment_id' );
		} elseif ( has_custom_logo() ) { // Get custom logo from theme customization
			$custom_logo_id  = get_theme_mod( 'custom_logo' );
			$custom_logo_url = wp_get_attachment_image_url( $custom_logo_id, 'full' );
			$logo_url        = $custom_logo_url;
		}

		// Set Logo Width
		$logo_width = get_option( 'wiopt_logo_width' );
		$logo_width = ! empty( $logo_width ) ? "style='width:$logo_width'" : '';

		// Final Logo
		$logo = "<img class='logo' src='$logo_url' $logo_width >";

		if ( ! empty( $logo_url ) ) {
			return $logo;
		}

		return '';
	}

	/**
	 * Only for Invoice 6 template
	 *
	 * @return string
	 */
	private function circleImage() {
		return plugin_dir_url( __FILE__ ) . 'templates/circle.png';
	}

	/**
	 * Get formatted regular price
	 *
	 * @param $orderId
	 * @param $regular_price
	 * @param $sale_price
	 * @param $orderPrice
	 *
	 * @return string
	 */
	public function getFormattedRegularPrice( $orderId, $regular_price, $sale_price, $orderPrice ) {
		if ( '1' == get_option( 'wiopt_show_discounted_price' ) && ! empty( $sale_price ) && ( $orderPrice < $regular_price ) ) {
			return $this->formatPrice( $regular_price, $orderId );
		}
		return '';
	}

	/**
	 * Format product price with currency symbol
	 *
	 * @param $price
	 *
	 * @return string
	 */
	private function formatPrice( $price, $orderId = null ) {

		$order = wc_get_order( $orderId );
		if ( 1 == get_option( 'wiopt_currency_code' ) ) {
			$price = number_format(
				$price,
				wc_get_price_decimals(),
				wc_get_price_decimal_separator(),
				wc_get_price_thousand_separator()
			);
			if ( get_option( 'woocommerce_currency_pos' ) == 'left' || get_option( 'woocommerce_currency_pos' ) == 'left_space' ) {
				   return $price = $order->get_currency() . ' ' . $price;
			}
			if ( get_option( 'woocommerce_currency_pos' ) == 'right' || get_option( 'woocommerce_currency_pos' ) == 'right_space' ) {
				return $price . ' ' . $order->get_currency();
			}
		} else {
			$price = wc_price( $price, array( 'currency' => $order->get_currency() ) );
			if ( ! get_option( 'wiopt_currency_code' ) ) {
				$price = str_replace( 'woocommerce-Price-currencySymbol"', 'woocommerce-Price-currencySymbol" style="font-family: Currencies"', $price );
			}

			return $price;
		}
	}

	/**
	 * Get date format according to plugin settings
	 *
	 * @param $orderDate
	 *
	 * @return string
	 */
	private function processCustomDateFormat( $orderDate ) {
		if ( ! empty( $orderDate ) ) {
			$format    = 'd M, o';
			$getFormat = get_option( 'wiopt_date_format' );
			if ( ! empty( $getFormat ) ) {
				$format = $getFormat;
			}

			return gmdate( $format, strtotime( $orderDate ) );
		}

		return '';
	}

	/**
	 * Set invoice number according to plugin settings
	 */
	private function processInvoiceNumber( $orderId ) {

		$invoiceNo = $orderId;

		// Invoice Number Type
		$getNumberType = get_option( 'wiopt_invoice_number_type' );

		// Get Prefix
		$getPrefix = get_option( 'wiopt_invoice_no_prefix' );
		$prefix    = ! empty( $getPrefix ) ? $getPrefix : '';

		// Get Suffix
		$getSuffix = get_option( 'wiopt_invoice_no_suffix' );
		$suffix    = ! empty( $getSuffix ) ? $getSuffix : '';

		// Get next number for custom sequence
        if ( ! empty(get_post_meta($orderId, 'wiopt_invoice_no_'.$orderId, true)) ) {
            $nextNo = get_post_meta($orderId, 'wiopt_invoice_no_'.$orderId, true);
        }else {
            $nextNo = get_post_meta($orderId, 'wiopt_invoice_no', true);
        }

		// Generate Invoice Number
		if ( 'pre_custom_number_suf' == $getNumberType ) {
			$invoiceNo = $prefix . $nextNo . $suffix;
		} elseif ( 'pre_order_number_suf' == $getNumberType ) {
			$invoiceNo = $prefix . $invoiceNo . $suffix;
		}

		// Process Invoice number macros
		$invoiceNo = woo_invo_ice_process_date_macros( $orderId, $invoiceNo );

		return $invoiceNo;
	}

	/**
	 *  Set order number according to plugin settings
	 *
	 * @param $orderId
	 *
	 * @return mixed|string
	 */
	private function processOrderNumber( $orderId ) {

		$orderNo = $orderId;

		// Order Number Type
		$getNumberType = get_option( 'wiopt_invoice_order_number_type' );

		if ( ! $getNumberType || 'order_number' == $getNumberType ) {
			return $orderNo;
		}

		// Add Prefix & Suffix to order number  Order Number
		if ( 'pre_order_order_number_suf' == $getNumberType ) {

			// Get Prefix
			$getPrefix = get_option( 'wiopt_order_no_prefix' );
			$prefix    = ! empty( $getPrefix ) ? $getPrefix : '';

			// Get Suffix
			$getSuffix = get_option( 'wiopt_order_no_suffix' );
			$suffix    = ! empty( $getSuffix ) ? $getSuffix : '';

			$orderNo = $prefix . $orderNo . $suffix;
		}

		// Check for any sequential order number plugin installed or not and then get the sequential number if found ###

		if ( class_exists( 'Alg_WC_Custom_Order_Numbers' ) ) {
			if ( '_wooinvoice_custom_order_numbers_for_woocommerce' == $getNumberType ) {
				$orderNo = get_post_meta( $orderId, '_alg_wc_custom_order_number', true );
			}
		}

		if ( class_exists( 'Wt_Advanced_Order_Number' ) ) {
			if ( '_wooinvoice_wt_woocommerce_sequential_order_numbers' == $getNumberType ) {
				$orderNo = wc_get_order( $orderId )->get_order_number();
			}
		}

		if ( class_exists( 'Wt_Advanced_Order_Number' ) ) {
			if ( '_wooinvoice_woocommerce_sequential_order_numbers' == $getNumberType ) {
				$orderNo = wc_get_order( $orderId )->get_order_number();
			}
		}

		if ( class_exists( 'WCSON_INIT' ) ) {
			if ( '_wooinvoice_woo_custom_and_sequential_order_number' == $getNumberType ) {
				$orderNo = get_post_meta( $orderId, '_wcson_order_number', true );
			}
		}

		if ( class_exists( 'BeRocket_Order_Numbers' ) ) {
			if ( '_wooinvoice_sequential_order_numbers_for_wooCommerce' == $getNumberType ) {
				$orderNo = get_post_meta( $orderId, '_sequential_order_number', true );
			}
		}

		if ( class_exists( 'OpenToolsOrdernumbersBasic' ) ) {
			if ( '_wooinvoice_woocommerce_basic_ordernumbers' == $getNumberType ) {
				$orderNo = get_post_meta( $orderId, '_oton_number_ordernumber', true );
			}
		}
		// Sequential number checking complete ###

		// Process order number macros
		$orderNo = woo_invo_ice_process_date_macros( $orderId, $orderNo );

		return $orderNo;
	}

	/**
	 * Process three date macros which are {{year}} {{month}} {{day}}
	 *
	 * @param $orderId
	 * @param $orderNo
	 *
	 * @return mixed
	 */
	private function processDateMacros( $orderId, $orderNo ) {
		$order_created = get_the_date( $d = '', $orderId );
		if ( strpos( $orderNo, '{{day}}' ) !== false ) {
			$orderNo = str_replace( '{{day}}', gmdate( 'd', strtotime( $order_created ) ), $orderNo );
		}
		if ( strpos( $orderNo, '{{month}}' ) !== false ) {
			$orderNo = str_replace( '{{month}}', gmdate( 'm', strtotime( $order_created ) ), $orderNo );
		}
		if ( strpos( $orderNo, '{{year}}' ) !== false ) {
			$orderNo = str_replace( '{{year}}', gmdate( 'Y', strtotime( $order_created ) ), $orderNo );
		}

		return $orderNo;
	}

	/**
	 * Get billing address according to plugin settings
	 *
	 * @param $orderId
	 *
	 * @return string
	 */
	private function getBillingAddress( $orderId ) {

		$details = sanitize_textarea_field( get_option( 'wiopt_buyer' ) );

		$full_address = '';
		if ( ! empty( $details ) ) {
			preg_match_all( '/{{(.*?)}}/', $details, $matches );
			$toReplace   = $matches[0];
			$replaceWith = array();

			if ( in_array( 'shipping_country', $matches[1] ) ) {
				$countryCode = get_post_meta( $orderId, '_shipping_country', true );
			} else {
				$countryCode = get_post_meta( $orderId, '_billing_country', true );
			}

			foreach ( $matches[1] as $key => $metaKey ) {
				$isTypeMeta = substr( "$metaKey", 0, 1 );

				$getMeta = get_post_meta( $orderId, $metaKey, true );

				// If meta not found then add underscore and try again
				if ( empty( $getMeta ) ) {
					if ( '_' != $isTypeMeta ) {
						$metaKey = '_' . $metaKey;
					}
					$getMeta = get_post_meta( $orderId, $metaKey, true );
				}

				if ( is_array( $getMeta ) ) {
					$getMeta = implode( '-', $getMeta );
				}

				if ( strpos( $metaKey, 'billing_state' ) !== false || strpos( $metaKey, 'shipping_state' ) !== false ) {
					$getMeta = $this->getStateLabel( $countryCode, $getMeta );
				}

				if ( strpos( $metaKey, 'shipping_country' ) !== false || strpos( $metaKey, 'billing_country' ) !== false ) {
					$getMeta = $this->getCountryLabel( $getMeta );
				}

				$getMeta = ! empty( $getMeta ) ? $getMeta : '';
				array_push( $replaceWith, $getMeta );
			}

			// Get Buyer block title
			$to = get_option( 'wiopt_block_title_to' );

			// Replace Billing information according to customers settings
			$address = str_replace( $toReplace, $replaceWith, $details );

			// Remove Empty Line.
			$address = preg_replace( "/\n\n/", "\n", $address );
			$address = preg_replace( "/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", '<br>', $address );
			$address = str_replace( '<br><br>', '<br>', $address );
			// Buyer Info
			$template = get_option( 'wiopt_templateid' );
			if ( 'invoice-9' == $template ) {
				$full_address = "<span style='font-weight:bold;color:#AED6CF;font-size:15px;'>$to</span><br>" . $address;
			} else {
				$full_address = '<b>' . $to . '</b><br>' . $address;
			}

			// Get VAT Label
			$vat = get_option( 'wiopt_VAT_ID' );
			$vat = ( false != $vat || ! empty( $vat ) ) ? $vat : 'VAT Number';
			// Get VAT Number
			if ( ! empty( get_post_meta( $orderId, 'wiopt_vat_id', true ) ) ) {
				$full_address .= '<br>' . $vat . ': ' . get_post_meta( $orderId, 'wiopt_vat_id', true );
			} else {
				$userId = $this->getUserIdByOrderId( $orderId );
				if ( get_user_meta( $userId, 'wiopt_vat', true ) != '' ) {
					$full_address .= '<br>' . $vat . ': ' . get_user_meta( $userId, 'wiopt_vat', true );
				}
			}

			// Get SSN Label
			$ssn = get_option( 'wiopt_SSN' );
			$ssn = ( false != $ssn || ! empty( $ssn ) ) ? $ssn : 'SSN';
			// Get SSN Number
			if ( ! empty( get_post_meta( $orderId, 'wiopt_ssn_id', true ) ) ) {
				$full_address .= '<br>' . $ssn . ': ' . get_post_meta( $orderId, 'wiopt_ssn_id', true );
			} else {
				$userId = $this->getUserIdByOrderId( $orderId );
				if ( ! empty( get_user_meta( $userId, 'wiopt_ssn', true ) ) ) {
					$full_address .= '<br>' . $ssn . ': ' . get_user_meta( $userId, 'wiopt_ssn', true );
				}
			}
		}

		return $full_address;
	}

	/**
	 * Get user id by order id to get customer profile information
	 *
	 * @param $order_id
	 *
	 * @return bool|int
	 */
	private function getUserIdByOrderId( $order_id ) {
		if ( empty( $order_id ) ) {
			return false;
		}
		$order_obj = wc_get_order( $order_id );

		return $order_obj->get_user_id();
	}

	/**
	 * If shipping information is empty, replace with billing information
	 *
	 * @param $args
	 *
	 * @return string
	 */
	private function replaceShippingAddress( $args ) {
		if ( ! empty( $args ) ) {
			return $args;
		} else {
			return '==';
		}
	}

	/**
	 * Get paid stamp according to plugin settings for invoice
	 *
	 * @param $order_status
	 *
	 * @return string
	 */
	private function getPaidStamp( $order_status ) {

		if ( 'completed' == $order_status && true == get_option( 'wiopt_paid_stamp' ) && false != get_option( 'wiopt_paid_stamp_image' ) ) {
			$selected_paid_stamp = get_option( 'wiopt_paid_stamp_image' );
			$paid_stamp          = WP_PLUGIN_URL . '/astama-pdf-invoice-for-woocommerce-pro/admin/images/paid-stamp/' . $selected_paid_stamp . '.png';
			$opacity             = get_option( 'wiopt_paid_stamp_opacity' ) != false ? get_option( 'wiopt_paid_stamp_opacity' ) : '1.0';

			return '<img src="' . $paid_stamp . '" alt="" style="margin-left:260px;margin-top:30px;width:20%;opacity:' . $opacity . '">';
		} else {
			return '';
		}
	}

	/**
	 * Get signature according to plugin settings for invoice
	 */
	private function getStoreSignature() {
		if ( get_option( 'wiopt_enable_signature' ) != false && ! empty( get_option( 'wiopt_signature_attachment_id' ) ) ) {
			if ( substr( get_option( 'wiopt_signature_attachment_id' ), 0, 7 ) === 'http://' || substr( get_option( 'wiopt_signature_attachment_id' ), 0, 8 ) === 'https://' ) {
				$image_id      = attachment_url_to_postid( get_option( 'wiopt_signature_attachment_id' ) );
				$fullsize_path = get_attached_file( $image_id );
				update_option( 'wiopt_signature_attachment_id', $fullsize_path );
				update_option( 'wiopt_signature_attachment_image_id', $image_id );
			}

			$signature  = '<td class="order-signature">';
			$signature .= '<img src="' . get_option( 'wiopt_signature_attachment_id' ) . '" alt="signature" style="width:150px;border-bottom:1px solid #ccc"><br/><p>Authorized Signature</p>';
			$signature .= '</td>';

			return $signature;
		}

		return '';
	}

	/**
	 * @param $order WC_Order
	 *
	 * @return string
	 */
	public function getShippingLabelMetas( $order ) {
		$get_meta_labels = get_option( 'wiopt_shipping_label_meta_labels' );
		$get_metas       = get_option( 'wiopt_shipping_label_metas' );

		$id    = $order['id'];
		$metas = '';
		foreach ( $get_metas as $key => $value ) {
			if ( ! empty( $value ) && ! empty( $get_meta_labels[ $key ] ) ) {
				$meta_value = get_post_meta( $id, $value, true );
				$label      = $get_meta_labels[ $key ];
				$metas     .= $label . ' ' . $meta_value . '<br>';
			} elseif ( empty( $value ) && ! empty( $get_meta_labels[ $key ] ) ) {
				$label  = $get_meta_labels[ $key ];
				$metas .= $label . ' <br>';
			}
		}

		return $metas;
	}

	/**
	 * Set billing address according to plugin settings for shipping lebel
	 *
	 * @param $order
	 * @param $column
	 * @param $row
	 *
	 * @return string
	 */

	public function processBillingAddressShippingLabel( $order, $column = null, $row = null ) {

		$details = get_option( 'wiopt_shipping_lebel_buyer' );

		$toReplace   = array(
			'{{billing_first_name}}',
			'{{billing_last_name}}',
			'{{billing_company}}',
			'{{billing_address_1}}',
			'{{billing_address_2}}',
			'{{billing_city}}',
			'{{billing_state}}',
			'{{billing_postcode}}',
			'{{billing_country}}',
			'{{billing_phone}}',
			'{{billing_email}}',
			'{{shipping_first_name}}',
			'{{shipping_last_name}}',
			'{{shipping_company}}',
			'{{shipping_address_1}}',
			'{{shipping_address_2}}',
			'{{shipping_city}}',
			'{{shipping_state}}',
			'{{shipping_postcode}}',
			'{{shipping_country}}',
			'{{shipping_phone}}',
			'{{shipping_email}}',
			"\n",
		);
		$replaceWith = array(
			! empty( $order['billing']['first_name'] ) ? $order['billing']['first_name'] : '==',
			! empty( $order['billing']['last_name'] ) ? $order['billing']['last_name'] : '==',
			! empty( $order['billing']['company'] ) ? $order['billing']['company'] : '==',
			! empty( $order['billing']['address_1'] ) ? $order['billing']['address_1'] : '==',
			! empty( $order['billing']['address_2'] ) ? $order['billing']['address_2'] : '==',
			! empty( $order['billing']['city'] ) ? $order['billing']['city'] : '==',
			! empty( $order['billing']['state'] ) ? $this->getStateLabel( $order['shipping']['country'], $order['billing']['state'] ) : '==',
			! empty( $order['billing']['postcode'] ) ? $order['billing']['postcode'] : '==',
			! empty( $order['billing']['country'] ) ? $this->getCountryLabel( $order['billing']['country'] ) : '==',
			! empty( $order['billing']['phone'] ) ? $order['billing']['phone'] : '==',
			! empty( $order['billing']['email'] ) ? $order['billing']['email'] : '==',
			! empty( $order['shipping']['first_name'] ) ? $order['shipping']['first_name'] : $this->replaceShippingAddress( $order['billing']['first_name'] ),
			! empty( $order['shipping']['last_name'] ) ? $order['shipping']['last_name'] : $this->replaceShippingAddress( $order['billing']['last_name'] ),
			! empty( $order['shipping']['company'] ) ? $order['shipping']['company'] : $this->replaceShippingAddress( $order['billing']['company'] ),
			! empty( $order['shipping']['address_1'] ) ? $order['shipping']['address_1'] : $this->replaceShippingAddress( $order['billing']['address_1'] ),
			! empty( $order['shipping']['address_2'] ) ? $order['shipping']['address_2'] : $this->replaceShippingAddress( $order['billing']['address_2'] ),
			! empty( $order['shipping']['city'] ) ? $order['shipping']['city'] : $this->replaceShippingAddress( $order['billing']['city'] ),
			! empty( $order['shipping']['state'] ) ? $this->getStateLabel( $order['shipping']['country'], $order['shipping']['state'] ) : $this->replaceShippingAddress( $order['billing']['state'] ),
			! empty( $order['shipping']['postcode'] ) ? $order['shipping']['postcode'] : $this->replaceShippingAddress( $order['billing']['postcode'] ),
			! empty( $order['shipping']['country'] ) ? $this->getCountryLabel( $order['shipping']['country'] ) : $this->replaceShippingAddress( $order['billing']['country'] ),
			! empty( $order['shipping']['phone'] ) ? $order['shipping']['phone'] : $this->replaceShippingAddress( $order['billing']['phone'] ),
			! empty( $order['shipping']['email'] ) ? $order['shipping']['email'] : $this->replaceShippingAddress( $order['billing']['email'] ),
			'<br>',
		);

		$to = get_option( 'wiopt_shipping_lebel_block_title_to' );

		// Replace Billing information according to customers settings
		$address = str_replace( $toReplace, $replaceWith, $details );
		// Remove Empty Line
		$address = str_replace( array( '==', '<br>==' ), '', $address );

		return '<div style="float:left;width:' . $column . '%"><p><b>' . $to . '</b><br>' . $address . '</p></div>';

	}

	/**
	 * Get Country label by country code
	 *
	 * @param $countryCode
	 *
	 * @return mixed
	 */
	private function getCountryLabel( $countryCode ) {
		if ( empty( $countryCode ) ) {
			return;
		}

		$Countries = $this->countries->get_countries();

		return $Countries[ $countryCode ];
	}

	/**
	 * Get State label by Country code and State code
	 *
	 * @param $countryCode
	 * @param $stateCode
	 *
	 * @return mixed
	 */
	private function getStateLabel( $countryCode, $stateCode ) {
		if ( empty( $countryCode ) || empty( $stateCode ) ) {
			return;
		}

		$states = $this->countries->get_states( $countryCode );

		return $states[ $stateCode ];
	}

	/**
	 * Get Product Image URL
	 *
	 * @param $id
	 *
	 * @return string
	 */

	private function getProductImage( $id ) {
		if ( has_post_thumbnail( $id ) ) {
			$attachment_ids[0] = get_post_thumbnail_id( $id );
			$attachment        = wp_get_attachment_image_src( $attachment_ids[0], 'full' );
			$image_url         = $attachment[0];

			return $image_url;
		}
	}

	/**
	 *  Format Product Name
	 *
	 * @param $name
	 * @param $sku
	 * @param $id
	 * @param null $orderId
	 *
	 * @return string
	 */
	public function processProductInfo( $name, $sku, $id, $orderId = null ) {

		$pName = '';

		// Action Before the item meta (for each item in the order details table) for action
		$pName = '<p>' . $this->woo_invo_ice_before_item_meta( $id ) . '</p>';

		$product = wc_get_product( $id );
		// Product Title Length Setting
		$product_title_length = get_option( 'wiopt_invoice_product_title_length' );
		if ( strlen( $name ) > $product_title_length && false != $product_title_length && '' != $product_title_length ) {
			$name   = substr( $name, 0, $product_title_length ) . '...';
			$pName .= '<p>' . $name;
		} else {
			$pName .= '<p>' . $name;
		}

		// Show SKU or ID
		$displayInfo = get_option( 'wiopt_disid', true );
		if ( ! empty( $displayInfo ) ) {
			if ( 'ID' == $displayInfo ) {
				$pName .= "<br/><span style='font-size: 9px;color: #382F57;'>ID: $id</span>";
			} elseif ( 'SKU' == $displayInfo ) {
				if ( ! empty( $sku ) ) {
					$pName .= "<br/><span style='font-size: 9px;color: #382F57;'>SKU: " . $sku . '</span>';
				}
			}
		}

		// Show Product Categories
		$displayCategory = get_option( 'wiopt_product_category_show', 1 );
		if ( '1' == $displayCategory ) {
			$terms = get_the_terms( $id, 'product_cat' );
			if ( $terms && ! is_wp_error( $terms ) ) {
				$categories = array_column( $terms, 'name' );
				$categories = implode( $categories, ', ' );
				if ( ! empty( $categories ) ) {
					$pName .= "<br/><span style='font-size: 9px;color: #382F57;'>Category: " . $categories . '</span>';
				}
			}
		}

		// Show Product Description
		$displayDescription = get_option( 'wiopt_product_description_show', true );
		if ( ! empty( $displayDescription ) && 'none' != $displayDescription ) {
			$description_length = get_option( 'wiopt_invoice_description_limit' );
			if ( 'short' == $displayDescription ) {
				$s_description = strip_tags( $product->get_short_description() );
				if ( '' != $s_description ) {
					if ( strlen( $s_description ) > $description_length && false != $description_length && '' != $description_length ) {
						$s_description = substr( $s_description, 0, $description_length ) . '...';
					}
					$pName .= "<br/><span style='font-size: 9px;color: #382F57;'>Description: " . $s_description . '</span>';
				}
			} elseif ( 'long' == $displayDescription ) {
				$l_description = strip_tags( apply_filters( 'the_content', $product->post->post_content ) );
				if ( '' != $l_description ) {
					if ( strlen( $l_description ) > $description_length && false != $description_length && '' != $description_length ) {
						$l_description = substr( $l_description, 0, $description_length ) . '...';
					}
					$pName .= "<br/><span style='font-size: 9px;color: #382F57;'>Description: " . $l_description . '</span>';
				}
			}
		}

		// Show Product Attributes
		$attributes = get_option( 'wiopt_product_attribute_show', true );
		if ( ! empty( $attributes ) ) {
			$attrInfo = array();
			foreach ( $attributes as $key => $attribute ) {
				$attributeValue = $product->get_attribute( $attribute );
				if ( $attributeValue ) {
					$attrInfo[] = ucwords( $attribute ) . ': ' . $attributeValue;
				}
			}
			if ( ! empty( $attrInfo ) ) {
				$pName .= "<br/><span style='font-size: 9px;color: #382F57;'>" . implode( $attrInfo, ', ' ) . '</span>';
			}
		}

		// Show Product Dimensions
		$dimensions = get_option( 'wiopt_invoice_product_dimension_show', true );
		if ( ! empty( $dimensions ) ) {
			$dimensionInfo = array();
			$unit          = get_option( 'woocommerce_dimension_unit' );
			$getDimension  = array(
				'width'  => $product->get_width() . ' ' . $unit,
				'height' => $product->get_height() . ' ' . $unit,
				'length' => $product->get_length() . ' ' . $unit,
				'weight' => wc_format_weight( $product->get_weight() ),
			);

			foreach ( $dimensions as $key => $dimension ) {
				$dimensionInfo[] = ucfirst( $dimension ) . ': ' . $getDimension[ $dimension ];
			}

			if ( ! empty( $dimensionInfo ) ) {
				$pName .= "<br><span style='font-size: 9px;color: #382F57;'> " . implode( $dimensionInfo, ', ' ) . '</span></br>';
			}
		}

		// Show product meta.
		$post_meta = get_option( 'wiopt_custom_post_meta', true );
		if ( ! empty( $post_meta ) ) {
			foreach ( $post_meta as $meta => $label ) {
				$metaValue = get_post_meta( $id, $meta, true );
				if ( $metaValue ) {
					$pName .= "<br><span style='font-size: 9px;color: #382F57;'>" . $label . ': ' . $metaValue . '</span></br>';
				}
			}
		}
		// Show order item meta.
        $order_item_meta = get_option( 'wiopt_order_item_meta', true );
		if ( ! empty( $order_item_meta ) ) {
			foreach ( $order_item_meta as $meta => $label ) {
				$meta_value = get_post_meta( $id, $meta, true );
				if ( $meta_value ) {
					$pName .= "<br><span style='font-size: 9px;color: #382F57;'>" . $label . ': ' . $meta_value . '</span></br>';
				}
			}
		}

		// Action After the item meta (for each item in the order details table) for action
		$pName .= '<p>' . $this->woo_invo_ice_after_item_meta( $id ) . '</p>';

		return $pName;
	}


	public function packingslipProductInfo( $name, $sku, $id ) {

		$pName = '';

		// Action Before the item meta (for each item in the order details table) for action
		$pName = '<p>' . $this->woo_packing_slip_before_item_meta( $id ) . '</p>';

		$product_title_length = get_option( 'wiopt_packingslip_product_title_length' );
		if ( strlen( $name ) > $product_title_length && false != $product_title_length && '' != $product_title_length ) {
			$name   = substr( $name, 0, $product_title_length ) . '...';
			$pName .= '<p>' . $name;
		} else {
			$pName .= '<p>' . $name;
		}

		$displayInfo = get_option( 'wiopt_packingslip_disid', true );
		if ( ! empty( $displayInfo ) ) {
			if ( 'ID' == $displayInfo ) {
				$pName .= "<br/><span style='font-size: xx-small;color: #382F57;'>ID: $id</span>";
			} elseif ( 'SKU' == $displayInfo ) {
				if ( ! empty( $sku ) ) {
					$pName .= "<br/><span style='font-size: xx-small;color: #382F57;'>SKU: " . $sku . '</span>';
				}
			}
		}

		$displayCategory = get_option( 'wiopt_packingslip_product_category_show', 1 );

		if ( '1' == $displayCategory ) {

			$categories    = wp_get_post_terms( $id, 'product_cat', array( 'fields' => 'ids' ) );
			$category_name = '';
			foreach ( $categories as $key => $category ) {
				$termid = get_term( $category, 'product_cat' );
				if ( 0 == $termid->parent ) {
					$category_name .= $termid->name . ', ';
				}
			}

			$category_name = rtrim( $category_name, ', ' );

			$pName .= "<br/><span style='font-size: xx-small;color: #382F57;'>Category: " . $category_name . '</span>';
		}

		$displayDescription = get_option( 'wiopt_packingslip_product_description_show', true );
		$description_length = get_option( 'wiopt_packingslip_description_limit' );

		if ( 'short' == $displayDescription ) {
			$product       = wc_get_product( $id );
			$s_description = strip_tags( $product->get_short_description() );
			if ( '' != $s_description ) {
				if ( strlen( $s_description ) > $description_length && false != $description_length && '' != $description_length ) {
					$s_description = substr( $s_description, 0, $description_length ) . '...';
				}
				$pName .= "<br/><span style='font-size: xx-small;color: #382F57;'>Description: " . $s_description . '</span>';
			}
		} elseif ( 'long' == $displayDescription ) {
			$product       = wc_get_product( $id );
			$l_description = strip_tags( apply_filters( 'the_content', $product->post->post_content ) );
			if ( '' != $l_description ) {
				if ( strlen( $l_description ) > $description_length && false != $description_length && '' != $description_length ) {
					$l_description = substr( $l_description, 0, $description_length ) . '...';
				}
				$pName .= "<br/><span style='font-size: xx-small;color: #382F57;'>Description: " . $l_description . '</span>';
			}
		}

		// Action After the item meta (for each item in the order details table) for action
		$pName .= '<p>' . $this->woo_packing_slip_after_item_meta( $id ) . '</p>';


		return $pName;
	}

	/**
	 * Add (Incl. Tax) after shipping total label according to plugin settings
	 *
	 * @param $text
	 * @param $shippingTax
	 *
	 * @return string
	 */
	private function getShippingText( $text, $shippingTax ) {
		if ( empty( $text ) ) {
			$text = 'SHIPPING';
		}
		if ( $shippingTax ) {
			$text .= ' (Incl. Tax)';
		}

		return $text;
	}

	/**
	 * Seller Info according to plugin settings
	 */
	private function getSellerInfo() {
		$from     = get_option( 'wiopt_block_title_from' );
		$company  = get_option( 'wiopt_cname' );
		$address  = str_replace( "\n", '<br>', sanitize_textarea_field( stripslashes( get_option( 'wiopt_cdetails' ) ) ) );
		$template = get_option( 'wiopt_templateid' );
		if ( 'invoice-9' == $template ) {
			return "<span style='font-weight:bold;color:#AED6CF;font-size:15px;'>$from</span><br>$company<br>$address";
		} else {
			return "<b>$from</b><br>$company<br>$address";
		}

	}



	// Woo Invoice Hook
	// Woo Hook

	/**
	 * Set Data After the customer/shipping notes
	 *
	 * @param $orderId Order ID
	 *
	 * @return string
	 */
	private function woo_invo_ice_custom_style() {
		ob_start();
		do_action( 'woo_invo_ice_custom_style', 'invoice' );

		return ob_get_clean();
	}

	/**
	 * Set Data Before all content on the document
	 *
	 * @param $orderId Order ID
	 *
	 * @return string
	 */
	private function woo_invo_ice_before_document( $orderId ) {
		$order = ( $orderId ) ? wc_get_order( $orderId ) : null;
		ob_start();
		// do_action('woo_invo_ice_before_document', $order);
		do_action( 'woo_invo_ice_before_document', $order, 'invoice' );

		return ob_get_clean();
	}

	/**
	 * Set Data After all content on the document
	 *
	 * @param $orderId Order ID
	 *
	 * @return string
	 */
	private function woo_invo_ice_after_document( $orderId ) {
		$order = ( $orderId ) ? wc_get_order( $orderId ) : null;
		ob_start();
		do_action( 'woo_invo_ice_after_document', $order, 'invoice' );

		return ob_get_clean();
	}

	/**
	 * Set Data After the document label (Invoice, Packing Slip etc.)
	 *
	 * @param $orderId Order ID
	 *
	 * @return string
	 */
	private function woo_invo_ice_after_document_label( $orderId ) {
		$order = ( $orderId ) ? wc_get_order( $orderId ) : null;
		ob_start();
		do_action( 'woo_invo_ice_after_document_label', $order, 'invoice' );

		return ob_get_clean();
	}

	/**
	 * Set Data Before the billing address
	 *
	 * @param $orderId Order ID
	 *
	 * @return string
	 */
	private function woo_invo_ice_before_billing_address( $orderId ) {
		$order = ( $orderId ) ? wc_get_order( $orderId ) : null;
		ob_start();
		do_action( 'woo_invo_ice_before_billing_address', $order, 'invoice' );

		return ob_get_clean();
	}

	/**
	 * Set Data After the billing address
	 *
	 * @param $orderId Order ID
	 *
	 * @return string
	 */
	private function woo_invo_ice_after_billing_address( $orderId ) {
		$order = ( $orderId ) ? wc_get_order( $orderId ) : null;
		ob_start();
		do_action( 'woo_invo_ice_after_billing_address', $order, 'invoice' );

		return ob_get_clean();
	}

	/**
	 * Set Data Before the order data (invoice number, order date, etc.)
	 *
	 * @param $orderId Order ID
	 *
	 * @return string
	 */
	private function woo_invo_ice_before_order_data( $orderId ) {
		$order = ( $orderId ) ? wc_get_order( $orderId ) : null;
		ob_start();
		do_action( 'woo_invo_ice_before_order_data', $order, 'invoice' );
		return ob_get_clean();
	}

	/**
	 * Set Data After the order data
	 *
	 * @param $orderId Order ID
	 *
	 * @return string
	 */
	private function woo_invo_ice_after_order_data( $orderId ) {
		$order = ( $orderId ) ? wc_get_order( $orderId ) : null;
		ob_start();
		do_action( 'woo_invo_ice_after_order_data', $order, 'invoice' );

		return ob_get_clean();
	}

	/**
	 * Set Data Before the order details table with all items
	 *
	 * @param $orderId Order ID
	 *
	 * @return string
	 */
	private function woo_invo_ice_before_product_list( $orderId ) {
		$order = ( $orderId ) ? wc_get_order( $orderId ) : null;
		ob_start();
		do_action( 'woo_invo_ice_before_product_list', $order, 'invoice' );

		return ob_get_clean();
	}

	/**
	 * Set Data After the order details table
	 *
	 * @param $orderId Order ID
	 *
	 * @return string
	 */
	private function woo_invo_ice_after_product_list( $orderId ) {
		$order = ( $orderId ) ? wc_get_order( $orderId ) : null;
		ob_start();
		do_action( 'woo_invo_ice_after_product_list', $order, 'invoice' );

		return ob_get_clean();
	}

	/**
	 * Set Data Before the item meta (for each item in the order details table)
	 *
	 * @param $productId Product ID
	 * @param $orderId   Order ID
	 *
	 * @return string
	 */
	private function woo_invo_ice_before_item_meta( $productId, $orderId = null ) {
		ob_start();
		$product = ( $productId ) ? wc_get_product( $productId ) : null;
		$order   = ( $orderId ) ? wc_get_order( $orderId ) : null;
		do_action( 'woo_invo_ice_before_item_meta', $product, $order, 'invoice' );

		return ob_get_clean();
	}

	/**
	 * Set Data After the item meta (for each item in the order details table)
	 *
	 * @param $productId Product ID
	 * @param $orderId   Order ID
	 *
	 * @return string
	 */
	private function woo_invo_ice_after_item_meta( $productId, $orderId = null ) {
		$product = ( $productId ) ? wc_get_product( $productId ) : null;
		$order   = ( $orderId ) ? wc_get_order( $orderId ) : null;
		ob_start();
		do_action( 'woo_invo_ice_after_item_meta', $product, $order, 'invoice' );

		return ob_get_clean();
	}

	/**
	 * Set Data Before the customer/shipping notes
	 *
	 * @param $orderId Order ID
	 *
	 * @return string
	 */
	private function woo_invo_ice_before_customer_notes( $orderId ) {
		$order = ( $orderId ) ? wc_get_order( $orderId ) : null;
		ob_start();
		do_action( 'woo_invo_ice_before_customer_notes', $order, 'invoice' );

		return ob_get_clean();
	}

	/**
	 * Set Data After the customer/shipping notes
	 *
	 * @param $orderId Order ID
	 *
	 * @return string
	 */
	private function woo_invo_ice_after_customer_notes( $orderId ) {
		$order = ( $orderId ) ? wc_get_order( $orderId ) : null;
		ob_start();
		do_action( 'woo_invo_ice_after_customer_notes', $order, 'invoice' );

		return ob_get_clean();
	}


	// Woo Packing Slip Hook

	/**
	 * Set Data After the customer/shipping notes
	 *
	 * @param $orderId Order ID
	 *
	 * @return string
	 */
	private function woo_packing_slip_custom_style() {
		ob_start();
		do_action( 'woo_invo_ice_custom_style', 'packing-slip' );

		return ob_get_clean();
	}

	/**
	 * Set Data Before all content on the document
	 *
	 * @param $orderId Order ID
	 *
	 * @return string
	 */
	private function woo_packing_slip_before_document( $orderId ) {
		$order = ( $orderId ) ? wc_get_order( $orderId ) : null;
		ob_start();
		do_action( 'woo_invo_ice_before_document', $order, 'packing-slip' );

		return ob_get_clean();
	}

	/**
	 * Set Data After all content on the document
	 *
	 * @param $orderId Order ID
	 *
	 * @return string
	 */
	private function woo_packing_slip_after_document( $orderId ) {
		$order = ( $orderId ) ? wc_get_order( $orderId ) : null;
		ob_start();
		do_action( 'woo_invo_ice_after_document', $order, 'packing-slip' );

		return ob_get_clean();
	}

	/**
	 * Set Data After the document label (Invoice, Packing Slip etc.)
	 *
	 * @param $orderId Order ID
	 *
	 * @return string
	 */
	private function woo_packing_slip_after_document_label( $orderId ) {
		$order = ( $orderId ) ? wc_get_order( $orderId ) : null;
		ob_start();
		do_action( 'woo_invo_ice_after_document_label', $order, 'packing-slip' );
		return ob_get_clean();
	}

	/**
	 * Set Data Before the billing address
	 *
	 * @param $orderId Order ID
	 *
	 * @return string
	 */
	private function woo_packing_slip_before_billing_address( $orderId ) {
		$order = ( $orderId ) ? wc_get_order( $orderId ) : null;
		ob_start();
		do_action( 'woo_invo_ice_before_billing_address', $order, 'packing-slip' );

		return ob_get_clean();
	}

	/**
	 * Set Data After the billing address
	 *
	 * @param $orderId Order ID
	 *
	 * @return string
	 */

	private function woo_packing_slip_after_billing_address( $orderId ) {
		$order = ( $orderId ) ? wc_get_order( $orderId ) : null;
		ob_start();
		do_action( 'woo_invo_ice_after_billing_address', $order, 'packing-slip' );
		return ob_get_clean();
	}

	/**
	 * Set Data Before the order data (invoice number, order date, etc.)
	 *
	 * @param $orderId Order ID
	 *
	 * @return string
	 */
	private function woo_packing_slip_before_order_data( $orderId ) {
		$order = ( $orderId ) ? wc_get_order( $orderId ) : null;
		ob_start();
		do_action( 'woo_invo_ice_before_order_data', $order, 'packing-slip' );

		return ob_get_clean();
	}

	/**
	 * Set Data After the order data
	 *
	 * @param $orderId Order ID
	 *
	 * @return string
	 */
	private function woo_packing_slip_after_order_data( $orderId ) {
		$order = ( $orderId ) ? wc_get_order( $orderId ) : null;
		ob_start();
		do_action( 'woo_invo_ice_after_order_data', $order, 'packing-slip' );

		return ob_get_clean();
	}

	/**
	 * Set Data Before the order details table with all items
	 *
	 * @param $orderId Order ID
	 *
	 * @return string
	 */
	private function woo_packing_slip_before_order_details( $orderId ) {
		$order = ( $orderId ) ? wc_get_order( $orderId ) : null;
		ob_start();
		do_action( 'woo_invo_ice_before_product_list', $order, 'packing-slip' );

		return ob_get_clean();
	}

	/**
	 * Set Data After the order details table
	 *
	 * @param $orderId Order ID
	 *
	 * @return string
	 */
	private function woo_packing_slip_after_order_details( $orderId ) {
		$order = ( $orderId ) ? wc_get_order( $orderId ) : null;
		ob_start();
		do_action( 'woo_invo_ice_after_product_list', $order, 'packing-slip' );

		return ob_get_clean();
	}

	/**
	 * Set Data Before the item meta (for each item in the order details table)
	 *
	 * @param $productId Product ID
	 * @param $orderId   Order ID
	 *
	 * @return string
	 */
	private function woo_packing_slip_before_item_meta( $productId, $orderId = null ) {
		ob_start();
		do_action( 'woo_invo_ice_before_item_meta', $productId, 'packing-slip' );

		return ob_get_clean();
	}

	/**
	 * Set Data After the item meta (for each item in the order details table)
	 *
	 * @param $productId Product ID
	 * @param $orderId   Order ID
	 *
	 * @return string
	 */
	private function woo_packing_slip_after_item_meta( $productId, $orderId = null ) {
		ob_start();
		do_action( 'woo_invo_ice_after_item_meta', $productId, 'packing-slip' );
		return ob_get_clean();
	}
}

// Initialize Invoice Engine
function woo_invo_ice_engine( $orderIds, $column = null, $paper_size = null, $row = null, $font_size = null ) {
	return $invoice = new Woo_Invo_Ice_Engine( $orderIds, $column, $paper_size, $row, $font_size );
}
