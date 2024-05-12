<li class="woo-invoice-seller-buyer-li">
	<form action="" method="post">
		<div class="_winvoice-row _winvoice-seller-block">
			<div class="_winvoice-col-sm-8 _winvoice-col-12">
				<div class="_winvoice-card _winvoice-mr-0">
					<div class="_winvoice-card-header">
						<div class="_winvoice-card-header-title">
							<h4><?php esc_html_e( 'Seller Info', 'webappick-pdf-invoice-for-woocommerce' ); ?></h4>
						</div>
					</div>
					<div class="_winvoice-card-body">
						<form action="" method="post">
							<?php wp_nonce_field( 'settings_form_nonce' ); ?>
							<div class="_winvoice-form-group">
								<label class="_winvoice-custom-label"
								       for="logo"><?php esc_html_e( 'Logo image', 'webappick-pdf-invoice-for-woocommerce' ); ?></label>

								<div style="display:inline-block;">
									<?php wp_enqueue_media(); ?>


										<?php
										$url = wp_get_attachment_url( get_option( 'wpifw_logo_attachment_image_id' ) );
										if ( get_option( 'wpifw_logo_attachment_image_id' ) != false && ! empty( get_option( 'wpifw_logo_attachment_image_id' ) ) ) {

											?>
                                                <div class='wpifw_logo-preview-wrapper' id="logo_assets">
                                                    <img class="_winvoice-logo-preview" id='wpifw_logo-preview'
                                                         src='<?php echo esc_url( $url ); ?>'>
                                                    <span class="dashicons dashicons-dismiss _winvoice-logo-preview upload_close wpifw_close_logo"></span>
                                                </div>
											<?php
										} else {
											?>
                                                <div class='wpifw_logo-preview-wrapper'>
                                                    <img class="_winvoice-logo-preview" id='wpifw_logo-preview'
                                                         src='<?php echo esc_url( $url ); ?>'>
                                                </div>
											<?php
										}
										?>
									<input id="wpifw_upload_logo_button" type="button"
									       class="_winvoice-btn _winvoice-btn-primary"
									       value="<?php esc_html_e( 'Upload Logo', 'webappick-pdf-invoice-for-woocommerce' ); ?>"/>
									<input type='hidden' name='wpifw_logo_attachment_id'
									       id='wpifw_logo_attachment_id'
									       value='<?php echo esc_attr( get_option( 'wpifw_logo_attachment_image_id' ) ); ?>'>

								</div>
							</div>


							<div class="_winvoice-form-group">
								<label class="_winvoice-custom-label"
								       for="logo-height-width"><?php esc_html_e( 'Logo size(Ex:20%)', 'webappick-pdf-invoice-for-woocommerce' ); ?></label>

								<input class="_winvoice-form-control _winvoice-uploaded-logo-width _winvoice-fixed-input"
								       style="width:300px;" type="text" name="wpifw_logo_width"
								       value='<?php echo esc_attr( get_option( 'wpifw_logo_width' ) ); ?>'>
							</div>

							<div class="_winvoice-form-group" style="display: none;">
								<label class="_winvoice-custom-label"
								       for="bltitle"><?php esc_html_e( 'Block title (From)', 'webappick-pdf-invoice-for-woocommerce' ); ?></label>
								<input type="text"
								       class="_winvoice-fixed-width _winvoice-form-control _winvoice-fixed-input"
								       id="bltitle" name="wpifw_block_title_from"
								       value="<?php echo esc_attr( get_option( 'wpifw_block_title_from' ) ); ?>">
							</div>

							<div class="_winvoice-form-group">
								<label class="_winvoice-custom-label"
								       for="cname"><?php esc_html_e( 'Company Name', 'webappick-pdf-invoice-for-woocommerce' ); ?></label>
								<input type="text"
								       class="_winvoice-fixed-width _winvoice-form-control _winvoice-fixed-input"
								       id="cname" name="wpifw_cname"
								       value="<?php echo esc_attr( get_option( 'wpifw_cname' ) ); ?>">
							</div>

                            <div class="_winvoice-form-group">
                                <label class="_winvoice-custom-label"
                                       for="wpifw_seller_vat_number"><?php esc_html_e( 'Company VAT Number', 'webappick-pdf-invoice-for-woocommerce' ); ?></label>
                                <input type="number"
                                       class="_winvoice-fixed-width _winvoice-form-control _winvoice-fixed-input"
                                       id="wpifw_seller_vat_number" name="wpifw_seller_vat_number"
                                       value="<?php echo esc_attr( get_option( 'wpifw_seller_vat_number' ) ); ?>">
                            </div>

							<div class="_winvoice-form-group">
								<label class="_winvoice-tinymce-label"
								       for="cdetails"><?php esc_html_e( 'Company Details', 'webappick-pdf-invoice-for-woocommerce' ); ?></label>

								<div class="_winvoice-tinymce-textarea">
												<textarea style="height:150px;" class="_winvoice-form-control"
												          id="cdetails" name="wpifw_cdetails"
												          value=""><?php echo esc_attr( get_option( 'wpifw_cdetails' ) ); ?></textarea>
								</div>
							</div>

							<div class="_winvoice-form-group">
								<label class="_winvoice-tinymce-label"
								       for="terms-and-condition"><?php esc_html_e( 'Footer 1', 'webappick-pdf-invoice-for-woocommerce' ); ?></label>

								<div class="_winvoice-tinymce-textarea">
												<textarea style="height:150px;" class="_winvoice-form-control"
												          id="terms-and-condition" name="wpifw_terms_and_condition"
												          value=""><?php echo esc_textarea( get_option( 'wpifw_terms_and_condition' ) ); ?></textarea>
								</div>

							</div>

							<div class="_winvoice-form-group">
								<label class="_winvoice-tinymce-label"
								       for="other-information"><?php esc_html_e( 'Footer 2', 'webappick-pdf-invoice-for-woocommerce' ); ?></label>

								<div class="_winvoice-tinymce-textarea">
												<textarea style="height:150px;" class="_winvoice-form-control"
												          id="other-information" name="wpifw_other_information"
												          value=""><?php echo esc_textarea( get_option( 'wpifw_other_information' ) ); ?></textarea>
								</div>
							</div>
							<div class="_winvoice-form-group">
								<label class="_winvoice-fixed-label"
								       for="wpifw_invoice_footer_font_size"> <?php esc_html_e( 'Footer Font Size( px )', 'webappick-pdf-invoice-for-woocommerce' ); ?></label>
								<input type="number"
								       class="_winvoice-form-control _winvoice-number-input _winvoice-fixed-input"
								       id="wpifw_invoice_footer_font_size"
								       name="wpifw_invoice_footer_font_size"
								       value='<?php echo( ! empty( get_option( 'wpifw_invoice_footer_font_size' ) ) ? esc_attr( get_option( 'wpifw_invoice_footer_font_size' ) ) : 9 ); ?>'>
							</div>
							<div class="_winvoice-form-group">
								<div class="_winvoice-custom-control _winvoice-custom-switch">
									<div class="_winvoice-toggle-label">
										<span class="_winvoice-checkbox-label"><?php esc_html_e( 'Show Footer Line', 'webappick-pdf-invoice-for-woocommerce' ); ?></span>
									</div>
									<div class="_winvoice-toggle-container"
									     tooltip="<?php esc_html_e( 'Show Footer Horizontal Line', 'webappick-pdf-invoice-for-woocommerce' ); ?>"
									     flow="right">
										<input type="hidden"
										       name="wpifw_display_footer_line"
										       value="0">
										<input type="checkbox"
										       class="_winvoice-custom-control-input"
										       id="wpifw_display_footer_line"
										       name="wpifw_display_footer_line"
										       value="1" <?php checked( get_option( 'wpifw_display_footer_line' ), $current, true ); ?>
										>
										<label class="_winvoice-custom-control-label"
										       for="wpifw_display_footer_line"></label>
									</div>
								</div>
							</div>
							<div class="_winvoice-card-footer _winvoice-save-changes-selector">
								<input type="submit" style="float:right;" name="wpifw_submit_seller"
								       value="<?php esc_html_e( 'Save Changes', 'webappick-pdf-invoice-for-woocommerce' ); ?>"
								       class="_winvoice-btn _winvoice-btn-sm _winvoice-btn-primary"/>
							</div>
						</form>
					</div>
				</div>
			</div>
            <!-- Seller and Buyer Tab Sidebar -->
            <div class="_winvoice-col-sm-4 _winvoice-col-12">
                <!--    Banner Section start    -->
				<?php include(plugin_dir_path(__FILE__) . 'lifetime-banner-widget.php'); ?>
                <!-- End Banner section -->
            </div>
		</div>
		<div class="_winvoice-row _winvoice-buyer-block">
			<div class="_winvoice-col-sm-8 _winvoice-col-12">
				<div class="_winvoice-card _winvoice-mr-0">
					<div class="_winvoice-card-header">
						<div class="_winvoice-card-header-title">
							<h4><?php esc_html_e( 'Buyer Info', 'webappick-pdf-invoice-for-woocommerce' ); ?></h4>
						</div>
					</div>
					<div class="_winvoice-card-body">
						<form action="" method="post">
							<?php wp_nonce_field( 'settings_form_nonce' ); ?>
							<div class="_winvoice-form-group" style="display: none;">
								<label class="_winvoice-custom-label"
								       for="btitle"><?php esc_html_e( 'Billing Title', 'webappick-pdf-invoice-for-woocommerce' ); ?></label>
								<input type="text"
								       class="_winvoice-fixed-width _winvoice-form-control _winvoice-fixed-input"
								       id="btitle" name="wpifw_block_title_to"
								       value="<?php echo esc_attr( get_option( 'wpifw_block_title_to' ) ); ?>">
							</div>

							<div class="_winvoice-form-group" style="display: none;">
								<label class="_winvoice-custom-label"
								       for="btitle"><?php esc_html_e( 'Shipping Title', 'webappick-pdf-invoice-for-woocommerce' ); ?></label>
								<input type="text"
								       class="_winvoice-fixed-width _winvoice-form-control _winvoice-fixed-input"
								       id="btitle" name="wpifw_block_ship_to"
								       value="<?php echo esc_attr( get_option( 'wpifw_block_ship_to' ) ); ?>">
							</div>
							<div class="_winvoice-form-group">
								<div class="_winvoice-custom-control _winvoice-custom-switch">
									<div class="_winvoice-toggle-label">
										<span class="_winvoice-checkbox-label"><?php esc_html_e( 'Enable Shipping Address', 'webappick-pdf-invoice-for-woocommerce' ); ?></span>
									</div>
									<div class="_winvoice-toggle-container"
									     tooltip="<?php esc_html_e( 'Show Shipping Address into invoice', 'webappick-pdf-invoice-for-woocommerce' ); ?>"
									     flow="right">
										<input type="hidden"
										       name="wpifw_display_shipping_address"
										       value="0">
										<input type="checkbox"
										       class="_winvoice-custom-control-input"
										       id="wpifw_display_shipping_address"
										       name="wpifw_display_shipping_address"
										       value="1" <?php checked( get_option( 'wpifw_display_shipping_address' ), $current, true ); ?>
										>
										<label class="_winvoice-custom-control-label"
										       for="wpifw_display_shipping_address"></label>
									</div>
								</div>
							</div>
							<div class="_winvoice-form-group">
								<div class="_winvoice-custom-control _winvoice-custom-switch">
									<div class="_winvoice-toggle-label">
										<span class="_winvoice-checkbox-label"><?php esc_html_e( 'Hide for Same Address', 'webappick-pdf-invoice-for-woocommerce' ); ?></span>
									</div>
									<div class="_winvoice-toggle-container"
									     tooltip="<?php esc_html_e( 'Hide for Same Billing & Shipping Address', 'webappick-pdf-invoice-for-woocommerce' ); ?>"
									     flow="right">
										<input type="hidden"
										       name="wpifw_hide_for_same_address"
										       value="0">
										<input type="checkbox"
										       class="_winvoice-custom-control-input"
										       id="wpifw_hide_for_same_address"
										       name="wpifw_hide_for_same_address"
										       value="1" <?php checked( get_option( 'wpifw_hide_for_same_address' ), $current, true ); ?>
										>
										<label class="_winvoice-custom-control-label"
										       for="wpifw_hide_for_same_address"></label>
									</div>
								</div>
							</div>


							<div class="_winvoice-form-group">
								<div class="_winvoice-custom-control _winvoice-custom-switch">
									<div class="_winvoice-toggle-label">
										<span class="_winvoice-checkbox-label"><?php esc_html_e( 'Disable Phone Number', 'webappick-pdf-invoice-for-woocommerce' ); ?></span>
									</div>
									<div class="_winvoice-toggle-container"
									     tooltip="<?php esc_html_e( 'Disable Phone Number', 'webappick-pdf-invoice-for-woocommerce' ); ?>"
									     flow="right">
										<input type="hidden"
										       name="wpifw_display_phone"
										       value="0">
										<input type="checkbox"
										       class="_winvoice-custom-control-input"
										       id="wpifw_display_phone"
										       name="wpifw_display_phone"
										       value="1" <?php checked( get_option( 'wpifw_display_phone' ), $current, true ); ?>
										>
										<label class="_winvoice-custom-control-label"
										       for="wpifw_display_phone"></label>
									</div>
								</div>
							</div>

							<div class="_winvoice-form-group">
								<div class="_winvoice-custom-control _winvoice-custom-switch">
									<div class="_winvoice-toggle-label">
										<span class="_winvoice-checkbox-label"><?php esc_html_e( 'Disable Email Address', 'webappick-pdf-invoice-for-woocommerce' ); ?></span>
									</div>
									<div class="_winvoice-toggle-container"
									     tooltip="<?php esc_html_e( 'Disable Email Address', 'webappick-pdf-invoice-for-woocommerce' ); ?>"
									     flow="right">
										<input type="hidden"
										       name="wpifw_display_email"
										       value="0">
										<input type="checkbox"
										       class="_winvoice-custom-control-input"
										       id="wpifw_display_email"
										       name="wpifw_display_email"
										       value="1" <?php checked( get_option( 'wpifw_display_email' ), $current, true ); ?>
										>
										<label class="_winvoice-custom-control-label"
										       for="wpifw_display_email"></label>
									</div>
								</div>
							</div>


							<div class="_winvoice-card-footer _winvoice-save-changes-selector">
								<input type="submit" style="float:right;" name="wpifw_submit_buyer"
								       value="<?php esc_html_e( 'Save Changes', 'webappick-pdf-invoice-for-woocommerce' ); ?>"
								       class="_winvoice-btn _winvoice-btn-sm _winvoice-btn-primary"/>
							</div>

						</form>

					</div>
				</div>
			</div>
		</div>
</li>
