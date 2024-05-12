<li class="woo-invoice-static-files-li">
	<form action="" method="post">
		<?php wp_nonce_field( 'settings_form_nonce' ); ?>
		<div class="_winvoice-row">
			<div class="_winvoice-col-sm-8 _winvoice-col-12">
				<div class="_winvoice-card _winvoice-mr-0">
					<div class="_winvoice-card-header">
						<div class="_winvoice-card-header-title">
							<h3><?php esc_html_e( 'Static Files', 'webappick-pdf-invoice-for-woocommerce' ); ?></h3>
						</div>
					</div>
					<div class="_winvoice-card-body wpifw_static_files_section">
						<div class="_winvoice-form-group wpifw_static_files" tooltip=""
						     flow="right">
							<input type="text"
							       class="_winvoice-form-control wpifw_static_files_0"
							       id="wpifw_static_files_0" name="wpifw_static_files_0"
							       value="<?php echo esc_attr( get_option( 'wpifw_static_files_0' ) ); ?>">
							<?php wp_enqueue_media(); ?>
							<input id="wpifw_upload_static_files_0_button" type="button"
							       class="_winvoice-btn _winvoice-btn-primary"
							       value="<?php esc_html_e( 'Set File', 'webappick-pdf-invoice-for-woocommerce' ); ?>"/>
						</div>
						<div class="_winvoice-form-group wpifw_static_files" tooltip=""
						     flow="right">
							<input type="text"
							       class="_winvoice-form-control wpifw_static_files_1"
							       id="wpifw_static_files_1" name="wpifw_static_files_1"
							       value="<?php echo esc_attr( get_option( 'wpifw_static_files_1' ) ); ?>">
							<?php wp_enqueue_media(); ?>
							<input id="wpifw_upload_static_files_1_button" type="button"
							       class="_winvoice-btn _winvoice-btn-primary"
							       value="<?php esc_html_e( 'Set File', 'webappick-pdf-invoice-for-woocommerce' ); ?>"/>
						</div>
						<div class="_winvoice-form-group wpifw_static_files" tooltip=""
						     flow="right">
							<input type="text"
							       class="_winvoice-form-control wpifw_static_files_2"
							       id="wpifw_static_files_2" name="wpifw_static_files_2"
							       value="<?php echo esc_attr( get_option( 'wpifw_static_files_2' ) ); ?>">
							<?php wp_enqueue_media(); ?>
							<input id="wpifw_upload_static_files_2_button" type="button"
							       class="_winvoice-btn _winvoice-btn-primary"
							       value="<?php esc_html_e( 'Set File', 'webappick-pdf-invoice-for-woocommerce' ); ?>"/>
						</div>

						<div class="_winvoice-form-group">
							<span class="_winvoice-custom-checkbox-label"><?php esc_html_e( 'Attach to', 'webappick-pdf-invoice-for-woocommerce' ); ?></span>
							<div class="_winvoice-custom-checkbox-container">
                                <?php echo $get_order_status_html( $wpifw_static_attach_check_list, 'wpifw_static_attach_check_list'); ?>
							</div>

						</div>
						<div class="_winvoice-card-footer _winvoice-save-changes-selector">
							<input type="submit" style="float:right;" name="wpifw_submit_static_files"
							       value="<?php esc_html_e( 'Save Changes', 'webappick-pdf-invoice-for-woocommerce' ); ?>"
							       class="_winvoice-btn _winvoice-btn-primary"/>
						</div>

					</div>
				</div>
			</div>
            <!-- Static-files-tab Sidebar -->
            <div class="_winvoice-col-sm-4 _winvoice-col-12">
                <!--    Banner Section start    -->
				<?php include(plugin_dir_path(__FILE__) . 'lifetime-banner-widget.php'); ?>
                <!-- End Banner section -->
            </div>
		</div>
	</form>
</li>
