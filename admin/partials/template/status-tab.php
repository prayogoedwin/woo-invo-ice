<li class="woo-invoice-shipping-label-li">
	<div class="_winvoice-row">
		<div class="_winvoice-col-8">
			<div class="_winvoice-card _winvoice-mr-0">
				<div class="_winvoice-card-header">
					<div class="_winvoice-card-header-title">
						<h3><?php esc_html_e( 'System Status', 'webappick-pdf-invoice-for-woocommerce' ); ?></h3>
					</div>
				</div>
				<div class="_winvoice-card-body">

                    <table class="system-status-table">
                        <tbody>
                        <?php
                        // Read plugin header data.
                        $chalan_plugin_data = get_plugin_data( CHALLAN_PRO_ROOT_FILE );

                        // WordPress check upload file size.
                        function calan_wp_minimum_upload_file_size() {
	                        $wp_size = wp_max_upload_size();
	                        if ( ! $wp_size ) {
		                        $wp_size = 'unknown';
	                        } else {
		                        $wp_size = round( ( $wp_size / 1024 / 1024 ) );
		                        $wp_size = $wp_size == 1024 ? '1GB' : $wp_size . 'MB'; //phpcs:ignore
	                        }

	                        return $wp_size;
                        }

                        // Minimum upload size set by hosting provider.
                        function calan_wp_upload_size_by_from_hosting() {
	                        $ini_size = ini_get( 'upload_max_filesize' );
	                        if ( ! $ini_size ) {
		                        $ini_size = 'unknown';
	                        } elseif ( is_numeric( $ini_size ) ) {
		                        $ini_size .= ' bytes';
	                        } else {
		                        $ini_size .= 'B';
	                        }

	                        return $ini_size;
                        }

                        function convertToBytes( string $from ) {
	                        $units  = [ 'B', 'KB', 'MB', 'GB', 'TB', 'PB' ];
	                        $number = substr( $from, 0, - 2 );
	                        $suffix = strtoupper( substr( $from, - 2 ) );

	                        //B or no suffix
	                        if ( is_numeric( substr( $suffix, 0, 1 ) ) ) {
		                        return preg_replace( '/[^\d]/', '', $from );
	                        }

	                        $exponent = array_flip( $units )[ $suffix ] ?? null;
	                        if ( $exponent === null ) { //phpcs:ignore
		                        return null;
	                        }

	                        return $number * ( 1024 ** $exponent );
                        }

                        // Check upload folder is writable.
                        function calan_upload_filter_is_writable() {
	                        $upload_dir                   = wp_upload_dir();
	                        $base_dir                     = $upload_dir['basedir'];
	                        $wpifw_invoice_dir            = $base_dir . "/WOO-INVOICE";
	                        $upload_dir_permission_status = '';
	                        $upload_dir_permission_status = ! file_exists( $wpifw_invoice_dir ) && ! is_writable( $wpifw_invoice_dir ) && ! is_writable( $base_dir ) ? 0 : '1';

	                        return $upload_dir_permission_status;
                        }

                        // Check zipArchive extension enable from hosting.
                        function chalan_check_zip_extension() {
	                        $extension = '';
	                        $extension = in_array( 'zip', get_loaded_extensions() );

	                        return $extension;
                        }

                        // Check MBstring extension enable from hosting.
                        function chalan_check_mbstring_extension() {
	                        $extension = '';
	                        $extension = in_array( 'mbstring', get_loaded_extensions() );

	                        return $extension;
                        }

                        // Check dom extension

                        function chalan_check_dom_extension() {
	                        $extension = '';
	                        $extension = in_array( 'dom', get_loaded_extensions() );

	                        return $extension;
                        }

                        // Minimum PHP version.
                        $chalan_current_php_version = phpversion();
                        $chalan_minimum_php_version = $chalan_plugin_data['RequiresPHP'] ? $chalan_plugin_data['RequiresPHP'] : '5.6';
                        $chalan_php_version_status         = $chalan_current_php_version < $chalan_minimum_php_version ? 0 : 1;

                        // Minimum WordPress Version.
                        $chalan_wp_current_version = get_bloginfo( 'version' );
                        $chalan_minimum_wp_version = $chalan_plugin_data['RequiresWP'] ? $chalan_plugin_data['RequiresWP'] : '4.4';
                        $chalan_wp_version_status         = $chalan_wp_current_version < $chalan_minimum_wp_version ? 0 : 1;

                        // Minimum Woocommerce Version.
                        if ( class_exists('woocommerce') ) {
	                        $chalan_wc_current_version = WC_VERSION;
                        }else {
	                        $chalan_wc_current_version = 'Not Active Woocommerce';
                        }

                        $chalan_minimum_wc_version = isset( $chalan_plugin_data['WC requires at least'] ) ? $chalan_plugin_data['WC requires at least'] : '3.2';
                        $chalan_wc_status = $chalan_wc_current_version < $chalan_minimum_wc_version ? 0 : 1;

                        // WordPress minimum upload size .
                        $calan_wp_minimum_upload_file_size = '40MB';

                        // Minimum WordPress upload size..
                        $chalan_wp_upload_size_status = convertToBytes( calan_wp_minimum_upload_file_size() ) < convertToBytes( $calan_wp_minimum_upload_file_size ) ? 0 : 1;

                        // Minimum upload file size from hosting provider.
                        $chalan_wp_upload_size_status_from_hosting = convertToBytes( calan_wp_upload_size_by_from_hosting() ) < convertToBytes( $calan_wp_minimum_upload_file_size ) ? 0 : 1;

                        // PHP Limit Time
                        $chalan_php_minimum_limit_time = '120';
                        $chalan_php_current_limit_time = ini_get('max_execution_time');
                        $chalan_php_limit_time_status = $chalan_php_minimum_limit_time <= $chalan_php_current_limit_time ? 1 : 0;

                        // Check WordPress debug status.
                        $chalan_wp_debug_status = WP_DEBUG == true ? 1 : 0;

                        // Check upload folder is writable.
                        $chalan_uplaod_folder_writable_status = calan_upload_filter_is_writable() == 0 ? 0 : 1;

                        // Check if zipArchie extension is enable in hosting.
                        $chalan_check_zip_extension_status = chalan_check_zip_extension() != '1' ? 0 : '1';

                        // Check MBstring extension from hsoting.
                        $chalan_check_mbstring_extension_status = chalan_check_mbstring_extension() != '1' ? 0 : '1';

                        // Check dom extension.
                        $chalan_check_dom_extension_status = chalan_check_dom_extension() != '1' ? 0 : '1';

                        $system_status = array(

	                        array(
		                        'title'           => esc_html__( 'PHP Version', 'webappick-pdf-invoice-for-woocommerce' ),
		                        'version'         => esc_html__('Current Version:  ', 'webappick-pdf-invoice-for-woocommerce') . $chalan_current_php_version,
		                        'status'          => $chalan_php_version_status,
		                        'success_message' => esc_html__( '- ok', 'webappick-pdf-invoice-for-woocommerce' ),
		                        'error_message' => esc_html__( 'Required Version: ', 'webappick-pdf-invoice-for-woocommerce' ) . $chalan_minimum_php_version,//phpcs:ignore
	                        ),

	                        array(
		                        'title'           => esc_html__( 'WordPress Version', 'webappick-pdf-invoice-for-woocommerce' ),
		                        'version'         => $chalan_wp_current_version,
		                        'status'          => $chalan_wp_version_status,
		                        'success_message' => esc_html__( '- ok', 'webappick-pdf-invoice-for-woocommerce' ),
		                        'error_message' => esc_html__( 'Required: ', 'webappick-pdf-invoice-for-woocommerce') . $chalan_minimum_wp_version , //phpcs:ignore
	                        ),

	                        array(
		                        'title'           => esc_html__( 'Woocommerce Version', 'webappick-pdf-invoice-for-woocommerce' ),
		                        'version'         => $chalan_wc_current_version,
		                        'status'          => $chalan_wc_status,
		                        'success_message' => esc_html__( '- ok', 'webappick-pdf-invoice-for-woocommerce' ),
		                        'error_message' => esc_html__( 'Required: ', 'webappick-pdf-invoice-for-woocommerce') . $chalan_minimum_wc_version, //phpcs:ignore
	                        ),

	                        array(
		                        'title'           => esc_html__( 'WordPress Upload Limit', 'webappick-pdf-invoice-for-woocommerce' ),
		                        'version'         => calan_wp_minimum_upload_file_size(),
		                        'status'          => $chalan_wp_upload_size_status,
		                        'success_message' => esc_html__( '- ok', 'webappick-pdf-invoice-for-woocommerce' ),
		                        'error_message' => esc_html__( 'Required:', 'webappick-pdf-invoice-for-woocommerce' ) . $calan_wp_minimum_upload_file_size,	//phpcs:ignore
	                        ),

	                        array(
		                        'title'           => esc_html__( 'WordPress Upload Limit Set By Hosting Provider', 'webappick-pdf-invoice-for-woocommerce' ),
		                        'version'         => calan_wp_upload_size_by_from_hosting(),
		                        'status'          => $chalan_wp_upload_size_status_from_hosting,
		                        'success_message' => esc_html__( '- ok', 'webappick-pdf-invoice-for-woocommerce' ),
		                        'error_message' => esc_html__( 'Required:', 'webappick-pdf-invoice-for-woocommerce' ) . $calan_wp_minimum_upload_file_size, //phpcs:ignore
	                        ),

	                        array(
		                        'title'           => esc_html__( 'PHP Limit Time', 'webappick-pdf-invoice-for-woocommerce' ),
		                        'version'         => esc_html__('Current Limit Time: ', 'webappick-pdf-invoice-for-woocommerce') . $chalan_php_current_limit_time,
		                        'status'          => $chalan_php_limit_time_status,
		                        'success_message' => esc_html__( '- Ok', 'webappick-pdf-invoice-for-woocommerce' ),
		                        'error_message' => esc_html__( 'Required:', 'webappick-pdf-invoice-for-woocommerce' ) . $chalan_php_minimum_limit_time,	//phpcs:ignore
	                        ),


	                        array(
		                        'title'           => esc_html__( 'WordPress Upload Directory Writable Permission', 'webappick-pdf-invoice-for-woocommerce' ),
		                        'version'         => '',
		                        'status'          => $chalan_uplaod_folder_writable_status,
		                        'success_message' => esc_html__( 'Writable - Ok', 'webappick-pdf-invoice-for-woocommerce' ),
		                        'error_message'   => esc_html__( 'Upload folder not writable permission', 'webappick-pdf-invoice-for-woocommerce' ),
	                        ),

	                        array(
		                        'title'           => esc_html__( 'WordPress Debug Mode', 'webappick-pdf-invoice-for-woocommerce' ),
		                        'version'         => '',
		                        'status'          => $chalan_wp_debug_status,
		                        'success_message' => esc_html__( 'WordPress Debug Mode is On', 'webappick-pdf-invoice-for-woocommerce' ),
		                        'error_message'   => __( '<b>WP_DEBUG_LOG</b> is false. Plugin can not write error logs if WP_DEBUG_LOG is set to false. You can learn more about debugging in WordPress from <a target="_blank" href="https://wordpress.org/support/article/debugging-in-wordpress/">here</a>', 'webappick-pdf-invoice-for-woocommerce' ),
	                        ),

	                        array(
		                        'title'           => esc_html__( 'zipArchive Extension', 'webappick-pdf-invoice-for-woocommerce' ),
		                        'version'         => '',
		                        'status'          => $chalan_check_zip_extension_status,
		                        'success_message' => esc_html__( 'Enable', 'webappick-pdf-invoice-for-woocommerce' ),
		                        'error_message'   => esc_html__( 'Please enable zip extension from hosting.', 'webappick-pdf-invoice-for-woocommerce' ),
	                        ),

	                        array(
		                        'title'           => esc_html__( 'MBString extension', 'webappick-pdf-invoice-for-woocommerce' ),
		                        'version'         => '',
		                        'status'          => $chalan_check_mbstring_extension_status,
		                        'success_message' => esc_html__( 'Enable', 'webappick-pdf-invoice-for-woocommerce' ),
		                        'error_message'   => esc_html__( 'Please enable MBString extension from hosting.', 'webappick-pdf-invoice-for-woocommerce' ),
	                        ),

	                        array(
		                        'title'           => esc_html__( 'Dom extension', 'webappick-pdf-invoice-for-woocommerce' ),
		                        'version'         => '',
		                        'status'          => $chalan_check_dom_extension_status,
		                        'success_message' => esc_html__( 'Enable', 'webappick-pdf-invoice-for-woocommerce' ),
		                        'error_message'   => esc_html__( 'Dom extension is not enable from hosting.', 'webappick-pdf-invoice-for-woocommerce' ),
	                        ),
                        );
                        ?>
                        <tr>
                            <th>Title</th>
                            <th>Status</th>
                            <th>Message</th>
                        </tr>
                        <!-- PHP Version -->
                        <?php
                        foreach ( $system_status as $value ) { ?>
                        <tr>
                            <td><?php printf( '%s', esc_html( $value['title'] ) ); ?></td>

                            <td>
		                        <?php if ( 1 == $value['status'] ) { ?>
                                    <span class="dashicons dashicons-yes"></span>
		                        <?php } else { ?>
                                    <span class="dashicons dashicons-warning"></span>

		                        <?php }; ?>
                            </td>
                            <td>
		                        <?php if ( 1 == $value['status'] ) { ?>
                                    <p class="wpifw_status_message">  <?php printf( '%s', esc_html( $value['version'] ) ); ?> <?php echo $value['success_message']; //phpcs:ignore ?></p>
		                        <?php } else { ?>
			                        <?php printf( '%s', esc_html( $value['version'] ) ); ?>
                                    <p class="wpifw_status_message"><?php echo $value['error_message']; //phpcs:ignore ?></p>

		                        <?php }; ?>

                            </td>
                        </tr>
                        <?php } ?>

                        </tbody></table>
				</div>
			</div>

    </div>
        <!--  Status-tab Sidebar -->
        <div class="_winvoice-col-sm-4 _winvoice-col-12">
            <!--    Banner Section start    -->
			<?php include(plugin_dir_path(__FILE__) . 'lifetime-banner-widget.php'); ?>
            <!-- End Banner section -->
        </div>
</li>
