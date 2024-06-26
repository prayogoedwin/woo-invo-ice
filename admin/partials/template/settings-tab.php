<li class="woo-invoice-settings-li">
    <div class="_winvoice-row">
        <div class="_winvoice-col-8">
            <div class="_winvoice-card _winvoice-mr-0">
                <div class="_winvoice-card-header">
                    <div class="_winvoice-card-header-title">
                        <h3><?php esc_html_e( 'Invoice Settings', 'webappick-pdf-invoice-for-woocommerce' ); ?></h3>
                    </div>
                </div>
                <div class="_winvoice-card-body">
                    <form action="" method="post">
						<?php wp_nonce_field( 'settings_form_nonce' ); ?>
                        <!--Start enable invoicing. -->
                        <div class="_winvoice-form-group">
							<?php
							$wpifw_enable_invoice = ( '' != get_option( 'wpifw_invoicing' ) ) ? get_option( 'wpifw_invoicing' ) : '1';
							?>
                            <div class="_winvoice-custom-control _winvoice-custom-switch">
                                <div class="_winvoice-toggle-label">
                                    <span class="_winvoice-checkbox-label"><?php esc_html_e( 'Enable Invoicing', 'webappick-pdf-invoice-for-woocommerce' ); ?></span>
                                </div>
                                <div class="_winvoice-toggle-container" tooltip="" flow="right">
                                    <input type="hidden" name="wpifw_invoicing" value="0">
                                    <input type="checkbox" class="_winvoice-custom-control-input"
                                           id="wpifw_invoicing" name="wpifw_invoicing"
                                           value="1" <?php checked( $wpifw_enable_invoice, $current, true ); ?>
                                    >
                                    <label class="_winvoice-custom-control-label"
                                           for="wpifw_invoicing"></label>
                                </div>
                            </div>
                        </div>
                        <!--End enable invoicing. -->

                        <!-- Document language. -->
                        <div class="_winvoice-form-group" tooltip="" flow="right">
                            <label class="_winvoice-custom-label"
                                   for="wpifw_pdf_document_language"> <?php esc_html_e( 'Document Language', 'webappick-pdf-invoice-for-woocommerce' ); ?></label>
                            <select class="_winvoice-fixed-width _winvoice-select-control _winvoice-fixed-input"
                                    id="wpifw_pdf_document_language"
                                    name="wpifw_pdf_document_language">
                                <option value="wpifw_pdf_site_language" <?php selected( get_option( 'wpifw_pdf_document_language' ), 'wpifw_pdf_site_language', true ); ?> >
									<?php esc_html_e( 'Site Language', 'webappick-pdf-invoice-for-woocommerce' ); ?>
                                </option>
                                <option value="wpifw_pdf_order_language" <?php selected( get_option( 'wpifw_pdf_document_language' ), 'wpifw_pdf_order_language', true ); ?> >
									<?php esc_html_e( 'Order / Customer Language', 'webappick-pdf-invoice-for-woocommerce' ); ?>
                                </option>
                                <?php
                                $default_languages = woo_invoice_pro_get_default_languages();

                                if ( isset($default_languages) && ! empty($default_languages) ) {

                                    foreach ( $default_languages as $key => $value ) {
                                        echo '<option value="'.esc_html($key).'" "'.selected( get_option( 'wpifw_pdf_document_language' ), $key, true ).'" >'.esc_html($value).'</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <!-- End Document language. -->

                        <!-- Document Font Family. -->
                        <div class="_winvoice-form-group" tooltip="" flow="right">
		                    <?php
		                    // Get font family list from database.
		                    $default_font_config = (new Mpdf\Config\FontVariables())->getDefaults();
		                    $custom_fonts = get_option('wpifw_custom_font_list') ?: [];
		                    $font_data = array_merge($default_font_config['fontdata'], $custom_fonts);

		                    // Sort the font data array by keys alphabetically.
		                    ksort($font_data);

		                    // Get font family list from the uploads folder.
		                    $upload_dir = wp_upload_dir();
		                    $font_dir = $upload_dir['basedir'] . '/WOO-INVOICE/WOO-INVOICE-FONTS/';

		                    // Check if the font directory exists.
		                    if (file_exists($font_dir)) {
			                    $font_files = array_diff(scandir($font_dir), ['..', '.']);
		                    }

		                    // Get the currently selected font family from the database.
		                    $current_font_family = get_option('wpifw_pdf_font_family');

		                    // Purify the font family name.
		                    function get_clean_name($name)
		                    {
			                    $name = ucwords(trim(str_replace(['.ttf', '-', ' '], ['', '-', ' '], $name)));
			                    return $name;
		                    }
		                    ?>
                            <label class="_winvoice-custom-label" for="wpifw_pdf_font_family">
			                    <?php esc_html_e('Choose Font Family', 'webappick-pdf-invoice-for-woocommerce'); ?>
                            </label>
                            <select class="_winvoice-fixed-width _winvoice-select-control _winvoice-fixed-input"
                                    id="wpifw_pdf_document_language"
                                    name="wpifw_pdf_font_family">
                                <option value="" <?php selected('default', 'default', true); ?>>
				                    <?php echo esc_html__('Default', 'webappick-pdf-invoice-for-woocommerce'); ?>
                                </option>
			                    <?php foreach ($font_data as $key => $value) : ?>
                                    <option value="<?php echo esc_html($key); ?>" <?php selected($key, $current_font_family, true); ?>>
					                    <?php echo esc_html(get_clean_name($key)); ?>
                                    </option>
			                    <?php endforeach; ?>
                            </select>
                        </div>
                        <!-- End Document Font Family. -->

                        <!--Download invoice from my account. -->
                        <div class="_winvoice-form-group">
                            <div class="_winvoice-custom-control _winvoice-custom-switch">
                                <div class="_winvoice-toggle-label">
                                    <span class="_winvoice-checkbox-label"><?php esc_html_e( 'Download Invoice From My Account', 'webappick-pdf-invoice-for-woocommerce' ); ?></span>
                                </div>
                                <div class="_winvoice-toggle-container"
                                     tooltip="<?php esc_html_e( 'Allow customer to download invoice from My Account order list table', 'webappick-pdf-invoice-for-woocommerce' ); ?>"
                                     flow="right">
                                    <input type="hidden" name="wpifw_download" value="0">
                                    <input type="checkbox" class="_winvoice-custom-control-input
												wpifw_download"
                                           id="wpifw_download" name="wpifw_download"
                                           value="1" <?php checked( get_option( 'wpifw_download' ), $current, true ); ?>
                                    >
                                    <label class="_winvoice-custom-control-label tips"
                                           for="wpifw_download"></label>
                                </div>
                            </div>
                        </div>

                        <div class="_winvoice-form-group" id="downloadAttechedData" style="display:
						<?php
						if ( ( get_option( 'wpifw_download' ) == 1 ) ) {
							echo 'block';
						} else {
							echo 'none';
						}
						?>
                                ">
                            <div class="_winvoice-custom-checkbox-label"></div>
                            <div class="_winvoice-custom-checkbox-container">
                                <?php echo $get_order_status_html( $wpifw_invoice_download_check_list, 'wpifw_invoice_download_check_list'); ?>
                            </div>
                        </div>

                        <!--End download invoice from my account. -->

                        <!--Start Invoice attach to email. -->
                        <div class="_winvoice-form-group">
                            <div class="_winvoice-custom-control _winvoice-custom-switch">
                                <div class="_winvoice-toggle-label">
                                    <span class="_winvoice-checkbox-label"><?php esc_html_e( 'Invoice Attach to Email', 'webappick-pdf-invoice-for-woocommerce' ); ?></span>
                                </div>
                                <div class="_winvoice-toggle-container"
                                     tooltip="<?php esc_html_e( 'Automatically attach invoice with order email.', 'webappick-pdf-invoice-for-woocommerce' ); ?>"
                                     flow="right">
                                    <input type="hidden" name="wpifw_order_email" value="0">
                                    <input type="checkbox" id="atttoorder"
                                           class="_winvoice-custom-control-input atttoorder"
                                           name="wpifw_order_email"
                                           value="1" <?php checked( get_option( 'wpifw_order_email' ), $current, true ); ?>
                                    >
                                    <label class="_winvoice-custom-control-label tips"
                                           for="atttoorder"></label>
                                </div>
                            </div>
                        </div>
                        <!--End Invoice attach to email. -->


                        <div class="_winvoice-form-group" id="emailAttechedData" style="display:
						<?php
						if ( ( get_option( 'wpifw_order_email' ) == 1 ) ) {
							echo 'block';
						} else {
							echo 'none';
						}
						?>">
                            <span class="_winvoice-custom-checkbox-label"></span>
                            <div class="_winvoice-custom-checkbox-container">
                                <?php echo $get_order_status_html( $wpifw_email_attach_check_list, 'wpifw_email_attach_check_list'); ?>
                            </div>
                        </div>

                        <!--Enable / Disable of attaching packing slip with email. -->
                        <div class="_winvoice-form-group">
                            <div class="_winvoice-custom-control _winvoice-custom-switch">
                                <div class="_winvoice-toggle-label">
                                    <span class="_winvoice-checkbox-label"><?php esc_html_e( 'Attach Packing Slip', 'webappick-pdf-invoice-for-woocommerce' ); ?></span>
                                </div>
                                <div class="_winvoice-toggle-container"
                                     tooltip="<?php esc_html_e( 'Attach packing slip only with completed order email.', 'webappick-pdf-invoice-for-woocommerce' ); ?>"
                                     flow="right">
                                    <input type="hidden" name="wpifw_email_packing_slip" value="0">
                                    <input type="checkbox" id="atttoorder1"
                                           class="_winvoice-custom-control-input atttoorder1"
                                           name="wpifw_email_packing_slip"
                                           value="1" <?php checked( get_option( 'wpifw_email_packing_slip' ), $current, true ); ?>
                                    >
                                    <label class="_winvoice-custom-control-label tips"
                                           for="atttoorder1"></label>
                                </div>
                            </div>
                        </div>
                        <!--Enable / Disable of attaching packing slip with email. -->

                        <div class="_winvoice-form-group" id="InvoiceCanGenerate" >
                            <div class="_winvoice-toggle-label">
                                    <span class="_winvoice-checkbox-label">Invoice Generated By</span>
                            </div>
                            <div class="_winvoice-custom-checkbox-container">
                            <?php
                            $wpifw_invoice_can_generate = (get_option('wpifw_invoice_can_generate')) ? get_option('wpifw_invoice_can_generate') : [];
                            global $wp_roles;
                            $all_roles = $wp_roles->roles;

                            $editable_roles = apply_filters('editable_roles', $all_roles);

                            foreach ( $editable_roles as $key => $user_role ) : ?>
                                <div class="_winvoice-custom-control _winvoice-custom-checkbox">
                                    <input type="checkbox" name="wpifw_invoice_can_generate[]"
                                           class="_winvoice-custom-control-input" id="InvoiceCanGenerate_<?php echo esc_html( $key ) ?>"
                                           value="<?php echo esc_html( $key )?>"
										<?php
										if ( in_array( $key, $wpifw_invoice_can_generate ) ) {
											echo 'checked';
										}
										?>
                                    >
                                    <label class="_winvoice-custom-control-label"
                                           for="InvoiceCanGenerate_<?php echo esc_html( $key )?>"><?php esc_html_e( ucfirst($key), 'webappick-pdf-invoice-for-woocommerce' ); ?></label>
                                </div>
                            <?php endforeach ?>
                            </div>
                        </div>

                        <!--End Invoice can generate. -->
                        <!--Allow free product. -->
                        <div class="_winvoice-form-group">
                            <div class="_winvoice-custom-control _winvoice-custom-switch">
                                <div class="_winvoice-toggle-label">
                                    <span class="_winvoice-checkbox-label"><?php esc_html_e( 'Allow Free Products', 'webappick-pdf-invoice-for-woocommerce' ); ?></span>
                                </div>
                                <div class="_winvoice-toggle-container"
                                     tooltip="<?php esc_html_e( 'Generate invoice for free products', 'webappick-pdf-invoice-for-woocommerce' ); ?>"
                                     flow="right">
                                    <input type="hidden" name="wpifw_free_order_attachment" value="0">
                                    <input type="checkbox" id="wpifw_free_order_attachment"
                                           class="_winvoice-custom-control-input"
                                           name="wpifw_free_order_attachment"
                                           value="1" <?php checked( get_option( 'wpifw_free_order_attachment' ), $current, true ); ?>
                                    >
                                    <label class="_winvoice-custom-control-label tips"
                                           for="wpifw_free_order_attachment"></label>
                                </div>
                            </div>
                        </div>
                        <!--End allow free product. -->

                        <!--Download as new tab. -->
                        <div class="_winvoice-form-group" tooltip="" flow="right">
                            <label class="_winvoice-custom-label"
                                   for="wpifw_pdf_invoice_button_behaviour"> <?php esc_html_e( 'Invoice Download as', 'webappick-pdf-invoice-for-woocommerce' ); ?></label>
                            <select class="_winvoice-fixed-width _winvoice-select-control _winvoice-fixed-input"
                                    id="wpifw_pdf_invoice_button_behaviour"
                                    name="wpifw_pdf_invoice_button_behaviour">
                                <option value="new_tab" <?php selected( get_option( 'wpifw_pdf_invoice_button_behaviour' ), 'new_tab', true ); ?> >
									<?php esc_html_e( 'Open in new tab', 'webappick-pdf-invoice-for-woocommerce' ); ?>
                                </option>
                                <option value="download" <?php selected( get_option( 'wpifw_pdf_invoice_button_behaviour' ), 'download', true ); ?> >
									<?php esc_html_e( 'Direct download', 'webappick-pdf-invoice-for-woocommerce' ); ?>
                                </option>
                            </select>
                        </div>
                        <!--End download as new tab. -->
                        <!--Enable / Disable to view file as html. -->
                        <div class="_winvoice-form-group">
                            <div class="_winvoice-custom-control _winvoice-custom-switch">
                                <div class="_winvoice-toggle-label">
                                    <span class="_winvoice-checkbox-label"><?php esc_html_e( 'Output Template as HTML', 'webappick-pdf-invoice-for-woocommerce' ); ?></span>
                                </div>
                                <div class="_winvoice-toggle-container"
                                     tooltip="<?php esc_html_e( 'Display document output as html.', 'webappick-pdf-invoice-for-woocommerce' ); ?>"
                                     flow="right">
                                    <input type="hidden" name="wpifw_output_type_html" value="0">
                                    <input type="checkbox" id="atttoorder09"
                                           class="_winvoice-custom-control-input atttoorder09"
                                           name="wpifw_output_type_html"
                                           value="1" <?php checked( get_option( 'wpifw_output_type_html' ), $current, true ); ?>
                                    >
                                    <label class="_winvoice-custom-control-label tips"
                                           for="atttoorder09"></label>
                                </div>
                            </div>
                        </div>
                        <!--Enable / Disable to view file as html. -->

                        <!--Enable / Disable of Barcode. -->
                        <div class="_winvoice-form-group">
                            <div class="_winvoice-header-title" style="padding-top:30px">
                                <h4><?php esc_html_e( 'Bar Code and QR code Settings', 'webappick-pdf-invoice-for-woocommerce' ); ?></h4>
                            </div>
                            <div class="_winvoice-custom-control _winvoice-custom-switch">
                                <div class="_winvoice-toggle-label">
                                    <span class="_winvoice-checkbox-label"><?php esc_html_e( 'Enable BarCode', 'webappick-pdf-invoice-for-woocommerce' ); ?></span>
                                </div>
                                <div class="_winvoice-toggle-container"
                                     tooltip="<?php esc_html_e( 'BarCode will show only for completed orders.', 'webappick-pdf-invoice-for-woocommerce' ); ?>"
                                     flow="right">
                                    <input type="hidden" name="wpifw_enable_barcode" value="0">
                                    <input type="checkbox" id="enable_barcode"
                                           class="_winvoice-custom-control-input atttoorder1"
                                           name="wpifw_enable_barcode"
                                           value="1" <?php checked( get_option( 'wpifw_enable_barcode' ), $current, true ); ?>>
                                    <label class="_winvoice-custom-control-label tips"
                                           for="enable_barcode"></label>
                                </div>
                            </div>
                        </div>
                        <!--Enable / Disable of Barcode. -->

                        <!--Enable / Disable of QRcode. -->
                        <div class="_winvoice-form-group">
                            <div class="_winvoice-custom-control _winvoice-custom-switch">
                                <div class="_winvoice-toggle-label">
                                    <span class="_winvoice-checkbox-label"><?php esc_html_e( 'Enable QRcode', 'webappick-pdf-invoice-for-woocommerce' ); ?></span>
                                </div>
                                <div class="_winvoice-toggle-container"
                                     tooltip="<?php esc_html_e( 'QR code will show only for all orders.', 'webappick-pdf-invoice-for-woocommerce' ); ?>"
                                     flow="right">
                                    <input type="hidden" name="wpifw_enable_qrcode" value="0">
                                    <input type="checkbox" id="enable_qrcode"
                                           class="_winvoice-custom-control-input atttoorder1"
                                           name="wpifw_enable_qrcode"
                                           value="1" <?php checked( get_option( 'wpifw_enable_qrcode' ), $current, true ); ?>>
                                    <label class="_winvoice-custom-control-label tips"
                                           for="enable_qrcode"></label>
                                </div>
                            </div>
                        </div>
                        <!--Enable / Disable of QRcode. -->
                        <!--Apply ZATCA start. -->
                        <div class="_winvoice-form-group">
                            <div class="_winvoice-custom-control _winvoice-custom-switch">
                                <div class="_winvoice-toggle-label">
                                    <span class="_winvoice-checkbox-label"><?php esc_html_e( 'Enable For Saudi Arabia', 'webappick-pdf-invoice-for-woocommerce' ); ?></span>
                                </div>
                                <div class="_winvoice-toggle-container"
                                     tooltip="<?php esc_html_e( 'Apply for Saudi Arab QR code law ZATCA.', 'webappick-pdf-invoice-for-woocommerce' ); ?>"
                                     flow="right">
                                    <input type="hidden" name="wpifw_enable_zatca" value="0">
                                    <input type="checkbox" id="enable_zatca"
                                           class="_winvoice-custom-control-input atttoorder1"
                                           name="wpifw_enable_zatca"
                                           value="1" <?php checked( get_option( 'wpifw_enable_zatca' ), $current, true ); ?>>
                                    <label class="_winvoice-custom-control-label tips"
                                           for="enable_zatca"></label>
                                </div>
                            </div>
                        </div>
                        <div class="_winvoice-form-group">
                            <div class="_winvoice-custom-control _winvoice-custom-switch">
                                <div class="_winvoice-toggle-label">
                                    <span class="_winvoice-checkbox-label"><?php esc_html_e( 'Display To Email Template', 'webappick-pdf-invoice-for-woocommerce' ); ?></span>
                                </div>
                                <div class="_winvoice-toggle-container"
                                     tooltip="<?php esc_html_e( 'Display QR code to email template.', 'webappick-pdf-invoice-for-woocommerce' ); ?>"
                                     flow="right">
                                    <input type="hidden" name="wpifw_display_qr_code_email_template" value="0">
                                    <input type="checkbox" id="wpifw_display_qr_code_email_template"
                                           class="_winvoice-custom-control-input atttoorder1"
                                           name="wpifw_display_qr_code_email_template"
                                           value="1" <?php checked( get_option( 'wpifw_display_qr_code_email_template' ), $current, true ); ?>>
                                    <label class="_winvoice-custom-control-label tips"
                                           for="wpifw_display_qr_code_email_template"></label>
                                </div>
                            </div>
                        </div>

						<!--                   Disable third party mpdf -->
						<div class="_winvoice-form-group">
							<div class="_winvoice-custom-control _winvoice-custom-switch">
								<div class="_winvoice-toggle-label">
									<span class="_winvoice-checkbox-label"><?php esc_html_e( 'Override third party mPDF Library', 'webappick-pdf-invoice-for-woocommerce' ); ?></span>
								</div>
								<div class="_winvoice-toggle-container"
									 tooltip="<?php esc_html_e( 'Override third party mPDF Library.', 'webappick-pdf-invoice-for-woocommerce' ); ?>"
									 flow="right">
									<input type="hidden" name="wpifw_disable_third_party_mpdf_library" value="0">
									<input type="checkbox" id="wpifw_disable_third_party_mpdf_library"
										   class="_winvoice-custom-control-input atttoorder1"
										   name="wpifw_disable_third_party_mpdf_library"
										   value="1" <?php checked( get_option( 'wpifw_disable_third_party_mpdf_library' ), $current, true ); ?>>
									<label class="_winvoice-custom-control-label tips"
										   for="wpifw_disable_third_party_mpdf_library"></label>
								</div>
							</div>
						</div>

                        <!--Apply ZATCA end. -->

                        <!--                                    <div class="_winvoice-form-group">-->
                        <!--                                        <div class="_winvoice-custom-control _winvoice-custom-switch">-->
                        <!--                                            <div class="_winvoice-toggle-label">-->
                        <!--                                                <span class="_winvoice-checkbox-label">-->
						<?php // _e( 'Enable RTL', 'woo-invoice' ); ?><!--</span>-->
                        <!--                                            </div>-->
                        <!--                                            <div class="_winvoice-toggle-container"-->
                        <!--                                                 tooltip="-->
						<?php // _e( 'Enable RTL for Arabic Language.', 'woo-invoice' ); ?><!--"-->
                        <!--                                                 flow="right">-->
                        <!--                                                <input type="hidden" name="wpifw_rtl" value="0">-->
                        <!--                                                <input type="checkbox" id="wpifw_rtl"-->
                        <!--                                                       class="_winvoice-custom-control-input"-->
                        <!--                                                       name="wpifw_rtl"-->
                        <!--                                                       value="1" --><?php // checked( get_option( 'wpifw_rtl' ), $current, true ); ?>
                        <!--                                                >-->
                        <!--                                                <label class="_winvoice-custom-control-label tips"-->
                        <!--                                                       for="wpifw_rtl"></label>-->
                        <!--                                            </div>-->
                        <!--                                        </div>-->
                        <!--                                    </div>-->


                        <!----------------------------------------------------
						Enable / Disable Page numbering.
						----------------------------------------------------->
                        <div class="_winvoice-form-group">
                            <div class="_winvoice-header-title" style="padding-top:30px">
                                <h4><?php esc_html_e( 'Pagination Settings', 'webappick-pdf-invoice-for-woocommerce' ); ?></h4>
                            </div>
                            <div class="_winvoice-custom-control _winvoice-custom-switch">
                                <div class="_winvoice-toggle-label">
                                    <span class="_winvoice-checkbox-label"><?php esc_html_e( 'Add Page Number', 'webappick-pdf-invoice-for-woocommerce' ); ?></span>
                                </div>
                                <div class="_winvoice-toggle-container"
                                     tooltip="<?php esc_html_e( 'Add page number to invoice and packing slip template.', 'webappick-pdf-invoice-for-woocommerce' ); ?>"
                                     flow="right">
                                    <input type="hidden" name="wpifw_enable_page_number" value="0">
                                    <input type="checkbox" id="atttoorder9"
                                           class="_winvoice-custom-control-input atttoorder9"
                                           name="wpifw_enable_page_number"
                                           value="1" <?php checked( get_option( 'wpifw_enable_page_number' ), $current, true ); ?>
                                    >
                                    <label class="_winvoice-custom-control-label tips"
                                           for="atttoorder9"></label>
                                </div>
                            </div>
                        </div>

                        <!----------------------------------------------------
						add page number option.
						----------------------------------------------------->
                        <div class="_winvoice-form-group" id="addPageNumber" style="display:
						<?php
						if ( ( get_option( 'wpifw_enable_page_number' ) == 1 ) ) {
							echo 'block';
						} else {
							echo 'none';
						}
						?>
                                ">
                            <span class="_winvoice-custom-checkbox-label"></span>
                            <div class="_winvoice-custom-checkbox-container">
                                <div class="_winvoice-custom-control _winvoice-custom-checkbox">
                                    <input type="checkbox" name="wpifw_add_page_number[]"
                                           class="_winvoice-custom-control-input" id="addPageNumber1"
                                           value="invoice"
										<?php
										if ( in_array( 'invoice', $wpifw_page_number_list ) ) {
											echo 'checked';
										}
										?>
                                    >
                                    <label class="_winvoice-custom-control-label"
                                           for="addPageNumber1"><?php esc_html_e( 'Invoice Template', 'webappick-pdf-invoice-for-woocommerce' ); ?></label>
                                </div>

                                <div class="_winvoice-custom-control _winvoice-custom-checkbox">
                                    <input type="checkbox" name="wpifw_add_page_number[]"
                                           class="_winvoice-custom-control-input" id="addPageNumber2"
                                           value="packing_slip"
										<?php
										if ( in_array( 'packing_slip', $wpifw_page_number_list ) ) {
											echo 'checked';
										}
										?>
                                    >
                                    <label class="_winvoice-custom-control-label"
                                           for="addPageNumber2"><?php esc_html_e( 'Packing Slip', 'webappick-pdf-invoice-for-woocommerce' ); ?></label>
                                </div>
                                <div class="_winvoice-custom-control _winvoice-custom-checkbox">
                                    <input type="checkbox" name="wpifw_add_page_number[]"
                                           class="_winvoice-custom-control-input" id="addPageNumber3"
                                           value="credit_note"
										<?php
										if ( in_array( 'credit_note', $wpifw_page_number_list ) ) {
											echo 'checked';
										}
										?>
                                    >
                                    <label class="_winvoice-custom-control-label"
                                           for="addPageNumber3"><?php esc_html_e( 'Credit Note Template', 'webappick-pdf-invoice-for-woocommerce' ); ?></label>
                                </div>
                            </div>

                            <!-- Customize page number. -->
                            <div class="_winvoice-form-group" tooltip="" flow="right" style="margin-top:10px;">
                                <label class="_winvoice-custom-label"
                                       for="wpifw_page_number_style"> <?php esc_html_e( 'Choose Page Number Style', 'webappick-pdf-invoice-for-woocommerce' ); ?></label>
                                <select class="_winvoice-fixed-width _winvoice-select-control _winvoice-fixed-input"
                                        id="wpifw_page_number_style"
                                        name="wpifw_page_number_style">
                                    <option value="0" <?php selected( get_option( 'wpifw_page_number_style' ), '0', true ); ?> >
										<?php esc_html_e( 'Choose page number style', 'webappick-pdf-invoice-for-woocommerce' ); ?>
                                    </option>
									<?php
									$page_not_style = [
										'arabic-indic',
										'bengali',
										'hebrew',
										'persian',
										'urdu',
										'thai',
										'cambodian',
										'cjk-decimal',
										'lao',
										'malayalam',
										'1',
										'A',
										'a',
										'I',
										'i',
										'lower-roman',
										'upper-roman',
										'lower-latin',
										'upper-latin',
										'lower-alpha',
										'upper-alpha',

									];
									$length         = count( $page_not_style );
									for ( $i = 0; $i < $length; $i ++ ) { ?>
                                        <option value="<?php echo esc_html( $page_not_style[ $i ] ); ?>" <?php selected( get_option( 'wpifw_page_number_style' ), $page_not_style[ $i ], true ); ?> >
											<?php echo esc_html( $page_not_style[ $i ] ); ?>
                                        </option>
									<?php } ?>
                                </select>
                            </div>
                            <!----------------------------------------------------
							End. Customize page number.
							----------------------------------------------------->
                        </div>
                        <div class="_winvoice-custom-control _winvoice-custom-switch">
                            <div class="_winvoice-toggle-label">
                                <span class="_winvoice-checkbox-label"><?php esc_html_e( 'Stop Repeating Header and Footer', 'webappick-pdf-invoice-for-woocommerce' ); ?></span>
                            </div>
                            <div class="_winvoice-toggle-container"
                                 tooltip="<?php esc_html_e( 'If invoice is more then 1 page, stop repeating header and footer.', 'webappick-pdf-invoice-for-woocommerce' ); ?>"
                                 flow="right">
                                <input type="hidden" name="wpifw_stop_repeating_header_footer" value="0">
                                <input type="checkbox" id="atttoorder19"
                                       class="_winvoice-custom-control-input atttoorder19"
                                       name="wpifw_stop_repeating_header_footer"
                                       value="1" <?php checked( get_option( 'wpifw_stop_repeating_header_footer' ), $current, true ); ?>
                                >
                                <label class="_winvoice-custom-control-label tips"
                                       for="atttoorder19"></label>
                            </div>
                        </div> <!-- end Non break header and footer. -->
                        <!----------------------------------------------------
						End. add page number option.
						----------------------------------------------------->

                        <div class="_winvoice-form-group">
                            <div class="_winvoice-header-title" style="padding-top:30px">
                                <h4><?php esc_html_e( 'PDF CSS Style', 'webappick-pdf-invoice-for-woocommerce' ); ?></h4>
                            </div>
                            <label class="_winvoice-fixed-label"
                                   for="wpifw_invoice_font_size"> <?php esc_html_e( 'Font Size( px )', 'webappick-pdf-invoice-for-woocommerce' ); ?></label>
                            <input type="number"
                                   class="_winvoice-form-control _winvoice-number-input _winvoice-fixed-input"
                                   id="wpifw_invoice_font_size"
                                   name="wpifw_invoice_font_size"
                                   value='<?php echo( ! empty( get_option( 'wpifw_invoice_font_size' ) ) ? esc_html( get_option( 'wpifw_invoice_font_size' ) ) : 11 ); ?>'>
                        </div>
                        <!-------------------------------------------
								   Write custom css.
					   -------------------------------------------->
                        <div class="_winvoice-form-group">
                            <label class="_winvoice-fixed-label"
                                   for="wpifw_custom_css"><?php echo esc_html_e( 'Invoice Template CSS', 'webappick-pdf-invoice-for-woocommerce' ); ?></label>

                            <div class="_winvoice-tinymce-textarea wpifw-wirte-custom-css">
                                <div tooltip="<?php esc_html_e( 'Write Invoice Template CSS', 'webappick-pdf-invoice-for-woocommerce' ); ?>"
                                     flow="right">
                                    <textarea style="height:100px;" class="_winvoice-form-control" id="wpifw_custom_css"
                                              name="wpifw_custom_css"><?php echo( ! empty( get_option( 'wpifw_custom_css' ) ) ? esc_html( get_option( 'wpifw_custom_css' ) ) : '' ); ?></textarea>
                                </div>
                                <p>Example: body{ color:red } span{ color:green } td{ color:blue } th{ color:yellow
                                    }</p>
                            </div>
                        </div> <!-- End write custom css -->


                        <!-------------------------------------------
								Write custom css for packing slip.
					   -------------------------------------------->
                        <div class="_winvoice-form-group">
                            <label class="_winvoice-fixed-label"
                                   for="wpifw_packing_slip_css"><?php echo esc_html_e( 'Packing Slip and Shipping Label Template CSS', 'webappick-pdf-invoice-for-woocommerce' ); ?></label>

                            <div class="_winvoice-tinymce-textarea wpifw-wirte-custom-css">
                                <div tooltip="<?php esc_html_e( 'Packing Slip and Shipping Label Template CSS', 'webappick-pdf-invoice-for-woocommerce' ); ?>"
                                     flow="right">
                                    <textarea style="height:100px;" class="_winvoice-form-control"
                                              id="wpifw_packing_slip_css"
                                              name="wpifw_packing_slip_css"><?php echo( ! empty( get_option( 'wpifw_packing_slip_css' ) ) ? esc_html( get_option( 'wpifw_packing_slip_css' ) ) : '' ); ?></textarea>
                                </div>
                                <p>Example: body{ color:red } span{ color:green } td{ color:blue } th{ color:yellow
                                    }</p>
                            </div>
                        </div> <!-- End write custom css -->

                        <!-------------------------------------------
									 Enable debug mode.
						-------------------------------------------->
                        <div class="_winvoice-form-group">
                            <div class="_winvoice-header-title" style="padding-top:30px">
                                <h4><?php esc_html_e( 'Invoice Configuration', 'webappick-pdf-invoice-for-woocommerce' ); ?></h4>
                            </div>
                            <div class="_winvoice-custom-control _winvoice-custom-switch">
                                <div class="_winvoice-toggle-label">
                                    <span class="_winvoice-checkbox-label"><?php esc_html_e( 'Enable Debug Mode', 'webappick-pdf-invoice-for-woocommerce' ); ?></span>
                                </div>
                                <div class="_winvoice-toggle-container"
                                     tooltip="<?php esc_html_e( 'Enable debug mode to show errors.', 'webappick-pdf-invoice-for-woocommerce' ); ?>"
                                     flow="right">
                                    <input type="hidden" name="wpifw_pdf_invoice_debug_mode" value="0">
                                    <input type="checkbox" id="wpifw_pdf_invoice_debug_mode"
                                           class="_winvoice-custom-control-input"
                                           name="wpifw_pdf_invoice_debug_mode"
                                           value="1" <?php checked( get_option( 'wpifw_pdf_invoice_debug_mode' ), $current, true ); ?>
                                    >
                                    <label class="_winvoice-custom-control-label tips"
                                           for="wpifw_pdf_invoice_debug_mode"></label>
                                </div>
                            </div>
                        </div> <!-- End enable debug mode -->

                        <!-------------------------------------------
									Enable dropbox upload.
						-------------------------------------------->
                        <div class="_winvoice-form-group">
                            <div class="_winvoice-custom-control _winvoice-custom-switch">
                                <div class="_winvoice-toggle-label">
                                    <span class="_winvoice-checkbox-label"><?php esc_html_e( 'Enable DropBox Upload', 'webappick-pdf-invoice-for-woocommerce' ); ?></span>
                                </div>

                                <div class="_winvoice-row">
                                    <div class="_winvoice-col-8">
                                        <div id="wpifw_pdf_invoice_display_tooltip" class="_winvoice-toggle-container"
                                             tooltip="<?php esc_attr_e( 'Upload Invoice to DropBox.', 'webappick-pdf-invoice-for-woocommerce' ); ?>"
                                             flow="right">
                                            <input type="hidden" name="wpifw_pdf_invoice_upload_to_dropbox" value="0">

                                            <input type="checkbox" id="wpifw_pdf_invoice_upload_to_dropbox"
                                                   class="_winvoice-custom-control-input"
                                                   name="wpifw_pdf_invoice_upload_to_dropbox"
                                                   value="1" <?php checked( get_option( 'wpifw_pdf_invoice_upload_to_dropbox' ), $current, true ); ?>
                                            >
                                            <label class="_winvoice-custom-control-label tips"
                                                   for="wpifw_pdf_invoice_upload_to_dropbox"></label>
                                        </div>
                                    </div>
                                    <div class="_winvoice-col-4">

                                        <div id="loading-image2"><img
                                                    src="data:image/gif,GIF89a%D8%00%D8%00%F2%07%00%F8%F8%F8%E0%E0%E0%C9%C9%C9%AC%AC%AC%8B%8B%8Bccc999%FF%FF%FF%21%FF%0BNETSCAPE2.0%03%01%00%00%00%21%FF%0BXMP%20DataXMP%3C%3Fxpacket%20begin%3D%22%EF%BB%BF%22%20id%3D%22W5M0MpCehiHzreSzNTczkc9d%22%3F%3E%20%3Cx%3Axmpmeta%20xmlns%3Ax%3D%22adobe%3Ans%3Ameta%2F%22%20x%3Axmptk%3D%22Adobe%20XMP%20Core%205.0-c060%2061.134777%2C%202010%2F02%2F12-17%3A32%3A00%20%20%20%20%20%20%20%20%22%3E%20%3Crdf%3ARDF%20xmlns%3Ardf%3D%22http%3A%2F%2Fwww.w3.org%2F1999%2F02%2F22-rdf-syntax-ns%23%22%3E%20%3Crdf%3ADescription%20rdf%3Aabout%3D%22%22%20xmlns%3Axmp%3D%22http%3A%2F%2Fns.adobe.com%2Fxap%2F1.0%2F%22%20xmlns%3AxmpMM%3D%22http%3A%2F%2Fns.adobe.com%2Fxap%2F1.0%2Fmm%2F%22%20xmlns%3AstRef%3D%22http%3A%2F%2Fns.adobe.com%2Fxap%2F1.0%2FsType%2FResourceRef%23%22%20xmp%3ACreatorTool%3D%22Adobe%20Photoshop%20CS5%20Macintosh%22%20xmpMM%3AInstanceID%3D%22xmp.iid%3ACCEA6E1E9C0C11E2AE47CE5CE2BEC7E2%22%20xmpMM%3ADocumentID%3D%22xmp.did%3ACCEA6E1F9C0C11E2AE47CE5CE2BEC7E2%22%3E%20%3CxmpMM%3ADerivedFrom%20stRef%3AinstanceID%3D%22xmp.iid%3ACCEA6E1C9C0C11E2AE47CE5CE2BEC7E2%22%20stRef%3AdocumentID%3D%22xmp.did%3ACCEA6E1D9C0C11E2AE47CE5CE2BEC7E2%22%2F%3E%20%3C%2Frdf%3ADescription%3E%20%3C%2Frdf%3ARDF%3E%20%3C%2Fx%3Axmpmeta%3E%20%3C%3Fxpacket%20end%3D%22r%22%3F%3E%01%FF%FE%FD%FC%FB%FA%F9%F8%F7%F6%F5%F4%F3%F2%F1%F0%EF%EE%ED%EC%EB%EA%E9%E8%E7%E6%E5%E4%E3%E2%E1%E0%DF%DE%DD%DC%DB%DA%D9%D8%D7%D6%D5%D4%D3%D2%D1%D0%CF%CE%CD%CC%CB%CA%C9%C8%C7%C6%C5%C4%C3%C2%C1%C0%BF%BE%BD%BC%BB%BA%B9%B8%B7%B6%B5%B4%B3%B2%B1%B0%AF%AE%AD%AC%AB%AA%A9%A8%A7%A6%A5%A4%A3%A2%A1%A0%9F%9E%9D%9C%9B%9A%99%98%97%96%95%94%93%92%91%90%8F%8E%8D%8C%8B%8A%89%88%87%86%85%84%83%82%81%80%7F~%7D%7C%7Bzyxwvutsrqponmlkjihgfedcba%60_%5E%5D%5C%5BZYXWVUTSRQPONMLKJIHGFEDCBA%40%3F%3E%3D%3C%3B%3A9876543210%2F.-%2C%2B%2A%29%28%27%26%25%24%23%22%21%20%1F%1E%1D%1C%1B%1A%19%18%17%16%15%14%13%12%11%10%0F%0E%0D%0C%0B%0A%09%08%07%06%05%04%03%02%01%00%00%21%F9%04%05%0A%00%07%00%2C%00%00%00%00%D8%00%D8%00%00%03%FFx%BA%DC%FE0%CAI%AB%BD8%EB%CD%BB%FF%60%28%8Edi%9Eh%AA%AEl%EB%BEp%2C%CFtm%DFx%AE%EF%7C%EF%FF%C0%A0pH%2C%1A%8F%C8%A4r%C9l%3A%9F%D0%A8tJ%ADZ%AF%D8%ACv%CB%EDz%BF%E0%B0xL.%9B%CF%E8%B4z%CDn%BB%DF%F0%B8%7CN%AF%DB%EF%F8%BC~%CF%EF%FB%FF%80%81%82%83%84%85%86%87%88%89%8A%8B%8C%8D%8E%8F%90%91%92%93%94%95%96%97%98%99%9A%9B%9C%9D%9E%9F%A0%A1%A2%A3%A4%A5%A6%A7%A8%A9%AA%AB%AC%AD%AE%AF%B0%B1%B2%B3%B4%B5%B6%B7%B8%B9%BA%BB%BC%19%05%06%BD%20%01%06%03%0E%06%C0%0D%03%06%00%C1%16%C3%C8%0C%C7%C6%06%01%CD%15%00%C7%D5%D1%D0%0A%CF%CC%D6%0C%04%04%0E%BF%C5%DB%0D%04%06%05%0E%01%DF%BB%D8%06%02%C9%EA%0D%D2%0C%E5%0D%00%01%DA%BC%CA%05%EE%07%BC%9D%5B%00%8F%DF%01%7D%ED%82%01%F85n%9B%BC%05%F6%14%08%88%A8%00%21%C0%5D%13%A9%85%23%16m%FF%DD%82t%0D%2B%EE%BB8%2B%00%01%92%0C%19%08%28%F0%10%C2%CA%96%07%F79%D0GRU%BA%7F%0D%9E%19%D4%601%DF%BE%9D%AB%16%AA%DB%99%CE%1C%07%84%3EG%C6%12%8A%93%A0%80%9A%17%00He%800%A1%2C%A6%40IT%85%DA%8A%29%D7%0F%FA%04X%AD%25%14%A6V%A5%AE%94%0D%20%A9O%C5%D4%A4Y%3F%29SgvFX%99%A6%02%FC%1AJ%23%AC%D8%AF%A0V%1E%3B%09%03%E1_%C0%A1%00%0C%F8U%D7m%80%BF%B0%1E%23%3E%3B%19%DC.%BCB%DEz%FAU%80%C0%80%B8%7D%F7%09%18%0Dj1gu%04%40%B7%F0%3BZ%ACjL%8F%09p%F6H%E3%F1%E8v%955%05%F8l%83%A6%E5%DF%C0%83%0B%CFR%A0%B8%BA%E2%C5q%B4%5EN%BA%93%EC%E3%EA%0C%84%AC%C1%7C%F9%F0%EB%D8%B3k%07%B2%BB%F1%0B%DF%A7v%CB.%3E%5D%86%80%01%AD%C7%CAE%8E%9Cw%8D%B0%E8%D1%8B%055%1E%7D%EE%D5%A2%D1%83j%3BD%F3v%5D%FF%BB%DD%07%02x%AE%28V%DCk%25%ECf%1F%2B%00%ACT%DCZ%85u%F7%99%80%98%08P%1F%85%2248%00z%08nb%1A%01%DE%15v%9E%7B%A4%0C%00%22%5B%21%86%A0%9EH%F3%15%28%5B%87%1B%28%06%A1-%00%C8F%D8%092%3EE%96%8D%18F%B5%E1%8CK%F1H%95%8E%1D%10x%D0%88%3DvRcg%17%99%98%22%05%E7%99%A5%21%90%AD%2Cv%E3%02%26%15%00%23%042%EE%94%23%2C%8A%91%24%8EQ%01%A5%26%C1n%06%8D8%13%91%B6%98t%E5%01N2%B0%A1J%20R%B5%E1%96%60%CAf%968%06%89C%95g%0D%20%19%8C%85o%D6%F8%A6%9F%04%99%08P%97%BD%18%BA%93%85d%1E%80%E8%02%16%EE%A4%60%92%A8%E8%97L%9D%E1%94%A7f%A0O%EAb%E8E%93V%E4%19%A6m%96%AA%80%AAp%BEy%5Dw%0E%B0z%1E%AA%C1%CC%F9%DF%AD%B8%E6%AA%EB%AE%BC%F6%EA%EB%AF%C0%06%2B%EC%B0%C4%16k%EC%B1%C8%26%AB%EC%B2%3F%CC6%EB%EC%B3%D0F%2B%ED%B4%D4Vk%ED%B5%D8f%AB%ED%B6%DCv%EB%ED%B7%E0%86%2B%EE%B8%E4%96k%EE%B9%E8%A6%AB%EE%BA%EC%B6%EB%EE%BB%F0%C6%2B%EF%BC%F4%D6k%EF%BD%F8%E6%AB%EF%BEm%24%00%00%21%F9%04%05%0A%00%07%00%2CK%00K%00B%00A%00%00%03%FFx%BA%DC%FE%AD%C0I%ABu%F2%EA%CD%BB%FF%20%00%8E%24%A1%0D%86%D9%10%2A%09eO%5B%C9%87%60%18n%CE%DD%B4%DE%0C%11C%802t%10n%3E%17%8Cr%13%24%21G%E0%A2%D89.%15%01%82%B3%23%7D2lB%07%0FtUtGGZ%E0%86%F3%EA%CE%0Av%AEP%10%B9%A7%F2%BB%FBV%26%F5%7D%60%1F%7F5z%0A%5B%0E%00%01v%2F%85%1F%01%87%13%05%29%8D%17%01T%15%03t%94%13%89%96%97%1A%83%85%02%9F%9B.%8B%A5%A8%A9O%A1%AA%0F%9D%A3%0B%04t%9A%AD%13%8F%B0%14t%3D%AD%8F%A4%B5%BF%C0%C1%C2%C3%C4%1B%B2%B3%B3%BB%BC%02%CC%CD%B8%C8%B4%C3%B7%CE%B8%C5%D6%D7%D8%D9%DA.%02%2C%D6%CD%BE%C7%BA%D7%CD1%DB_%E7%E9z%90%EA%0A%CA%94%CC%1E%B2%EF%8D%CE%95%EE%F4%A5%CD%A71%04%BE%BC%86%26%B0k%F0%2F%5D%3E%12%B7%9E%1C%E4%A6P%DF%80%81%F2ZA4%F6%60%22%87%82%84%3E%BC%5Bx%81A_%8D%01%18%B9h%F9%01%02b%C8%1C%16%A7%C0I%D5c%00%01~g%02%AC%94%99%F2%CD%85%95%07V%D6%BC%93e%E0%CA%95%1E%9FX%FC4%F3%A1%03K%AAp%E6t%A0%B4%D6O%08%20%85%055sTG%02%00%21%F9%04%05%0A%00%07%00%2CL%00L%00%40%00A%00%00%03%FFx%BA%DC%FE%90%95H%ABm%F3%EA%CDW1A%27%8E%C3%93%8D%A8v%8A%84Q%A6%DD%AA%00%86%DC%BD0do%F2P%E7%95%02%80%81%5B%10%1AE%E0c%10r%14%8E%8D%26EJ%19%8Av%87%02vCU%2A%A0%DEErf%A8uU%E1%86%20%BA%D5%80%D3%0E%2B%90%D0%86%CF%ED%CE3J%8FG%1A%DE%1EO%7D%1D%02e%7F%10tu%83%0A%01%1Fek%11%02Z%05c%8B_%86%80%96%22%3E%87%9A%40%03%90%9E%A2%A3%23%02%01t%04%04%A1%A4S%03%8E%06%AB%07%04%95%A4%92%86%86%0B%00%02%03%99%AC%0A%B7%01%7C%0A%B1%BE%07r%C5%C8%C9%CA%CB%9A%A8%82%CC%8C%C1%D2%A7O%D5%BD%C8%01%A6%A6%D0%DC%DD%DE%DF%91%C2%CC%BA%BC%A9%CB%D9Mr%02%A9%A8%DD%C1%DAM%D7%D0%00%EF%E2%E0%F7%22%C7%F8%0C%F2%7D%BA%DB%1BR%11%D3%94%0D%20%85r%B4D%15%14%96%2A%21%29z%03%97%E9c%60%CF%81C%20%13S%F0%BA%08%24%5C%22%87%8D%04%3D%5E%00%19%A5%8F%C8%83%B3%96t%D4s%F2%C1%BA1%00R%E6%88%98%ADC%CD%0B%19%1D%088vs%D0%801L%18%B4%3C%20%60%28%0A%5Eg%92%24%01%00J%A7%CF%25%40%D5%400%BAIg%D4%0DT9%9C%D9ElL%D6%3EW%9D%22c%0A%C1%EB%80%9C%0A-N%05W%14O%02%00%21%F9%04%05%0A%00%07%00%2CK%00K%00B%00A%00%00%03%FFx%BA%DC%FE%8C%C0I%AB%85%C2%95%CB%7B%DF%1A%C8%88%5E%C9%15%40C.%C5j%96%C3%B7fO%FCvA%23%B8%AEB%AB7%CBN%02T%ADZ%9A%20%87%98%1C%29%9F%8D%40%CF%C5T%F0%A0%8ET%ADA%F8y%AA%8C%DC%0B%7C%D0B%7B%D2%20%D1%CC%F2%DEl%CE%F2%8DP%20c%27%3D%93%FD%CE2%E4%C7%7C%0F%7FJb%81%0DlKn%86o%18u%8BA%83%5Cp%8F%1FK%8E%94%1C%93Bu%85%98%7C%88%9EQ%A1%A3Y%A4%86%04%04%03%02%A0%A6%17%24%A8%A8%AD%15%04%06%B5%B6%9A%0B%A8%B8%B2%05%B6%05%03%9D%B2%1D%02%04%C1%C2%C7%C8%C9%CA%CB%0D%03%B0%CF%BB%C2%B6%D3%B5%CE%CF%BA%CC%07%BD%D4~%D9%DE%DF%E0%E1%E2%25%C4%8A5%D8%CA%D3%BF%C1%CE%D6%AA%AC%AD%C4%DCD%ED%C0%E2%01%03%DB%06%0B%F0%E1%01%C6%E3%02%96z%21%60%404O%FFL%B4K%F6%2F%40%BFf%AA%98%25%A4P%F0%20%B2%7F%FD%2C%22%7BXAf%E3%93%89w%22b%0A%20%00%E0%0B%8Fw%40%06%11i%08%14I%93%99%1E%E0%83IH%A5%07%96%0C%0Cr%B4%40r%C2%CE%81%0E%0A%06%7D%08%8F%26%1FsAu%04%23%F93%88P%1DA%DD%28%02Pr%24%CE%05R%15%292%FADg%14snz%06%E5z%C3dU%06%02%CC%19kz%F4A%DA%A4%DE%B4b%C8%06V-%94%04%00%21%F9%04%05%0A%00%07%00%2CL%00K%00A%00B%00%00%03%FFx%BA%DC%FE%8C%C0I%ABe%80%8CW%9E%BC%60%D8%7CW%27%9E%17%D9%98h%2B%0E%85PB%9BK%11%84%EC%D4%0C%BB%F8%3F%9BE%15%11%1A-%BA%05Q%01%042%8F%C3Gr%C2%0BB%A9%90%00%25%90%5B%5D%15%CB%037%8Cr%02%0A%CE%CB%80%0C-%B0ma%EDWqN%BF%DE%F3%2F%BE%FD%5D%E7%8FS%7F%15r%278%04%84%82%28U%0D~%89%21%7B%23%8E%20v%13%90y%02%06%94%92%9B%9C%9D%9Ew%8B%9F%29%8C%86%1A%03%00%A2%10%05%06%AC%99%0D%01%02%A1%A9%0Ex%A8%B3%3B%B7%B9%BA%BB%BC%B7k%BF%1A%BD%07%AB%AD%AD%05%BF%BF%C2%CA%CB%CC%CD%CE%22%B6%CF%0C%B0%03%D5%BC%9A%07%D5%D6%D25%D5%02%81%D2%E1%0D%D1%E2%21%88%9E%87%E5%0B%03%AC%D8%D3%DA%E7%9D%04%AD%B2%0D%E0%B3%01%C4%06%F7b%DE%C2%EC%AC%D8%C4%EB%24%60%20%3BK%0Bb%91%9B%83%C9%C0%40%14%FC%E6%B4%024%20%E2%97V%0F%2FX%FCC%CCEE%AC%7B%1B-%00%18%88%F1D%C6%90%20H%1A%40%B8%E0aA%14%F1%1E%A6%3B%02%00%1C%ACW%29o%85%0Cp%8E%A7%24%90%17%1E%06Xx%E5%5B%16%7B%10%88%A6%CA8N%19J%05L%09B%B0%A9%0E%834%9F%82%12%00%00%21%F9%04%05%0A%00%07%00%2CK%00L%00A%00%40%00%00%03%FFx%BA%DC%CE%C2%11%E2%E2%BB8kE_g%DF%26%8E%9E%14%1E%40q%92%EC%22X%CDz%C8Ck%3B%81d6%83%3C%DF%9B%00%A1%16%2B%81%0A%80X%01%28%EA%19%17%13%1DD%C5%14%AD%04%3EW%B6%0A%B9%10%60%0A%A2%26%B7%18%14%C4%0C%F4%08%ABFqG%EDf%1B%CB%15%14%C0%1C%EA%AD%17%7Fc%B6r~%1A%04%2AdL%7C%82%19%80%2C%7D~%01z%8C%89%89K%17%03%96%92%5C%05%94%18%96%03I%98%2C%9A%22%01%9Dx%A0%0Ff%9B%24%97%A7%1B%8D%ADL%A6%B0%B3%B4%B3%96%02%86%B5-%A2%07%01%02%9D%B7%BA%19d%05%06%AA%0E%AC%C2%17%C5%94%9F%17%01%CE%CA%0C%B9%D2%D5%D6%D7%D8%D9%18%BF%C0%C1%DA%0A%C5%06%C6%E2%BF%E5%03%E5%DF%0C%E1%C6%E9%ED%EE%EF%F06%D4%D7%AF.%E7%B2%D7%27%BE%E7%F7%E9B%EB%EC%0E%BCx1%EF%1B%80%1E%CC%E2%8DQ%C8%10%94%2F%01%D1j%09%10G%A0%E0%85%81%0D%17%00%C0%A8%8D%7B%80%B8c%0D%06F%BCfg%18%3E7%B3F%96%B1q2%91%CA%1B%B8f1%B3H%C2%17ML%20%5B%C8z%19%09SLP%1E%F5%25di%F1%E6%28r%0D%1EI%2Ah%D4%0C%98%A1%D30%CD%9B%9A%26%E0%02q%F5%AA%F8%8A%18%80ZW%08%E2x%84%85E5%295q%D4%8A-%BAQ%14GZ%03j%26%1Ah%A5%12%9AY%B1%20%9D%08%FBzw%DA%D8lL%E7%21%05%3C%92%EF%11%A3%1A%12%00%00%21%F9%04%05%0A%00%07%00%2CL%00K%00A%00B%00%00%03%FFx%BA%DC%FEk%0CH%AB%BDm%E2%CD%BB%D3%0C%01%89%5Ei%81%17i%AE%10%AA%A8%E1%23%B0%A7%7B%044NW%82%AD%B8%B0E%F05%DBQl%03%02%C02%3C4%8D%0C%C9E%CA%2C%E8v%CB%D6%23%7Bt%3CW%BEC%92k%02%14%9A_K%F1%11%EE%10%DA%AB%1E%F4q%15%CE%D7%F3%0A%19%9C%1F%A5%3Dx%7D9%03u%0C%7B%82%14%81%0C%01%12%8A%88%1B%7F%00%3D%03%8E%8F%5BB%85%96F%7F%0D%99%9A%0D%05%2C%9E%9F%A4%A5%10%87%A6%1E%3A%3D%95%A9%14d0%AC%AD%AE%0C%05%B6t%94%94%B4%5E%B7%07%B3%BB%C0%C1%C2%C3%C4%C5j%02%C8%C9%C6N%06%B6%CE%B6%AC%B9%BF%A9%04%CD%D6%05%06%9C%CB%DB%DC%DD%DE%07%A8%BB%A3%0B%01%C8%DB%BD%0E%C9%C8%E3%C0%D8%A1%9D%DF%F1%1C%E1%F2%F1p%16%EC%A4%F4%0E%F9%A4%EF%18%E5%04%F4%DB6%90V%98%82%B4%FEq%40%C8Ba%8EztL94Q%8E%A1%91%89%1B%02%8C%0A%B0oK%CA%25Q%10%2CV%28%D4%91b%03%01%06f%29zR%B2C%A6j0%9Ay%91%B1%CB%80%81%3A67%60%84%02%20S%80%9C%0C%80%0E%13%E8EfP%03%C5Pas%21%D4c%2A%9B%85%9AnC%89%B4%81%D4e%3Fm%5C%BD7%0C%9B%A5%04%00%21%F9%04%05%0A%00%07%00%2CK%00L%00B%00A%00%00%03%FFx%BA%DC%CE%C1%8D%F1%E8%BB8%EB%03l%9B%12%01ld%89y%0B%CA%A8f%0B%3D%C2%10%ADj%40%08%B0k%C6%E3%87%D3%0D%01a%C6%20%E8L%81%C1oU%F94%8F%3B%99%E4%83%02%10X%07%2C%F4%D1%93%10%1F%B6%A5b%60%DC%96%04b%8E%E0%7B%E9%1E%D2fE%00%7D%D1%B6%08%A2%10%7C%E7H%B2%8F%7Be%5Bt%0Dnq%2BWq%7B%87%17%82%8A%84%8C%15%89%91%0A%86%94%07%8E-%8B%97%3A7%18h%02%96%9C%246x%1B%90%A3%A4d%99%19%A0%7F%A9%0Ex%9E%9A%A1%B0%18%A6%B6%B9%BA%BB%BC%B9h%AF%BD%18s%03%05%8Es%A0k%C1%27%04%C5%C5%AC%00%C7%9B%C1%CC%93%CA%D6%D7%D8%D9%DA%DB%B0%D1%C8%C0%D6%05%E2%E3%C5s%E6k%C9%DC%07%E2%CC%E3%EA%EF%F0%F1%F2%F3%B0%D0%E6%E0%BC%CC%0Bp%E7%01%F8%CA%ED%0A4%F0%E7O%D4%B69%0A%8A%D1%5B%A8%CB%E0Bh%D8%C4%B9%B0%F7%2F%97%C4%12%FE%D4%5D%CC%901q%DEF%07%D0%0C%DAYh%C0%80%B48%02%23%05%28i%80%97C%13%2Cy%89%7B%A9ae%C9%29%A9%C4U%BC%10%B3%01%01%03%AC%5C%A4t0%B3%D3%CD%81%25O%C6%19yA%C8%83%92%99%86%B6%90%CA%24%92%80%92%FF%A8%5E%D0%3A%AA%24S0%06%B8%E6%1Ap%94R%81%AFP%92~%00%BA%21hBX%D5%A0%B8%7D%F8n%E7%82%02%06%EC%DA%12%CB0%03%DF8%09%00%00%21%F9%04%05%0A%00%07%00%2CL%00K%00A%00B%00%00%03%FFx%BA%DC%FEK%08H%AB%BDm%E2%13%B6%FF%91%C6%88%E3%00%9E%179%3A%26%EArN%A0%1E%F3%D1%BEg%5D%3F73%F48%87NU%03%2A%06%9D%20e%A6k%0CT%01%A32%03%25%AA%A4RW%B2%21%DB.%00%15%80%C0kk%A2%C8%0A%89%F2%07%C9Zd%90%DD%07%CB%3E%8F%A7%F8%0B%3CO%F9%B9%3D%5D%7C%3Cy%7B%82%0Bu%1Eb%60%868n%81%8C%27%03%04%04r2w%90%17%8B6%99%8F%98q%93%7F%0Fh%9E%0A%01%93ra%A4%A2%9A%AA%AD%AEJ%60%01%B2%01%AC%AF%A9%07%04_%A5c%A3%B6%87%93%A0%2Ab%B3%BEN%93%C5%2F%A8%C8%CB%CC%CD%CE%98%B3%D1%CF%0A%C0%D5%04%D1%C4%CF%92%0B%93%05%A1%D3%E0%E1%E2%E3%02%94%CE%A8Q%05%06%EB%06%CE%04%05%04%40%04%EC%EC%D7%CE-%EF%A7%07%EC%DE%BD%CBb%DBJ%F9%1BG%F0%85%A9%82%0F%E6%19%F8f%28%9E%07%85%06r%F9%1AP%A0%A22%01%EA%0C%14%18%E8%09j%00Ex%14%20%2Aku0%E4%C28%C8%CA%8D%0B%00r%8A%BA%02%9E%DE%E5%81I%2A%1F%8E%97%1CQ%CCxW%A0%D6%07%9A%2C%D6p%B1%89b%23%84%9C%16%2A%26%04%0A%08%02S%10h%242%40%0A%C2g%A8%A7%0A%B0%F2%D1%8A%F5%29%C6%07Z%B7%82%BD%A0%AE%96%D4f%5C%C72%92r%96A%D7ga%9D%C2U%DB%20.B%84m%F3%24%00%00%21%F9%04%05%0A%00%07%00%2CK%00L%00B%00%40%00%00%03%FFx%BA%DC%FEL%887%A1%BD8%BB%AA%BB%D7%DC%22%0C%D4g%9E%5B%FA%04%A8%C6B%AF%18%2As%FB%01B%7C%0Ds%7D%F8%B6%15%A4%E7%01%06%8F%0C%1Dr%99%014%8C%CE%93r1e2%AB%87h%2B0%C0%1A%5D%D6%CC%E8%1A.%C3%CC%25%B4%19%C0%93b%D5H-%FC4%20a%02%EFy%A3%FE%C9%EB%F9%28~jvz%85%86p%01_%87%1F%06%8D%05%5D%8B%1F%03%04v%03%8D%97%06%05%8A%85%15%84%0D%5C%05%97%05%91%16u~%02%04%9B%85r%A4%AD%AE%AF%B0%B1%0E%98%B4%B2%07%93%04%B9%BA%03%A1%B4%99%B6%0A%BA%BA%C0%C4%C5%C6%C7%C8%19%82%0E%A3%B2%04%0Br%9E%22%C0%C3%C9%18%CF%D6%D9h%D2%86%AA%C6%04%CD%1E%DC%A4%D5%DAT%C1%04%82%E1%B6%E0%DE%E6%17%01%EB%EF%8B%D8A%F1%F3%0E%ACs%F5%26%F7%91%FA%28%00%CA%2B%B6l%19%BF%5B%10%00%1E%198%D0%C32C%EB%AA%08%28p%F0U%C3%86sBM%19%F8K%15%19%B1%81%40%DC%85%D1%27%CF_%B2P%C9%14%16hH%40%A1%87%04%00%21%F9%04%05%0A%00%07%00%2CK%00K%00A%00B%00%00%03%FFx%BA%DC%FE%2C%04H%AB%BDm%E2%CD%7B%D6%D1%13%08%5Ey%81%0D%99%9A%EC%01%40%C1%1B%8A%0E%DA%C2%8E%7C%3E%80%A0%DE%B8%9A%CD%A6%F81%8C%40%91NA%7C%20%8BI%8B%A4%D1%5BN%8F%0E%DF%B2%25%204%89%CD%8C8%D8%21%18%0A%DF%A4%CF9%08W%0C%F0%DC%95%EB%BC%C1%0D%EE%D6%B6%F8%EC%DC%A3%16%02%03%7D%1E%7F%80N%84%26%05%06%89%87e%06%04%22%8DI%7BLm%0Ffp%03%8E%5C%03%97Nwh%9C%1D%82%83%95%0C%03%A1%A3%27%9E%02y%11%99%93%A3%9E%AFl%AB%B7%B8%B9%BA%BB%BC%19%A5%0B%8Bw%90%BD%142%9E%C7%B5%C4%0E%C7%93%C9%BA%00%CE%CA%D2%D3%D4%D5%D4%C1%C2g%D6%A8%C7%C7%DB%17%03%04%E1%E3%DF%E5%E6%E7%E8%40%9B%E9%10%91%E6%E3%B4%0C%04%D8g%5E%DF%82%E2%04%E2%0A%C1%E2%A7%DB%F86%FDK7%90%9DA%40%B2%1C%05%D0%97%10%98%A6i%0D%1B%B8%A3%06%20%DC%3E%08%EB%D0Y%C4%C8%88iZ%9E%88.%0A%14%C85%11H%C1C%17%81%8C%E4U%B2%C4%3F%90%1C%C2%DDX%E9%80%26%8B.%EDX%D8d%B0%93%C5%BC%3D2MT%EA%D9%B2%06%A6b%80zf%5C%A0o%99%C4%A2%9Cz%1E%90%CA%00%40%01%A8%B9%A4j%95%D8%0E%26%87%9E%7D%94%16%E8%83%F5%D6%D8%0DK%17x%9Di%A3%A7%D5%B2%BD%A8%12%FD%B6%B0%E1Z%96%B7%12%00%00%21%F9%04%05%0A%00%07%00%2CL%00L%00A%00A%00%00%03%FFx%BA%DC~%C1%0C%17%82%03%F6%E9%CD%BB0%C6%931%C2%D8%9D%A8%06%9A%10%DB%A60G%10N%21Q%0D%16%EF%0F81%03C%A1Qy%00D%3CT0%C4%880%9BDW1%89%F25%40%02%28Q%E3%A2%3E%3E%06%93%8D%C6%91za%E3%A6%21%CB%EB%9E%8F%A4%D5%19%B2a%EF%86%0F%82%81%FC%A6%94%92%016%06p%0B%3Fs%87%0F6%05n%88%0A%01%7F%88x%8D%93%941%90%3B%86%0C%84%95%28v%0DKk%9C%3C%00%02%A5%1C%92%A2%96%9E%28%7C%A9%1A%A60%9B%AE%AF%AB%29%8C%B3%B8%B9%BA%A2p%82%AD%BB0%02%03%B5%07%A8%C0_%C2%C9%9A%03%BF%C7%0D%A5%C3%B7%0A%99%CE%D5%D6%D7%D8%D9%DA%DB%9D%03%DE%DF%C3%8AB%20%CD%D5%C9%C3%E8%C4%DC%EB%EC%ED%EE%EF%07%B2%89B%DA%C2%DE%AB%3F%8A%DCp%E0%03%19%05%C6%D8e%F8%21%0D%9E%C1%83%9A%92%00%AC%E6%8D%C7%C2k%DE%0A%16%0B%E8L%00%01j5%28bk%A8%A1n%00%C6l%01%3E%96%29WI%A4B%92%8D8%BE%01%88r%123%93%29%0A%10%90%B8%C3%0D%CC%0D%00X%3A%B8%19l%E7E%1E%03d%BA%91%C7c%E6%2B%A0y%3C%E2%E0%40%CD%E2GuT%CAA%3D%60%B1%1A%81%02%AB%9A%CDh%D2%2C%C0OW%BFrju%D0%8C%A7%28%8Cc%C9%BA%AA%F5qk%83%96%BB%AE%CAK%AB%8D%EE%DBlD%15%943%CB%D0U%02%00%3B"
                                                    alt="loading"></div>
                                        <div id="warning2"><span class="dashicons dashicons-warning"></span></div>
                                        <span id="success2"><span class="dashicons dashicons-yes"></span></span>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- End enable dropbox upload -->
                        <!-- Dropbox Client, Secret, Token, Destination Folder -->

                        <div class="_winvoice_dropbox_client_section" style="<?php
						if ( ( get_option( 'wpifw_pdf_invoice_upload_to_dropbox' ) == 1 ) ) {
							echo 'display:block';
						} else {
							echo 'display:none';
						}
						?>">
                            <div class="_winvoice-form-group wpifw_invoice_dropboxapi_text">
                                <label class="_winvoice-fixed-label"
                                       for="wpifw_invoice_dropboxapi_client_id"> <?php esc_html_e( 'DropBox Client ID', 'webappick-pdf-invoice-for-woocommerce' ); ?></label>
                                <input type="text"
                                       class="_winvoice-form-control _winvoice-number-input _winvoice-fixed-input wpifw_invoice_dropboxapi"
                                       id="wpifw_invoice_dropboxapi_client_id"
                                       name="wpifw_invoice_dropboxapi_client_id"
                                       placeholder="Enter DropBox ClientId"
                                       value='<?php echo( ! empty( get_option( 'wpifw_invoice_dropboxapi_client_id' ) ) ? esc_html( get_option( 'wpifw_invoice_dropboxapi_client_id' ) ) : '' ); ?>'>
                            </div>
                            <div class="_winvoice-form-group wpifw_invoice_dropboxapi_text">
                                <label class="_winvoice-fixed-label"
                                       for="wpifw_invoice_dropboxapi_client_secret"> <?php esc_html_e( 'DropBox Client Secret', 'webappick-pdf-invoice-for-woocommerce' ); ?></label>
                                <input type="text"
                                       class="_winvoice-form-control _winvoice-number-input _winvoice-fixed-input wpifw_invoice_dropboxapi"
                                       id="wpifw_invoice_dropboxapi_client_secret"
                                       name="wpifw_invoice_dropboxapi_client_secret"
                                       placeholder="Enter DropBox Client Secret"
                                       value='<?php echo( ! empty( get_option( 'wpifw_invoice_dropboxapi_client_secret' ) ) ? esc_html( get_option( 'wpifw_invoice_dropboxapi_client_secret' ) ) : '' ); ?>'>
                            </div>
                            <div class="_winvoice-form-group wpifw_invoice_dropboxapi_text">
                                <label class="_winvoice-fixed-label"
                                       for="wpifw_invoice_dropboxapi_access_token"> <?php esc_html_e( 'DropBox Access Token', 'webappick-pdf-invoice-for-woocommerce' ); ?></label>
                                <input type="text"
                                       class="_winvoice-form-control _winvoice-number-input _winvoice-fixed-input wpifw_invoice_dropboxapi"
                                       id="wpifw_invoice_dropboxapi_access_token"
                                       name="wpifw_invoice_dropboxapi_access_token"
                                       placeholder="Enter DropBox Access Token"
                                       value='<?php echo( ! empty( get_option( 'wpifw_invoice_dropboxapi_access_token' ) ) ? esc_html( get_option( 'wpifw_invoice_dropboxapi_access_token' ) ) : '' ); ?>'>
                            </div>

                            <div class="_winvoice-form-group wpifw_invoice_dropboxapi_text">
                                <label class="_winvoice-fixed-label"
                                       for="wpifw_invoice_dropboxapi_folder_path"> <?php esc_html_e( 'Destination Folder Name(optional)', 'webappick-pdf-invoice-for-woocommerce' ); ?></label>
                                <input type="text"
                                       class="_winvoice-form-control _winvoice-number-input _winvoice-fixed-input wpifw_invoice_dropboxapi"
                                       id="wpifw_invoice_dropboxapi_folder_path"
                                       name="wpifw_invoice_dropboxapi_folder_path"
                                       placeholder="Example folderName/child/grandchild"
                                       value='<?php echo( ! empty( get_option( 'wpifw_invoice_dropboxapi_folder_path' ) ) ? esc_html( get_option( 'wpifw_invoice_dropboxapi_folder_path' ) ) : '' ); ?>'>
                                <p id="show_ajax_query_result" style="display:none;
                                                    float: right;
                                                    position: absolute;
                                                    background-color: #444;
                                                    color:white;
                                                    margin-left: 385px;
                                                    margin-top:-70px;
                                                    height: 30px;
                                                    vertical-align: middle;
                                                    padding: 6px;
                                                    border-radius: 4px;
                                                    "></p>

                            </div>
                        </div>
                        <div class="_winvoice-form-group">
                            <div class="_winvoice-custom-control _winvoice-custom-switch">
                                <div class="_winvoice-toggle-label">
                                    <label class="_winvoice-checkbox-label"
                                           for="wpifw_data_collection"><?php esc_html_e( 'Send Debug Info', 'webappick-pdf-invoice-for-woocommerce' ); ?></label>
                                </div>
                                <div class="_winvoice-toggle-container" tooltip="" flow="right">

                                    <input type="checkbox" id="wpifw_data_collection"
                                           class="_winvoice-custom-control-input wpifw_data_collection"
                                           name="wpifw_data_collection"
                                           value="1" <?php checked( Woo_Invoice_ProWebAppickAPI::getInstance()->is_tracking_allowed(), true, true ); ?>
                                    >
                                    <label class="_winvoice-custom-control-label tips"
                                           for="wpifw_data_collection"></label>
                                </div>
                                <div class="clear"></div>
                                <div>
                                    <p class="description">
										<?php
										esc_html_e( 'To opt out, leave this box unchecked. Your Feed Data remains un-tracked, and no data will be collected. No sensitive data is tracked.', 'webappick-pdf-invoice-for-woocommerce' );
										?>
                                        <a href="#"
                                           class="wpifw-show-data-collection-list"><?php esc_html_e( 'See What We Collect', 'webappick-pdf-invoice-for-woocommerce' ); ?></a>
                                    </p>
                                    <ul class="tracker_collection_list" style="display: none;">
                                        <li><?php echo implode( '</li><li>', Woo_Invoice_ProWebAppickAPI::getInstance()->get_data_collection_description() ); //phpcs:ignore ?></li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="_winvoice-card-footer _winvoice-save-changes-selector">
                            <input class="_winvoice-btn _winvoice-btn-primary" style="float:right;"
                                   type="submit" id="wpifw_invoice_settings_submit_btn" name="wpifw_submit"
                                   value="<?php esc_html_e( 'Save Changes', 'webappick-pdf-invoice-for-woocommerce' ); ?>"/>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Settings Tab Sidebar -->
        <div class="_winvoice-col-sm-4 _winvoice-col-12">
            <!--    Banner Section start    -->
		    <?php include(plugin_dir_path(__FILE__) . 'lifetime-banner-widget.php'); ?>
            <!-- End Banner section -->
        </div>
</li>
