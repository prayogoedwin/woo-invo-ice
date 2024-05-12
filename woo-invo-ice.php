<?php
/**
 * Plugin Name:     Woo Invo Ice
 * Plugin URI:      https://astamatechnology.com/woo-invo-ice
 * Description:     Plugin description is here.
 * Version:         1.0.0
 * Author:          Astama Technology
 * Author URI:      https://astamatechnology.com/
 * License:         Private
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

define( 'WOO_INVO_ICE_VERSION', '1.0.0' );

if ( ! defined( 'WOO_INVO_ICE_PATH' ) ) {
    /**
     * Plugin Path with trailing slash
     *
     * @var string dirname( __FILE__ )
     */
    define( 'WOO_INVO_ICE_PATH', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'WOO_INVO_ICE_PLUGIN_URL' ) ) {
    /**
     * Plugin Directory URL.
     *
     * @var string
     * @since 1.2.2
     */
    define( 'WOO_INVO_ICE_PLUGIN_URL', trailingslashit( plugin_dir_url( __FILE__ ) ) );
}

if ( ! defined( 'WOO_INVO_ICE_BASE_NAME' ) ) {
    /**
     * Plugin Base name..
     *
     * @var string
     * @since 1.2.2
     */
    define( 'WOO_INVO_ICE_BASE_NAME', plugin_basename( __FILE__ ) );
}

// is uploads folder writable
function woo_invo_ice_is_uploads_folder_writable() {
    $upload_dir             = wp_upload_dir();
    $base_dir               = $upload_dir['basedir'];

    if ( is_writable( $base_dir ) ) {
        return true;
    }
    return false;
}

/**
 * Process Macros for order or invoice id
 *
 * @param int    $order_id Order id.
 * @param string $order_no order no.
 *
 * @return mixed
 */
function woo_invo_ice_process_date_macros( $order_id, $order_no ) {
    $order_created = get_the_date( 'Y-m-d', $order_id );
    if ( strpos( $order_no, '{{day}}' ) !== false ) {
        $order_no = str_replace( '{{day}}', gmdate( 'd', strtotime( $order_created ) ), $order_no );
    }
    if ( strpos( $order_no, '{{month}}' ) !== false ) {
        $order_no = str_replace( '{{month}}', gmdate( 'm', strtotime( $order_created ) ), $order_no );
    }
    if ( strpos( $order_no, '{{year}}' ) !== false ) {
        $order_no = str_replace( '{{year}}', gmdate( 'Y', strtotime( $order_created ) ), $order_no );
    }

    return $order_no;
}

/**
 * Get Invoice number by order id
 *
 * @param int $id Order Id.
 *
 * @return mixed|string
 */
function woo_invo_ice_get_invoice_number( $id ) {
    // this key is deprecated from version 4.1.1 .
    // But still need for getting our plugin's generated invoice number.
    $invoice_no = get_post_meta($id, 'wiopt_invoice_no', true);

    if ( empty($invoice_no) ) {
        // Check for previous installed third party plugins generated invoice number.
        if ( '' != get_post_meta( $id, '_wcpdf_invoice_number', true) ) {
            // Plugin : woocommerce-pdf-invoices-packing-slips
            $invoice_no = get_post_meta( $id, '_wcpdf_invoice_number', true);

            return $invoice_no;
        } elseif ( '' != get_post_meta( $id, '_bewpi_invoice_pdf_path', true) ) {
            //Plugin : woocommerce-pdf-invoices
            //this is a path need to find out the invoice number.
            $post_meta = get_post_meta( $id, '_bewpi_invoice_pdf_path', true);
            preg_match('/[\/]{1}[\d\D]{1,}[.]{1}/i', $post_meta , $matches);
            $invoice_no = substr($matches[0], 1, -1);

            return $invoice_no;
        } elseif ( '' != get_post_meta( $id, 'wf_invoice_number', true) ) {
            // Plugin : print-invoices-packing-slip-labels-for-woocommerce
            $invoice_no = get_post_meta( $id, 'wf_invoice_number', true);

            return $invoice_no;
        } elseif ( '' != get_post_meta( $id, '_wcdn_invoice_number', true) ) {
            // Plugin : woocommerce-delivery-notes
            $invoice_no = get_post_meta( $id, '_wcdn_invoice_number', true);

            return $invoice_no;
        } elseif ( '' != get_post_meta( $id, '_ywpi_invoice_number', true) ) {
            //Plugin : woocommerce-delivery-notes
            $invoice_no = get_post_meta( $id, '_ywpi_invoice_number', true);

            return $invoice_no;
        } else {
            // Plugin : astama-pdf-invoice-for-woocommerce-pro
            $invoice_no = $id;

            // Invoice Number Type.
            $get_number_type = get_option( 'wiopt_invoice_number_type' );

            // Get Prefix.
            $prefix = get_option( 'wiopt_invoice_no_prefix' );
            $prefix = ! empty( $prefix ) ? $prefix : '';

            // Get Suffix.
            $suffix = get_option( 'wiopt_invoice_no_suffix' );
            $suffix = ! empty( $suffix ) ? $suffix : '';

            // Get next number for custom sequence.
            // $next_no = get_option( 'wiopt_invoice_no' );
            // $next_no = ! empty( $next_no ) ? $next_no : 1;

            // Generate Invoice Number.
            if ( 'pre_custom_number_suf' === $get_number_type ) {
                $invoice_no = $prefix . $id . $suffix;
            } elseif ( 'pre_order_number_suf' === $get_number_type ) {
                $invoice_no = $prefix . $id . $suffix;
            }

            $invoice_no = woo_invo_ice_process_date_macros( $id, $invoice_no );

            update_post_meta( $id, 'wiopt_invoice_no_'.$id, $invoice_no );
        }
    }

    return $invoice_no;
}

if ( ! defined( 'WOO_INVO_ICE_DIR' ) ) {
    /**
     * Custom Font Directory.
     *
     * @var string.
     * @since 2.3.1
     */
    $upload_dir        = wp_upload_dir();
    $base_dir          = $upload_dir['basedir'];
    $wiopt_invoice_dir = $base_dir."/woo-invo-ice";
    define( 'WOO_INVO_ICE_DIR', $wiopt_invoice_dir . '/' );
    if ( woo_invo_ice_is_uploads_folder_writable() ) {
        if ( ! file_exists(WOO_INVO_ICE_DIR.'.htaccess' ) ) {
            mkdir( WOO_INVO_ICE_DIR, 0777, true );
            // Protect files from public access.
            $content = 'deny from all';
            $fp = fopen(WOO_INVO_ICE_DIR . '.htaccess', 'wb');//phpcs:ignore
            fwrite($fp, $content);//phpcs:ignore
            fclose($fp);//phpcs:ignore
        }
    }
}

if ( ! defined( 'WOO_INVO_ICE_FONT_DIR' ) ) {
    /**
     * Custom Font Directory.
     *
     * @var string
     * @since 2.3.1
     */
    $upload_dir             = wp_upload_dir();
    $base_dir               = $upload_dir['basedir'];
    $wiopt_invoice_font_dir = WOO_INVO_ICE_DIR."woo-invo-ice-fonts";
    define( 'WOO_INVO_ICE_FONT_DIR', $wiopt_invoice_font_dir . '/' );
    if ( woo_invo_ice_is_uploads_folder_writable() ) {
        if ( ! file_exists(WOO_INVO_ICE_FONT_DIR ) ) {
            mkdir( $wiopt_invoice_font_dir, 0777, true );
            // Protect files from public access.
            $content = 'deny from all';
            $fp      = fopen( WOO_INVO_ICE_FONT_DIR . '.htaccess', 'wb' );//phpcs:ignore
            fwrite( $fp, $content );//phpcs:ignore
            fclose( $fp );//phpcs:ignore
        }
    }
}

// Main class of the plugin.
class Woo_Invo_Ice {

    public function __construct() {
        // HPOS compatibility
        if( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
            add_action( 'before_woocommerce_init', function () {
                if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
                    \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
                }
            } );
        }
    }

    /**
     * Loading all dependencies
     * 
     * @return void
     */
    public function load() {
        include_once WOO_INVO_ICE_PATH . 'includes/vendor/autoload.php';
        include_once WOO_INVO_ICE_PATH . 'includes/helper.php';
        include_once WOO_INVO_ICE_PATH . 'includes/hooks.php';
        include_once WOO_INVO_ICE_PATH . 'includes/class-woo-invo-ice-generare-qrcode.php';
        include_once WOO_INVO_ICE_PATH . 'includes/class-woo-invo-ice-tag.php';
        
        include_once WOO_INVO_ICE_PATH . 'includes/class-woo-invo-ice-helper.php';
        include_once WOO_INVO_ICE_PATH . 'includes/class-woo-invo-ice-pdf.php';
        include_once WOO_INVO_ICE_PATH . 'includes/class-woo-invo-ice-hooks.php';
        include_once WOO_INVO_ICE_PATH . 'includes/class-woo-invo-ice-orders.php';
		include_once WOO_INVO_ICE_PATH . 'includes/class-woo-invo-ice-engine.php';
        include_once WOO_INVO_ICE_PATH . 'includes/class-woo-invo-ice-template.php';

        add_action( 'wp_ajax_wiopt_generate_invoice', array( $this, 'woo_invo_ice_generate_invoice' ) );
        add_action( 'wp_ajax_nopriv_wiopt_generate_invoice', array( $this, 'woo_invo_ice_generate_invoice' ) );
        
        add_action( 'wp_ajax_wiopt_generate_invoice_packing_slip', array( $this, 'woo_invo_ice_generate_packing_slip' ) );
        add_action( 'wp_ajax_nopriv_wiopt_generate_invoice_packing_slip', array( $this, 'woo_invo_ice_generate_packing_slip' ) );
    
        add_action( 'wp_ajax_wiopt_generate_shipping_label', array( $this, 'woo_invo_ice_generate_shipping_label' ) );
        add_action( 'wp_ajax_nopriv_wiopt_generate_shipping_label', array( $this, 'woo_invo_ice_generate_shipping_label' ) );
    }

    /**
     * Who can generate pdf.
     */
    public function woo_invo_ice_pdf_can_generate( $order ) {
        $is_invoice_generate_ignore = apply_filters( 'wiopt_invoice_generate_ignore', true, $order );

        if ( ! $is_invoice_generate_ignore ) {
            wp_die('Invoice can not generated');
        }

        $invoice_can_generate = ( null != get_option('wiopt_invoice_can_generate') ) ? get_option('wiopt_invoice_can_generate') : [];
        
        $user  = $order->get_user_id();
        $default_role = [
            'administrator',
            'shop_manager',
        ];

        $default_role = array_unique(array_merge($default_role, $invoice_can_generate));
        foreach ( $default_role as $key => $value ) {
            if ( wc_user_has_role( get_current_user_id(), $value)
            || get_current_user_id() == $user ) {
                return true;
            } else {
                $status = false;
            }
        }

        return $status;
    }

    /**
     * Process PDF Invoice Making Action
     */
    public function woo_invo_ice_generate_invoice() {
        if ( ! is_user_logged_in() ) {
            auth_redirect();
            exit;
        }

        // Verify Nonce.
        $retrieved_nonce = isset( $_REQUEST['_wpnonce'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['_wpnonce'] ) ) : '';
        if ( ! wp_verify_nonce( $retrieved_nonce, 'wiopt_pdf_nonce' ) ) {
            die( 'Failed security check' );
        }

        if ( isset( $_REQUEST['order_id'] ) ) {
            $ids   = sanitize_text_field( wp_unslash( $_REQUEST['order_id'] ));
            $order = \wc_get_order( $ids );

            // Check order exist or not
            if ( empty( $order ) ) {
                die( 'Order not found.' );
            }

            if ( $this->woo_invo_ice_pdf_can_generate( $order ) ) {
                woo_invo_ice_engine( $ids )->generateInvoice( $ids );
            } else {
                die( 'You are not allowed to download this invoice.' );
            }
        } else if ( isset( $_REQUEST['order_ids'] ) ) {
            $order_ids = sanitize_text_field( wp_unslash( $_REQUEST['order_ids'] ));

            if ( '' !== $order_ids ) {
                $ids = explode( ',', $order_ids)[0];
                $order = \wc_get_order( $ids );

                // Check order exist or not
                if ( empty( $order ) ) {
                    die( 'Order not found.' );
                }
            } else {
                die( 'There is no order is selected.' );
            }

            if ( $this->woo_invo_ice_pdf_can_generate( $order ) ) {
                woo_invo_ice_engine( $order_ids )->generateInvoice( $order_ids );
            } else {
                die( 'You are not allowed to download invoices.' );
            }
        }
    }

    /**
     * Process PDF Packing slip Making Action
     */
    function woo_invo_ice_generate_packing_slip() {
        // Verify Nonce.
        check_ajax_referer( 'wiopt_pdf_nonce' );
        $order_ids = '';
        if ( isset( $_REQUEST['order_id'] ) ) {
            $order_ids = sanitize_text_field( wp_unslash( $_REQUEST['order_id'] ));
        } elseif ( isset( $_REQUEST['order_ids'] ) ) {
            $order_ids = sanitize_text_field( wp_unslash( $_REQUEST['order_ids'] ));
        }
        $vendor = null;
        if ( isset( $_REQUEST['vendor'] ) ) {
            $vendor = sanitize_text_field( wp_unslash( $_REQUEST['vendor'] ));
        }

        woo_invo_ice_engine( $order_ids )->generatePackingSlip( $order_ids, null, null, $vendor );
    }

    /**
     * Process Shipping label action
     */
    function woo_invo_ice_generate_shipping_label() {
        // Verify Nonce.
        check_ajax_referer( 'wiopt_pdf_nonce' );
        $order_ids = '';
        if ( isset( $_REQUEST['order_id'] ) ) {
            $order_ids = sanitize_text_field( wp_unslash( $_REQUEST['order_id'] ));
        } elseif ( isset( $_REQUEST['order_ids'] ) ) {
            $order_ids = sanitize_text_field( wp_unslash( $_REQUEST['order_ids'] ));
        }

        $columns    = ( isset( $_REQUEST['column'] ) ) ? sanitize_text_field( wp_unslash( $_REQUEST['column'] )) : '';
        $rows       = ( isset( $_REQUEST['row'] ) ) ? sanitize_text_field( wp_unslash( $_REQUEST['row'] )) : '';
        $paper_size = ( isset( $_REQUEST['paper_size'] ) ) ? sanitize_text_field( wp_unslash( $_REQUEST['paper_size'] )) : '';
        $font_size  = ( isset( $_REQUEST['font'] ) ) ? sanitize_text_field( wp_unslash( $_REQUEST['font'] )) : '';

        woo_invo_ice_engine( $order_ids, $columns, $paper_size, $rows, $font_size )->generateShippingLabel( $order_ids );
    }
}

// Initialize the plugin.
function plugin_load() {
    $class = new Woo_Invo_Ice();
    $class->load();
}
add_action( 'woocommerce_init', 'plugin_load' );
