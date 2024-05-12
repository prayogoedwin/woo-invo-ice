<?php

$order_item_meta_query          = woo_invoice_item_meta_query();
$woo_invoice_order_meta_query   = woo_invoice_order_meta_query();
$woo_invoice_product_meta_query = woo_invoice_product_meta_query();
?>
<li class="woo-invoice-invoice-li">
    <div class="_winvoice-row">
        <div class="_winvoice-col-sm-8 _winvoice-col-12">
            <div class="_winvoice-card _winvoice-mr-0">
                <div class="_winvoice-card-body _woinvoice-invoice-template">
                    <form action="" method="post" enctype="multipart/form-data">
						<?php wp_nonce_field( 'settings_form_nonce' ); ?>
                        <div class="_winvoice-header-title">
                            <h4><?php esc_html_e( 'Invoice Template', 'webappick-pdf-invoice-for-woocommerce' ); ?></h4>
                        </div>
                        <div class="_winvoice-form-group">
                            <label class="_winvoice-fixed-label"
                                   for="templateid"> <?php esc_html_e( 'Invoice Template', 'webappick-pdf-invoice-for-woocommerce' ); ?></label>
                            <a class="_winvoice-btn _winvoice-btn-primary" data-toggle="modal"
                               data-target="#winvoiceModalTemplates"
                               style="color:#fff"><?php esc_html_e( 'Select Template', 'webappick-pdf-invoice-for-woocommerce' ); ?></a>
                            <div class="_winvoice-modal fade" id="winvoiceModalTemplates" tabindex="-1"
                                 role="dialog" aria-hidden="true">
                                <div class="_winvoice-modal-dialog _winvoice-modal-dialog-centered"
                                     role="document">
                                    <div class="_winvoice-modal-content">
                                        <div class="_winvoice-modal-card" data-toggle="lists"
                                             data-lists-values="[&quot;name&quot;]">
                                            <div class="_winvoice-card-header">
                                                <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
																<span aria-hidden="true"
                                                                      style="font-size: 30px;text-align: right;display: block;">×</span>
                                                </button>
                                            </div>

                                            <div class="_winvoice-card-body">

                                                <div class="_winvoice-row">
                                                    <div class="_winvoice-col-sm-4">
                                                        <a href="#" class="_winvoice-template-selection"
                                                           data-template="invoice-1"><img
                                                                    src="<?php echo plugin_dir_url( __FILE__ ) . '../../images/templates/invoice-1.png'; ?>"
                                                                    alt=""
                                                                    style="
																	<?php
																	if ( get_option( 'wpifw_templateid' ) == 'invoice-1' ) {
																		echo esc_attr( $style2 );
																	} else {
																		echo esc_attr( $style );
																	}
																	?>
                                                                            "></a>
                                                    </div>
                                                    <div class="_winvoice-col-sm-4">
                                                        <a href="#" class="_winvoice-template-selection"
                                                           data-template="invoice-2"><img
                                                                    src="<?php echo plugin_dir_url( __FILE__ ) . '../../images/templates/invoice-2.png'; ?>"
                                                                    alt=""
                                                                    style="
																	<?php
																	if ( get_option( 'wpifw_templateid' ) == 'invoice-2' ) {
																		echo esc_attr( $style2 );
																	} else {
																		echo esc_attr( $style );
																	}
																	?>
                                                                            "></a>
                                                    </div>
                                                    <div class="_winvoice-col-sm-4">
                                                        <a href="#" class="_winvoice-template-selection"
                                                           data-template="invoice-3"><img
                                                                    src="<?php echo plugin_dir_url( __FILE__ ) . '../../images/templates/invoice-3.png'; ?>"
                                                                    alt=""
                                                                    style="
																	<?php
																	if ( get_option( 'wpifw_templateid' ) == 'invoice-3' ) {
																		echo esc_attr( $style2 );
																	} else {
																		echo esc_attr( $style );
																	}
																	?>
                                                                            "></a>
                                                    </div>
                                                    <div class="_winvoice-col-sm-4">
                                                        <a href="#" class="_winvoice-template-selection"
                                                           data-template="invoice-4"><img
                                                                    src="<?php echo plugin_dir_url( __FILE__ ) . '../../images/templates/invoice-4.png'; ?>"
                                                                    alt=""
                                                                    style="
																	<?php
																	if ( get_option( 'wpifw_templateid' ) == 'invoice-4' ) {
																		echo esc_attr( $style2 );
																	} else {
																		echo esc_attr( $style );
																	}
																	?>
                                                                            "></a>
                                                    </div>
                                                    <div class="_winvoice-col-sm-4" style="display:none">
                                                        <a href="#" class="_winvoice-template-selection"
                                                           data-template="invoice-5"><img
                                                                    src="<?php echo plugin_dir_url( __FILE__ ) . '../../images/templates/invoice-5.png'; ?>"
                                                                    alt=""
                                                                    style="
																	<?php
																	if ( get_option( 'wpifw_templateid' ) == 'invoice-5' ) {
																		echo esc_attr( $style2 );
																	} else {
																		echo esc_attr( $style );
																	}
																	?>
                                                                            "></a>
                                                    </div>
                                                    <div class="_winvoice-col-sm-4" style="display:none">
                                                        <a href="#" class="_winvoice-template-selection"
                                                           data-template="invoice-6"><img
                                                                    src="<?php echo plugin_dir_url( __FILE__ ) . '../../images/templates/invoice-6.png'; ?>"
                                                                    alt=""
                                                                    style="
																	<?php
																	if ( get_option( 'wpifw_templateid' ) == 'invoice-6' ) {
																		echo esc_attr( $style2 );
																	} else {
																		echo esc_attr( $style );
																	}
																	?>
                                                                            "></a>
                                                    </div>
                                                    <div class="_winvoice-col-sm-4" style="display:none">
                                                        <a href="#" class="_winvoice-template-selection"
                                                           data-template="invoice-7"><img
                                                                    src="<?php echo plugin_dir_url( __FILE__ ) . '../../images/templates/invoice-7.png'; ?>"
                                                                    alt=""
                                                                    style="
																	<?php
																	if ( get_option( 'wpifw_templateid' ) == 'invoice-7' ) {
																		echo esc_attr( $style2 );
																	} else {
																		echo esc_attr( $style );
																	}
																	?>
                                                                            "></a>
                                                    </div>
                                                    <div class="_winvoice-col-sm-4">
                                                        <a href="#" class="_winvoice-template-selection"
                                                           data-template="invoice-8"><img
                                                                    src="<?php echo plugin_dir_url( __FILE__ ) . '../../images/templates/invoice-8.png'; ?>"
                                                                    alt=""
                                                                    style="
																	<?php
																	if ( get_option( 'wpifw_templateid' ) == 'invoice-8' ) {
																		echo esc_attr( $style2 );
																	} else {
																		echo esc_attr( $style );
																	}
																	?>
                                                                            "></a>
                                                    </div>
                                                    <div class="_winvoice-col-sm-4">
                                                        <a href="#" class="_winvoice-template-selection"
                                                           data-template="invoice-9"><img
                                                                    src="<?php echo plugin_dir_url( __FILE__ ) . '../../images/templates/invoice-9.png'; ?>"
                                                                    alt=""
                                                                    style="
																	<?php
																	if ( get_option( 'wpifw_templateid' ) == 'invoice-9' ) {
																		echo esc_attr( $style2 );
																	} else {
																		echo esc_attr( $style );
																	}
																	?>
                                                                            "></a>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="_winvoice-card-footer">
                                                <button class="_winvoice-btn _winvoice-btn-primary"
                                                        data-dismiss="modal" aria-label="Close"
                                                        style="float:right;margin-bottom: 20px;">Close
                                                </button>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="_winvoice-form-group">
							<?php
							$wpifw_invoice_paper_size = ( '' != get_option( 'wpifw_invoice_paper_size' ) ) ? get_option( 'wpifw_invoice_paper_size' ) : 'A4';
							?>
                            <label class="wpifw-invoice-paper-size-label _winvoice-fixed-label"
                                   for="wpifw-invoice-paper-size"> <?php esc_html_e( 'Paper Size', 'webappick-pdf-invoice-for-woocommerce' ); ?></label>
                            <div>
                                <select id="wpifw-invoice-paper-size"
                                        name="wpifw_invoice_paper_size"
                                        class="_winvoice-select-control _winvoice-fixed-input">
                                    <option value="A3" <?php selected( $wpifw_invoice_paper_size, 'A3', true ); ?>>
                                        A3
                                    </option>
                                    <option value="A4" <?php selected( $wpifw_invoice_paper_size, 'A4', true ); ?>>
                                        A4
                                    </option>
                                    <option value="A5" <?php selected( $wpifw_invoice_paper_size, 'A5', true ); ?>>
                                        A5
                                    </option>
                                    <option value="Letter" <?php selected( $wpifw_invoice_paper_size, 'Letter', true ); ?>>
                                        Letter
                                    </option>
                                    <option value="custom" <?php selected( $wpifw_invoice_paper_size, 'custom', true ); ?>>
										<?php esc_html_e( 'Custom Paper Size', 'webappick-pdf-invoice-for-woocommerce' ); ?>
                                    </option>
                                </select>
                                <div class="wpifw_invoice_custom_paper_size"
                                     style="overflow:hidden;display:none">
                                    <input style="float:left" type="text"
                                           class="_winvoice-form-control"
                                           id="_winvoice_invoice_custom_paper_wide"
                                           name="wpifw_invoice_custom_paper_wide"
                                           value="<?php echo esc_attr( get_option( 'wpifw_invoice_custom_paper_wide' ) ); ?>"
                                           placeholder="wide(mm)">
                                    <span class="wpifw_invoice_custom_paper_times">X</span><input
                                            style="float:right" type="text"
                                            class="_winvoice-form-control"
                                            id="_winvoice_invoice_custom_paper_height"
                                            name="wpifw_invoice_custom_paper_height"
                                            value="<?php echo esc_attr( get_option( 'wpifw_invoice_custom_paper_height' ) ); ?>"
                                            placeholder="height(mm)">
                                </div>
                            </div>
                        </div>

                        <!--<div class="_winvoice-form-group" tooltip="" flow="right">
										<label class="_winvoice-fixed-label" for="wpifw_template_ancient_color"><?php /*_e('Ancient Color', 'webappick-pdf-invoice-for-woocommerce'); */ ?></label>
										<input style="width:140px;" type="text" class="_winvoice-form-control wpifw_template_ancient_color" id ="wpifw_template_ancient_color" name="wpifw_template_ancient_color" value="<?php /*echo esc_attr(get_option("wpifw_template_ancient_color")); */ ?>">
									</div>-->

                        <div class="_winvoice-header-title">
                            <h4><?php esc_html_e( 'Paid Stamp', 'webappick-pdf-invoice-for-woocommerce' ); ?></h4>
                        </div>

                        <div class="_winvoice-form-group">
                            <div class="_winvoice-custom-control _winvoice-custom-switch">
                                <div class="_winvoice-toggle-label">
                                    <span class="_winvoice-checkbox-label"><?php esc_html_e( 'Enable Paid Stamp', 'webappick-pdf-invoice-for-woocommerce' ); ?></span>
                                </div>
                                <div class="_winvoice-toggle-container">
                                    <input type="hidden" name="wpifw_paid_stamp" value="0">
                                    <input type="checkbox" class="_winvoice-custom-control-input"
                                           id="wpifw_paid_stamp" name="wpifw_paid_stamp"
                                           value="1" <?php checked( get_option( 'wpifw_paid_stamp' ), $current, true ); ?>
                                    >
                                    <label class="_winvoice-custom-control-label"
                                           for="wpifw_paid_stamp"></label>
                                </div>


                            </div>
                        </div>

                        <div class="_winvoice-form-group">
                            <label class="_winvoice-fixed-label"
                                   for="paid-stamp-image"> <?php esc_html_e( 'Select Paid Stamp', 'webappick-pdf-invoice-for-woocommerce' ); ?></label>
                            <div style="display:inline-block;">
								<?php if ( '' == get_option( 'wpifw_custom_stamp_attachment_id' ) ) { ?>
                                    <div class="upload_assets">
                                        <img class="_winvoice-stamp-preview"
                                             src="<?php echo WP_PLUGIN_URL . '/webappick-pdf-invoice-for-woocommerce-pro/admin/images/paid-stamp/' . get_option( 'wpifw_paid_stamp_image', 'paid-stamp-1' ) . '.png'; ?>"
                                             alt="">
                                    </div>
								<?php } ?>
                                <a class="_winvoice-stamp-btn _winvoice-btn _winvoice-btn-primary"
                                   data-toggle="modal" data-target="#modalStamps" style="color:#fff;margin-top:20px">
                                    Select Stamp
                                </a>
                            </div>

                            <div class="_winvoice-modal fade" id="modalStamps" tabindex="-1" role="dialog"
                                 aria-hidden="true">
                                <div class="_winvoice-modal-dialog _winvoice-modal-dialog-centered"
                                     role="document">
                                    <div class="_winvoice-modal-content">
                                        <div class="_winvoice-modal-card" data-toggle="lists"
                                             data-lists-values="[&quot;name&quot;]">
                                            <div class="_winvoice-card-header">
                                                <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
																<span aria-hidden="true"
                                                                      style="font-size: 30px;text-align: right;display: block;">×</span>
                                                </button>
                                            </div>

                                            <div class="_winvoice-card-body">

                                                <div class="_winvoice-row">

													<?php
													$paid_stamp = '';
													for ( $i = 1; $i <= 17; $i ++ ) {
														$paid_stamp .= '<div class="_winvoice-col-sm-3">
                                                                        <a href="#" class="_winvoice-stamp-selection" data-stamp="paid-stamp-' . $i . '"><img class="_winvoice-paid-stamp-img" src="' . WP_PLUGIN_URL . '/webappick-pdf-invoice-for-woocommerce-pro/admin/images/paid-stamp/paid-stamp-' . $i . '.png" alt=""></a>
                                                                        </div>';
													}

													echo $paid_stamp; // phpcs:ignore

													?>
                                                </div>

                                            </div>
                                            <div class="_winvoice-card-footer">
                                                <button class="_winvoice-btn _winvoice-btn-primary"
                                                        data-dismiss="modal" aria-label="Close"
                                                        style="float:right;margin-bottom: 20px;">Close
                                                </button>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="_winvoice-form-group" style="display:flex;align-items:center">
                            <label class="_winvoice-fixed-label"
                                   for="wpifw_custom_stamp_attachment_id"><?php esc_html_e( 'Custom Stamp', 'webappick-pdf-invoice-for-woocommerce' ); ?></label>

                            <div style="display:inline-block;">
								<?php wp_enqueue_media(); ?>
								<?php
								$url = wp_get_attachment_url( esc_html( get_option( 'wpifw_custom_stamp_attachment_id' ) ) );
								if ( get_option( 'wpifw_custom_stamp_attachment_id' ) != false && ! empty( get_option( 'wpifw_custom_stamp_attachment_id' ) ) ) {
									?>
                                    <div class="upload_assets" id="custom_stamp_assets">
                                        <img class="_winvoice_custom_stamp_preview" id='wpifw_custom_stamp_preview'
                                             src='<?php echo esc_url( $url ); ?>'>
                                        <span class="dashicons dashicons-dismiss _winvoice_custom_stamp_preview upload_close wpifw_close_custom_stamp"></span>
                                    </div>
									<?php
								} else { ?>
                                    <div class="upload_assets">
                                        <img class="_winvoice_custom_stamp_preview" id='wpifw_custom_stamp_preview'
                                             src='<?php echo esc_url( $url ); ?>'>
                                    </div>
								<?php }
								?>
                                <input id="wpifw_upload_custom_stamp_button" type="button"
                                       class="_winvoice-btn _winvoice-btn-primary"
                                       value="<?php esc_html_e( 'Upload Stamp', 'webappick-pdf-invoice-for-woocommerce' ); ?>"/>
                                <input type='hidden' name='wpifw_custom_stamp_attachment_id'
                                       id='wpifw_custom_stamp_attachment_id'
                                       value='<?php echo esc_attr( get_option( 'wpifw_custom_stamp_attachment_id' ) ); ?>'>
                            </div>

                        </div>


                        <div class="_winvoice-header-title">
                            <h4><?php esc_html_e( 'Signature', 'webappick-pdf-invoice-for-woocommerce' ); ?></h4>
                        </div>

                        <div class="_winvoice-form-group">
                            <div class="_winvoice-custom-control _winvoice-custom-switch">
                                <div class="_winvoice-toggle-label">
                                    <span class="_winvoice-checkbox-label"><?php esc_html_e( 'Enable Signature', 'webappick-pdf-invoice-for-woocommerce' ); ?></span>
                                </div>
                                <div class="_winvoice-toggle-container">
                                    <input type="hidden" name="wpifw_enable_signature" value="0">
                                    <input type="checkbox" class="_winvoice-custom-control-input"
                                           id="wpifw_enable_signature" name="wpifw_enable_signature"
                                           value="1" <?php checked( get_option( 'wpifw_enable_signature' ), $current, true ); ?>
                                    >
                                    <label class="_winvoice-custom-control-label"
                                           for="wpifw_enable_signature"></label>
                                </div>

                            </div>
                        </div>

                        <div class="_winvoice-form-group" style="display: flex;align-items:center">
                            <label class="_winvoice-fixed-label"
                                   for="logo"><?php esc_html_e( 'Upload Signature', 'webappick-pdf-invoice-for-woocommerce' ); ?></label>

                            <div style="display:inline-block;">
								<?php wp_enqueue_media(); ?>
								<?php
								$url = wp_get_attachment_url( esc_html( get_option( 'wpifw_signature_attachment_image_id' ) ) );
								if ( get_option( 'wpifw_signature_attachment_image_id' ) != false && ! empty( get_option( 'wpifw_signature_attachment_image_id' ) ) ) {

									?>
                                    <div class="upload_assets" id="signature_assets">
                                        <img class="_winvoice-signature-preview" id='wpifw_signature-preview'
                                             src='<?php echo esc_url( $url ); ?>'>
                                        <span class="dashicons dashicons-dismiss _winvoice-signature-preview upload_close wpifw_close_signature"></span>
                                    </div>
									<?php
								} else { ?>
                                    <div class="upload_assets">
                                        <img class="_winvoice-signature-preview" id='wpifw_signature-preview'
                                             src='<?php echo esc_url( $url ); ?>'>
                                    </div>
								<?php }
								?>
                                <input id="wpifw_upload_signature_button" type="button"
                                       class="_winvoice-signature-btn _winvoice-btn _winvoice-btn-primary"
                                       value="<?php esc_html_e( 'Upload Signature', 'webappick-pdf-invoice-for-woocommerce' ); ?>"/>
                                <input type='hidden' name='wpifw_signature_attachment_id'
                                       id='wpifw_signature_attachment_id'
                                       value='<?php echo esc_attr( get_option( 'wpifw_signature_attachment_image_id' ) ); ?>'>
                            </div>

                        </div>

                        <div class="_winvoice-header-title">
                            <h4><?php esc_html_e( 'Invoice Background Image', 'webappick-pdf-invoice-for-woocommerce' ); ?></h4>
                        </div>

                        <div class="_winvoice-form-group">
                            <div class="_winvoice-custom-control _winvoice-custom-switch">
                                <div class="_winvoice-toggle-label">
                                    <span class="_winvoice-checkbox-label"><?php esc_html_e( 'Enable Invoice Background', 'webappick-pdf-invoice-for-woocommerce' ); ?></span>
                                </div>
                                <div class="_winvoice-toggle-container">
                                    <input type="hidden" name="wpifw_enable_invoice_background" value="0">
                                    <input type="checkbox" class="_winvoice-custom-control-input"
                                           id="wpifw_enable_invoice_background"
                                           name="wpifw_enable_invoice_background"
                                           value="1" <?php checked( get_option( 'wpifw_enable_invoice_background' ), $current, true ); ?>
                                    >
                                    <label class="_winvoice-custom-control-label"
                                           for="wpifw_enable_invoice_background"></label>
                                </div>
                            </div>
                        </div>

                        <div class="_winvoice-form-group" style="display:flex;align-items:center;">
                            <label class="_winvoice-fixed-label"><?php esc_html_e( 'Upload Invoice Background', 'webappick-pdf-invoice-for-woocommerce' ); ?></label>

                            <div style="display:inline-block;">
								<?php wp_enqueue_media(); ?>
								<?php
								if ( get_option( 'wpifw_invoice_background_attachment_image_id' ) != false && ! empty( get_option( 'wpifw_invoice_background_attachment_image_id' ) ) ) {
									$url = wp_get_attachment_url( get_option( 'wpifw_invoice_background_attachment_image_id' ) );
									?>
                                    <div class="upload_assets" id="wpifw_invoice_background">
                                        <img style="width:200px" class="_winvoice-invoice-background-preview"
                                             id='wpifw_invoice-background-preview' src='<?php echo esc_url( $url ); ?>'>
                                        <span class="dashicons dashicons-dismiss _winvoice-invoice-background-preview upload_close wpifw_invoice_close_background"></span>
                                    </div>
									<?php
								} else {
									?>
                                    <img style="width: 200px" class="_winvoice-invoice-background-preview"
                                         id='wpifw_invoice-background-preview' src=''>
									<?php
								} ?>
                                <input id="wpifw_upload_invoice_background_button" type="button"
                                       class="_winvoice-invoice-background-btn _winvoice-btn _winvoice-btn-primary"
                                       value="<?php esc_html_e( 'Upload Background Image', 'webappick-pdf-invoice-for-woocommerce' ); ?>"/>
                                <input type='hidden' name='wpifw_invoice_background_attachment_id'
                                       id='wpifw_invoice_background_attachment_id'
                                       value='<?php echo esc_attr( get_option( 'wpifw_invoice_background_attachment_image_id' ) ); ?>'>
                            </div>

                        </div>
                        <div class="_winvoice-form-group" tooltip="" flow="right">
                            <label class="_winvoice-fixed-label"
                                   for="wpifw_invoice_background_opacity"><?php esc_html_e( 'Background Image Opacity', 'webappick-pdf-invoice-for-woocommerce' ); ?></label>
                            <input type="text"
                                   class="_winvoice-form-control wpifw_invoice_background_opacity _winvoice-fixed-input"
                                   id="wpifw_invoice_background_opacity"
                                   name="wpifw_invoice_background_opacity"
                                   value="<?php echo get_option( 'wpifw_invoice_background_opacity' ) != null ? esc_attr( get_option( 'wpifw_invoice_background_opacity' ) ) : '0.1'; ?>">
                        </div>

                        <div class="_winvoice-header-title">
                            <h4><?php esc_html_e( 'Order Info', 'webappick-pdf-invoice-for-woocommerce' ); ?></h4>
                        </div>


                        <!--=======================================================================-->
                        <!--  Display order number-->
                        <!--=======================================================================-->
						<?php
						if ( get_option( "wpifw_display_order_number" ) === false ) {
							update_option( 'wpifw_display_order_number', true );
						}
						?>
                        <div class="_winvoice-form-group">
                            <div class="_winvoice-custom-control _winvoice-custom-switch">
                                <div class="_winvoice-toggle-label">
                                    <span class="_winvoice-checkbox-label"><?php esc_html_e( 'Display Order Number', 'webappick-pdf-invoice-for-woocommerce' ); ?></span>
                                </div>
                                <div class="_winvoice-toggle-container">
                                    <input type="hidden" name="wpifw_display_order_number" value="0">
                                    <input type="checkbox"
                                           class="_winvoice-custom-control-input"
                                           id="wpifw_display_order_number"
                                           name="wpifw_display_order_number"
                                           value="1" <?php checked( get_option( 'wpifw_display_order_number' ), $current, true ); ?>
                                    >
                                    <label class="_winvoice-custom-control-label"
                                           for="wpifw_display_order_number"></label>
                                </div>
                            </div>
                        </div>

                        <div class="_winvoice-form-group" tooltip="" flow="right">
                            <label class="_winvoice-fixed-label"
                                   for="wpifw_invoice_order_number_type"> <?php esc_html_e( 'Numbering Type', 'webappick-pdf-invoice-for-woocommerce' ); ?></label>
                            <select id="wpifw_invoice_order_number_type"
                                    name="wpifw_invoice_order_number_type"
                                    class="_winvoice-select-control _winvoice-fixed-input">
                                <option value="order_number" <?php selected( get_option( 'wpifw_invoice_order_number_type' ), 'order_number', true ); ?>><?php esc_html_e( 'Order Number', 'webappick-pdf-invoice-for-woocommerce' ); ?></option>
                                <option value="order_id" <?php selected( get_option( 'wpifw_invoice_order_number_type' ), 'order_id', true ); ?>><?php esc_html_e( 'Order ID', 'webappick-pdf-invoice-for-woocommerce' ); ?></option>

								<?php
								if ( class_exists( 'Alg_WC_Custom_Order_Numbers' ) ) {
									?>
                                    <option value="_wooinvoice_custom_order_numbers_for_woocommerce" <?php selected( get_option( 'wpifw_invoice_order_number_type' ), '_wooinvoice_custom_order_numbers_for_woocommerce', true ); ?>><?php esc_html_e( 'Custom Order Numbers for WooCommerce', 'webappick-pdf-invoice-for-woocommerce' ); ?></option>
									<?php
								}
								?>
								<?php
								if ( class_exists( 'Wt_Advanced_Order_Number' ) ) {
									?>
                                    <option value="_wooinvoice_wt_woocommerce_sequential_order_numbers" <?php selected( get_option( 'wpifw_invoice_order_number_type' ), '_wooinvoice_wt_woocommerce_sequential_order_numbers', true ); ?>><?php esc_html_e( 'Sequential Order Number for WooCommerce', 'webappick-pdf-invoice-for-woocommerce' ); ?></option>
									<?php
								}
								?>
								<?php
								if ( class_exists( 'WC_Seq_Order_Number' ) ) {
									?>
                                    <option value="_wooinvoice_woocommerce_sequential_order_numbers" <?php selected( get_option( 'wpifw_invoice_order_number_type' ), '_wooinvoice_woocommerce_sequential_order_numbers', true ); ?>><?php esc_html_e( 'WooCommerce Sequential Order Numbers', 'webappick-pdf-invoice-for-woocommerce' ); ?></option>
									<?php
								}
								?>
								<?php
								if ( class_exists( 'WCSON_INIT' ) ) {
									?>
                                    <option value="_wooinvoice_woo_custom_and_sequential_order_number" <?php selected( get_option( 'wpifw_invoice_order_number_type' ), '_wooinvoice_woo_custom_and_sequential_order_number', true ); ?>><?php esc_html_e( 'Woo Custom and Sequential Order Number', 'webappick-pdf-invoice-for-woocommerce' ); ?></option>
									<?php
								}
								?>
								<?php
								if ( class_exists( 'BeRocket_Order_Numbers' ) ) {
									?>
                                    <option value="_wooinvoice_sequential_order_numbers_for_wooCommerce" <?php selected( get_option( 'wpifw_invoice_order_number_type' ), '_wooinvoice_sequential_order_numbers_for_wooCommerce', true ); ?>><?php esc_html_e( 'Sequential Order Numbers for WooCommerce', 'webappick-pdf-invoice-for-woocommerce' ); ?></option>
									<?php
								}
								?>
								<?php
								if ( class_exists( 'OpenToolsOrdernumbersBasic' ) ) {
									?>
                                    <option value="_wooinvoice_woocommerce_basic_ordernumbers" <?php selected( get_option( 'wpifw_invoice_order_number_type' ), '_wooinvoice_woocommerce_basic_ordernumbers', true ); ?>><?php esc_html_e( 'WooCommerce Basic Ordernumbers', 'webappick-pdf-invoice-for-woocommerce' ); ?></option>
									<?php
								}
								?>
                            </select>
                        </div>

                        <div class="_winvoice-form-group _winvoice-next-order"
                             tooltip="" flow="right">

                        </div>
                        <div class="_winvoice-form-group _winvoice-order-prefix"
                             tooltip="Avilable macros: {{year}}, {{month}} and {{day}}"
                             flow="right">
                            <label class="_winvoice-fixed-label"
                                   for="order-invprefix"><?php esc_html_e( 'Order No. Prefix', 'webappick-pdf-invoice-for-woocommerce' ); ?></label>
                            <input type="text" class="_winvoice-form-control _winvoice-fixed-input"
                                   id="order-invprefix" name="wpifw_order_no_prefix"
                                   value="<?php echo esc_attr( get_option( 'wpifw_order_no_prefix' ) ); ?>">
                        </div>

                        <div class="_winvoice-form-group _winvoice-order-suffix"
                             tooltip="Avilable macros: {{year}}, {{month}} and {{day}}"
                             flow="right">


                            <label class="_winvoice-fixed-label"
                                   for="order-insuff"><?php esc_html_e( 'Order No. Suffix', 'webappick-pdf-invoice-for-woocommerce' ); ?></label>
                            <input type="text"
                                   class="_winvoice-form-control _winvoice-fixed-input"
                                   id="order-insuff"
                                   name="wpifw_order_no_suffix"
                                   value="<?php echo esc_attr( get_option( 'wpifw_order_no_suffix' ) ); ?>">
                        </div>

                        <!--=======================================================================-->
                        <!--  Display shipping total with tax and without tax-->
                        <!--=======================================================================-->
                        <div class="_winvoice-form-group" tooltip=""
                             flow="right">
                            <label class="_winvoice-fixed-label"
                                   for="wpifw_invoice_display_shipping_total"> <?php esc_html_e( 'Display Shipping Total', 'webappick-pdf-invoice-for-woocommerce' ); ?></label>
                            <select id="wpifw_invoice_display_shipping_total"
                                    name="wpifw_invoice_display_shipping_total"
                                    class="_winvoice-select-control _winvoice-fixed-input">
                                <option value="wpifw_invoice_display_shipping_total_with_tax" <?php selected( get_option( 'wpifw_invoice_display_shipping_total' ), 'wpifw_invoice_display_shipping_total_with_tax', true ); ?>><?php esc_html_e( 'With Tax', 'webappick-pdf-invoice-for-woocommerce' ); ?></option>
                                <option value="wpifw_invoice_display_shipping_total_without_tax" <?php selected( get_option( 'wpifw_invoice_display_shipping_total' ), 'wpifw_invoice_display_shipping_total_without_tax', true ); ?>><?php esc_html_e( 'Without Tax', 'webappick-pdf-invoice-for-woocommerce' ); ?></option>
                            </select>
                        </div>
                        <!--=======================================================================-->
                        <!--  Display order note -->
                        <!--=======================================================================-->
                        <div class="_winvoice-form-group">
                            <div class="_winvoice-custom-control _winvoice-custom-switch">
                                <div class="_winvoice-toggle-label">
                                    <span class="_winvoice-checkbox-label"><?php esc_html_e( 'Display Order Note', 'webappick-pdf-invoice-for-woocommerce' ); ?></span>
                                </div>
                                <div class="_winvoice-toggle-container">
                                    <input type="hidden" name="wpifw_show_order_note" value="0">
                                    <input type="checkbox"
                                           class="_winvoice-custom-control-input"
                                           id="wpifw_show_order_note"
                                           name="wpifw_show_order_note"
                                           value="1" <?php checked( get_option( 'wpifw_show_order_note' ), $current, true ); ?>
                                    >
                                    <label class="_winvoice-custom-control-label"
                                           for="wpifw_show_order_note"></label>
                                </div>
                            </div>
                        </div>

                        <!--=======================================================================-->
                        <!--  Display order status -->
                        <!--=======================================================================-->
                        <div class="_winvoice-form-group">
                            <div class="_winvoice-custom-control _winvoice-custom-switch">
                                <div class="_winvoice-toggle-label">
                                    <span class="_winvoice-checkbox-label"><?php esc_html_e( 'Display Order Status', 'webappick-pdf-invoice-for-woocommerce' ); ?></span>
                                </div>
                                <div class="_winvoice-toggle-container">
                                    <input type="hidden" name="wpifw_show_order_status" value="0">
                                    <input type="checkbox"
                                           class="_winvoice-custom-control-input"
                                           id="wpifw_show_order_status"
                                           name="wpifw_show_order_status"
                                           value="1" <?php checked( get_option( 'wpifw_show_order_status' ), $current, true ); ?>
                                    >
                                    <label class="_winvoice-custom-control-label"
                                           for="wpifw_show_order_status"></label>
                                </div>
                            </div>
                        </div>

                        <!-- ===========================
						 Add order meta for invoice.
						 =========================== -->
                        <div class="_winvoice-form-group" tooltip="" flow="right">
                            <label class="_winvoice-fixed-label"
                                   for="wpifw_product_order_meta_show"> <?php esc_html_e( 'Add Order Meta', 'webappick-pdf-invoice-for-woocommerce' ); ?></label>

                            <a href="#" class="_winvoice-add-order-meta">
                                <div class="_winvoice-add-order-meta-btn">
                                    <span class="dashicons dashicons-plus-alt"></span>
									<?php esc_html_e( 'Add Order Meta', 'webappick-pdf-invoice-for-woocommerce' ); ?>
                                </div>
                            </a>

                            <div class="_winvoice_order_meta"
                                 style="float: none; padding-top: 10px;">
								<?php
								$order_meta_label = get_option( '_winvoice_order_meta_label' );
								$order_meta_name  = get_option( '_winvoice_order_meta_name' );
								$order_meta_place = get_option( '_winvoice_order_meta_name_position' );

								if ( $order_meta_label ) {

									foreach ( $order_meta_label as $key => $value ) {
										?>
                                        <div class="_winvoice_order_meta_html _winvoice_col_3">
                                            <input type="text"
                                                   placeholder="Meta Label"
                                                   class="_winvoice-form-control _winvoice-product-order-meta-label"
                                                   name="_winvoice_order_meta_label[]"
                                                   value="<?php echo $value; //phpcs:ignore ?>">
                                            <select
                                                    id="wpifw_product_order_meta_show"
                                                    class="_winvoice-select-control _winvoice-product-order-meta"
                                                    name="_winvoice_order_meta_name[]">
                                                <option value=""
                                                        disabled><?php esc_html_e( 'Select Order Meta', 'webappick-pdf-invoice-for-woocommerce' ); ?></option>
												<?php
												if ( count( $woo_invoice_order_meta_query ) ) {
													foreach ( $woo_invoice_order_meta_query as $meta ) {
														$selected = $order_meta_name[ $key ] == $meta->meta_key ? 'selected' : '';
														echo '<option value=' . $meta->meta_key . ' ' . $selected . ' >' . $meta->meta_key . '</option>'; //phpcs:ignore
													}
												}
												?>
                                            </select>
                                            <!--where to show?-->
                                            <select
                                                    id="wpifw_product_order_meta_position"
                                                    class="_winvoice-select-control _winvoice-product-order-meta-position"
                                                    name="_winvoice_order_meta_name_position[]">
                                                <option value=""
                                                        disabled><?php esc_html_e( 'Whare to show ?', 'webappick-pdf-invoice-for-woocommerce' ); ?></option>

												<?php
												if ( count( woo_invoice_order_meta_data_position() ) ) {
													foreach ( woo_invoice_order_meta_data_position() as $index => $val ) {
														$selected = $index == $order_meta_place[ $key ] ? 'selected' : '';
														echo '<option value=' . $index . ' ' . $selected . '>' . $val . '</option>'; //phpcs:ignore
													}
												}
												?>
                                            </select>
                                            <a href="#"
                                               class="_winvoice-delete-order-meta"><span
                                                        class="dashicons dashicons-trash"
                                                        style="color:#D94D40"></span></a>
                                        </div>

										<?php
									}
								} else {
									?>
                                    <div style="display: none;">
                                        <div class="_winvoice_order_meta_html _winvoice_col_3">
                                            <input data="2" type="text"
                                                   placeholder="Meta Label"
                                                   class="_winvoice-form-control _winvoice-product-order-meta-label"
                                                   name="_winvoice_order_meta_label[]"
                                            >
                                            <select
                                                    id="wpifw_product_order_meta_show"
                                                    class="_winvoice-select-control _winvoice-product-order-meta"
                                                    name="_winvoice_order_meta_name[]"
                                            >
                                                <option value=""
                                                        disabled><?php esc_html_e( 'Select Order Meta', 'webappick-pdf-invoice-for-woocommerce' ); ?></option>
												<?php
												if ( count( $woo_invoice_order_meta_query ) ) {
													foreach ( $woo_invoice_order_meta_query as $meta ) {
														echo '<option value=' . $meta->meta_key . '>' . $meta->meta_key . '</option>'; //phpcs:ignore
													}
												}
												?>
                                            </select>
                                            <!--where to show?-->
                                            <select
                                                    id="wpifw_product_order_meta_position"
                                                    class="_winvoice-select-control _winvoice-product-order-meta-position"
                                                    name="_winvoice_order_meta_name_position[]"
                                            >
                                                <option value=""
                                                        disabled><?php esc_html_e( 'Whare to show ?', 'webappick-pdf-invoice-for-woocommerce' ); ?></option>
												<?php

												if ( count( woo_invoice_order_meta_data_position() ) ) {
													foreach ( woo_invoice_order_meta_data_position() as $key => $val ) {
														echo '<option value=' . $key . '>' . $val . '</option>'; //phpcs:ignore
													}
												}
												?>
                                            </select>
                                            <a href="#"
                                               class="_winvoice-delete-order-meta"><span
                                                        class="dashicons dashicons-trash"
                                                        style="color:#D94D40"></span></a>
                                        </div>

                                    </div>

								<?php } ?>
                            </div>
                            <p style="opacity: .7" class="_winvoice_order_meta_html _winvoice_col_3">Notice: If order
                                meta value is empty or an array will not display.</p>

                        </div>
                        <!-- ===========================
						 End Add order meta for invoice.
						 =========================== -->

                        <div class="_winvoice-header-title">
                            <h4><?php esc_html_e( 'Invoice Number', 'webappick-pdf-invoice-for-woocommerce' ); ?></h4>
                        </div>
						<?php $wpifw_invoice_number = ! get_option( 'wpifw_display_invoice_number' ) && '' != get_option( 'wpifw_display_invoice_number' ) ? get_option( 'wpifw_display_invoice_number' ) : 1; ?>
                        <div class="_winvoice-form-group">
                            <div class="_winvoice-custom-control _winvoice-custom-switch">
                                <div class="_winvoice-toggle-label">
                                    <span class="_winvoice-checkbox-label"><?php esc_html_e( 'Display Invoice Number', 'webappick-pdf-invoice-for-woocommerce' ); ?></span>
                                </div>
                                <div class="_winvoice-toggle-container">
                                    <input type="hidden" name="wpifw_display_invoice_number" value="0">
                                    <input type="checkbox"
                                           class="_winvoice-custom-control-input"
                                           id="wpifw_display_invoice_number"
                                           name="wpifw_display_invoice_number"
                                           value="1" <?php checked( $wpifw_invoice_number, $current, true ); ?>
                                    >
                                    <label class="_winvoice-custom-control-label"
                                           for="wpifw_display_invoice_number"></label>
                                </div>
                            </div>
                        </div>

                        <div class="_winvoice-form-group" tooltip=""
                             flow="right">
                            <label class="_winvoice-fixed-label"
                                   for="wpifw_invoice_number_type"> <?php esc_html_e( 'Numbering Type', 'webappick-pdf-invoice-for-woocommerce' ); ?></label>
                            <select id="wpifw_invoice_number_type"
                                    name="wpifw_invoice_number_type"
                                    class="_winvoice-select-control _winvoice-fixed-input">
                                <option value="pre_custom_number_suf" <?php selected( get_option( 'wpifw_invoice_number_type' ), 'pre_custom_number_suf', true ); ?>><?php esc_html_e( 'Prefix + Custom Sequence + Suffix', 'webappick-pdf-invoice-for-woocommerce' ); ?></option>
                                <option value="pre_order_number_suf" <?php selected( get_option( 'wpifw_invoice_number_type' ), 'pre_order_number_suf', true ); ?>><?php esc_html_e( 'Prefix + Order Number + Suffix', 'webappick-pdf-invoice-for-woocommerce' ); ?></option>
                                <option value="order_number" <?php selected( get_option( 'wpifw_invoice_number_type' ), 'order_number', true ); ?>><?php esc_html_e( 'Order Number', 'webappick-pdf-invoice-for-woocommerce' ); ?></option>
                            </select>
                        </div>

						<?php
						if ( get_option( 'wpifw_invoice_number_type' ) == 'pre_order_number_suf' || get_option( 'wpifw_invoice_number_type' ) == 'order_number' ) {

						?>
                        <div class="_winvoice-form-group _winvoice-next-invoice _winvoice-hidden"
                             style="width:100%;"
                             tooltip="If you change sequential no invoice order no will be change"
                             flow="right">
							<?php
							} else {
							?>
                            <div class="_winvoice-form-group _winvoice-next-invoice"
                                 style="width:100%;"
                                 tooltip="If you change sequential no invoice order no will be changed"
                                 flow="right">
								<?php
								}
								?>

                                <label class="_winvoice-fixed-label"
                                       for="invno"><?php esc_html_e( 'Next Invoice No.', 'webappick-pdf-invoice-for-woocommerce' ); ?></label>
                                <input type="number"
                                       class="_winvoice-form-control _winvoice-number-input _winvoice-fixed-input"
                                       id="invno" name="wpifw_invoice_no"
                                       value="<?php echo esc_attr( get_option( 'wpifw_invoice_no' ) ); ?>">
                            </div>
							<?php
							if ( get_option( 'wpifw_invoice_number_type' ) == 'order_number' ) {
							?>
                            <div class="_winvoice-form-group _winvoice-invoice-prefix _winvoice-hidden"
                                 style="width: 100%;"
                                 tooltip="Avilable macros: {{year}}, {{month}} and {{day}}"
                                 flow="right">
								<?php
								} else {
								?>
                                <div class="_winvoice-form-group _winvoice-invoice-prefix"
                                     style="width:100%;"
                                     tooltip="Avilable macros: {{year}}, {{month}} and {{day}}"
                                     flow="right">
									<?php
									}
									?>
                                    <label class="_winvoice-fixed-label"
                                           for="invprefix"><?php esc_html_e( 'Invoice No. Prefix', 'webappick-pdf-invoice-for-woocommerce' ); ?></label>
                                    <input type="text"
                                           class="_winvoice-form-control _winvoice-fixed-input"
                                           id="invprefix"
                                           name="wpifw_invoice_no_prefix"
                                           value="<?php echo esc_attr( get_option( 'wpifw_invoice_no_prefix' ) ); ?>">
                                </div>

								<?php
								if ( get_option( 'wpifw_invoice_number_type' ) == 'order_number' ) {
								?>
                                <div class="_winvoice-form-group _winvoice-invoice-suffix _winvoice-hidden"
                                     style="width:100%;"
                                     tooltip="Avilable macros: {{year}}, {{month}} and {{day}}"
                                     flow="right">
									<?php
									} else {
									?>
                                    <div class="_winvoice-form-group _winvoice-invoice-suffix"
                                         style="width:100%;"
                                         tooltip="Avilable macros: {{year}}, {{month}} and {{day}}"
                                         flow="right">
										<?php
										}
										?>


                                        <label class="_winvoice-fixed-label"
                                               for="insuff"><?php esc_html_e( 'Invoice No. Suffix', 'webappick-pdf-invoice-for-woocommerce' ); ?></label>
                                        <input type="text"
                                               class="_winvoice-form-control _winvoice-fixed-input"
                                               id="insuff"
                                               name="wpifw_invoice_no_suffix"
                                               value="<?php echo esc_attr( get_option( 'wpifw_invoice_no_suffix' ) ); ?>">
                                    </div>

                                    <div class="_winvoice-header-title">
                                        <h4><?php esc_html_e( 'Date & Currency', 'webappick-pdf-invoice-for-woocommerce' ); ?></h4>
                                    </div>
                                    <div class="_winvoice-form-group"
                                         tooltip="" flow="right">
                                        <label class="_winvoice-fixed-label"
                                               for="date"> <?php esc_html_e( 'Date Format', 'webappick-pdf-invoice-for-woocommerce' ); ?></label>
                                        <select id="date"
                                                name="wpifw_date_format"
                                                class="_winvoice-select-control _winvoice-fixed-input">
                                            <option value="d M, o" <?php selected( get_option( 'wpifw_date_format' ), 'd M, o', true ); ?> >
                                                Date Month, Year
                                            </option>
                                            <option value="m/d/y" <?php selected( get_option( 'wpifw_date_format' ), 'm/d/y', true ); ?> >
                                                mm/dd/yy
                                            </option>
                                            <option value="d/m/y" <?php selected( get_option( 'wpifw_date_format' ), 'd/m/y', true ); ?> >
                                                dd/mm/yy
                                            </option>
                                            <option value="y/m/d" <?php selected( get_option( 'wpifw_date_format' ), 'y/m/d', true ); ?> >
                                                yy/mm/dd
                                            </option>
                                            <option value="d/m/Y" <?php selected( get_option( 'wpifw_date_format' ), 'd/m/Y', true ); ?>>
                                                dd/mm/yyyy
                                            </option>
                                            <option value="Y/m/d" <?php selected( get_option( 'wpifw_date_format' ), 'Y/m/d', true ); ?>>
                                                yyyy/mm/dd
                                            </option>
                                            <option value="m/d/Y" <?php selected( get_option( 'wpifw_date_format' ), 'm/d/Y', true ); ?>>
                                                mm/dd/yyyy
                                            </option>
                                            <option value="y-m-d" <?php selected( get_option( 'wpifw_date_format' ), 'y-m-d', true ); ?>>
                                                yy-mm-dd
                                            </option>
                                            <option value="Y-m-d" <?php selected( get_option( 'wpifw_date_format' ), 'Y-m-d', true ); ?>>
                                                yyyy-mm-dd
                                            </option>
                                        </select>
                                    </div>

                                    <div class="_winvoice-form-group">
                                        <div class="_winvoice-custom-control _winvoice-custom-switch">
                                            <div class="_winvoice-toggle-label">
                                                <span class="_winvoice-checkbox-label"><?php esc_html_e( 'Display Currency Code', 'webappick-pdf-invoice-for-woocommerce' ); ?></span>
                                            </div>
                                            <div class="_winvoice-toggle-container" tooltip=""
                                                 flow="right">
                                                <input type="hidden" name="wpifw_currency_code"
                                                       value="0">
                                                <input type="checkbox"
                                                       class="_winvoice-custom-control-input"
                                                       id="discurrency"
                                                       name="wpifw_currency_code"
                                                       value="1" <?php checked( get_option( 'wpifw_currency_code' ), $current, true ); ?>
                                                >
                                                <label class="_winvoice-custom-control-label"
                                                       title="Display Currency Code into Invoice Total"
                                                       for="discurrency"></label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="_winvoice-form-group">
                                        <div class="_winvoice-custom-control _winvoice-custom-switch">
                                            <div class="_winvoice-toggle-label">
                                                <span class="_winvoice-checkbox-label"><?php esc_html_e( 'Display Payment Method', 'webappick-pdf-invoice-for-woocommerce' ); ?></span>
                                            </div>
                                            <div class="_winvoice-toggle-container" tooltip=""
                                                 flow="right">
                                                <input type="hidden"
                                                       name="wpifw_payment_method_show"
                                                       value="0">
                                                <input type="checkbox"
                                                       class="_winvoice-custom-control-input"
                                                       id="disPayment"
                                                       name="wpifw_payment_method_show"
                                                       value="1" <?php checked( get_option( 'wpifw_payment_method_show' ), $current, true ); ?>
                                                >
                                                <label class="_winvoice-custom-control-label"
                                                       title="Display Payment Method into Invoice"
                                                       for="disPayment"></label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="_winvoice-header-title">
                                        <h4><?php esc_html_e( 'Product Info', 'webappick-pdf-invoice-for-woocommerce' ); ?></h4>
                                    </div>

                                    <div class="_winvoice-form-group">
                                        <label class="_winvoice-fixed-label"
                                               for="wpifw_invoice_product_per_page"> <?php esc_html_e( 'Product Per Page', 'webappick-pdf-invoice-for-woocommerce' ); ?></label>
                                        <input type="number"
                                               class="_winvoice-form-control _winvoice-number-input _winvoice-fixed-input"
                                               id="wpifw_invoice_product_per_page"
                                               name="wpifw_invoice_product_per_page"
                                               value='<?php echo esc_attr( ! empty( get_option( 'wpifw_invoice_product_per_page' ) ) ? get_option( 'wpifw_invoice_product_per_page' ) : 6 ); ?>'>
                                    </div>

                                    <div class="_winvoice-form-group"
                                         tooltip="" flow="right">
                                        <label class="_winvoice-fixed-label"
                                               for="disid"> <?php esc_html_e( 'Display ID/SKU', 'webappick-pdf-invoice-for-woocommerce' ); ?></label>
                                        <select id="disid"
                                                name="wpifw_disid"
                                                class="_winvoice-select-control _winvoice-fixed-input">
                                            <option value="None" <?php selected( get_option( 'wpifw_disid' ), 'None', true ); ?>><?php esc_html_e( 'None', 'webappick-pdf-invoice-for-woocommerce' ); ?></option>
                                            <option value="SKU" <?php selected( get_option( 'wpifw_disid' ), 'SKU', true ); ?>><?php esc_html_e( 'SKU', 'webappick-pdf-invoice-for-woocommerce' ); ?></option>
                                            <option value="ID" <?php selected( get_option( 'wpifw_disid' ), 'ID', true ); ?>><?php esc_html_e( 'ID', 'webappick-pdf-invoice-for-woocommerce' ); ?></option>
                                        </select>
                                    </div>
                                    <div class="_winvoice-form-group"
                                         tooltip="<?php esc_html_e( 'Keep Empty for no limit.', 'webappick-pdf-invoice-for-woocommerce' ); ?>"
                                         flow="right">
                                        <label class="_winvoice-fixed-label"
                                               for="wpifw_invoice_product_title_length"> <?php esc_html_e( 'Product Title Length', 'webappick-pdf-invoice-for-woocommerce' ); ?></label>
                                        <input type="number"
                                               class="_winvoice-form-control _winvoice-number-input _winvoice-fixed-input"
                                               id="wpifw_invoice_product_title_length"
                                               name="wpifw_invoice_product_title_length"
                                               value='<?php echo esc_attr( ! empty( get_option( 'wpifw_invoice_product_title_length' ) ) ? get_option( 'wpifw_invoice_product_title_length' ) : '' ); ?>'>
                                    </div>

                                    <div class="_winvoice-form-group">
										<?php
									    // $wpifw_display_product_img = ( '' != get_option( 'wpifw_product_image_show' ) ) ? get_option( 'wpifw_product_image_show' ) : '1';
										// Set a default value for wpifw_product_image_show if it's not set
										if (get_option('wpifw_product_image_show') === false) {
											update_option('wpifw_product_image_show', '1');
										}

										// Now retrieve the value
										$wpifw_display_product_img = get_option('wpifw_product_image_show', '1');
										?>
                                        <div class="_winvoice-custom-control _winvoice-custom-switch">
                                            <div class="_winvoice-toggle-label">
                                                <span class="_winvoice-checkbox-label"><?php esc_html_e( 'Display Product Image', 'webappick-pdf-invoice-for-woocommerce' ); ?></span>
                                            </div>
                                            <div class="_winvoice-toggle-container" tooltip=""
                                                 flow="right">
                                                <input type="hidden"
                                                       name="wpifw_product_image_show"
                                                       value="0">

                                                <input type="checkbox"
                                                       class="_winvoice-custom-control-input"
                                                       id="wpifw_product_image_show"
                                                       name="wpifw_product_image_show"
                                                       value="1" <?php checked( $wpifw_display_product_img, $current, true ); ?>
                                                >
                                                <label class="_winvoice-custom-control-label"
                                                       title="Display Product image into Invoice"
                                                       for="wpifw_product_image_show"></label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="_winvoice-form-group">
                                        <div class="_winvoice-custom-control _winvoice-custom-switch">
                                            <div class="_winvoice-toggle-label">
                                                <span class="_winvoice-checkbox-label"><?php esc_html_e( 'Display Product Category', 'webappick-pdf-invoice-for-woocommerce' ); ?></span>
                                            </div>
                                            <div class="_winvoice-toggle-container" tooltip=""
                                                 flow="right">
                                                <input type="hidden"
                                                       name="wpifw_product_category_show"
                                                       value="0">
                                                <input type="checkbox"
                                                       class="_winvoice-custom-control-input"
                                                       id="wpifw_product_category_show"
                                                       name="wpifw_product_category_show"
                                                       value="1" <?php checked( get_option( 'wpifw_product_category_show' ), $current, true ); ?>
                                                >
                                                <label class="_winvoice-custom-control-label"
                                                       title="Display Product Category into Invoice"
                                                       for="wpifw_product_category_show"></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="_winvoice-form-group"
                                         tooltip="" flow="right">
                                        <label class="_winvoice-fixed-label"
                                               for="wpifw_product_description_show"> <?php esc_html_e( 'Display Description', 'webappick-pdf-invoice-for-woocommerce' ); ?></label>
                                        <select
                                                id="wpifw_product_description_show"
                                                name="wpifw_product_description_show"
                                                class="_winvoice-select-control _winvoice-fixed-input">
                                            <option value="none" <?php selected( get_option( 'wpifw_product_description_show' ), 'none', true ); ?>><?php esc_html_e( 'None', 'webappick-pdf-invoice-for-woocommerce' ); ?></option>
                                            <option value="short" <?php selected( get_option( 'wpifw_product_description_show' ), 'short', true ); ?>><?php esc_html_e( 'Short Description', 'webappick-pdf-invoice-for-woocommerce' ); ?></option>
                                            <option value="long" <?php selected( get_option( 'wpifw_product_description_show' ), 'long', true ); ?>><?php esc_html_e( 'Long Description', 'webappick-pdf-invoice-for-woocommerce' ); ?></option>
                                        </select>
                                    </div>

                                    <div class="_winvoice-form-group" id="hideDescriptionLimit">
                                        <label class="_winvoice-fixed-label"
                                               for="wpifw_invoice_description_limit"> <?php esc_html_e( 'Description Limit', 'webappick-pdf-invoice-for-woocommerce' ); ?></label>
                                        <input type="number"
                                               class="_winvoice-form-control _winvoice-number-input _winvoice-fixed-input"
                                               id="wpifw_invoice_description_limit"
                                               name="wpifw_invoice_description_limit"
                                               value='<?php echo esc_attr( ! empty( get_option( 'wpifw_invoice_description_limit' ) ) ? get_option( 'wpifw_invoice_description_limit' ) : '' ); ?>'>
                                    </div>

                                    <div class="_winvoice-form-group" style="display: none;">
                                        <div class="_winvoice-custom-control _winvoice-custom-switch">
                                            <div class="_winvoice-toggle-label">
                                                <span class="_winvoice-checkbox-label"><?php esc_html_e( 'Display Sale Price', 'webappick-pdf-invoice-for-woocommerce' ); ?></span>
                                            </div>
                                            <div class="_winvoice-toggle-container" tooltip=""
                                                 flow="right">
                                                <input type="hidden"
                                                       name="wpifw_show_discounted_price"
                                                       value="0">
                                                <input type="checkbox"
                                                       class="_winvoice-custom-control-input"
                                                       id="wpifw_show_discounted_price"
                                                       name="wpifw_show_discounted_price"
                                                       value="1" <?php checked( get_option( 'wpifw_show_discounted_price' ), $current, true ); ?>
                                                >
                                                <label class="_winvoice-custom-control-label"
                                                       for="wpifw_show_discounted_price"></label>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="_winvoice-form-group"
                                         tooltip="" flow="right">
                                        <label class="_winvoice-fixed-label"
                                               for="wpifw_product_attribute_show"> <?php esc_html_e( 'Display Attributes', 'webappick-pdf-invoice-for-woocommerce' ); ?></label>
                                        <select
                                                id="wpifw_product_attribute_show"
                                                name="wpifw_product_attribute_show[]"
                                                class="wpifw_attr wpifw_attributes generalInput _winvoice-fixed-input"
                                                multiple>
											<?php
											if ( class_exists( 'WooCommerce' ) ) {
												global $woocommerce;
												if ( function_exists( 'wc_get_attribute_taxonomies' ) ) {
													$attribute_taxonomies = wc_get_attribute_taxonomies();
												} else {
													$attribute_taxonomies = $woocommerce->get_attribute_taxonomies();
												}

												if ( count( $attribute_taxonomies ) ) {
													foreach ( $attribute_taxonomies as $attribute_taxonomy ) {
														echo '<option value=' . $attribute_taxonomy->attribute_name . '>' . $attribute_taxonomy->attribute_label . '</option>'; //phpcs:ignore
													}
												}
											}
											?>
                                        </select>

                                    </div>

                                    <div class="_winvoice-form-group"
                                         tooltip="" flow="right">
                                        <label class="_winvoice-fixed-label"
                                               for="wpifw_product_table_header"> <?php esc_html_e( 'Select Product Column', 'webappick-pdf-invoice-for-woocommerce' ); ?></label>
                                        <select
                                                id="wpifw_product_table_header"
                                                name="wpifw_product_table_header[]"
                                                class="wpifw_header wpifw_attributes generalInput _winvoice-fixed-input"
                                                multiple>
											<?php
											if ( class_exists( 'WooCommerce' ) ) {

												$options = [
													'price'                 => 'Cost',
													'quantity'              => 'Qty',
													'tax'                   => 'Tax',
													'tax_inc_discounted'    => 'Tax Inc. Discount',
													'tax_ex_discounted'     => 'Tax Ex. Discount',
													'total'                 => 'Total',
													'total_inc_discounted'  => 'Total Inc. Discount',
													'total_ex_discounted'   => 'Total Ex. Discount',
													'tax_rate'              => 'Tax %',
													'regular_price'         => 'Regular Price',
													'sale_price'            => 'Sale Price',
													'regular_price_with_tax'=> 'Regular Price With Tax',
													'sale_price_with_tax'   => 'Sale Price With Tax',
													'price_with_tax'        => 'Price With Tax',
													'discount'              => 'Discount',
													'total_inc_tax'         => 'Total Inc. Tax',
													'total_ex_tax'          => 'Total Ex. Tax',
												];

												foreach ( $options as $key => $value ) {
													?>
                                                    <option value="<?php echo $key ?>"><?php echo $value ?></option>
													<?php
												}
											} ?>
                                        </select>
                                    </div>


                                    <div class="_winvoice-form-group">
                                        <div class="_winvoice-custom-control _winvoice-custom-switch">
                                            <div class="_winvoice-toggle-label">
                                                <span class="_winvoice-checkbox-label"><?php esc_html_e( 'Display Product Dimension', 'webappick-pdf-invoice-for-woocommerce' ); ?></span>
                                            </div>
                                            <div class="_winvoice-toggle-container" tooltip="" flow="right">
                                                <input type="hidden" name="wpifw_invoice_product_dimension_show"
                                                       value="0">
                                                <input type="checkbox" class="_winvoice-custom-control-input"
                                                       id="wpifw_invoice_product_dimension_show"
                                                       name="wpifw_invoice_product_dimension_show"
                                                       value="1" <?php checked( get_option( 'wpifw_invoice_product_dimension_show' ), $current, true ); ?>
                                                >
                                                <label class="_winvoice-custom-control-label"
                                                       title="Display Product Dimension into packing slip"
                                                       for="wpifw_invoice_product_dimension_show"></label>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- ===========================
										Add Product meta for invoice.
									 =========================== -->
                                    <div class="_winvoice-form-group" tooltip="" flow="right">

                                        <label class="_winvoice-fixed-label"
                                               for="wpifw_product_post_meta_show"> <?php esc_html_e( 'Add Product Meta', 'webappick-pdf-invoice-for-woocommerce' ); ?></label>
                                        <a href="#" class="_winvoice-add-meta">
                                            <div class="_winvoice-add-meta-btn">
                                                <span class="dashicons dashicons-plus-alt"></span>
												<?php esc_html_e( 'Add Product Meta', 'webappick-pdf-invoice-for-woocommerce' ); ?>
                                            </div>
                                        </a>
                                        <div class="_winvoice_meta"
                                             style="float:none; padding-top: 10px;">
											<?php
											$custom_post_meta = get_option( 'wpifw_custom_post_meta' );

											if ( false == $custom_post_meta ) {
												$custom_post_meta = array();
											}
											if ( count( $custom_post_meta ) > 0 ) {
												foreach ( $custom_post_meta as $key => $value ) {
													?>
                                                    <div class="_winvoice_meta_html">
                                                        <input type="text"
                                                               placeholder="Meta Label"
                                                               class="_winvoice-form-control _winvoice-product-post-meta-label"
                                                               name="<?php echo esc_html( $key ); ?>_winvoice_post_meta_label"
                                                               value="<?php echo esc_html( $value ); ?>">
                                                        <select
                                                                id="wpifw_product_post_meta_show"
                                                                class="_winvoice-select-control _winvoice-product-post-meta"
                                                                name="<?php echo esc_html( $key ); ?>_winvoice_post_meta_name">
                                                            <option value="" <?php selected( get_option( 'wpifw_product_post_meta_show' ), '', true ); ?>><?php esc_html_e( 'Select Post Meta', 'webappick-pdf-invoice-for-woocommerce' ); ?></option>
															<?php
															if ( ! empty( $woo_invoice_product_meta_query ) ) {
																foreach ( $woo_invoice_product_meta_query as $meta ) {
																	$selected = $key == $meta->meta_key ? 'selected' : '';
																	echo '<option value=' . $meta->meta_key . ' ' . $selected . '>' . $meta->meta_key . '</option>'; //phpcs:ignore
																}
															}
															?>
                                                        </select>
                                                        <a href="#"
                                                           class="_winvoice-delete-meta"><span
                                                                    class="dashicons dashicons-trash"
                                                                    style="color:#D94D40"></span></a>
                                                    </div>
													<?php
												}
											} else {
												?>
                                                <div style="display: none">
                                                    <div class="_winvoice_meta_html">
                                                        <input type="text"
                                                               placeholder="Meta Label"
                                                               class="_winvoice-form-control _winvoice-product-post-meta-label">
                                                        <select
                                                                id="wpifw_product_post_meta_show"
                                                                class="_winvoice-select-control _winvoice-product-post-meta">
                                                            <option disabled
                                                                    value="" <?php selected( get_option( 'wpifw_product_post_meta_show' ), '', true ); ?>><?php esc_html_e( 'Select Post Meta', 'webappick-pdf-invoice-for-woocommerce' ); ?></option>
															<?php
															if ( count( $woo_invoice_product_meta_query ) ) {
																foreach ( $woo_invoice_product_meta_query as $meta ) {
																	echo '<option value=' . esc_attr( $meta->meta_key ) . ' ' . selected( get_option( 'wpifw_product_post_meta_show' ), $meta->meta_key, true ) . '>' . esc_attr( $meta->meta_key ) . '</option>';
																}
															}
															?>
                                                        </select>
                                                        <a href="#"
                                                           class="_winvoice-delete-meta"><span
                                                                    class="dashicons dashicons-trash"
                                                                    style="color:#D94D40"></span></a>
                                                    </div>
                                                </div>
											<?php } ?>
                                        </div>
                                    </div>
                                    <!-- ==================================
										End Add Product meta for invoice.
									 ================================== -->

                                    <!-- ===============================
										Add order item meta for invoice.
									 =============================== -->

                                    <div class="_winvoice-form-group" tooltip="" flow="right">
                                        <label class="_winvoice-fixed-label"
                                               for="wpifw_order_item_meta_show"> <?php esc_html_e( 'Add Order Item Meta', 'webappick-pdf-invoice-for-woocommerce' ); ?></label>
                                        <a href="#" class="_winvoice-add-order-item-meta">
                                            <div class="_winvoice-add-order-item-meta-btn">
                                                <span class="dashicons dashicons-plus-alt"></span>
												<?php esc_html_e( 'Add Order Item Meta', 'webappick-pdf-invoice-for-woocommerce' ); ?>
                                            </div>
                                        </a>
                                        <div class="_winvoice_order_item_meta"
                                             style="float:none; padding-top: 10px;">
											<?php
											$custom_order_item_meta = get_option( 'wpifw_order_item_meta' );
											if ( false == $custom_order_item_meta ) {
												$custom_order_item_meta = array();
											}
											if ( count( $custom_order_item_meta ) > 0 ) {
												foreach ( $custom_order_item_meta as $key => $value ) {
													?>
                                                    <div class="_winvoice_order_item_meta_html">
                                                        <input type="text"
                                                               placeholder="Item Meta Label"
                                                               class="_winvoice-form-control _winvoice-order-item-meta-label"
                                                               name="<?php echo esc_html( $key ); ?>_winvoice_order_item_meta_label"
                                                               value="<?php echo esc_html( $value ); ?>">
                                                        <select
                                                                id="wpifw_order_item_meta_show"
                                                                class="_winvoice-select-control _winvoice-order-item-meta"
                                                                name="<?php echo esc_html( $key ); ?>_winvoice_order_item_meta_name">
                                                            <option value="" <?php selected( get_option( 'wpifw_order_item_meta_show' ), '', true ); ?>><?php esc_html_e( 'Select Order Item Meta', 'webappick-pdf-invoice-for-woocommerce' ); ?></option>
															<?php

															if ( ! empty( $order_item_meta_query ) ) {
																foreach ( $order_item_meta_query as $meta ) {
																	$selected = $key == $meta->meta_key ? 'selected' : '';
																	echo '<option value=' . $meta->meta_key . ' ' . $selected . '>' . $meta->meta_key . '</option>'; //phpcs:ignore
																}
															}
															?>
                                                        </select>
                                                        <a href="#"
                                                           class="_winvoice-delete-order-item-meta"><span
                                                                    class="dashicons dashicons-trash"
                                                                    style="color:#D94D40"></span></a>
                                                    </div>
													<?php
												}
											} else {
												?>
                                                <div style="display: none">
                                                    <div class="_winvoice_order_item_meta_html">
                                                        <input type="text"
                                                               placeholder="Item Meta Label"
                                                               class="_winvoice-form-control _winvoice-order-item-meta-label">
                                                        <select
                                                                id="wpifw_order_item_meta_show"
                                                                class="_winvoice-select-control _winvoice-order-item-meta">
                                                            <option disabled
                                                                    value="" <?php selected( get_option( 'wpifw_order_item_meta_show' ), '', true ); ?>><?php esc_html_e( 'Select Order Item Meta', 'webappick-pdf-invoice-for-woocommerce' ); ?></option>
															<?php

															if ( count( $order_item_meta_query ) ) {
																foreach ( $order_item_meta_query as $meta ) {
																	echo '<option value=' . esc_attr( $meta->meta_key ) . ' ' . selected( get_option( 'wpifw_order_item_meta_show' ), $meta->meta_key, true ) . '>' . esc_attr( $meta->meta_key ) . '</option>';
																}
															}
															?>
                                                        </select>
                                                        <a href="#"
                                                           class="_winvoice-delete-order-item-meta"><span
                                                                    class="dashicons dashicons-trash"
                                                                    style="color:#D94D40"></span></a>
                                                    </div>
                                                </div>
											<?php } ?>
                                        </div>
                                    </div>
                                    <!-- =================================
										End order item meta for invoice.
									 =================================== -->

                                    <div class="_winvoice-header-title" style="display: none;">
                                        <h4><?php esc_html_e( 'Proforma Invoice', 'webappick-pdf-invoice-for-woocommerce' ); ?></h4>
                                    </div>

                                    <div class="_winvoice-form-group" style="display: none;">
                                        <div class="_winvoice-custom-control _winvoice-custom-switch">
                                            <div class="_winvoice-toggle-label">
                                                <span class="_winvoice-checkbox-label"><?php esc_html_e( 'Enable Proforma Invoicing', 'webappick-pdf-invoice-for-woocommerce' ); ?></span>
                                            </div>
                                            <div class="_winvoice-toggle-container" tooltip=""
                                                 flow="right">
                                                <input type="hidden"
                                                       name="wpifw_proforma_invoicing"
                                                       value="0">
                                                <input type="checkbox"
                                                       class="_winvoice-custom-control-input"
                                                       id="wpifw_proforma_invoicing"
                                                       name="wpifw_proforma_invoicing"
                                                       value="1" <?php checked( get_option( 'wpifw_proforma_invoicing' ), $current, true ); ?>
                                                >
                                                <label class="_winvoice-custom-control-label"
                                                       for="wpifw_proforma_invoicing"></label>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="_winvoice-header-title">
                                        <h4><?php esc_html_e( 'Tax & Fees', 'webappick-pdf-invoice-for-woocommerce' ); ?></h4>
                                    </div>
                                    <div class="_winvoice-form-group" style="display: none;">
                                        <div class="_winvoice-custom-control _winvoice-custom-switch">
                                            <div class="_winvoice-toggle-label">
                                                <span class="_winvoice-checkbox-label"><?php esc_html_e( 'Total Column Inc. TAX', 'webappick-pdf-invoice-for-woocommerce' ); ?></span>
                                            </div>
                                            <div class="_winvoice-toggle-container" tooltip=""
                                                 flow="right">
                                                <input type="hidden" name="wpifw_inc_tax_total"
                                                       value="0">
                                                <input type="checkbox"
                                                       class="_winvoice-custom-control-input"
                                                       id="wpifw_inc_tax_total"
                                                       name="wpifw_inc_tax_total"
                                                       value="1" <?php checked( get_option( 'wpifw_inc_tax_total' ), $current, true ); ?>
                                                >
                                                <label class="_winvoice-custom-control-label"
                                                       for="wpifw_inc_tax_total"></label>
                                            </div>
                                        </div>
                                    </div>

                                    <!--                                    <div class="_winvoice-form-group">-->
                                    <!--                                        <div class="_winvoice-custom-control _winvoice-custom-switch">-->
                                    <!--                                            <div class="_winvoice-toggle-label">-->
                                    <!--                                                <span class="_winvoice-checkbox-label">-->
									<?php //esc_html_e( 'Tax Column', 'webappick-pdf-invoice-for-woocommerce' ); ?><!--</span>-->
                                    <!--                                            </div>-->
                                    <!--                                            <div class="_winvoice-toggle-container" tooltip=""-->
                                    <!--                                                 flow="right">-->
                                    <!--                                                <input type="hidden" name="wpifw_show_tax"-->
                                    <!--                                                       value="0">-->
                                    <!--                                                <input type="checkbox"-->
                                    <!--                                                       class="_winvoice-custom-control-input"-->
                                    <!--                                                       id="wpifw_show_tax" name="wpifw_show_tax"-->
                                    <!--                                                       value="1" --><?php //checked( get_option( 'wpifw_show_tax' ), $current, true ); ?>
                                    <!--                                                >-->
                                    <!--                                                <label class="_winvoice-custom-control-label"-->
                                    <!--                                                       for="wpifw_show_tax"></label>-->
                                    <!--                                            </div>-->
                                    <!---->
                                    <!--                                        </div>-->
                                    <!--                                    </div>-->
                                    <!---->
                                    <!--                                    <div class="_winvoice-form-group">-->
                                    <!--                                        <div class="_winvoice-custom-control _winvoice-custom-switch">-->
                                    <!--                                            <div class="_winvoice-toggle-label">-->
                                    <!--                                                <span class="_winvoice-checkbox-label">-->
									<?php //esc_html_e( 'Tax Percentage Column', 'webappick-pdf-invoice-for-woocommerce' ); ?><!--</span>-->
                                    <!--                                            </div>-->
                                    <!--                                            <div class="_winvoice-toggle-container" tooltip=""-->
                                    <!--                                                 flow="right">-->
                                    <!--                                                <input type="hidden" name="wpifw_tax_percentage"-->
                                    <!--                                                       value="0">-->
                                    <!--                                                <input type="checkbox"-->
                                    <!--                                                       class="_winvoice-custom-control-input"-->
                                    <!--                                                       id="wpifw_tax_percentage"-->
                                    <!--                                                       name="wpifw_tax_percentage"-->
                                    <!--                                                       value="1" --><?php //checked( get_option( 'wpifw_tax_percentage' ), $current, true ); ?>
                                    <!--                                                >-->
                                    <!--                                                <label class="_winvoice-custom-control-label"-->
                                    <!--                                                       for="wpifw_tax_percentage"></label>-->
                                    <!--                                            </div>-->
                                    <!---->
                                    <!--                                        </div>-->
                                    <!--                                    </div>-->

                                    <div class="_winvoice-form-group">
                                        <div class="_winvoice-custom-control _winvoice-custom-switch">
                                            <div class="_winvoice-toggle-label">
                                                <span class="_winvoice-checkbox-label"><?php esc_html_e( 'Fees', 'webappick-pdf-invoice-for-woocommerce' ); ?></span>
                                            </div>
                                            <div class="_winvoice-toggle-container" tooltip=""
                                                 flow="right">
                                                <input type="hidden" name="wpifw_total_fees"
                                                       value="0">
                                                <input type="checkbox"
                                                       class="_winvoice-custom-control-input"
                                                       id="wpifw_total_fees"
                                                       name="wpifw_total_fees"
                                                       value="1" <?php checked( get_option( 'wpifw_total_fees' ), $current, true ); ?>
                                                >
                                                <label class="_winvoice-custom-control-label"
                                                       for="wpifw_total_fees"></label>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="_winvoice-form-group">
                                        <div class="_winvoice-custom-control _winvoice-custom-switch">
                                            <div class="_winvoice-toggle-label">
                                                <span class="_winvoice-checkbox-label"><?php esc_html_e( 'Display Total Without Tax', 'webappick-pdf-invoice-for-woocommerce' ); ?></span>
                                            </div>
                                            <div class="_winvoice-toggle-container"
                                                 tooltip="<?php esc_html_e( 'Display total amount without tax into invoice summery section.', 'webappick-pdf-invoice-for-woocommerce' ); ?>"
                                                 flow="right">
                                                <input type="hidden"
                                                       name="wpifw_display_total_without_tax"
                                                       value="0">
                                                <input type="checkbox"
                                                       class="_winvoice-custom-control-input"
                                                       id="wpifw_display_total_without_tax"
                                                       name="wpifw_display_total_without_tax"
                                                       value="1" <?php checked( get_option( 'wpifw_display_total_without_tax' ), $current, true ); ?>
                                                >
                                                <label class="_winvoice-custom-control-label"
                                                       for="wpifw_display_total_without_tax"></label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="_winvoice-header-title">
                                        <h4><?php esc_html_e( 'VAT ID & SSN', 'webappick-pdf-invoice-for-woocommerce' ); ?></h4>
                                    </div>
                                    <div class="_winvoice-form-group">
                                        <div class="_winvoice-custom-control _winvoice-custom-switch">
                                            <div class="_winvoice-toggle-label">
                                                <span class="_winvoice-checkbox-label"><?php esc_html_e( 'Enable VAT ID', 'webappick-pdf-invoice-for-woocommerce' ); ?></span>
                                            </div>
                                            <div class="_winvoice-toggle-container"
                                                 tooltip="<?php esc_html_e( 'Enable this option to add the VAT ID input field into checkout page.', 'webappick-pdf-invoice-for-woocommerce' ); ?>"
                                                 flow="right">
                                                <input type="hidden" name="wpifw_display_vat_id"
                                                       value="0">
                                                <input type="checkbox"
                                                       class="_winvoice-custom-control-input"
                                                       id="wpifw_display_vat_id"
                                                       name="wpifw_display_vat_id"
                                                       value="1" <?php checked( get_option( 'wpifw_display_vat_id' ), $current, true ); ?>
                                                >
                                                <label class="_winvoice-custom-control-label"
                                                       title="Enable to show the VAT ID input field in order checkout page"
                                                       for="wpifw_display_vat_id"></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="_winvoice-form-group">
                                        <div class="_winvoice-custom-control _winvoice-custom-switch">
                                            <div class="_winvoice-toggle-label">
                                                <span class="_winvoice-checkbox-label"><?php esc_html_e( 'Enable SSN', 'webappick-pdf-invoice-for-woocommerce' ); ?></span>
                                            </div>
                                            <div class="_winvoice-toggle-container"
                                                 tooltip="<?php esc_html_e( 'Enable this option to add the SSN input field into checkout page', 'webappick-pdf-invoice-for-woocommerce' ); ?>"
                                                 flow="right">
                                                <input type="hidden" name="wpifw_display_ssn"
                                                       value="0">
                                                <input type="checkbox"
                                                       class="_winvoice-custom-control-input"
                                                       id="wpifw_display_ssn"
                                                       name="wpifw_display_ssn"
                                                       value="1" <?php checked( get_option( 'wpifw_display_ssn' ), $current, true ); ?>
                                                >
                                                <label class="_winvoice-custom-control-label"
                                                       title="Enable to show the SSN input field in order checkout page"
                                                       for="wpifw_display_ssn"></label>
                                            </div>
                                        </div>

                                        <div class="_winvoice-form-group"
                                             style="display: none;">
                                            <div class="_winvoice-custom-control _winvoice-custom-switch">
                                                <div class="_winvoice-toggle-label">
                                                    <span class="_winvoice-checkbox-label"><?php esc_html_e( 'Show Total Without Discount', 'webappick-pdf-invoice-for-woocommerce' ); ?></span>
                                                </div>
                                                <div class="_winvoice-toggle-container"
                                                     tooltip="" flow="right">
                                                    <input type="hidden"
                                                           name="wpifw_display_total_without_discount"
                                                           value="0">
                                                    <input type="checkbox"
                                                           class="_winvoice-custom-control-input"
                                                           id="wpifw_display_total_without_discount"
                                                           name="wpifw_display_total_without_discount"
                                                           value="1" <?php checked( get_option( 'wpifw_display_total_without_discount' ), $current, true ); ?>
                                                    >
                                                    <label class="_winvoice-custom-control-label"
                                                           for="wpifw_display_total_without_discount"></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="_winvoice-header-title">
                                            <h4><?php esc_html_e( 'Bank Accounts', 'webappick-pdf-invoice-for-woocommerce' ); ?></h4>
                                        </div>
                                        <div class="_winvoice-form-group">
                                            <div class="_winvoice-custom-control _winvoice-custom-switch">
                                                <div class="_winvoice-toggle-label">
                                                    <span class="_winvoice-checkbox-label"><?php esc_html_e( 'Show Bank Accounts', 'webappick-pdf-invoice-for-woocommerce' ); ?></span>
                                                </div>
                                                <div class="_winvoice-toggle-container"
                                                     tooltip="Show Bank Accounts Info into Invoice"
                                                     flow="right">
                                                    <input type="hidden"
                                                           name="wpifw_display_bank_account"
                                                           value="0">
                                                    <input type="checkbox"
                                                           class="_winvoice-custom-control-input"
                                                           id="wpifw_display_bank_account"
                                                           name="wpifw_display_bank_account"
                                                           value="1" <?php checked( get_option( 'wpifw_display_bank_account' ), $current, true ); ?>
                                                    >
                                                    <label class="_winvoice-custom-control-label"
                                                           for="wpifw_display_bank_account"></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="_winvoice-card-footer _winvoice-save-changes-selector">

                                        <input type="submit" style="float:right;"
                                               name="wpifw_submit_invoice_section"
                                               value="<?php esc_html_e( 'Save Changes', 'webappick-pdf-invoice-for-woocommerce' ); ?>"
                                               class="_winvoice-btn _winvoice-btn-primary"/>
                                    </div>

                    </form>

                </div>
            </div>
        </div>

        <!-- Invoice Sidebar -->
        <div class="_winvoice-col-sm-4 _winvoice-col-12">
            <!-- Invoice preview template Start -->
            <div class="_winvoice-card">
                <div class="_winvoice-card-header">
                    <h4><?php esc_html_e( 'SELECTED TEMPLATE', 'webappick-pdf-invoice-for-woocommerce' ); ?></h4>
                </div>
                <div class="_winvoice-card-body">
					<?php $wpifw_templateid = ( '' != get_option( 'wpifw_templateid' ) ) ? get_option( 'wpifw_templateid' ) : 'invoice-1'; ?>
                    <img class="_winvoice-template-preview"
                         src="<?php echo plugin_dir_url( __FILE__ ) . '../../images/templates/' . esc_html( $wpifw_templateid ); ?>.png"
                         alt="preview">
					<?php
					global $wpdb;
					$results       = $wpdb->get_col( $wpdb->prepare( "SELECT MAX(ID) FROM {$wpdb->prefix}posts WHERE post_type LIKE %s", 'shop_order' ) );
					$last_order_id = apply_filters( 'wpifw_last_order_id', $results[0] );
					if ( ! empty( $last_order_id ) && '' != $last_order_id && class_exists( 'WooCommerce' ) ) {
						$preview_url = wp_nonce_url( admin_url( 'admin-ajax.php?action=wpifw_generate_invoice&order_id=' . $last_order_id ), 'wpifw_pdf_nonce' );
						?>
                        <a target="_blank" href="<?php echo esc_url( $preview_url ); ?>"
                           class="invoice_template_preiview_btn"><?php echo esc_html_e( 'PREVIEW', 'webappick-pdf-invoice-for-woocommerce' ); ?></a>

						<?php
					} else {
						?>
                        <a target="_blank"
                           href="<?php echo WP_PLUGIN_URL . '/webappick-pdf-invoice-for-woocommerce-pro/admin/images/templates/' . esc_html( $wpifw_templateid ); ?>.png"
                           class="invoice_template_preiview_btn"><?php echo esc_html_e( 'PREVIEW', 'webappick-pdf-invoice-for-woocommerce' ); ?></a>
						<?php
					} ?>

                </div>
            </div>
            <!-- End invoice preview template -->
            <!--    Banner Section start    -->
           <?php include(plugin_dir_path(__FILE__) . 'lifetime-banner-widget.php'); ?>
            <!-- End Banner section -->
        </div>

    </div>

</li>
