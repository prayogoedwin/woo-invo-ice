<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

use Kunnu\Dropbox\Dropbox;
use Kunnu\Dropbox\DropboxApp;
use Kunnu\Dropbox\DropboxFile;
use Kunnu\Dropbox\DropboxClient;
use Kunnu\Dropbox\Exceptions\DropboxClientException;
use Automattic\WooCommerce\Internal\DataStores\Orders\CustomOrdersTableController;
use Automattic\WooCommerce\Utilities\OrderUtil;

use Woo_Invo_Ice\GenerateQrCode;
use Woo_Invo_Ice\Tag;

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
 * This class responsible for maintaining and registering all hooks that power
 * the plugin.
 *
 * @since      1.0.0
 * @package    Woo_Invo_Ice
 * @subpackage Woo_Invo_Ice/includes
 * @author     Md Ohidul Islam <wahid@astama.com>
 */
class Woo_Invo_Ice_Hooks {

	/**
	 * Woo_Invo_Ice_Hooks constructor.
	 */
	public function __construct() {


		if ( '0' !== get_option( 'wiopt_invoicing' ) ) {

			// ####################### MY ACCOUNT ACTIONS ############################
			// Add Download Invoice button to My Account Order List Page.
			if ( ! empty( get_option( 'wiopt_download' ) ) ) {
				add_filter(
					'woocommerce_my_account_my_orders_actions',
					array(
						$this,
						'add_my_account_order_action_download_invoice',
					),
					10,
					2
				);

				// Redirect to new tab
				if ( 'new_tab' == get_option( 'wiopt_pdf_invoice_button_behaviour' ) || empty( 'new_tab' == get_option( 'wiopt_pdf_invoice_button_behaviour' ) ) ) {
					add_action( 'woocommerce_after_account_orders', array( $this, 'action_after_account_orders_js' ) );
				}
			}

			if ( ! empty( get_option( 'wiopt_download' ) ) ) {
				add_filter(
					'woocommerce_my_account_my_orders_actions',
					array(
						$this,
						'add_my_account_order_action_download_credit_note',
					),
					1,
					2
				);

				// Redirect to new tab
				if ( 'new_tab' == get_option( 'wiopt_pdf_invoice_button_behaviour' ) || empty( 'new_tab' == get_option( 'wiopt_pdf_invoice_button_behaviour' ) ) ) {
					add_action( 'woocommerce_after_account_orders', array(
						$this,
						'action_after_account_orders_credit_note_js',
					) );
				}
			}
			// Add Download Button in Order View Page.
			if ( ! empty( get_option( 'wiopt_download' ) ) ) {
				add_action(
					'woocommerce_order_details_after_order_table',
					array(
						$this,
						'add_my_account_order_view_action_download_invoice',
					)
				);
			}
			// Add Download Button for credit note in Order View Page.
			if ( ! empty( get_option( 'wiopt_download' ) ) ) {
				add_action(
					'woocommerce_order_details_after_order_table',
					array(
						$this,
						'add_my_account_order_view_action_download_credit_note',
					)
				);
			}

			// ####################### EMAIL ACTIONS ##########################################


			// Add invoice number to order.
			add_action( 'woocommerce_new_order', array( $this, 'add_invoice_number_to_order' ), 999 );

			// Add QR code to email template.
			if ( get_option( 'wiopt_display_qr_code_email_template' ) ) {
				add_action( 'woocommerce_email_before_order_table', [ $this, 'add_qr_code_to_email' ], 10, 4 );

			}


			/**
			 * Added from 4.0.13
			 */
			add_action( 'woocommerce_checkout_update_order_meta', function ( $order_id, $posted ) {
				update_post_meta( $order_id, 'woo_invo_ice_order_lang', get_locale() );
			}, 10, 2 );

			// Filter to add Invoice attachment with order email.
			if ( ! empty( get_option( 'wiopt_order_email' ) ) ) {
				add_filter( 'woocommerce_email_attachments', array( $this, 'attach_invoice_to_order_email' ), 90, 4 );
				// Filter to add Invoice download link with order email.
				// add_filter( 'woocommerce_email_after_order_table', array( $this, 'add_invoice_download_link' ), 91, 4 ); !
			}

			// ####################### ADMIN ORDER EDIT PAGE META BOX ############################
			// Add Custom MetaBox for PDF Download Button.
			add_action( 'add_meta_boxes', array( $this, 'add_custom_meta_box' ) );

			// ####################### ADMIN ORDER LIST TABLE ACTIONS ############################
			// Register Admin order list table bulk actions.
			add_filter( 'bulk_actions-edit-shop_order', array( $this, 'register_admin_order_list_bulk_actions' ), 11 );
			// Handle bulk Invoice Making action.
			add_filter( 'handle_bulk_actions-edit-post', array( $this, 'invoice_bulk_action_handler' ), 10, 3 );
			// Add admin Order list actions buttons.
			add_action(
				'woocommerce_admin_order_actions_end',
				array(
					$this,
					'register_admin_order_list_actions_buttons',
				)
			);

			// Logo selector modal.
			add_action( 'admin_footer', array( $this, 'logo_selector_print_scripts' ) );

			// ###########################################################
			// #################### VAT & SSN Field Actions ##############
			// ###########################################################
			// My Account Profile Fields.
			add_action(
				'woocommerce_edit_account_form',
				array(
					$this,
					'woo_invo_ice_add_vat_ssn_to_edit_account_form',
				)
			);
			add_action( 'woocommerce_save_account_details', array( $this, 'woo_invo_ice_save_vat_ssn_account_details' ), 12, 1 );
			// Admin User Edit Page.
			add_action( 'show_user_profile', array( $this, 'woo_invo_ice_wp_vat_ssn_fields' ) );
			add_action( 'edit_user_profile', array( $this, 'woo_invo_ice_wp_vat_ssn_fields' ) );
			// User Edit Page.
			add_action( 'personal_options_update', array( $this, 'woo_invo_ice_wp_vat_ssn_fields_save' ) );
			add_action( 'edit_user_profile_update', array( $this, 'woo_invo_ice_wp_vat_ssn_fields_save' ) );
			// Checkout Fields.
			add_filter( 'woocommerce_billing_fields', array( $this, 'woo_invo_ice_add_checkout_field' ) );
			add_action(
				'woocommerce_checkout_update_order_meta',
				array(
					$this,
					'woo_invo_ice_checkout_field_update_order_meta',
				)
			);

			// ###########################################################
			// #################### PLUGIN SETTINGS Actions ##############
			// ###########################################################
			// Get Product Show Status.
			add_action(
				'wp_ajax_wiopt_get_product_dimension_show',
				array(
					$this,
					'woo_invo_ice_get_product_dimension_show',
				)
			);
			// Enable Product Dimension Show.
			add_action(
				'wp_ajax_wiopt_invoice_product_dimension_show',
				array(
					$this,
					'woo_invo_ice_product_dimension_show',
				)
			);
			// Get Product Attribute Show Status.
			add_action(
				'wp_ajax_wiopt_get_product_attribute_show',
				array(
					$this,
					'woo_invo_ice_get_product_attribute_show',
				)
			);

			// Get Product Attribute Show Status.
			add_action(
				'wp_ajax_wiopt_get_product_column_show',
				array(
					$this,
					'woo_invo_ice_get_product_column',
				)
			);
			// Enable Product Attribute Show.
			add_action(
				'wp_ajax_wiopt_product_attribute_show',
				array(
					$this,
					'woo_invo_ice_product_attribute_show',
				)
			);

			// Selcet Product column.
			add_action(
				'wp_ajax_wiopt_select_product_column',
				array(
					$this,
					'wiopt_select_product_column',
				)
			);

			// Get Product column for packing slip.
			add_action(
				'wp_ajax_get_wiopt_packingslip_product_table_header',
				array(
					$this,
					'get_wiopt_packingslip_product_table_header',
				)
			);

			// Selcet Product column for packing slip.
			add_action(
				'wp_ajax_wiopt_packingslip_product_table_header',
				array(
					$this,
					'wiopt_packingslip_product_table_header',
				)
			);

			// Save csv fields.
			add_action(
				'wp_ajax_wiopt_save_csv_fields',
				array(
					$this,
					'woo_invo_ice_wiopt_save_csv_fields',
				)
			);

			// Get bulk download csv fields.
			add_action(
				'wp_ajax_wiopt_get_csv_fields_show',
				array(
					$this,
					'woo_invo_ice_get_csv_fields_show',
				)
			);

			// Get paid stamp show status.
			add_action( 'wp_ajax_wiopt_paid_stamp_enabled', array( $this, 'woo_invo_ice_paid_stamp_enabled' ) );
			// Enable paid stamp show.
			add_action( 'wp_ajax_wiopt_save_paid_stamp', array( $this, 'woo_invo_ice_save_paid_stamp' ) );
			// Save Invoice Template.
			add_action( 'wp_ajax_wiopt_save_pdf_template', array( $this, 'woo_invo_ice_save_pdf_template' ) );

			// ################## PLUGIN HACKS ######################
			add_action( 'wp_ajax_wiopt_save_review_notice', array( $this, 'woo_invo_ice_save_review_notice' ) );
			add_filter(
				'plugin_action_links_' . plugin_basename( __FILE__ ),
				array(
					$this,
					'woo_invo_ice_plugin_action_links',
				)
			);

			// ################## WCFM Marketplace Hooks ######################

			if ( class_exists( 'WCFM' ) ) {
				add_filter(
					'wcfm_orders_module_actions',
					array(
						$this,
						'wcfm_orders_module_actions_callback',
					),
					10,
					3
				);
			}
			// ################## WCFM Marketplace Hooks  End ######################


			// ################## Dropbox Api ######################

			// Download Custom Fonts.
			add_action( 'wp_ajax_woo_invo_ice_font_download_ajax', array( $this, 'woo_invo_ice_font_download_ajax' ) );

			// Check DropBox API is exist.
			add_action( 'wp_ajax_woo_invo_ice_dropboxapi_callback', array( $this, 'woo_invo_ice_dropboxapi_callback' ) );

			// Download DropBox API.
			add_action( 'wp_ajax_woo_invo_ice_dropboxapi_download', array( $this, 'woo_invo_ice_dropboxapi_download' ) );

			// If one order is completed, upload previous order as pdf to dropbox.
			add_action( 'woocommerce_order_status_completed', array(
				$this,
				'woo_invo_ice_upload_completed_order_to_dropbox',
			), 101, 1 );

			// On input check dropbox folder exist or not.
			add_action( 'wp_ajax_woo_invo_ice_check_dropbox_folder_exist', array(
				$this,
				'woo_invo_ice_check_dropbox_folder_exist',
			) );
			// ################## Dropbox Api End ######################

			// ################## Billing Address ######################
			add_filter( 'woo_invo_ice_billing_info', array( $this, 'woo_invo_ice_billing_info_callback' ), 20, 3 );


			// ################## After order note   ######################
			if ( is_plugin_active( 'seed-confirm-pro/seed-confirm-pro.php' ) ) {
				add_action( 'woo_invo_ice_after_customer_notes', array(
					$this,
					'woo_invo_ice_add_slip_to_order_note',
				), 99, 2 );
			}

			if ( is_plugin_active( 'wc-confirm-payment/woocommerce-confirm-payment.php' ) ) {
				add_action( 'woo_invo_ice_after_customer_notes', array(
					$this,
					'woo_invo_ice_add_payment_slip_to_order_note',
				), 99, 2 );
			}
		}
		// response to is document printed.
		add_action( 'wp_ajax_woo_invo_ice_is_document_printed', [ $this, 'woo_invo_ice_is_document_printed' ] );
		// add script to footer for configuring about is document printed or not
		add_action( 'admin_print_footer_scripts', function () {
			?>
            <script type="text/javascript">
                function woo_invo_ice_save_is_document_printed(...parems) {
                    let arr = parems[0].split(',')
                    let buttonClass = arr[3] // button class
                    let order_id = arr[2] // order id
                    let url = arr[1] // url
                    let nonce = arr[0] // nonce
                    let buttonBehaviour = arr[4] // button behaviour example new_tab/download.
                    which = buttonClass;
                    let button = document.getElementById(buttonClass)
                    button.addEventListener('click', function (e) {
                        e.preventDefault();
                        wp.ajax.post('woo_invo_ice_is_document_printed', {
                            _wpnonce: nonce,
                            which: which,
                            order_id: parseInt(order_id),
                        });
                    })
                    button.click();
                    url = url.replace(/&amp;/g, '&');
                    if (buttonBehaviour == 'new_tab') {
                        window.open(url, 'blank')
                    } else {
                        window.open(url)
                    }
                }

            </script><?php
		}, 99 );

	}

	/**
	 * Callback for download custom fonts
	 *
	 * @return string
	 */
	public function woo_invo_ice_font_download_ajax() {
		// Check valid request form user.
		check_ajax_referer( 'wiopt_pdf_nonce' );

		$get_font_content = file_get_contents( $_FILES["file"]["tmp_name"] ); // phpcs:ignore
		$file_name        = sanitize_text_field( wp_unslash( $_FILES["file"]["name"] ) ); // phpcs:ignore
		if ( ! empty( $get_font_content ) ) {
			// Make lowercase uploaded file name.
			$get_name_lowercase = strtolower( $file_name );
			// Get last extension of file name.
			$get_last_extension = strrchr( $get_name_lowercase, '.' );
			// Check last name if zip.
			if ( '.zip' !== $get_last_extension ) {
				file_put_contents( WOO_INVO_ICE_FONT_DIR . $file_name, $get_font_content );
				$key = strtolower( $file_name );

				// Initialize variables before using them.
				$regular      = $file_name;
				$bold         = $file_name;
				$italic       = $file_name;
				$bold_italic  = $file_name;

				// Update variables based on conditions.
				if ( strpos( $file_name, 'regular' ) ) {
					$regular = $file_name;
				} else if ( strpos( $file_name, 'Bold' ) ) {
					$bold = $file_name;
				} else if ( strpos( $file_name, 'Italic' ) ) {
					$italic = $file_name;
				} else if ( strpos( $file_name, 'BoldItalic' ) ) {
					$bold_italic = $file_name;
				}

				$custom_font_list = array(
					$key => array(
						'R'  => $regular,
						'B'  => $bold,
						'I'  => $italic,
						'BI' => $bold_italic,
					),
				);

				$old_value = get_option( 'wiopt_custom_font_list' ) != '' ? get_option( 'wiopt_custom_font_list' ) : [];
				$data      = array_merge( $old_value, $custom_font_list );

				update_option( 'wiopt_custom_font_list', $data );

				$response = array(
					'font_name' => $file_name,
				);
				wp_send_json_success( $response );
				wp_die();
			} else {
				// Upload zip file.
				file_put_contents( WOO_INVO_ICE_FONT_DIR . $file_name, $get_font_content );
				// Extract Zip file.
				if ( class_exists( 'ZipArchive' ) ) {
					$zip = new ZipArchive();
					if ( $zip->open( WOO_INVO_ICE_FONT_DIR . $file_name ) === true ) {
						$zip->extractTo( WOO_INVO_ICE_FONT_DIR );
						$zip->close();
						unlink( WOO_INVO_ICE_FONT_DIR . $file_name );
						$response = array(
							'font_name' => $file_name,
						);
						wp_send_json_success( $response );
						wp_die();
					}
				} else {
					wp_send_json_error( 'Please enable ZipArchive php extension to extract zip file.' );
					wp_die();
				}
			}
		}
		wp_send_json_success();
		wp_die();
	}

	/**
	 * Check if dropboxapi folder is downloaded.
	 */
	public function woo_invo_ice_dropboxapi_callback() {
		// DropBox SDK path to save.
		$file_dir = WOO_INVO_ICE_INVOICE_DIR . '/dropboxapi/';

		$count_size = 0;

		$count = 0;
		// Check DropBox SDK is already exists or not. if exist then count size.
		if ( file_exists( $file_dir . 'composer.json' ) ) {
			$dir_array = scandir( $file_dir );
			foreach ( $dir_array as $key => $filename ) {
				if ( ( '..' != $filename ) && ( '.' != $filename ) ) {
					if ( is_dir( $file_dir . '/' . $filename ) ) {
						$new_foldersize = filesize( $file_dir . '/' . $filename );
						$count_size     = $count_size + $new_foldersize;
					} elseif ( is_file( $file_dir . '/' . $filename ) ) {
						$count_size = $count_size + filesize( $file_dir . '/' . $filename );
						$count ++;
					}
				}
			}
			if ( $count_size > 0 ) {
				wp_send_json_success( $count_size );
				wp_die();
			}
		}

		wp_send_json_error();
		wp_die();

	}


	/**
	 * Callback for download DropBox API
	 * @see  https://github.com/kunalvarma05/dropbox-php-sdk
	 */
	public function woo_invo_ice_dropboxapi_download() {
		// DropBox SDK URL.
		$drop_box_url = esc_url( 'https://github.com/astamahasan/invoice/raw/main/dropboxapi.zip' );

		// Get SDK name from url
		$api = basename( $drop_box_url );

		// Save api to defined directory
		$get_font_content = file_get_contents( $drop_box_url );
		if ( ! empty( $get_font_content ) ) {
			$start_time = microtime( true );
			file_put_contents( WOO_INVO_ICE_INVOICE_DIR . $api, $get_font_content );
			$end_time      = microtime( true );
			$download_time = $end_time - $start_time;
			$zip           = new ZipArchive();
			if ( $zip->open( WOO_INVO_ICE_INVOICE_DIR . $api ) === true ) {
				$zip->extractTo( WOO_INVO_ICE_INVOICE_DIR );
				$zip->close();
				unlink( WOO_INVO_ICE_INVOICE_DIR . $api );
				$response = array(
					'time'        => $download_time,
					'folder_name' => $api,
				);
				wp_send_json_success( $response );
				wp_die();

			}
		}
		wp_send_json_error();
		wp_die();
	}

	/**
	 * Upload completed order to dropbox.
	 *
	 * @param $order_id .
	 *
	 * @see  https://github.com/kunalvarma05/dropbox-php-sdk
	 */
	public function woo_invo_ice_upload_completed_order_to_dropbox( $order_id ) {

		if ( '' != $order_id ) {
			// Enable Dropbox api to upload pdf.
			if ( '1' == get_option( 'wiopt_pdf_invoice_upload_to_dropbox' ) && file_exists( WOO_INVO_ICE_INVOICE_DIR . '/dropboxapi/composer.json' ) ) {

				$client_id     = get_option( 'wiopt_invoice_dropboxapi_client_id' );
				$client_secret = get_option( 'wiopt_invoice_dropboxapi_client_secret' );
				$access_token  = get_option( 'wiopt_invoice_dropboxapi_access_token' );
				$dropbox_dir   = get_option( 'wiopt_invoice_dropboxapi_folder_path' );

				// Check DropBox credentials data is given.
				if ( ( isset( $client_id ) && ( '' != $client_id ) ) && ( isset( $client_secret ) && ( '' != $client_secret ) ) && ( isset( $access_token ) && ( '' != $access_token ) ) ) {
					// Custom folder.
					if ( '' != $dropbox_dir ) {
						$this->upload_invoice_to_dropbox( $client_id, $client_secret, $access_token, '', $dropbox_dir );
					} else {
						$this->upload_invoice_to_dropbox( $client_id, $client_secret, $access_token );
					}
				}
			}
		}

	}

	/**
	 * Verify dropbox credentials and push completed order to dropbox.
	 *
	 * @param $client_id
	 * @param $client_secret
	 * @param $access_token
	 * @param string $local_dir
	 * @param string $dropbox_dir
	 */

	public function upload_invoice_to_dropbox( $client_id, $client_secret, $access_token, $local_dir = '', $dropbox_dir = '' ) {

		/**
		 * Configure Dropbox Application
		 *
		 * @param string $client_id Application Client ID
		 * @param string $client_secret Application Client Secret
		 * @param string $access_token Access Token
		 */
		$app = new DropboxApp( $client_id, $client_secret, $access_token );

		/**
		 * Configure Dropbox service.
		 *
		 * @param \Kunnu\Dropbox\DropboxApp
		 * @param array $config Configuration Array
		 */
		$dropbox = new Dropbox( $app );
		// Get all file from local directory.
		if ( '' != $local_dir ) {
			$file_dir = WOO_INVO_ICE_INVOICE_DIR . $local_dir;
		} else {
			$file_dir = WOO_INVO_ICE_INVOICE_DIR;
		}

		$files = array();
		$dir   = dir( $file_dir );
		while ( $file = $dir->read() ) {
			if ( ( '.' != $file ) && ( '..' != $file ) ) {
				$ext = explode( '.', $file );
				if ( end( $ext ) === 'pdf' ) {
					$files[] = $file;
				}
			}
		}
		$dir->close();

		try {
			foreach ( $files as $file ) {
				$file_path = WOO_INVO_ICE_INVOICE_DIR . $local_dir . $file;
				if ( '' != $local_dir ) {
					$file_path = WOO_INVO_ICE_INVOICE_DIR . $local_dir . $file;
				} else {
					$file_path = WOO_INVO_ICE_INVOICE_DIR . $file;
				}
				// File to Upload
				$file_name = basename( $file_path );
				/**
				 * Create Dropbox File from Path
				 *
				 * @param string $file_path Path of the file to upload
				 * @param string $mode The type of access
				 */
				$dropbox_file = new DropboxFile( $file_path );
				// Upload the file to Dropbox
				if ( '' != $dropbox_dir ) {

					try {

						$dropbox->createFolder( '/' . $dropbox_dir );

						$uploaded_file = $dropbox->upload( $dropbox_file, '/' . $dropbox_dir . '/' . $file_name, array( 'autorename' => false ) );

					} catch ( Exception $e ) {
						// upload pdf to given folder.
						$uploaded_file = $dropbox->upload( $dropbox_file, '/' . $dropbox_dir . '/' . $file_name, array( 'autorename' => false ) );

					}
				} else {
					// upload pdf to dropbox main directory.
					$uploaded_file = $dropbox->upload( $dropbox_file, '/' . $file_name, array( 'autorename' => false ) );
				}
			}
		} catch ( DropboxClientException $e ) {

			echo $e->getMessage(); //phpcs:ignore

		}
	}

	/**
	 * @return void
	 * @throws DropboxClientException
	 */
	public function woo_invo_ice_check_dropbox_folder_exist() {
		if ( '1' == get_option( 'wiopt_pdf_invoice_upload_to_dropbox' ) ) {

			$client_id     = get_option( 'wiopt_invoice_dropboxapi_client_id' );
			$client_secret = get_option( 'wiopt_invoice_dropboxapi_client_secret' );
			$access_token  = get_option( 'wiopt_invoice_dropboxapi_access_token' );
			$app           = new DropboxApp( $client_id, $client_secret, $access_token );
			$dropbox       = new Dropbox( $app );
			try {
				// Check nonce validation
				check_ajax_referer( 'wpif_drobox_api_nonce' );

				$folder_name = isset( $_POST['fName'] ) ? trim( sanitize_text_field( wp_unslash( $_POST['fName'] ) ), '/' ) : '';

				$dropbox->getMetadata( '/' . $folder_name );
				wp_send_json_success();
				wp_die();
			} catch ( Exception $e ) {
				wp_send_json_error();
				wp_die();
			}
		}
	}

	/**
	 * @param $actions
	 * @param $order_id
	 * @param WC_Order $order
	 *
	 * @return string
	 */
	public function wcfm_orders_module_actions_callback( $actions, $order_id, $order ) {
		$order_id    = $order->get_id(); //phpcs:ignore
		$user        = get_current_user_id();
		$url         = wp_nonce_url( admin_url( 'admin-ajax.php?action=wiopt_generate_invoice_packing_slip&order_id=' . $order_id . '&vendor=' . $user ), 'wiopt_pdf_nonce' );
		$text        = __( 'Packing Slip', 'astama-pdf-invoice-for-woocommerce' );
		$class       = 'tips parcial wiopt_invoice_packing_slip wiopt_button_invoice_packing_slip';
		$src         = WOO_INVO_ICE_PLUGIN_URL . 'admin/images/shipping_list.svg';
		$image_class = 'width: 19px;margin-top: 4px;margin-left: 3px;';

		$actions .= '<a style="margin-bottom:-8px" target="_blank" href="' . esc_url( $url ) . '" class="wcfm-action-icon ' . esc_html( $class ) . '" data-tip="' . esc_html( $text ) . '" title="' . esc_html( $text ) . '"> <img src="' . esc_url( $src ) . '" style="' . esc_html( $image_class ) . '"/></a>';

		return $actions;

	}

	/**
	 * Add Download Invoice button into My Account Order Actions for Customer
	 *
	 * @param array $actions My Account Order List table actions.
	 * @param WC_Order $order Order Object.
	 *
	 * @return mixed
	 */
	public function add_my_account_order_action_download_invoice( $actions, $order ) {

		$order_id                          = $order->get_id();
		$wiopt_invoice_download_check_list = ( get_option( 'wiopt_invoice_download_check_list' ) == false || is_null( get_option( 'wiopt_invoice_download_check_list' ) ) ) ? array() : get_option( 'wiopt_invoice_download_check_list' );
		$output_type                       = ( get_option( 'wiopt_output_type_html' ) ) ? '&output=html' : '';

		if ( in_array( 'always_allow', $wiopt_invoice_download_check_list ) ) {
			$actions['wiopt-my-account-invoice'] = array(
				'url'   => wp_nonce_url( admin_url( 'admin-ajax.php?action=wiopt_generate_invoice&order_id=' . $order_id . $output_type ), 'wiopt_pdf_nonce' ),
				'name'  => __( 'Download Invoice', 'astama-pdf-invoice-for-woocommerce' ),
				'class' => 'wiopt_invoice_action_button',
			);

		} elseif ( woo_invo_ice_is_current_status_checked( $order->get_status(), $wiopt_invoice_download_check_list ) ) {
			$actions['wiopt-my-account-invoice'] = array(
				'url'   => wp_nonce_url( admin_url( 'admin-ajax.php?action=wiopt_generate_invoice&order_id=' . $order_id . $output_type ), 'wiopt_pdf_nonce' ),
				'name'  => __( 'Download Invoice', 'astama-pdf-invoice-for-woocommerce' ),
				'class' => 'wiopt_invoice_action_button',
			);
		}


		return $actions;
	}

	/**
	 * Add Download Invoice button into My Account Order Actions for Customer
	 *
	 * @param array $actions My Account Order List table actions.
	 * @param WC_Order $order Order Object.
	 *
	 * @return mixed
	 */
	public function add_my_account_order_action_download_credit_note( $actions, $order ) {

		$order_id                          = $order->get_id();
		$wiopt_invoice_download_check_list = ( get_option( 'wiopt_invoice_download_check_list' ) == false || is_null( get_option( 'wiopt_invoice_download_check_list' ) ) ) ? array() : get_option( 'wiopt_invoice_download_check_list' );

		$output_type = ( get_option( 'wiopt_output_type_html' ) ) ? '&output=html' : '';
		if ( $order->get_total_refunded() > 0 || 'refunded' === $order->get_status() ) {
			$actions['wiopt-my-account-credit-note'] = array(
				'url'   => wp_nonce_url( admin_url( 'admin-ajax.php?action=wiopt_generate_credit_note&order_id=' . $order_id . '&template=credit_note' . $output_type ), 'wiopt_pdf_nonce' ),
				'name'  => __( 'Credit Note', 'astama-pdf-invoice-for-woocommerce' ),
				'class' => 'wiopt_invoice_action_button',
			);
		}

		return $actions;
	}

	/**
	 * Js load after order action.
	 * only for invoice;
	 */
	public function action_after_account_orders_js() {
		$action_slug = 'wiopt-my-account-invoice';
		?>
        <script>
            jQuery(function ($) {
                $('a.<?php echo esc_html( $action_slug ); ?>').each(function () {
                    $(this).attr('target', '_blank');
                })
            });
        </script>
		<?php
	}

	/**
	 * Js load after order action.
	 * only for credit note;
	 */
	public function action_after_account_orders_credit_note_js() {
		$action_slug = 'wiopt-my-account-credit-note';
		?>
        <script>
            jQuery(function ($) {
                $('a.<?php echo esc_html( $action_slug ); ?>').each(function () {
                    $(this).attr('target', '_blank');
                })
            });
        </script>
		<?php
	}

	/**
	 * JavaScript code for printing.
	 */
	private function printButtonScript( $url ) {
		?>
        <script>
            /**
             * Initiate printing using a hidden iframe.
             *
             * @param {string} url - The URL to be printed.
             */
            function printButton(url) {
                // Create a hidden iframe
                const iframe = document.createElement('iframe');
                iframe.style.display = 'none';
                document.body.appendChild(iframe);

                // Set the iframe source to the URL
                iframe.src = url;

                // When the iframe is loaded, initiate the print
                iframe.onload = function () {
                    iframe.contentWindow.print();
                };
            }

            /**
             * Toggle the visibility of the print options dropdown.
             *
             * @param {HTMLElement} button - The button triggering the action.
             */
            function togglePrintOptionsDropdown(button) {
                const dropdown = button.nextElementSibling;
                if (dropdown.style.display === 'none') {
                    closePrintOptionsDropdowns();
                    dropdown.style.display = 'block';
                } else {
                    dropdown.style.display = 'none';
                }
            }

            /**
             * Close all print options dropdowns.
             */
            function closePrintOptionsDropdowns() {
                const dropdowns = document.querySelectorAll('.wiopt-print-option');
                dropdowns.forEach(function(dropdown) {
                    dropdown.style.display = 'none';
                });
            }
        </script>
		<?php
	}


	/**
	 * Add download invoice button in order view actions.
	 *
	 * @param WC_Order $order Order Object.
	 */
	public function add_my_account_order_view_action_download_invoice( $order ) {
		$order_id                          = $order->get_id();
		$wiopt_invoice_download_check_list = ( get_option( 'wiopt_invoice_download_check_list' ) == false || is_null( get_option( 'wiopt_invoice_download_check_list' ) ) ) ? array() : get_option( 'wiopt_invoice_download_check_list' );
		$output_type                       = ( get_option( 'wiopt_output_type_html' ) ) ? '&output=html' : '';
		if ( in_array( 'always_allow', $wiopt_invoice_download_check_list )
		     || woo_invo_ice_is_current_status_checked( $order->get_status(), $wiopt_invoice_download_check_list )
		     || ( $order->is_paid() && in_array( 'payment_complete', $wiopt_invoice_download_check_list ) )
		) {
			if ( $order->get_customer_id() ) {
				// Download Invoice Button
				$download_invoice_text = get_option( 'wiopt_DOWNLOAD_INVOICE_TEXT' );
				$download_invoice_text = ( $download_invoice_text ) ? $download_invoice_text : 'Download Invoice';
				$download_url = wp_nonce_url( admin_url( 'admin-ajax.php?action=wiopt_generate_invoice&order_id=' . $order_id . $output_type ), 'wiopt_pdf_nonce' );

				// Print Invoice Button
				$print_invoice_text = get_option( 'wiopt_PRINT_INVOICE_TEXT' );
				$print_invoice_text = ( $print_invoice_text ) ? $print_invoice_text : 'Print Invoice';
				$print_url = wp_nonce_url( admin_url( 'admin-ajax.php?action=wiopt_generate_invoice&order_id=' . $order_id . '&print=true' . $output_type ), 'wiopt_pdf_nonce' );

				// Function for print option
				$this->printButtonScript( $print_url );
				?>
                <a class="woocommerce-button button wiopt-my-account-invoice" href="<?php echo esc_url( $download_url ); ?>" target="_blank" style="margin-bottom: 10px;">
                    <span class="dashicons dashicons-download"></span><?php echo esc_attr__( 'Invoice', 'astama-pdf-invoice-for-woocommerce' ); ?>
                </a>
                <a class="woocommerce-button button wiopt-my-account-invoice" onclick="printButton('<?php echo esc_js( $print_url ); ?>')" style="margin-bottom: 10px;">
                    <span class="dashicons dashicons-printer"></span><?php echo esc_attr__( 'Invoice', 'astama-pdf-invoice-for-woocommerce' ); ?>
                </a>
				<?php
			}
		}
	}

	/**
	 * Add Download Button for credit note in Order View Page.
	 *
	 * @param WC_Order $order Order Object.
	 */
	public function add_my_account_order_view_action_download_credit_note( $order ) {
		$order_id                          = $order->get_id();
		$wiopt_invoice_download_check_list = ( get_option( 'wiopt_invoice_download_check_list' ) == false || is_null( get_option( 'wiopt_invoice_download_check_list' ) ) ) ? array() : get_option( 'wiopt_invoice_download_check_list' );
		$output_type                       = ( get_option( 'wiopt_output_type_html' ) ) ? '&output=html' : '';
		if ( ( 'refunded' === $order->get_status() && in_array( 'refunded', $wiopt_invoice_download_check_list ) )
		     || in_array( 'always_allow', $wiopt_invoice_download_check_list )
		     || $order->get_total_refunded() > 0
		) {
			if ( $order->get_customer_id() ) {
				// Download Credit Note Button
				$url_download = wp_nonce_url( admin_url( "admin-ajax.php?action=wiopt_generate_credit_note&order_id={$order_id}&template=credit_note{$output_type}" ), 'wiopt_pdf_nonce' );

				// Print Credit Note Button
				$url_print = wp_nonce_url( admin_url( "admin-ajax.php?action=wiopt_generate_credit_note&order_id={$order_id}&template=credit_note{$output_type}&print=true" ), 'wiopt_pdf_nonce' );

				// Function for print option
				$this->printButtonScript( $url_print );
				?>
                <a class="woocommerce-button button wiopt-my-account-credit-note" href="<?php echo esc_url( $url_download ); ?>" target="_blank" style="margin-bottom: 10px;">
                    <span class="dashicons dashicons-download"></span><?php echo esc_attr__( 'Credit Note', 'astama-pdf-invoice-for-woocommerce' ); ?>
                </a>
                <a class="woocommerce-button button wiopt-my-account-credit-note" onclick="printButton('<?php echo esc_js( $url_print ); ?>')" style="margin-bottom: 10px;">
                    <span class="dashicons dashicons-printer"></span><?php echo esc_attr__( 'Credit Note', 'astama-pdf-invoice-for-woocommerce' ); ?>
                </a>
				<?php
			}
		}
	}

	/**
	 * Register MetaBox to add PDF Download Button
	 *
	 * @source https://github.com/woocommerce/woocommerce/wiki/High-Performance-Order-Storage-Upgrade-Recipe-Book
	 */
	public function add_custom_meta_box() {
		$screen = class_exists( '\Automattic\WooCommerce\Internal\DataStores\Orders\CustomOrdersTableController' ) && wc_get_container()->get( CustomOrdersTableController::class )->custom_orders_table_usage_is_enabled()
			? wc_get_page_screen_id( 'shop-order' )
			: 'shop_order';
		add_meta_box(
			'wiopt-meta-box',
			__( 'Woo Invoice', 'astama-pdf-invoice-for-woocommerce' ),
			array(
				$this,
				'pdf_meta_box_markup',
			),
			$screen,
			'side',
			'high',
			null
		);
	}

	/**
	 * Add PDF Download button to MetaBox &
	 * Add PDF Packing Slip button to Meta Box
	 *
	 * @param object $order Order object.
	 */
	public function pdf_meta_box_markup( $order ) {
		wp_nonce_field( basename( __FILE__ ), 'meta-box-nonce' );
		if ( OrderUtil::custom_orders_table_usage_is_enabled() ) {
			// HPOS usage is enabled.
			$order_id          = $order->get_id();
			$paper_size        = get_option( 'wiopt_shipping_lebel_paper', 'A4' );
			$font_size         = get_option( 'wiopt_shipping_lebel_font_size', '12' );
			$invoice           = wp_nonce_url( admin_url( "admin-ajax.php?action=wiopt_generate_invoice&order_id={$order_id}" ), 'wiopt_pdf_nonce' );
			$packing_slip      = wp_nonce_url( admin_url( "admin-ajax.php?action=wiopt_generate_invoice_packing_slip&order_id={$order_id}" ), 'wiopt_pdf_nonce' );
			$shipping_label    = wp_nonce_url( admin_url( "admin-ajax.php?action=wiopt_generate_shipping_label&order_id={$order_id}&paper_size={$paper_size}&font={$font_size}" ), 'wiopt_pdf_nonce' );
			$credit_note       = wp_nonce_url( admin_url( "admin-ajax.php?action=wiopt_generate_credit_note&template=credit_note&order_id={$order_id}&paper_size={$paper_size}&font={$font_size}" ), 'wiopt_pdf_nonce' );
		} else {
            // If HPOS is disabled.
			global $post;
			$paper_size        = get_option( 'wiopt_shipping_lebel_paper', 'A4' );
			$font_size         = get_option( 'wiopt_shipping_lebel_font_size', '12' );
			$invoice           = wp_nonce_url( admin_url( "admin-ajax.php?action=wiopt_generate_invoice&order_id={$post->ID}" ), 'wiopt_pdf_nonce' );
			$packing_slip      = wp_nonce_url( admin_url( "admin-ajax.php?action=wiopt_generate_invoice_packing_slip&order_id={$post->ID}" ), 'wiopt_pdf_nonce' );
			$shipping_label    = wp_nonce_url( admin_url( "admin-ajax.php?action=wiopt_generate_shipping_label&order_id={$post->ID}&paper_size={$paper_size}&font={$font_size}" ), 'wiopt_pdf_nonce' );
			$credit_note       = wp_nonce_url( admin_url( "admin-ajax.php?action=wiopt_generate_credit_note&template=credit_note&order_id={$post->ID}&paper_size={$paper_size}&font={$font_size}" ), 'wiopt_pdf_nonce' );
		}
		$invoice_text        = get_option( 'wiopt_invoice_title', 'Invoice' );
		$packing_slip_text   = get_option( 'wiopt_PACKING_SLIP_TEXT', 'Packing Slip' );
		$shipping_label_text = get_option( 'wiopt_SHIPPING_LABEL_TEXT', 'Shipping Label' );
		?>

        <div class="wiopt_invoice_info">
			<?php $buttons = array(
				'Invoice' => $invoice,
				'Packing Slip' => $packing_slip,
				'Credit Note' => $credit_note,
				'Shipping Label' => $shipping_label
			); ?>

            <table class="wiopt_order_invoice_table">
				<?php foreach ($buttons as $button_text => $button_url):
					$order = OrderUtil::custom_orders_table_usage_is_enabled() ? $order : wc_get_order($post->ID);
					$show_credit_note = $order->get_total_refunded() > 0 || 'refunded' === $order->get_status();
                    if ($button_text === 'Credit Note' && !$show_credit_note) continue;
					// Function for print option
					$this->printButtonScript( $button_url ); ?>

                    <tr>
                        <td class="wiopt_button-text _winvoice-info-<?php echo sanitize_title($button_text); ?>"><?php echo esc_html($button_text); ?></td>
                        <!-- Add the Download button for each link -->
                        <td class="wiopt_order_buttons">
                            <a href="<?php echo esc_url($button_url); ?>" target="_blank">
                                <button type="button" class="wiopt_button_invoice button button-default _winvoice-info-<?php echo sanitize_title($button_text); ?>">
                                    <span class="dashicons dashicons-download"></span>
                                </button>
                            </a>
                        </td>
                        <!-- Add the Print button for each link -->
                        <td class="wiopt_order_buttons">
                            <button type="button" class="wiopt_button_invoice_print button button-default _winvoice-info-print" onclick="printButton('<?php echo esc_url($button_url); ?>')">
                                <span class="dashicons dashicons-printer"></span>
                            </button>
                        </td>
                    </tr>
				<?php endforeach; ?>
            </table>

        </div>

		<?php
	}


	/**
	 * Admin order list page bulk action handler.
	 *
	 * @param \https\Url $redirect_to Redirect url.
	 * @param string $doaction Action Name.
	 * @param array $post_ids Post Ids.
	 *
	 * @return string
	 */
	public function invoice_bulk_action_handler( $redirect_to, $doaction, $post_ids ) {
		if ( 'wiopt_bulk_invoice' !== $doaction ) {
			return $redirect_to;
		}
		if ( 'wiopt_bulk_invoice_packing_slip' !== $doaction ) {
			return $redirect_to;
		}
		if ( 'wiopt_generate_shipping_label' !== $doaction ) {
			return $redirect_to;
		}
		if ( 'wiopt_order_list' !== $doaction ) {
			return $redirect_to;
		}
		if ( 'wiopt_csv_order_list' !== $doaction ) {
			return $redirect_to;
		}

		$redirect_to = add_query_arg( 'bulk_emailed_posts', count( $post_ids ), $redirect_to );

		return $redirect_to;
	}

	/**
	 * Register bulk invoice making action
	 *
	 * @param array $bulk_actions Admin Order List table bulk action.
	 *
	 * @return array
	 */
	public function register_admin_order_list_bulk_actions( $bulk_actions ) {
		$bulk_actions['wiopt_bulk_invoice']              = __( 'Make PDF Invoice', 'astama-pdf-invoice-for-woocommerce' );
		$bulk_actions['wiopt_bulk_invoice_packing_slip'] = __( 'Make Packing Slip', 'astama-pdf-invoice-for-woocommerce' );
		$bulk_actions['wiopt_generate_shipping_label']   = __( 'Make Shipping Label', 'astama-pdf-invoice-for-woocommerce' );
		$bulk_actions['wiopt_order_list']                = __( 'Make Order List (PDF)', 'astama-pdf-invoice-for-woocommerce' );
		$bulk_actions['wiopt_csv_order_list']            = __( 'Make Order List (CSV)', 'astama-pdf-invoice-for-woocommerce' );

		return $bulk_actions;
	}

	/**
	 * Add Admin order list table action buttons
	 *
	 * @param WC_Order $order Order Object.
	 */
	public function register_admin_order_list_actions_buttons( $order ) {
		// Get Order ID (compatibility with all WC versions).
		$order_id    = $order->get_id();
		$nonce       = wp_create_nonce( 'woo_invo_ice_print_nonce' );
		$image_css   = 'width: 19px;margin-top: 4px;margin-left: 2px;';
		$output_type = ( get_option( 'wiopt_output_type_html' ) ) ? '&output=html' : '';

		$buttons = array(
			array(
				'type'      => 'invoice',
				'text'      => __( 'PDF Invoice', 'astama-pdf-invoice-for-woocommerce' ),
				'src'       => WOO_INVO_ICE_PLUGIN_URL . 'admin/images/invoice.svg',
				'option'    => "wiopt_invoice_action_button_$order_id",
				'action'    => 'wiopt_generate_invoice',
			),
			array(
				'type'      => 'packing_slip',
				'text'      => __( 'Packing Slip', 'astama-pdf-invoice-for-woocommerce' ),
				'src'       => WOO_INVO_ICE_PLUGIN_URL . 'admin/images/shipping_list.svg',
				'option'    => "wiopt_button_invoice_packing_slip_$order_id",
				'action'    => 'wiopt_generate_invoice_packing_slip',
			),
			array(
				'type'      => 'shipping_label',
				'text'      => __( 'Shipping Label', 'astama-pdf-invoice-for-woocommerce' ),
				'src'       => WOO_INVO_ICE_PLUGIN_URL . 'admin/images/shipping-label.svg',
				'option'    => "wiopt_invoice_shipping_label_$order_id",
				'action'    => 'wiopt_generate_shipping_label',
			),
			array(
				'type'      => 'credit_note',
				'text'      => __( 'Credit Note', 'astama-pdf-invoice-for-woocommerce' ),
				'src'       => WOO_INVO_ICE_PLUGIN_URL . 'admin/images/credit-note.svg',
				'option'    => "wiopt_invoice_credit_note_$order_id",
				'action'    => 'wiopt_generate_credit_note',
				'condition' => $order->get_total_refunded() > 0 || 'refunded' === $order->get_status(),
			),
		);

		/**
		 * Filter the admin order list buttons.
		 *
		 * @param array    $buttons   Array of buttons.
		 * @param int      $order_id  Order ID.
		 */
		$buttons = apply_filters('wiopt_admin_order_list_buttons', $buttons, $order_id);

		foreach ( $buttons as $button ) {
			if ( isset( $button['condition'] ) && ! $button['condition'] ) {
				continue;
			}

			if ( $button['type'] === 'shipping_label' ) {
				$paper_size = '' != get_option( 'wiopt_shipping_lebel_paper' ) ? get_option( 'wiopt_shipping_lebel_paper' ) : 'A4';
				$font_size  = '' != get_option( 'wiopt_shipping_lebel_font_size' ) ? get_option( 'wiopt_shipping_lebel_font_size' ) : '12';
				$url        = wp_nonce_url( admin_url( "admin-ajax.php?action={$button['action']}&order_id=$order_id&paper_size=$paper_size&font=$font_size$output_type" ), 'wiopt_pdf_nonce' );
			} elseif ( $button['type'] === 'credit_note' ) {
				$output_type = get_option( 'wiopt_output_type_html' ) ? '&output=html' : '';
				$url         = wp_nonce_url( admin_url( "admin-ajax.php?action={$button['action']}&template=credit_note&order_id=$order_id$output_type" ), 'wiopt_pdf_nonce' );
			} else {
				$url = wp_nonce_url( admin_url( "admin-ajax.php?action={$button['action']}&order_id=$order_id$output_type" ), 'wiopt_pdf_nonce' );
			}

			// Add 'exists' class based on option existence
			$is_printed = get_option( $button['option'] );
			$class      = "tips parcial wiopt_{$button['type']} {$button['option']}";
			$class      .= $is_printed ? ' exists' : ''; // Add 'exists' class conditionally

			echo $this->get_document_button( $nonce, $url, $order_id, "{$button['option']}_{$order_id}", $class, $button['text'], $button['src'] ); // phpcs:ignore
		}
        // Function for Print option
		$this->printButtonScript( $url );
		// Tooltip Print button.
		echo '<div class="wiopt-tooltip-container">';
		echo '<span class="wiopt-tooltip" data-tip="Print Options">';
		echo '<a class="button wiopt-print-dropdown-button" title="Print Options" onclick="togglePrintOptionsDropdown(this)"><span class="dashicons dashicons-printer"></span></a>';
		echo '<div class="wiopt-print-option" style="display: none;"><ul class="wiopt-print-options" >';
		foreach ($buttons as $button) {
			$show_credit_note = $order->get_total_refunded() > 0 || 'refunded' === $order->get_status();
			if ( $button['type'] === 'credit_note' && !$show_credit_note) continue;
			if ( $button['type'] === 'shipping_label' ) {
				$paper_size = '' != get_option( 'wiopt_shipping_lebel_paper' ) ? get_option( 'wiopt_shipping_lebel_paper' ) : 'A4';
				$font_size  = '' != get_option( 'wiopt_shipping_lebel_font_size' ) ? get_option( 'wiopt_shipping_lebel_font_size' ) : '12';
				$url        = wp_nonce_url( admin_url( "admin-ajax.php?action={$button['action']}&order_id=$order_id&paper_size=$paper_size&font=$font_size$output_type" ), 'wiopt_pdf_nonce' );
			} elseif ( $button['type'] === 'credit_note' ) {
				$url = wp_nonce_url( admin_url( "admin-ajax.php?action={$button['action']}&template=credit_note&order_id=$order_id$output_type" ), 'wiopt_pdf_nonce' );
			} else {
				$url = wp_nonce_url( admin_url( "admin-ajax.php?action={$button['action']}&order_id=$order_id$output_type" ), 'wiopt_pdf_nonce' );
			}
			$print_text = __('Print', 'astama-pdf-invoice-for-woocommerce') . ' ' . esc_html($button['text']);

			echo "<li><a class='wiopt_invoice_print' title='" . esc_html($button['text']) . "' onclick='printButton(\"" . esc_url($url) . "\"); closePrintOptionsDropdowns();'>" . esc_html($print_text) . '</a></li>';
		}
		echo '</ul></div>';
		echo '</span>';
		echo '</div>';
	}

	/**
	 * Get shop_order page button icon.
	 *
	 * @param $nonce
	 * @param $url
	 * @param $order_id
	 * @param $which
	 * @param $class
	 * @param $button_text
	 * @param $image_src
	 *
	 * @return false|string
	 */
	public function get_document_button( $nonce, $url, $order_id, $which, $class, $button_text, $image_src ) {
		$image_css = 'width: 19px;margin-top: 4px;margin-left: 2px;';
		$params    = implode( ',', [
			$nonce,
			$url,
			$order_id,
			$which,
			get_option( 'wiopt_pdf_invoice_button_behaviour', 'new_tab' )
		] );

		// Add 'exists' class to the button based on option existence
		$class .= get_option( $which ) ? ' exists' : '';

		ob_start();
		?>
        <a onclick="woo_invo_ice_save_is_document_printed( '<?php echo esc_js( $params ); ?>');return false;"
           data-which="<?php echo esc_attr( $which ) ?>"
           id="<?php echo esc_attr( $which ) ?>"
           data-nonce="<?php echo esc_attr( $nonce ) ?>" href="#" class="button <?php echo esc_attr( $class ) ?>"
           data-tip="<?php echo esc_html( $button_text ) ?>" title="<?php echo esc_html( $button_text ) ?>"> <img
                    src="<?php echo esc_url( $image_src ) ?>" style="<?php echo esc_html( $image_css ) ?>"/></a>
		<?php

		$button = ob_get_contents();
		ob_end_clean();

		return $button;

	}
	/**
	 * Ajax Action For Hiding Compatibility Notices
	 */
	public function woo_invo_ice_is_document_printed() {
		check_ajax_referer( 'woo_invo_ice_print_nonce' );

		if ( isset( $_REQUEST['which'] ) && ! empty( $_REQUEST['which'] ) && ! empty( $_REQUEST['order_id'] ) ) {
			$option_name = sanitize_text_field( wp_unslash( $_REQUEST['which'] ) );
			update_option( $option_name, true );
			wp_send_json_success( true );
			wp_die();
		}
		wp_send_json_error( esc_html__( 'Invalid Request.', 'astama-pdf-invoice-for-woocommerce' ) );
		wp_die();
	}

	/**
	 * Attach Invoice with Order Email
	 *
	 * @param array $attachments Order email attachments.
	 * @param string $status Email Type.
	 * @param WC_Order $order Order object.
	 *
	 * @return array
	 * @throws \Mpdf\MpdfException PDF Output.
	 */
	public function attach_invoice_to_order_email( $attachments, $status, $order ) {

		if ( ! $order instanceof WC_Order ) {
			return $attachments;
		}

		// Don't attach invoice for free order.
		if ( empty( get_option( 'wiopt_free_order_attachment' ) ) && $order->get_total() == '0.00' ) {
			return $attachments;
		}

		$order_id = $order->get_id();


		$allowed_statuses = ( get_option( 'wiopt_email_attach_check_list' ) == false || is_null( get_option( 'wiopt_email_attach_check_list' ) ) ) ? array() : get_option( 'wiopt_email_attach_check_list' );

		if ( empty( $allowed_statuses ) ) {
			$allowed_statuses = array(
				'new_order',
				'customer_processing_order',
				'customer_invoice',
				'customer_completed_order',
				'customer_on_hold_order',
				'customer_refunded_order',
				'customer_partially_refunded_order',
				'customer_note',
				'cancelled_order',
				'failed_order',
			);

			if ( in_array( 'woocommerce-subscriptions/woocommerce-subscriptions.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {//phpcs:ignore
				array_push( $allowed_statuses, 'new_renewal_order' );
				array_push( $allowed_statuses, 'customer_completed_renewal_order' );
				array_push( $allowed_statuses, 'customer_renewal_invoice' );
			}
		}


		$allowed_statuses = apply_filters( 'woo_invo_ice_email_types', $allowed_statuses );

		// Delete old pdf files before generating new one.
		array_map( 'unlink', glob( WOO_INVO_ICE_INVOICE_DIR . '*.pdf' ) ); // Delete files.

		// Generate & Save Invoice.
		WPIFW_PDF( $order_id )->savePdf( $order_id );

		// Attach invoice with email.
		$invoice_no = ( ! empty( get_post_meta( $order_id, 'wiopt_invoice_no_' . $order_id, true ) ) ) ? get_post_meta( $order_id, 'wiopt_invoice_no_' . $order_id, true ) : woo_invo_ice_get_invoice_number( $order_id );

		if ( isset( $status ) && ( 'customer_refunded_order' === $status ) ) {
			$file_name = 'Credit-Note-Of-Invoice-' . $invoice_no;
			$file_name = apply_filters( 'woo_invo_ice_file_name', $file_name, 'credit_note', $order_id );
		} else {
			$file_name = 'Invoice-' . $invoice_no;
			$file_name = apply_filters( 'woo_invo_ice_file_name', $file_name, 'invoice', $order_id );
		}

		// Return file path.
		if ( in_array( 'always_allow', $allowed_statuses ) ) {
			$pdf_path      = WOO_INVO_ICE_INVOICE_DIR . $file_name . '.pdf';
			$attachments[] = $pdf_path;
		} elseif ( isset( $status ) && woo_invo_ice_is_current_status_checked( $status, $allowed_statuses ) ) {
			$pdf_path      = WOO_INVO_ICE_INVOICE_DIR . $file_name . '.pdf';
			$attachments[] = $pdf_path;
		}

		// Attach packing slip with email.
		if ( function_exists( 'wcs_order_contains_subscription' ) && wcs_order_contains_subscription( $order_id, array(
				'parent',
				'renewal'
			) ) ) {
		} else {
			if ( isset( $status ) && ( 'customer_completed_order' === $status ) && ( '1' === get_option( 'wiopt_email_packing_slip' ) )
			) {
				$file_name     = 'Packing-slip-' . $invoice_no;
				$file_name     = apply_filters( 'woo_invo_ice_file_name', $file_name, 'packing_slip', $order_id );
				$pdf_path      = WOO_INVO_ICE_INVOICE_DIR . $file_name . '.pdf';
				$attachments[] = $pdf_path;
			}
		}


		$static_allowed_statuses = ( get_option( 'wiopt_static_attach_check_list' ) != false ) ? get_option( 'wiopt_static_attach_check_list' ) : array();
		if ( empty( $static_allowed_statuses ) ) {
			$static_allowed_statuses = array(
				'new_order',
				'customer_processing_order',
				'customer_invoice',
				'customer_on_hold_order',
				'customer_completed_order',
				'customer_refunded_order',
				'customer_partially_refunded_order',
				'customer_note',
				'cancelled_order',
				'failed_order',
			);
		}

		if ( in_array( 'always_allow', $allowed_statuses ) ) {
			for ( $i = 0; $i < 3; $i ++ ) {
				if ( get_option( 'wiopt_static_files_' . $i ) != false && ! empty( 'wiopt_static_files_' . $i ) ) {
					$id             = attachment_url_to_postid( get_option( 'wiopt_static_files_' . $i ) );
					$full_size_path = get_attached_file( $id );
					$attachments[]  = $full_size_path;
				}
			}
		} elseif ( isset( $status ) && woo_invo_ice_is_current_status_checked( $status, $static_allowed_statuses ) ) {
			for ( $i = 0; $i < 3; $i ++ ) {
				if ( get_option( 'wiopt_static_files_' . $i ) != false && ! empty( 'wiopt_static_files_' . $i ) ) {
					$id             = attachment_url_to_postid( get_option( 'wiopt_static_files_' . $i ) );
					$full_size_path = get_attached_file( $id );
					$attachments[]  = $full_size_path;
				}
			}
		}

		$attachments = apply_filters( 'wiopt_new_order_attachments', $attachments, $status, $order );

		return $attachments;
	}

	/**
	 * Add QR code to WC email.
	 *
	 */
	public function add_qr_code_to_email( $order, $sent_to_admin, $plain_text, $email ) {
		echo do_shortcode( "[woo_invo_ice_qr_code order_id='" . $order->get_id() . "']" );
	}


	/**
	 * Add Invoice Download link to order email
	 *
	 * @param WC_Order $order Order Object.
	 */
	public function add_invoice_download_link( $order ) {
		$upload   = wp_upload_dir();
		$base_url = $upload['baseurl'];

		$allowed_statuses = array(
			'new_order',
			'customer_invoice',
			'completed',
			'customer_on_hold_order',
			'completed_renewal_order',
			'customer_renewal_invoice',
			'new_renewal_order',
		);

		if ( get_option( 'wiopt_proforma_invoicing' ) == '1' ) {
			array_push( $allowed_statuses, 'customer_processing_order' );
			array_push( $allowed_statuses, 'customer_processing_renewal_order' );
		}

		$status = $order->get_status();

		$order_id = $order->get_id();


		if ( ! empty( get_post_meta( $order_id, 'wiopt_invoice_no_' . $order_id, true ) ) ) {
			$invoice_id = get_post_meta( $order_id, 'wiopt_invoice_no_' . $order_id, true );
		} else {
			$invoice_id = get_post_meta( $order_id, 'wiopt_invoice_no', true );
		}


		if ( file_exists( WOO_INVO_ICE_INVOICE_DIR . 'Invoice-' . $invoice_id . '.pdf' ) && ( isset( $status ) && in_array( $status, $allowed_statuses ) ) ) {
			$download_link         = $base_url . '/WPIFW-INVOICE/Invoice-' . $invoice_id . '.pdf';
			$download_invoice_text = get_option( 'wiopt_DOWNLOAD_INVOICE_TEXT' );
			$download_invoice_text = ( $download_invoice_text ) ? $download_invoice_text : 'Download Invoice';
			echo '<a href="' . esc_url( $download_link ) . '" target="_blank"  class="button wiopt_invoice" data-tip="" title="">' . esc_html( $download_invoice_text ) . '</a> <br><br/>';
		}

	}

	/**
	 * Generate Next Invoice Sequence
	 */
	public function add_invoice_number_to_order( $order_id ) {

		$invoice_no = wc_get_order( $order_id )->get_order_number();


		// Get next number for custom sequence.
		$next_no = get_option( 'wiopt_invoice_no' );
		$next_no = ! empty( $next_no ) ? $next_no : 1;
		++ $next_no;

		$get_number_type = get_option( 'wiopt_invoice_number_type' );

		// Get Prefix.
		$prefix = get_option( 'wiopt_invoice_no_prefix' );
		$prefix = ! empty( $prefix ) ? $prefix : '';

		// Get Suffix.
		$suffix = get_option( 'wiopt_invoice_no_suffix' );
		$suffix = ! empty( $suffix ) ? $suffix : '';

		// Generate Invoice Number.
		if ( 'pre_custom_number_suf' === $get_number_type ) {
			$invoice_no = $prefix . $next_no . $suffix;
		} elseif ( 'pre_order_number_suf' === $get_number_type ) {
			$invoice_no = $prefix . $invoice_no . $suffix;
		}


		$invoice_no = woo_invo_ice_process_date_macros( $order_id, $invoice_no );

		update_post_meta( $order_id, 'wiopt_invoice_no_' . $order_id, $invoice_no );
		update_option( 'wiopt_invoice_no', $next_no );


	}


	/**
	 *  Add Logo uploader script to footer
	 */
	public function logo_selector_print_scripts() {
		?>
        <script type='text/javascript'>

            /*--------- Custom Invoice Logo Javascript ----------*/
            jQuery(document).ready(function ($) {

                jQuery(document).on("click", "#wiopt_upload_logo_button", function (e) {
                    e.preventDefault();
                    var $button = $(this);


                    // Create the media frame.
                    var file_frame = wp.media.frames.file_frame = wp.media({
                        title: 'Select or upload image',
                        library: { // remove these to show all
                            type: 'image' // specific mime
                        },
                        button: {
                            text: 'Select'
                        },
                        multiple: false  // Set to true to allow multiple files to be selected
                    });

                    // When an image is selected, run a callback.
                    file_frame.on('select', function () {
                        // We set multiple to false so only get one image from the uploader

                        var attachment = file_frame.state().get('selection').first().toJSON();

                        $('#wiopt_logo-preview').attr('src', attachment.url).css('width', 'auto');
                        $('#wiopt_logo_attachment_id').val(attachment.id);

                        $button.siblings('input').val(attachment.id).change();

                    });

                    // Finally, open the modal
                    file_frame.open();
                });
            });

            /*--------- Custom Stamp Javascript ----------*/
            jQuery(document).ready(function ($) {

                jQuery(document).on("click", "#wiopt_upload_custom_stamp_button", function (e) {
                    e.preventDefault();
                    var $button = $(this);

                    // Create the media frame.
                    var file_frame = wp.media.frames.file_frame = wp.media({
                        title: 'Select or upload image',
                        library: { // remove these to show all
                            type: 'image' // specific mime
                        },
                        button: {
                            text: 'Select'
                        },
                        multiple: false  // Set to true to allow multiple files to be selected
                    });

                    // When an image is selected, run a callback.
                    file_frame.on('select', function () {
                        // We set multiple to false so only get one image from the uploader

                        var attachment = file_frame.state().get('selection').first().toJSON();

                        $('#wiopt_custom_stamp_preview').attr('src', attachment.url);
                        $('#wiopt_custom_stamp_attachment_id').val(attachment.id);
                        $button.siblings('input').val(attachment.id).change();

                    });

                    // Finally, open the modal
                    file_frame.open();
                });
            });


            /*--------- Signature Javascript ----------*/
            jQuery(document).ready(function ($) {

                jQuery(document).on("click", "#wiopt_upload_signature_button", function (e) {
                    e.preventDefault();
                    var $button = $(this);


                    // Create the media frame.
                    var file_frame = wp.media.frames.file_frame = wp.media({
                        title: 'Select or upload image',
                        library: { // remove these to show all
                            type: 'image' // specific mime
                        },
                        button: {
                            text: 'Select'
                        },
                        multiple: false  // Set to true to allow multiple files to be selected
                    });

                    // When an image is selected, run a callback.
                    file_frame.on('select', function () {
                        // We set multiple to false so only get one image from the uploader

                        var attachment = file_frame.state().get('selection').first().toJSON();

                        $('#wiopt_signature-preview').attr('src', attachment.url);
                        $('#wiopt_signature_attachment_id').val(attachment.id);
                        $button.siblings('input').val(attachment.id).change();

                    });

                    // Finally, open the modal
                    file_frame.open();
                });
            });


            /*------------- Custom Background Javascript ----------*/
            jQuery(document).ready(function ($) {

                jQuery(document).on("click", "#wiopt_upload_invoice_background_button", function (e) {
                    e.preventDefault();
                    var $button = $(this);


                    // Create the media frame.
                    var file_frame = wp.media.frames.file_frame = wp.media({
                        title: 'Select or upload image',
                        library: { // remove these to show all
                            type: 'image' // specific mime
                        },
                        button: {
                            text: 'Select'
                        },
                        multiple: false  // Set to true to allow multiple files to be selected
                    });

                    // When an image is selected, run a callback.
                    file_frame.on('select', function () {
                        // We set multiple to false so only get one image from the uploader

                        var attachment = file_frame.state().get('selection').first().toJSON();

                        $('#wiopt_invoice-background-preview').attr('src', attachment.url);
                        $('#wiopt_invoice_background_attachment_id').val(attachment.id);

                        $button.siblings('input').val(attachment.id).change();

                    });

                    // Finally, open the modal
                    file_frame.open();
                });
            });


            /*------------- Upload Static file Javascript ----------*/
            jQuery(document).ready(function ($) {

                $(".wiopt_static_files_section .wiopt_static_files").each(function (index) {
                    jQuery(document).on("click", "#wiopt_upload_static_files_" + index + "_button", function (e) {
                        e.preventDefault();
                        var $button = $(this);


                        // Create the media frame.
                        var file_frame = wp.media.frames.file_frame = wp.media({
                            title: 'Select or upload image',
                            /*library: { // remove these to show all
								type: 'application/pdf' // specific mime
							},*/
                            button: {
                                text: 'Select'
                            },
                            multiple: false  // Set to true to allow multiple files to be selected
                        });

                        // When an image is selected, run a callback.
                        file_frame.on('select', function () {
                            // We set multiple to false so only get one image from the uploader

                            var attachment = file_frame.state().get('selection').first().toJSON();

                            $("input[name='wiopt_static_files_" + index + "']").val(attachment.url);

                            $button.siblings('#wiopt_static_files_' + index).val(attachment.url).change();

                        });

                        // Finally, open the modal
                        file_frame.open();
                    });
                });


            });


            /*------------ Upload Packing Slip Background Javascript ----------*/
            jQuery(document).ready(function ($) {

                jQuery(document).on("click", "#wiopt_upload_packingslip_background_button", function (e) {
                    e.preventDefault();
                    var $button = $(this);


                    // Create the media frame.
                    var file_frame = wp.media.frames.file_frame = wp.media({
                        title: 'Select or upload image',
                        library: { // remove these to show all
                            type: 'image' // specific mime
                        },
                        button: {
                            text: 'Select'
                        },
                        multiple: false  // Set to true to allow multiple files to be selected
                    });

                    // When an image is selected, run a callback.
                    file_frame.on('select', function () {
                        // We set multiple to false so only get one image from the uploader

                        var attachment = file_frame.state().get('selection').first().toJSON();

                        $('#wiopt_packingslip-background-preview').attr('src', attachment.url).css('width', 'auto');
                        $('#wiopt_packingslip_background_attachment_id').val(attachment.id);

                        $button.siblings('input').val(attachment.id).change();

                    });

                    // Finally, open the modal
                    file_frame.open();
                });
            });

        </script>
		<?php
	}

	/**
	 * Add VAT & SSN filed into My Account edit user page
	 */
	public function woo_invo_ice_add_vat_ssn_to_edit_account_form() {
		// Check if the filter allows displaying fields
		if ( apply_filters( 'woo_invo_ice_display_vat_ssn_fields', true ) ) {
			$user = wp_get_current_user();
			?>
            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                <label for="wiopt_vat"><?php esc_html_e( 'VAT Number', 'astama-pdf-invoice-for-woocommerce' ); ?>
                    <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="wiopt_vat"
                           id="wiopt_vat" value="<?php echo esc_attr( $user->wiopt_vat ); ?>"/>
                </label>
            </p>
            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                <label for="wiopt_ssn"><?php esc_html_e( 'SSN', 'astama-pdf-invoice-for-woocommerce' ); ?>
                    <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="wiopt_ssn"
                           id="wiopt_ssn" value="<?php echo esc_attr( $user->wiopt_ssn ); ?>"/>
                </label>
            </p>
			<?php
		}
	}


	/**
	 * My Account Save VAT and SSN value
	 * phpcs:disable
	 *
	 * @param int $user_id User id.
	 */
	public function woo_invo_ice_save_vat_ssn_account_details( $user_id ) {

		if ( isset( $_POST['wiopt_vat'] ) ) {
			update_user_meta( $user_id, 'wiopt_vat', sanitize_text_field( $_POST['wiopt_vat'] ) );
		}

		if ( isset( $_POST['wiopt_ssn'] ) ) {
			update_user_meta( $user_id, 'wiopt_ssn', sanitize_text_field( $_POST['wiopt_ssn'] ) );
		}
	}//phpcs:enable


	/**
	 * Add VAT & SSN field to WordPress Edit User Page
	 *
	 * @param object $user User Object.
	 */
	public function woo_invo_ice_wp_vat_ssn_fields( $user ) {
		?>
        <h3><?php esc_html_e( 'Vat & SSN information', 'astama-pdf-invoice-for-woocommerce' ); ?></h3>

        <table class="form-table">
            <tr>
                <th>
                    <label for="wiopt_vat"><?php esc_html_e( 'VAT Number', 'astama-pdf-invoice-for-woocommerce' ); ?></label>
                </th>
                <td>
                    <input type="text" name="wiopt_vat" id="wiopt_vat"
                           value="<?php echo esc_attr( get_the_author_meta( 'wiopt_vat', $user->ID ) ); ?>"
                           class="regular-text"/><br/>
                    <span class="description"><?php esc_html_e( 'Please enter your Vat Id for invoice.', 'astama-pdf-invoice-for-woocommerce' ); ?></span>
                </td>
            </tr>
            <tr>
                <th>
                    <label for="wiopt_ssn"><?php esc_html_e( 'SSN', 'astama-pdf-invoice-for-woocommerce' ); ?></label>
                </th>
                <td>
                    <input type="text" name="wiopt_ssn" id="wiopt_ssn"
                           value="<?php echo esc_attr( get_the_author_meta( 'wiopt_ssn', $user->ID ) ); ?>"
                           class="regular-text"/><br/>
                    <span class="description"><?php esc_html_e( 'Please enter your SSN for Invoice.', 'astama-pdf-invoice-for-woocommerce' ); ?></span>
                </td>
            </tr>
        </table>
		<?php
	}


	/**
	 * WordPress Edit User Page Save User Vat and SSN info
	 *
	 * @param int $user_id User Id.
	 *
	 * @return bool
	 */
	public function woo_invo_ice_wp_vat_ssn_fields_save( $user_id ) {
		if ( ! current_user_can( 'edit_user', $user_id ) ) {
			return false;
		}
		$vat = isset( $_POST['wiopt_vat'] ) ? sanitize_text_field( $_POST['wiopt_vat'] ) : '';//phpcs:ignore
		$ssn = isset( $_POST['wiopt_ssn'] ) ? sanitize_text_field( $_POST['wiopt_ssn'] ) : '';//phpcs:ignore
		update_user_meta( $user_id, 'wiopt_vat', $vat );
		update_user_meta( $user_id, 'wiopt_ssn', $ssn );
	}

	/**
	 * Add VAT ID AND SSN field to the checkout page
	 *
	 * @param mixed $fields Checkout Fields.
	 *
	 * @return mixed
	 */
	public function woo_invo_ice_add_checkout_field( $fields ) {
		if ( get_option( 'wiopt_display_vat_id' ) == true ) {
			$vat_id = ( get_option( 'wiopt_VAT_ID' ) ) ? get_option( 'wiopt_VAT_ID' ) : esc_html__( 'VAT Number', 'astama-pdf-invoice-for-woocommerce' );
			$vat_id = apply_filters( 'woo_invo_ice_checkout_vat_id_label', $vat_id );
			$fields['wiopt_vat_id'] = array(
				'label'    => $vat_id,
				'type'     => 'text',
				'class'    => array( 'form-row-wide' ),
				'priority' => 101,
				'required' => false,
			);
		}

		if ( get_option( 'wiopt_display_ssn' ) == true ) {
			$ssn = ( get_option( 'wiopt_SSN' ) ) ? get_option( 'wiopt_SSN' ) : esc_html__( 'SSN', 'astama-pdf-invoice-for-woocommerce' );
			$ssn = apply_filters( 'woo_invo_ice_checkout_ssn_label', $ssn );
			$fields['wiopt_ssn_id'] = array(
				'label'    => $ssn,
				'type'     => 'text',
				'class'    => array( 'form-row-wide' ),
				'priority' => 102,
				'required' => false,
			);
		}

		return $fields;
	}


	/**
	 * Update VAT and SSN checkout field value
	 * phpcs:disable
	 *
	 * @param int $order_id Order id.
	 */
	public function woo_invo_ice_checkout_field_update_order_meta( $order_id ) {
		if ( isset( $_POST['wiopt_vat_id'] ) && ! empty( $_POST['wiopt_vat_id'] ) ) {
			update_post_meta( $order_id, 'wiopt_vat_id', sanitize_text_field( $_POST['wiopt_vat_id'] ) );
		}
		if ( isset( $_POST['wiopt_ssn_id'] ) && ! empty( $_POST['wiopt_ssn_id'] ) ) {
			update_post_meta( $order_id, 'wiopt_ssn_id', sanitize_text_field( $_POST['wiopt_ssn_id'] ) );
		}
	}//phpcs:enable


	/**
	 * Process invoice template number
	 */
	public function woo_invo_ice_save_pdf_template() {
		// Verify Nonce.
		check_ajax_referer( 'wiopt_pdf_nonce' );
		$template = isset( $_POST['template'] ) ? sanitize_text_field( wp_unslash( $_POST['template'] ) ) : 'invoice-1';
		update_option( 'wiopt_templateid', $template );
		$response = plugin_dir_url( __DIR__ ) . '/admin/images/templates/' . esc_html( $template );
		wp_send_json_success( $response );
		wp_die();
	}

	/**
	 * Enable paid stamp show
	 */
	public function woo_invo_ice_save_paid_stamp() {
		// Verify Nonce.
		check_ajax_referer( 'wiopt_pdf_nonce' );

		$stamp = isset( $_POST['stamp'] ) ? sanitize_text_field( wp_unslash( $_POST['stamp'] ) ) : '';
		update_option( 'wiopt_paid_stamp_image', $stamp );
		$stamp_response = plugin_dir_url( __DIR__ ) . '/admin/images/paid-stamp/' . esc_html( $stamp );
		wp_send_json_success( $stamp_response );
	}

	/**
	 * Get paid stamp show status
	 */
	public function woo_invo_ice_paid_stamp_enabled() {
		// Verify Nonce.
		check_ajax_referer( 'wiopt_pdf_nonce' );
		update_option( 'wiopt_paid_stamp', true );
		wp_send_json_success();
	}

	/**
	 * Enable Product Attribute Show
	 */
	public function woo_invo_ice_product_attribute_show() {
		// Verify Nonce.
		check_ajax_referer( 'wiopt_pdf_nonce' );
		$attributes = array();
		if ( isset( $_POST['attribute'] ) ) {
			foreach ( $_POST['attribute'] as $key => $value ) { //phpcs:ignore
				$attributes[ sanitize_text_field( $key ) ] = sanitize_text_field( $value );
			}
		}
		$value = ! empty( $attributes ) ? $attributes : '';
		update_option( 'wiopt_product_attribute_show', $value );
		wp_send_json_success();
	}

	/**
	 * Save Product Header.
	 */
	public function wiopt_select_product_column() {
		// Verify Nonce.
		check_ajax_referer( 'wiopt_pdf_nonce' );
		$columns = array();
		if ( isset( $_POST['columns'] ) ) {
			foreach ( $_POST['columns'] as $key => $value ) { //phpcs:ignore
				$columns[ sanitize_text_field( $key ) ] = sanitize_text_field( $value );
			}
		}
		$value = ! empty( $columns ) ? $columns : '';
		update_option( 'wiopt_select_product_column', $value );
		wp_send_json_success();
	}

	/**
	 * Save Product Header For Packing Slip.
	 */
	public function wiopt_packingslip_product_table_header() {
		// Verify Nonce.
		check_ajax_referer( 'wiopt_pdf_nonce' );
		$columns = array();
		if ( isset( $_POST['columns'] ) ) {
			foreach ( $_POST['columns'] as $key => $value ) { //phpcs:ignore
				$columns[ sanitize_text_field( $key ) ] = sanitize_text_field( $value );
			}
		}
		$value = ! empty( $columns ) ? $columns : '';
		update_option( 'wiopt_packingslip_product_table_header', $value );
		wp_send_json_success();
	}

	/**
	 * Save CSV Fields.
	 */
	public function woo_invo_ice_wiopt_save_csv_fields() {
		// Verify Nonce.
		check_ajax_referer( 'wiopt_pdf_nonce' );
		$attributes = array();
		if ( isset( $_POST['attribute'] ) ) {
			foreach ( $_POST['attribute'] as $key => $value ) { //phpcs:ignore
				$attributes[ sanitize_text_field( $key ) ] = sanitize_text_field( $value );
			}
		}
		$value = ! empty( $attributes ) ? $attributes : '';
		update_option( 'wiopt_add_fields_csv', $value );
		wp_send_json_success();
	}

	/**
	 * Display bulk download csv fields.
	 */
	public function woo_invo_ice_get_csv_fields_show() {
		// Verify Nonce.
		check_ajax_referer( 'wiopt_pdf_nonce' );
		$wiopt_add_fields_csv = get_option( 'wiopt_add_fields_csv' );
		wp_send_json_success( $wiopt_add_fields_csv );
	}


	/**
	 * Get Product Attribute Status
	 */
	public function woo_invo_ice_get_product_attribute_show() {
		// Verify Nonce.
		check_ajax_referer( 'wiopt_pdf_nonce' );
		$wiopt_product_attribute_show = get_option( 'wiopt_product_attribute_show' );
		wp_send_json_success( $wiopt_product_attribute_show );
	}

	/**
	 * Get Product Column data
	 */
	public function woo_invo_ice_get_product_column() {
		// Verify Nonce.
		check_ajax_referer( 'wiopt_pdf_nonce' );
		$wiopt_product_column_show = get_option( 'wiopt_select_product_column' );
		wp_send_json_success( $wiopt_product_column_show );
	}

	/**
	 * Get Product Column for packing slip
	 */
	public function get_wiopt_packingslip_product_table_header() {
		// Verify Nonce.
		check_ajax_referer( 'wiopt_pdf_nonce' );
		$wiopt_product_column_show = get_option( 'wiopt_packingslip_product_table_header' );
		wp_send_json_success( $wiopt_product_column_show );
	}


	/**
	 * Enable Product Dimension Show
	 */
	public function woo_invo_ice_product_dimension_show() {
		// Verify Nonce.
		check_ajax_referer( 'wiopt_pdf_nonce' );

		$dimensions = array();
		foreach ( $_POST['dimension'] as $key => $value ) { //phpcs:ignore
			$dimensions[ sanitize_text_field( $key ) ] = sanitize_text_field( $value );
		}
		$value = isset( $_POST['dimension'] ) ? $dimensions : '';
		update_option( 'wiopt_invoice_product_dimension_show', $value );
		wp_send_json_success();
	}


	/**
	 * Get Product Show Status
	 */
	public function woo_invo_ice_get_product_dimension_show() {
		// Verify Nonce.
		check_ajax_referer( 'wiopt_pdf_nonce' );
		$wiopt_invoice_product_dimension_show = get_option( 'wiopt_invoice_product_dimension_show' ) ? get_option( 'wiopt_invoice_product_dimension_show' ) : '';
		wp_send_json_success( $wiopt_invoice_product_dimension_show );
	}

	/**
	 * Show Review request admin notice
	 */
	public function woo_invo_ice_save_review_notice() {
		// Verify Nonce.
		check_ajax_referer( 'wiopt_pdf_nonce' );
		$notice                 = isset( $_POST['notice'] ) ? sanitize_text_field( wp_unslash( $_POST['notice'] ) ) : '';
		$value['review_notice'] = $notice;
		$value['updated_at']    = time();

		update_option( 'woo_invo_ice_review_notice', $value );
		wp_send_json_success( $value );
	}

	/**
	 * Add some links to Admin plugin list page
	 *
	 * @param array $links Plugin Links.
	 *
	 * @return array
	 */
	public function woo_invo_ice_plugin_action_links( $links ) {
		$links[] = '<a style="color:#8e44ad;" href="' . admin_url( 'admin.php?page=astama-woo-invo-ice-settings' ) . '">' . __( 'Settings', 'astama-pdf-invoice-for-woocommerce' ) . '</a>';
		$links[] = '<a style="color:#8e44ad;" href="https://astama.com/my-account/contact-support/" target="_blank">' . __( 'Support', 'astama-pdf-invoice-for-woocommerce' ) . '</a>';

		return $links;
	}

	/**
	 * Extend Billing Address
	 *
	 * @param string $address Billing Address
	 * @param WC_Order $order Order Object
	 * @param string $template invoice|packing_slip
	 *
	 * @return string
	 */
	public function woo_invo_ice_billing_info_callback( $address, $order, $template ) {

		$order_id = $order->get_id();
		// Add SSN and VAT ID if billing address.
		if ( 'invoice' == $template ) {
			if ( get_option( 'wiopt_display_vat_id' ) ) {
				// Get VAT Number.
				if ( ! empty( get_post_meta( $order_id, 'wiopt_vat_id', true ) ) ) {
					$address .= '<br/>';
					$address .= woo_invo_ice_filter_label( 'VAT Number', $order, $template ) . ' : ' . get_post_meta( $order_id, 'wiopt_vat_id', true );
				} else {
					if ( get_user_meta( $order->get_user_id(), 'wiopt_vat', true ) != '' ) {
						$address .= '<br/>';
						$address .= woo_invo_ice_filter_label( 'VAT Number', $order, $template ) . ' : ' . get_user_meta( $order->get_user_id(), 'wiopt_vat', true );
					}
				}
			}

			if ( get_option( 'wiopt_display_ssn' ) ) {
				// Get SSN Number.
				if ( ! empty( get_post_meta( $order_id, 'wiopt_ssn_id', true ) ) ) {
					$address .= '<br/>' . woo_invo_ice_filter_label( 'SSN', $order, $template ) . ' : ' . get_post_meta( $order_id, 'wiopt_ssn_id', true );
				} else {
					if ( ! empty( get_user_meta( $order->get_user_id(), 'wiopt_ssn', true ) ) ) {
						$address .= '<br/>' . woo_invo_ice_filter_label( 'SSN', $order, $template ) . ' : ' . get_user_meta( $order->get_user_id(), 'wiopt_ssn', true );
					}
				}
			}
		}

		return $address;
	}

	/**
	 * Get after order note content. this function is for getting payment slip if "seed confirm pro" plugin is active.
	 *
	 * @param $order
	 * @param $template_type
	 */
	public function woo_invo_ice_add_slip_to_order_note( $order, $template_type ) {

		if ( 'invoice' === $template_type || 'credit_note' === $template_type ) {
			$args = array(
				'post_type'  => 'seed_confirm_log',
				'meta_key'   => 'seed-confirm-order',
				'meta_value' => $order->get_id()
			); //phpcs:ignore;

			$posts = get_posts( $args );

			if ( empty( $posts ) ) {
				return;
			}

			$post_id  = $posts[0]->ID;
			$file_url = get_post_meta( $post_id, 'seed-confirm-image', true );

			if ( empty( $file_url ) ) {
				return;
			}

			$filetype  = wp_check_filetype( $file_url );
			$file_icon = $file_url;
			if ( strpos( $filetype['type'], 'application' ) !== false ) {
				if ( "pdf" === $filetype['ext'] ) {
					$file_icon = plugin_dir_url( __FILE__ ) . 'img/pdf.png';
				} else {
					$file_icon = plugin_dir_url( __FILE__ ) . 'img/zip.png';
				}
			}
			$default_width  = apply_filters( 'woo_invo_ice_payment_slip_width', '200' );
			$default_height = apply_filters( 'woo_invo_ice_payment_slip_height', '200' );
			$file_html      = sprintf( wp_kses(
				'<a href="%s" target="_blank"><img src="%s" height="' . $default_height . '" width="' . $default_width . '"></a>',
				array(
					'a'   => array(
						'href'   => array(),
						'target' => array(),
					),
					'img' => array(
						'src'    => array(),
						'width'  => array(),
						'height' => array(),
					),
				)
			), esc_url( $file_url ), esc_url( $file_icon ) );

			$output = sprintf( '<div>
            <h3>' . woo_invo_ice_filter_label( 'Payment Slip', $order, $template_type ) . '</h3>
            <p>%1$s</p>
            </div>', $file_html );
			echo $output; //phpcs:ignore

			return;
		}

		return;

	}

	/**
	 * Get after order note content. this function is for getting payment slip if "wc-confirm-payment" plugin is active.
	 *
	 * @param $order
	 * @param $template_type
	 */
	public function woo_invo_ice_add_payment_slip_to_order_note( $order, $template_type ) {

		if ( 'invoice' === $template_type || 'credit_note' === $template_type ) {

			$payment_id     = get_post_meta( $order->get_id(), '_wcp_order_payment_id', true );
			$default_width  = apply_filters( 'woo_invo_ice_payment_slip_width', '100' );
			$default_height = apply_filters( 'woo_invo_ice_payment_slip_height', '100' );

			if ( has_post_thumbnail( $payment_id ) ) {
				echo '<h4>' . woo_invo_ice_filter_label( 'Payment Slip', $order, $template_type ) . '</h4><br/>';// phpcs:ignore
				echo '<a target="_blank"  href="' . get_the_post_thumbnail_url( $payment_id, 'full' ) . '">' . get_the_post_thumbnail( $payment_id, array(
						$default_height,
						$default_width
					) ) . '</a>';// phpcs:ignore
			}
		}
	}


}


new Woo_Invo_Ice_Hooks();
