<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link  https://webappick.com
 * @since 1.0.0
 *
 * @package    Woo_Invoice_Pro
 * @subpackage Woo_Invoice_Pro/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Woo_Invoice_Pro
 * @subpackage Woo_Invoice_Pro/admin
 * @author     Md Ohidul Islam <wahid@webappick.com>
 */
class Woo_Invoice_Pro_Admin {


	/**
	 * The ID of this plugin.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version     The version of this plugin.
	 *
	 * @since 1.0.0
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_base_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Woo_Invoice_Pro as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Woo_Invoice_Pro will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/minify/woo-invoice-pro-admin.min.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Woo_Invoice_Pro as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Woo_Invoice_Pro will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_style( $this->plugin_name . '_selectize-css', plugin_dir_url( __FILE__ ) . 'css/minify/selectize.min.css', array(), $this->version );
		wp_enqueue_style( $this->plugin_name . '_woo-invoice-boilerplate', plugin_dir_url( __FILE__ ) . 'css/minify/woo-invoice-pro-boilerplate-admin.min.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name . '_flatpickr', plugin_dir_url( __FILE__ ) . 'css/minify/flatpickr.min.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name . '_wp-color-picker' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_scripts() {
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Woo_Invoice_Pro as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Woo_Invoice_Pro will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/woo-invoice-pro-admin.js', array( 'jquery' ), $this->version, false );
		$wpifw_nonce = wp_create_nonce( 'wpifw_pdf_nonce' );
		wp_localize_script(
			$this->plugin_name,
			'wpifw_ajax_obj',
			array(
				'wpifw_ajax_url' => admin_url( 'admin-ajax.php' ),
				'nonce'          => $wpifw_nonce,
			)
		);

		// Download Custom Fonts Js.
		wp_enqueue_script( $this->plugin_name . 'download-fonts', plugin_dir_url( __FILE__ ) . 'js/woo-invoice-font.js', array( 'jquery' ), $this->version, true );
		$wpifw_nonce = wp_create_nonce( 'wpifw_pdf_nonce' );
		wp_localize_script(
			$this->plugin_name . 'download-fonts',
			'wpifw_ajax_obj_font',
			array(
				'wpifw_ajax_font_url' => admin_url( 'admin-ajax.php' ),
				'nonce'               => $wpifw_nonce,
			)
		);

		// Download DropBox API
		wp_enqueue_script( $this->plugin_name . 'dropboxapi', plugin_dir_url( __FILE__ ) . 'js/woo-invoice-dropboxapi.js', array( 'jquery' ), $this->version, true );
		$wpifw_nonce = wp_create_nonce( 'wpif_drobox_api_nonce' );
		wp_localize_script(
			$this->plugin_name . 'dropboxapi',
			'wpifw_ajax_obj_dropboxapi',
			array(
				'wpifw_ajax_dropboxapi_url' => admin_url( 'admin-ajax.php' ),
				'nonce'                     => $wpifw_nonce,
			)
		);

		wp_enqueue_script( $this->plugin_name, 'wpifw_ajax_obj', array( 'jquery' ), $this->version, true );
		wp_enqueue_script( $this->plugin_name . '_jquery-selectize', plugin_dir_url( __FILE__ ) . 'js/selectize.min.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( $this->plugin_name . '_boilerplate', plugin_dir_url( __FILE__ ) . 'js/woo-invoice-pro-bundle.min.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( $this->plugin_name . '_flatpickr-js', plugin_dir_url( __FILE__ ) . 'js/flatpickr.min.js', array( 'jquery' ), $this->version, false );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_common_scripts() {
		wp_enqueue_script( $this->plugin_name . 'common', plugin_dir_url( __FILE__ ) . 'js/woo-invoice-common.js', array( 'jquery' ), $this->version, false );
		$wpifw_nonce = wp_create_nonce( 'wpifw_pdf_nonce' );
		wp_localize_script(
			$this->plugin_name . 'common',
			'wpifw_ajax_obj_2',
			array(
				'wpifw_ajax_url_2' => admin_url( 'admin-ajax.php' ),
				'nonce'            => $wpifw_nonce,
			)
		);
		wp_enqueue_script( $this->plugin_name . 'common', 'wpifw_ajax_obj_2', array( 'jquery' ), $this->version, true );

	}

	/**
	 * Load Plugin Settings Menu
	 */
	public function load_admin_menu() {
		$hook = add_menu_page( __( 'Challan', 'webappick-pdf-invoice-for-woocommerce' ), __( 'Challan', 'webappick-pdf-invoice-for-woocommerce' ), 'manage_woocommerce', 'webappick-woo-invoice-settings', 'woo_invoice_pro', 'dashicons-media-spreadsheet' );
		add_submenu_page( 'webappick-woo-invoice-settings', __( 'Settings', 'webappick-pdf-invoice-for-woocommerce' ), __( 'Settings', 'webappick-pdf-invoice-for-woocommerce' ), 'manage_woocommerce', 'webappick-woo-invoice-settings', 'woo_invoice_pro' );
		add_submenu_page( 'webappick-woo-invoice-settings', __( 'Docs', 'webappick-pdf-invoice-for-woocommerce' ), '<span class="woo-invoice-docs">' . __( 'Docs', 'webappick-pdf-invoice-for-woocommerce' ). '</span>', 'manage_woocommerce', 'webappick-woo-invoice-docs', 'woo_invoice_pro_docs' );

		add_action( 'admin_print_scripts-' . $hook, array( $this, 'enqueue_styles' ) );
		add_action( 'admin_print_scripts-' . $hook, array( $this, 'enqueue_scripts' ) );
	}

	/**
	 * Redirect user to with new menu slug (if user browser any bookmarked url)
	 *
	 * @return void
	 * @since  1.3.1
	 */
	public function handle_old_menu_slugs() {
		global $pagenow;
		// redirect user to new old slug => new slug.
		$redirect_to = array(
			'webappick-pdf-invoice-for-woocommerce-pro/admin/class-woo-invoice-pro-admin.php' => 'webappick-woo-invoice-settings',
		);
		if ( 'admin.php' === $pagenow && isset( $_GET['page'] ) && ! empty( $_GET['page'] ) ) {
			foreach ( $redirect_to as $from => $to ) {
				if ( $_GET['page'] !== $from ) {
					continue;
				}
				wp_safe_redirect( admin_url( 'admin.php?page=' . $to ), 301 );
				die();
			}
		}
	}
}



