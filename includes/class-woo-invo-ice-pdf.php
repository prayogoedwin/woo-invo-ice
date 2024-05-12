<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

class Woo_Invo_Ice_PDF {

	/**
	 * Template Content.
	 *
	 * @var string $html Template Content.
	 */
	private $html;
	/**
	 * Paper Size
	 *
	 * @var null
	 */
	private $paper_size;
	/**
	 * Template Type
	 *
	 * @var string $template
	 */
	private $template;
	/**
	 * File name.
	 *
	 * @var string $file_name
	 */
	private $file_name;
	/**
	 * Order Id.
	 *
	 * @var $order_id
	 */
	private $order_id;
	/**
	 * MPDF Config
	 *
	 * @var array $config
	 */
	private $config = array();

	/**
	 * Woo_Invo_Ice_PDF constructor.
	 *
	 * @param string    $html       Template Content.
	 * @param string    $file_name  File Name.
	 * @param int|array $order_id   Order Ids.
	 * @param string    $template   Template type.
	 * @param string    $paper_size Paper Size.
	 */
	public function __construct( $html, $file_name, $order_id, $template, $paper_size ) {
		$this->html       = $html;
		$this->template   = $template;
		$this->file_name  = $file_name;
		$this->paper_size = $this->paper_size( $paper_size );
		$this->order_id   = $order_id;
		$this->mpdf_config();
	}

	/**
	 * MPDF Config
	 */
	public function mpdf_config() {
		// Increase mpdf generate execution time.
		set_time_limit(500);
		// Increase mpdf HTML code size.
		ini_set("pcre.backtrack_limit", "9000000");

		// Temporary Directory.
		$upload = wp_upload_dir();
		$basedir = $upload['basedir'];

		// Extra Fonts
		$extra_fonts = [
			'sun-exta' => 'Sun-ExtA.ttf',
			'sun-extb' => 'Sun-ExtB.ttf',
			'unbatang' => 'UnBatang_0613.ttf',
		];

		$default_config = (new Mpdf\Config\ConfigVariables())->getDefaults();
		$font_dirs = $default_config['fontDir'];

		// Add custom font dir for extra fonts.
		$extra_font_dir = $upload['basedir'] . '/woo-invo-ice/woo-invo-ice-fonts';
		if (file_exists($extra_font_dir)) {
			$font_dirs = array_merge($font_dirs, [$extra_font_dir]);
		}
		$this->config['fontDir'] = $font_dirs;

		$default_font_config = (new Mpdf\Config\FontVariables())->getDefaults();

		// Load Custom Fonts.
		$custom_fonts = get_option('wiopt_custom_font_list') ?: [];
		$font_data = array_merge($default_font_config['fontdata'], $custom_fonts);

		foreach ($extra_fonts as $key => $value) {
			$font_path = $extra_font_dir . '/' . $value;
			if (file_exists($font_path)) {
				switch ($key) {
					case 'sun-exta':
						$font_data['sun-exta'] = [
							'R' => 'Sun-ExtA.ttf',
							'sip-ext' => 'sun-extb', /* SIP=Plane2 Unicode (extension B) */
						];
						break;
					case 'sun-extb':
						$font_data['sun-extb'] = ['R' => 'Sun-ExtB.ttf'];
						break;
					case 'unbatang':
						$font_data['unbatang'] = ['R' => 'UnBatang_0613.ttf'];
						break;
				}
			}
		}

		$this->config['fontdata'] = apply_filters('woo_invo_ice_pdf_font_data', $font_data, $this->template);

		$this->config['allowCJKoverflow'] = true;
		$this->config['allow_charset_conversion'] = true;
		$this->config['autoLangToFont'] = true;
		$this->config['mode'] = '-aCJK';
		$this->config['format'] = $this->paper_size;
		$this->config['default_font_size'] = 10;

		$woo_invo_ice_default_fonts = '';
		global $locale;

		if ('bn_BD' == $locale) {
			$this->config['default_font'] = 'freeserif';
			$woo_invo_ice_default_fonts = $this->config['default_font'];
		} else {
			$this->config['default_font'] = get_option('wiopt_pdf_font_family', 'freeserif') ? 'freeserif' : '';
			$woo_invo_ice_default_fonts = $this->config['default_font'];
		}

		$this->config['default_font'] = apply_filters('woo_invo_ice_default_font_family_name', $woo_invo_ice_default_fonts);

		$default_page_no_style = ('0' != get_option('wiopt_page_number_style')) ? get_option('wiopt_page_number_style') : '1';

		$template_type = get_option('wiopt_add_page_number');
		if (1 == get_option('wiopt_enable_page_number') && count($template_type) > 0) {
			if (in_array('invoice', $template_type) && 'invoice' === $this->template) {
				$this->config['defaultPageNumStyle'] = $default_page_no_style;
			} elseif (in_array('packing_slip', $template_type) && 'packing_slip' === $this->template) {
				$this->config['defaultPageNumStyle'] = $default_page_no_style;
			}
		}

		$this->config['fontdata']['xbriyaz']['R'] = 'XB-Riyaz.ttf';
		$this->config['fontdata']['xbriyaz']['B'] = 'XB-RiyazBd.ttf';
		$this->config['fontdata']['xbriyaz']['I'] = 'XB-RiyazIt.ttf';
		$this->config['fontdata']['xbriyaz']['BI'] = 'XB-RiyazBdIt.ttf';
		$this->config['fontdata']['xb riyaz.ttf']['R'] = 'XB-Riyaz.ttf';

		return apply_filters('woo_invo_ice_mpdf_settings', $this->config);
	}

	/**
	 * Generate PDF
	 *
	 * @return string
	 */
	public function generatePDF() {
		// echo '<pre>';
		// print_r($this->config);
		// die;
		try {
			$mpdf               = new \Mpdf\Mpdf( $this->config );
			$mpdf->autoScriptToLang = true; //PHPCS:ignore
			$mpdf->baseScript       = 1;//PHPCS:ignore
			$mpdf->autoVietnamese   = true;//PHPCS:ignore
			$mpdf->autoArabic       = true;//PHPCS:ignore

			// $mpdf->fonttrans['freeserif'] = true;
			$mpdf->debug = get_option( 'wiopt_pdf_invoice_debug_mode' ) == '1' ? true : false;
			// Add Background Image.
			$water_mark = $this->get_watermark();
			if ( $water_mark ) {
				$mpdf->SetWatermarkImage(
					$water_mark['background'],
					$water_mark['opacity'],
					'P',
					'P'
				);
                $mpdf->showWatermarkImage = true;//PHPCS:ignore
			}

			$template = ucwords( str_replace( '_', ' ', $this->template ) );
			$order_id = '-' . $this->order_id;
			if ( strpos( $order_id, ',' ) !== false ) {
				$order_id = '';
				$template = $template . 's';
			}
			$mpdf->allow_charset_conversion = true;
			$mpdf->charset_in               = 'UTF-8';


			$mpdf->WriteHTML( $this->html);
            $invoice_no = ( ! empty(get_post_meta($this->order_id, 'wiopt_invoice_no_'.$this->order_id, true))) ? get_post_meta($this->order_id, 'wiopt_invoice_no_'.$this->order_id, true) : woo_invo_ice_get_invoice_number($this->order_id);
            $order = new WC_Order( $order_id );
            $items = $order->get_items();
            if ( 'save' == $this->template ) { // Save Invoice before email.
                $order = wc_get_order($this->order_id);
                if ( $order instanceof WC_Order && 'refunded' === $order->get_status() ) {
                    $filename = 'Credit-Note-Of-Invoice-' . $invoice_no;
	                $filename = apply_filters( 'woo_invo_ice_file_name', $filename, $this->template, $order_id );
                }else {
                    $filename = 'Invoice-' . $invoice_no;
	                $filename = apply_filters( 'woo_invo_ice_file_name', $filename, $this->template, $order_id );
                }


                $mpdf->Output( WOO_INVO_ICE_INVOICE_DIR . $filename . '.pdf', 'F' );
			} else {
                if ( 'save_packing_slip' == $this->template ) { // Save Invoice before email.
                    $filename = 'Packing-slip-' . $invoice_no;
                    $filename = apply_filters( 'woo_invo_ice_file_name', $filename, $this->template, $order_id );
                    return $mpdf->Output( WOO_INVO_ICE_INVOICE_DIR . $filename . '.pdf', 'F' );
                }
				$filename = $template .'-'. $invoice_no;
				$filename = apply_filters( 'woo_invo_ice_file_name', $filename, $this->template, $order_id );

				if ( get_option( 'wiopt_pdf_invoice_button_behaviour' ) == 'download' ) {
					$mpdf->Output( $filename . '.pdf', 'D' );
				} else {
					$mpdf->Output( $filename . '.pdf', 'I' );
				}
			}
		} catch ( \Mpdf\MpdfException $e ) { // Note: safer fully qualified exception name used for catch.
			// Process the exception, log, print etc.
			if ( 'save' !== $this->template ) {
                $pdf_error_message = esc_attr($e->getMessage());
                if ( strpos($pdf_error_message, 'Cannot find TTF') !== false ) {
                    $doc_link = '<a target="_blank" href="'.admin_url() .'admin.php?page=astama-woo-invo-ice-settings">' .esc_html('Go to setting page').'</a>'; //phpcs:ignore
                    if( is_admin() ) {
                        echo $pdf_error_message. '<h3><br/>' . __('Please go to Woo Invoice plugins setting page to download the missing fonts. '.$doc_link, 'astama-pdf-invoice-for-woocommerce') . '</h3>'; //phpcs:ignore
                    }else{
                        echo $pdf_error_message. '<h4><br/>' . __('Please notify site admin to download invoice.', 'astama-pdf-invoice-for-woocommerce') . '</h4>'; //phpcs:ignore
                    }
                } else {
                    echo esc_attr($e->getMessage());
                }
			}
		}
	}

	/**
	 * Get Water mark image
	 *
	 * @return array|bool
	 */
	private function get_watermark() {
		$background = '';
		$opacity    = '';
		if ( 'invoice' == $this->template || 'credit_note' == $this->template || 'save' == $this->template ) {
			if ( get_option( 'wiopt_enable_invoice_background' ) == '1' ) {
				$opacity = ( get_option( 'wiopt_invoice_background_opacity' ) ) ? get_option( 'wiopt_invoice_background_opacity' ) : .3;
				if ( get_option( 'wiopt_enable_invoice_background' ) ) {
					$background = get_option( 'wiopt_invoice_background_attachment_id' );
				}
			}
		} elseif ( 'packing_slip' == $this->template || 'save_packing_slip' == $this->template ) {
			if ( get_option( 'wiopt_enable_packingslip_background' ) == '1' ) {
				$opacity = get_option( 'wiopt_packingslip_background_opacity' ) != false && ! empty( 'wiopt_packingslip_background_opacity' ) ? get_option( 'wiopt_packingslip_background_opacity' ) : 1;
				if ( get_option( 'wiopt_enable_packingslip_background' ) != false && ! empty( get_option( 'wiopt_packingslip_background_attachment_id' ) ) ) {
					$background = get_option( 'wiopt_packingslip_background_attachment_id' );
				}
			}
		}

		if ( ! empty( $background ) ) {

			return array(
				'background' => $background,
				'opacity'    => $opacity,
			);
		}

		return false;
	}

	/**
	 * Get Template Paper Size
	 *
	 * @param  string|array $paper_size Paper Size.
	 * @return mixed
	 */
	private function paper_size( $paper_size ) {
		if ( 'invoice' == $this->template || 'credit_note' == $this->template ) {
			if ( false != get_option( 'wiopt_invoice_paper_size' ) && ! empty( get_option( 'wiopt_invoice_paper_size' ) ) && 'custom' != get_option( 'wiopt_invoice_paper_size' ) ) {
				$this->paper_size = get_option( 'wiopt_invoice_paper_size' );
			} elseif ( false != get_option( 'wiopt_invoice_paper_size' ) && ! empty( get_option( 'wiopt_invoice_paper_size' ) ) && 'custom' == get_option( 'wiopt_invoice_paper_size' ) ) {
				$this->paper_size = array();
				array_push( $this->paper_size, get_option( 'wiopt_invoice_custom_paper_wide' ) );
				array_push( $this->paper_size, get_option( 'wiopt_invoice_custom_paper_height' ) );
			} else {
				$this->paper_size = 'A4';
			}
		} elseif ( 'packing_slip' == $this->template || 'save' == $this->template ) {
			if ( false != get_option( 'wiopt-pickingslip-paper-size' ) && ! empty( get_option( 'wiopt-pickingslip-paper-size' ) ) && 'custom' != get_option( 'wiopt-pickingslip-paper-size' ) ) {
				$this->paper_size = get_option( 'wiopt-pickingslip-paper-size' );
			} elseif ( false != get_option( 'wiopt-pickingslip-paper-size' ) && ! empty( get_option( 'wiopt-pickingslip-paper-size' ) ) && 'custom' == get_option( 'wiopt-pickingslip-paper-size' ) ) {
				$this->paper_size = array();
				array_push( $this->paper_size, get_option( 'wiopt_pickingslip_custom_paper_wide' ) );
				array_push( $this->paper_size, get_option( 'wiopt_pickingslip_custom_paper_height' ) );
			} else {
				$this->paper_size = 'A4';
			}
		} elseif ( 'label' == $this->template ) {
			$paper_sizes = array( 'A3', 'A4', 'A5', 'Letter' );
			if ( in_array( $paper_size, $paper_sizes ) ) {
				$this->paper_size = $paper_size;
			} else {
				$this->paper_size = explode( ',', $paper_size );
			}
		}
		return apply_filters( 'woo_invo_ice_paper_size', $this->paper_size, $this->template );
	}
}

/**
 * Init PDF Class
 *
 * @param string    $html       Template Content.
 * @param string    $file_name  File Name.
 * @param int|array $order_id   Order Ids.
 * @param string    $template   Template type.
 * @param string    $paper_size Paper Size.
 *
 * @return Woo_Invo_Ice_PDF
 */
function woo_invo_ice_pdf( $html, $file_name, $order_id, $template, $paper_size = 'A4' ) {
	return new Woo_Invo_Ice_PDF( $html, $file_name, $order_id, $template, $paper_size );
}
