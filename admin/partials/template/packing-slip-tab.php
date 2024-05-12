<?php

$order_item_meta_query          = woo_invoice_item_meta_query();
$woo_invoice_order_meta_query   = woo_invoice_order_meta_query();
$woo_invoice_product_meta_query = woo_invoice_product_meta_query();
?>
<li class="woo-invoice-packing-slip-li">
	<div class="_winvoice-row">
		<div class="_winvoice-col-sm-8 _winvoice-col-12">
			<div class="_winvoice-card _winvoice-mr-0">
				<div class="_winvoice-card-body">

					<form action="" method="post" enctype="multipart/form-data">
						<?php wp_nonce_field( 'settings_form_nonce' ); ?>

						<div class="_winvoice-header-title">
							<h4><?php esc_html_e( 'Packingslip Background', 'webappick-pdf-invoice-for-woocommerce' ); ?></h4>
						</div>

						<div class="_winvoice-form-group">
							<div class="_winvoice-custom-control _winvoice-custom-switch">
								<div class="_winvoice-toggle-label">
									<span class="_winvoice-checkbox-label"><?php esc_html_e( 'Enable Background Image', 'webappick-pdf-invoice-for-woocommerce' ); ?></span>
								</div>
								<div class="_winvoice-toggle-container">
									<input type="hidden" name="wpifw_enable_packingslip_background" value="0">
									<input type="checkbox" class="_winvoice-custom-control-input"
									       id="wpifw_enable_packingslip_background"
									       name="wpifw_enable_packingslip_background"
									       value="1" <?php checked( get_option( 'wpifw_enable_packingslip_background' ), $current, true ); ?>
									>
									<label class="_winvoice-custom-control-label"
									       for="wpifw_enable_packingslip_background"></label>
								</div>

							</div>
						</div>

						<div class="_winvoice-form-group">
							<label class="_winvoice-fixed-label"><?php esc_html_e( 'Upload Background', 'webappick-pdf-invoice-for-woocommerce' ); ?></label>

							<div style="display:inline-block;">
								<?php wp_enqueue_media(); ?>

								<input id="wpifw_upload_packingslip_background_button" type="button"
								       class="_winvoice-packingslip-background-btn _winvoice-btn _winvoice-btn-primary"
								       value="<?php echo esc_html( 'Upload Background Image' ); ?>"/>
								<input type='hidden' name='wpifw_packingslip_background_attachment_id'
								       id='wpifw_packingslip_background_attachment_id'
								       value='<?php echo esc_attr( get_option( 'wpifw_packingslip_background_attachment_image_id' ) ); ?>'>
							</div>

						</div>
						<div class="_winvoice-form-group" tooltip="" flow="right">
							<label class="_winvoice-fixed-label"
							       for="wpifw_packingslip_background_opacity"><?php esc_html_e( 'Background Image Opacity', 'webappick-pdf-invoice-for-woocommerce' ); ?></label>
							<input type="text"
							       class="_winvoice-form-control wpifw_packingslip_background_opacity _winvoice-fixed-input"
							       id="wpifw_packingslip_background_opacity"
							       name="wpifw_packingslip_background_opacity"
							       value="<?php echo esc_attr( get_option( 'wpifw_packingslip_background_opacity' ) ); ?>">
						</div>

						<div class="_winvoice-header-title">
							<h4><?php esc_html_e( 'Product Info', 'webappick-pdf-invoice-for-woocommerce' ); ?></h4>
						</div>

						<div class="_winvoice-form-group">
							<label class="_winvoice-fixed-label"
							       for="wpifw_packingslip_product_per_page"> <?php esc_html_e( 'Product Per Page', 'webappick-pdf-invoice-for-woocommerce' ); ?></label>
							<input type="number"
							       class="_winvoice-form-control _winvoice-number-input _winvoice-fixed-input"
							       id="wpifw_packingslip_product_per_page"
							       name="wpifw_packingslip_product_per_page"
							       value='<?php echo( ! empty( get_option( 'wpifw_packingslip_product_per_page' ) ) ? esc_html( get_option( 'wpifw_packingslip_product_per_page' ) ) : '6' ); ?>'>
						</div>

						<div class="_winvoice-form-group" tooltip="" flow="right">
							<label class="_winvoice-fixed-label"
							       for="packingslip_disid"> <?php esc_html_e( 'Display ID/SKU', 'webappick-pdf-invoice-for-woocommerce' ); ?></label>
							<select id="packingslip_disid"
							        name="wpifw_packingslip_disid"
							        class="_winvoice-select-control _winvoice-fixed-input">
								<option value="None" <?php selected( get_option( 'wpifw_packingslip_disid' ), 'None', true ); ?>><?php esc_html_e( 'None', 'webappick-pdf-invoice-for-woocommerce' ); ?></option>
								<option value="SKU" <?php selected( get_option( 'wpifw_packingslip_disid' ), 'SKU', true ); ?>><?php esc_html_e( 'SKU', 'webappick-pdf-invoice-for-woocommerce' ); ?></option>
								<option value="ID" <?php selected( get_option( 'wpifw_packingslip_disid' ), 'ID', true ); ?>><?php esc_html_e( 'ID', 'webappick-pdf-invoice-for-woocommerce' ); ?></option>
							</select>
						</div>

						<div class="_winvoice-form-group"
						     tooltip="<?php esc_html_e( 'Keep Empty for no limit.', 'webappick-pdf-invoice-for-woocommerce' ); ?>"
						     flow="right">
							<label class="_winvoice-fixed-label"
							       for="wpifw_packingslip_product_title_length"> <?php esc_html_e( 'Product Title Length', 'webappick-pdf-invoice-for-woocommerce' ); ?></label>
							<input type="number"
							       class="_winvoice-form-control _winvoice-number-input _winvoice-fixed-input"
							       id="wpifw_packingslip_product_title_length"
							       name="wpifw_packingslip_product_title_length"
							       value='<?php echo( ! empty( get_option( 'wpifw_packingslip_product_title_length' ) ) ? esc_attr( get_option( 'wpifw_packingslip_product_title_length' ) ) : '' ); ?>'>
						</div>

						<div class="_winvoice-form-group">
							<?php
							$pickingslip_paper_size = ( '' != get_option( 'wpifw-pickingslip-paper-size' )) ? get_option( 'wpifw-pickingslip-paper-size') : 'A4';
							?>
							<label
								class="wpifw-packingslip-paper-size-label _winvoice-fixed-label"
								for="wpifw-pickingslip-paper-size"> <?php esc_html_e( 'Paper Size', 'webappick-pdf-invoice-for-woocommerce' ); ?></label>
							<div class="_winvoice-custom-paper">
								<select id="wpifw-pickingslip-paper-size"
								        name="wpifw-pickingslip-paper-size"
								        class="_winvoice-select-control _winvoice-fixed-input">
									<option value="A3" <?php selected( $pickingslip_paper_size, 'A3', true ); ?>>
										A3
									</option>
									<option value="A4" <?php selected( $pickingslip_paper_size, 'A4', true ); ?>>
										A4
									</option>
									<option value="A5" <?php selected( $pickingslip_paper_size, 'A5', true ); ?>>
										A5
									</option>
									<option value="Letter" <?php selected( $pickingslip_paper_size, 'Letter', true ); ?>>
										Letter
									</option>
									<option value="custom" <?php selected( $pickingslip_paper_size, 'custom', true ); ?>>
										<?php esc_html_e( 'Custom Paper Size', 'webappick-pdf-invoice-for-woocommerce' ); ?>
									</option>
								</select>
								<div class="wpifw_packingslip_custom_paper_size"
								     style="overflow:hidden;display:none">
									<input style="float:left" type="text"
									       class="_winvoice-form-control"
									       id="_winvoice_packingslip_custom_paper_wide"
									       name="wpifw_pickingslip_custom_paper_wide"
									       value="<?php echo esc_attr( get_option( 'wpifw_pickingslip_custom_paper_wide' ) ); ?>"
									       placeholder="wide(mm)">
									<span class="wpifw_invoice_custom_paper_times">X</span><input
										style="float:right" type="text"
										class="_winvoice-form-control"
										id="_winvoice_pickingslip_custom_paper_height"
										name="wpifw_pickingslip_custom_paper_height"
										value="<?php echo esc_attr( get_option( 'wpifw_pickingslip_custom_paper_height' ) ); ?>"
										placeholder="height(mm)">
								</div>
							</div>
						</div>

						<div class="_winvoice-form-group">
                            <?php
                            // Set a default value for wpifw_product_image_show if it's not set
                            if (get_option('wpifw_packingslip_product_image_show') === false) {
                            update_option('wpifw_packingslip_product_image_show', '1');
                            }

                            // Now retrieve the value
                            $wpifw_packingslip_display_product_img = get_option('wpifw_packingslip_product_image_show', '1');
                            ?>
							<div class="_winvoice-custom-control _winvoice-custom-switch">
								<div class="_winvoice-toggle-label">
									<span class="_winvoice-checkbox-label"><?php esc_html_e( 'Display Product Image', 'webappick-pdf-invoice-for-woocommerce' ); ?></span>
								</div>
								<div class="_winvoice-toggle-container">
									<input type="hidden" name="wpifw_packingslip_product_image_show" value="0">
									<input type="checkbox" class="_winvoice-custom-control-input"
									       id="wpifw_packingslip_product_image_show"
									       name="wpifw_packingslip_product_image_show"
									       value="1" <?php checked( $wpifw_packingslip_display_product_img, $current, true ); ?>
									>
									<label class="_winvoice-custom-control-label"
									       title="Display Product image into packing slip"
									       for="wpifw_packingslip_product_image_show"></label>
								</div>
							</div>
						</div>

						<div class="_winvoice-form-group">
							<div class="_winvoice-custom-control _winvoice-custom-switch">
								<div class="_winvoice-toggle-label">
									<span class="_winvoice-checkbox-label"><?php esc_html_e( 'Display Product Category', 'webappick-pdf-invoice-for-woocommerce' ); ?></span>
								</div>
								<div class="_winvoice-toggle-container" tooltip="" flow="right">
									<input type="hidden" name="wpifw_packingslip_product_category_show" value="0">
									<input type="checkbox" class="_winvoice-custom-control-input"
									       id="wpifw_packingslip_product_category_show"
									       name="wpifw_packingslip_product_category_show"
									       value="1" <?php checked( get_option( 'wpifw_packingslip_product_category_show' ), $current, true ); ?>
									>
									<label class="_winvoice-custom-control-label"
									       title="Display Product Category into packing slip"
									       for="wpifw_packingslip_product_category_show"></label>
								</div>
							</div>
						</div>
                        <div class="_winvoice-form-group"
                             tooltip="" flow="right">
                            <label class="_winvoice-fixed-label"
                                   for="wpifw_packingslip_product_table_header"> <?php esc_html_e( 'Select Product Column', 'webappick-pdf-invoice-for-woocommerce' ); ?></label>
                            <select
                                    id="wpifw_packingslip_product_table_header"
                                    name="wpifw_packingslip_product_table_header[]"
                                    class="wpifw_packingslip_header generalInput _winvoice-fixed-input"
                                    multiple>
								<?php
								if ( class_exists( 'WooCommerce' ) ) {
									$options = [
										'weight'               => 'Weight',
										'quantity'             => 'Qty',
										'dimension'             => 'Dimension',
									];

									foreach ( $options as $key => $value ) {
										?>
                                        <option value="<?php echo $key ?>"><?php echo $value ?></option>
										<?php
									}
								}
                                ?>
                            </select>
                        </div>
						<div class="_winvoice-form-group">
							<div class="_winvoice-custom-control _winvoice-custom-switch">
								<div class="_winvoice-toggle-label">
									<span class="_winvoice-checkbox-label"><?php esc_html_e( 'Display Vendor', 'webappick-pdf-invoice-for-woocommerce' ); ?></span>
								</div>
								<div class="_winvoice-toggle-container" tooltip="" flow="right">
									<input type="hidden" name="wpifw_packingslip_product_display_vendor" value="0">
									<input type="checkbox" class="_winvoice-custom-control-input"
									       id="wpifw_packingslip_product_display_vendor"
									       name="wpifw_packingslip_product_display_vendor"
									       value="1" <?php checked( get_option( 'wpifw_packingslip_product_display_vendor' ), $current, true ); ?>
									>
									<label class="_winvoice-custom-control-label"
									       title="Display Vendor into packing slip"
									       for="wpifw_packingslip_product_display_vendor"></label>
								</div>
							</div>
						</div>
						<!--<div class="_winvoice-form-group">
										<div class="_winvoice-custom-control _winvoice-custom-switch" tooltip="" flow="right">
											<span class="_winvoice-checkbox-label"><?php /*_e('Display Short Description', 'webappick-pdf-invoice-for-woocommerce'); */ ?></span>

  <input type="checkbox" class="_winvoice-custom-control-input" id ="wpifw_packingslip_product_description_show" name="wpifw_packingslip_product_description_show" value="1" <?php /*checked(get_option('wpifw_packingslip_product_description_show'),$current,true); */ ?> >
											<label class="_winvoice-custom-control-label" title="Display Product short description into packing slip" for="wpifw_packingslip_product_description_show"></label>
										</div>
									</div>-->

						<div class="_winvoice-form-group" tooltip="" flow="right">
							<label class="_winvoice-fixed-label"
							       for="wpifw_packingslip_product_description_show"> <?php esc_html_e( 'Display Description', 'webappick-pdf-invoice-for-woocommerce' ); ?></label>
							<select id="wpifw_packingslip_product_description_show"
							        name="wpifw_packingslip_product_description_show"
							        class="_winvoice-select-control _winvoice-fixed-input">
								<option value="none" <?php selected( get_option( 'wpifw_packingslip_product_description_show' ), 'none', true ); ?>><?php esc_html_e( 'None', 'webappick-pdf-invoice-for-woocommerce' ); ?></option>
								<option value="short" <?php selected( get_option( 'wpifw_packingslip_product_description_show' ), 'short', true ); ?>><?php esc_html_e( 'Short Description', 'webappick-pdf-invoice-for-woocommerce' ); ?></option>
								<option value="long" <?php selected( get_option( 'wpifw_packingslip_product_description_show' ), 'long', true ); ?>><?php esc_html_e( 'Long Description', 'webappick-pdf-invoice-for-woocommerce' ); ?></option>
							</select>
						</div>

						<div class="_winvoice-form-group" id="wpifw_hide_packing_slip_description_limit">
							<label class="_winvoice-fixed-label"
							       for="wpifw_packingslip_description_limit"> <?php esc_html_e( 'Description Limit Words', 'webappick-pdf-invoice-for-woocommerce' ); ?></label>
							<input type="number"
							       class="_winvoice-form-control _winvoice-number-input _winvoice-fixed-input"
							       id="wpifw_packingslip_description_limit"
							       name="wpifw_packingslip_description_limit"
							       value='<?php echo( ! empty( get_option( 'wpifw_packingslip_description_limit' ) ) ? esc_html( get_option( 'wpifw_packingslip_description_limit' ) ) : '' ); ?>'>
						</div>
						<!-- ============================================
							 Add product meta for packing slip.
						============================================== -->
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
								$custom_post_meta = get_option( 'wpifw_custom_post_meta_ps' );
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
											       name="<?php echo esc_attr( $key ); ?>_winvoice_post_meta_label"
											       value="<?php echo esc_attr( $value ); ?>">
											<select
												id="wpifw_product_post_meta_show"
												class="_winvoice-select-control _winvoice-product-post-meta"
												name="<?php echo esc_attr( $key ); ?>_winvoice_post_meta_name">
												<option value="" <?php selected( get_option( 'wpifw_product_post_meta_show_ps' ), '', true ); ?>><?php esc_html_e( 'Select Post Meta', 'webappick-pdf-invoice-for-woocommerce' ); ?></option>
												<?php
												if ( ! empty( $woo_invoice_product_meta_query ) ) {
													foreach ( $woo_invoice_product_meta_query as $meta ) {
														$selected = $key == $meta->meta_key ? 'selected' : '';
														echo '<option value=' . esc_attr( $meta->meta_key ) . ' ' . esc_attr( $selected ) . '>' . esc_attr( $meta->meta_key ) . '</option>';
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
												        value="" <?php selected( get_option( 'wpifw_product_post_meta_show_ps' ), '', true ); ?>><?php esc_html_e( 'Select Post Meta', 'webappick-pdf-invoice-for-woocommerce' ); ?></option>
												<?php
												if ( count( $woo_invoice_product_meta_query ) ) {
													foreach ( $woo_invoice_product_meta_query as $meta ) {
														echo '<option value=' . esc_attr( $meta->meta_key ) . ' ' . selected( get_option( 'wpifw_product_post_meta_show_ps' ), esc_attr( $meta->meta_key ), true ) . '>' . esc_attr( $meta->meta_key ) . '</option>';
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
						<!-- ============================================
							End add product meta for packing slip.
						============================================== -->

						<!-- =====================================
							Add order item meta for packing slip.
						========================================= -->
						<div class="_winvoice-form-group" tooltip="" flow="right">
							<label class="_winvoice-fixed-label"
							       for="wpifw_order_item_meta_show_ps"> <?php esc_html_e( 'Add Order Item Meta', 'webappick-pdf-invoice-for-woocommerce' ); ?></label>
							<a href="#" class="_winvoice-add-order-item-meta-ps">
								<div class="_winvoice-add-order-item-meta-btn-ps">
									<span class="dashicons dashicons-plus-alt"></span>
									<?php esc_html_e( 'Add Order Item Meta', 'webappick-pdf-invoice-for-woocommerce' ); ?>
								</div>
							</a>
							<div class="_winvoice_order_item_meta_ps"
							     style="float:none; padding-top: 10px;">
								<?php
								$custom_order_item_meta_ps = get_option( 'wpifw_order_item_meta_ps' );
								if ( false == $custom_order_item_meta_ps ) {
									$custom_order_item_meta_ps = array();
								}
								if ( count( $custom_order_item_meta_ps ) > 0 ) {
									foreach ( $custom_order_item_meta_ps as $key => $value ) {
										?>
										<div class="_winvoice_order_item_meta_html_ps">
											<input type="text"
											       placeholder="Item Meta Label"
											       class="_winvoice-form-control _winvoice-order-item-meta-label-ps"
											       name="<?php echo esc_html( $key ); ?>_winvoice_order_item_meta_label_ps"
											       value="<?php echo esc_html( $value ); ?>">
											<select
												id="wpifw_order_item_meta_show_ps"
												class="_winvoice-select-control _winvoice-order-item-meta-ps"
												name="<?php echo esc_html( $key ); ?>_winvoice_order_item_meta_name_ps">
												<option value="" <?php selected( get_option( 'wpifw_order_item_meta_show_ps' ), '', true ); ?>><?php esc_html_e( 'Select Order Item Meta', 'webappick-pdf-invoice-for-woocommerce' ); ?></option>
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
											   class="_winvoice-delete-order-item-meta-ps"><span
													class="dashicons dashicons-trash"
													style="color:#D94D40"></span></a>
										</div>
										<?php
									}
								} else {
									?>
									<div style="display: none">
										<div class="_winvoice_order_item_meta_html_ps">
											<input type="text"
											       placeholder="Item Meta Label"
											       class="_winvoice-form-control _winvoice-order-item-meta-label-ps">
											<select
												id="wpifw_order_item_meta_show_ps"
												class="_winvoice-select-control _winvoice-order-item-meta-ps">
												<option disabled
												        value="" <?php selected( get_option( 'wpifw_order_item_meta_show_ps' ), '', true ); ?>><?php esc_html_e( 'Select Order Item Meta', 'webappick-pdf-invoice-for-woocommerce' ); ?></option>
												<?php
												if ( count( $order_item_meta_query ) ) {
													foreach ( $order_item_meta_query as $meta ) {
														echo '<option value=' . esc_attr( $meta->meta_key ) . ' ' . selected( get_option( 'wpifw_order_item_meta_show_ps' ), $meta->meta_key, true ) . '>' . esc_attr( $meta->meta_key ) . '</option>';
													}
												}
												?>
											</select>
											<a href="#"
											   class="_winvoice-delete-order-item-meta-ps"><span
													class="dashicons dashicons-trash"
													style="color:#D94D40"></span></a>
										</div>
									</div>
								<?php } ?>
							</div>
						</div>
						<!-- =====================================
							End order item meta for packing slip.
						========================================= -->





						<div class="_winvoice-header-title">
							<h4><?php esc_html_e( 'Order Info', 'webappick-pdf-invoice-for-woocommerce' ); ?></h4>
						</div>
						<div class="_winvoice-form-group">
							<div class="_winvoice-custom-control _winvoice-custom-switch">
								<div class="_winvoice-toggle-label">
									<span class="_winvoice-checkbox-label"><?php esc_html_e( 'Display Order Note', 'webappick-pdf-invoice-for-woocommerce' ); ?></span>
								</div>
								<div class="_winvoice-toggle-container">
									<input type="hidden" name="wpifw_show_order_note_ps" value="0">
									<input type="checkbox"
									       class="_winvoice-custom-control-input"
									       id="wpifw_show_order_note_ps"
									       name="wpifw_show_order_note_ps"
									       value="1" <?php checked( get_option( 'wpifw_show_order_note_ps' ), $current, true ); ?>
									>
									<label class="_winvoice-custom-control-label"
									       for="wpifw_show_order_note_ps"></label>
								</div>
							</div>
						</div>

						<div class="_winvoice-form-group">
							<div class="_winvoice-custom-control _winvoice-custom-switch">
								<div class="_winvoice-toggle-label">
									<span class="_winvoice-checkbox-label"><?php esc_html_e( 'Display Refund Address', 'webappick-pdf-invoice-for-woocommerce' ); ?></span>
								</div>
								<div class="_winvoice-toggle-container">
									<input type="hidden" name="wpifw_display_refund_address" value="0">
									<input type="checkbox"
									       class="_winvoice-custom-control-input"
									       id="wpifw_display_refund_address"
									       name="wpifw_display_refund_address"
									       value="1" <?php checked( get_option( 'wpifw_display_refund_address' ), $current, true ); ?>
									>
									<label class="_winvoice-custom-control-label"
									       for="wpifw_display_refund_address"></label>
								</div>
							</div>
						</div>
						<!-- ===========================
						Add order meta for packing_slip.
						=========================== -->
						<div class="_winvoice-form-group" tooltip="" flow="right">
							<label class="_winvoice-fixed-label"
							       for="wpifw_product_order_meta_show"> <?php esc_html_e( 'Add Order Meta', 'webappick-pdf-invoice-for-woocommerce' ); ?></label>

							<a href="#" class="_winvoice-add-order-meta-ps">
								<div class="_winvoice-add-order-meta-btn">
									<span class="dashicons dashicons-plus-alt"></span>
									<?php esc_html_e( 'Add Order Meta', 'webappick-pdf-invoice-for-woocommerce' ); ?>
								</div>
							</a>

							<div class="_winvoice_order_meta_ps"
							     style="float: none; padding-top: 10px;">
								<?php

								$order_meta_label_ps = get_option( '_winvoice_order_meta_label_ps' );
								$order_meta_name_ps = get_option( '_winvoice_order_meta_name_ps' );
								$order_meta_place_ps = get_option( '_winvoice_order_meta_name_position_ps' );

								if ( $order_meta_label_ps ) {
									foreach ( $order_meta_label_ps as $key => $value ) {
										?>
										<div class="_winvoice_order_meta_html_ps  _winvoice_col_3">
											<input data="1" type="text"
											       placeholder="Meta Label"
											       class="_winvoice-form-control _winvoice-product-order-meta-label-ps"
											       name="_winvoice_order_meta_label_ps[]"
											       value="<?php echo  $value; //phpcs:ignore?>">
											<select
												id="wpifw_product_order_meta_show_ps"
												class="_winvoice-select-control _winvoice-product-order-meta-ps"
												name="_winvoice_order_meta_name_ps[]">
												<option value="" disabled  ><?php esc_html_e( 'Select Order Meta', 'webappick-pdf-invoice-for-woocommerce' ); ?></option>
												<?php
												if ( count( $woo_invoice_order_meta_query ) ) {
													foreach ( $woo_invoice_order_meta_query as $meta ) {
														$selected = $order_meta_name_ps[ $key ] == $meta->meta_key ? 'selected' : '';
														echo '<option value=' . $meta->meta_key . ' '.$selected.' >' . $meta->meta_key . '</option>' ; //phpcs:ignore
													}
												}
												?>
											</select>
											<!--where to show?-->
											<select
												id="wpifw_product_order_meta_position_ps"
												class="_winvoice-select-control _winvoice-product-order-meta-position-ps"
												name="_winvoice_order_meta_name_position_ps[]">
												<option value=""  disabled  ><?php esc_html_e( 'Whare to show ?', 'webappick-pdf-invoice-for-woocommerce' ); ?></option>

												<?php
												if ( count( woo_invoice_order_meta_data_position() ) ) {
													foreach ( woo_invoice_order_meta_data_position() as $index => $val ) {
														$selected = $index == $order_meta_place_ps[ $key ] ? 'selected' : '';
														echo '<option value=' . $index . ' ' . $selected . '>' . $val . '</option>'; //phpcs:ignore
													}
												}
												?>
											</select>
											<a href="#"
											   class="_winvoice-delete-order-meta-ps"><span
													class="dashicons dashicons-trash"
													style="color:#D94D40"></span></a>
										</div>
										<?php
									}
								} else {
									?>
									<div style="display: none;">
										<div class="_winvoice_order_meta_html_ps _winvoice_col_3">
											<input type="text"
											       placeholder="Meta Label"
											       class="_winvoice-form-control _winvoice-product-order-meta-label-ps"
											       name="_winvoice_order_meta_label_ps[]"
											>
											<select
												id="wpifw_product_order_meta_show_ps"
												class="_winvoice-select-control _winvoice-product-order-meta-ps"
												name="_winvoice_order_meta_name_ps[]"
											>
												<option value=""  disabled  ><?php esc_html_e( 'Select Order Meta', 'webappick-pdf-invoice-for-woocommerce' ); ?></option>
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
												id="wpifw_product_order_meta_position_ps"
												class="_winvoice-select-control _winvoice-product-order-meta-position-ps"
												name="_winvoice_order_meta_name_position_ps[]"
											>
												<option value=""  disabled  ><?php esc_html_e( 'Whare to show ?', 'webappick-pdf-invoice-for-woocommerce' ); ?></option>
												<?php
												if ( count( woo_invoice_order_meta_data_position() ) ) {
													foreach ( woo_invoice_order_meta_data_position() as $key => $val ) {
														echo '<option value=' . $key . '>' . $val . '</option>'; //phpcs:ignore
													}
												}
												?>
											</select>
											<a href="#"
											   class="_winvoice-delete-order-meta-ps"><span
													class="dashicons dashicons-trash"
													style="color:#D94D40"></span></a>
										</div>
									</div>
								<?php } ?>
							</div>
                            <p style="opacity: .7" class="_winvoice_order_meta_html _winvoice_col_3">Notice: If order meta value is empty or an array will not display.</p>
						</div>
						<!-- ===========================
						 End Add order meta for packing_slip.
						 =========================== -->

						<div class="_winvoice-header-title" >
							<h4><?php esc_html_e( 'Size', 'webappick-pdf-invoice-for-woocommerce' ); ?></h4>
						</div>

						<div class="_winvoice-form-group" >
							<div class="_winvoice-custom-control _winvoice-custom-switch">
								<div class="_winvoice-toggle-label">
									<span class="_winvoice-checkbox-label"><?php esc_html_e( 'Display Product Dimension', 'webappick-pdf-invoice-for-woocommerce' ); ?></span>
								</div>
								<div class="_winvoice-toggle-container" tooltip="" flow="right">
									<input type="hidden" name="wpifw_packingslip_product_dimension_show" value="0">
									<input type="checkbox" class="_winvoice-custom-control-input"
									       id="wpifw_packingslip_product_dimension_show"
									       name="wpifw_packingslip_product_dimension_show"
									       value="1" <?php checked( get_option( 'wpifw_packingslip_product_dimension_show' ), $current, true ); ?>
									>
									<label class="_winvoice-custom-control-label"
									       title="Display Product Dimension into packing slip"
									       for="wpifw_packingslip_product_dimension_show"></label>
								</div>
							</div>
						</div>

						<div class="_winvoice-form-group" style="display: none;">
							<div class="_winvoice-custom-control _winvoice-custom-switch">
								<div class="_winvoice-toggle-label">
									<span class="_winvoice-checkbox-label"><?php esc_html_e( 'Display Product Width', 'webappick-pdf-invoice-for-woocommerce' ); ?></span>
								</div>
								<div class="_winvoice-toggle-container" tooltip="" flow="right">
									<input type="hidden" name="wpifw_packingslip_product_width_show" value="0">
									<input type="checkbox" class="_winvoice-custom-control-input"
									       id="wpifw_packingslip_product_width_show"
									       name="wpifw_packingslip_product_width_show"
									       value="1" <?php checked( get_option( 'wpifw_packingslip_product_width_show' ), $current, true ); ?>
									>
									<label class="_winvoice-custom-control-label"
									       title="Display Product width into packing slip"
									       for="wpifw_packingslip_product_width_show"></label>
								</div>
							</div>
						</div>

						<div class="_winvoice-form-group" style="display: none;">
							<div class="_winvoice-custom-control _winvoice-custom-switch">
								<div class="_winvoice-toggle-label">
									<span class="_winvoice-checkbox-label"><?php esc_html_e( 'Display Product Height', 'webappick-pdf-invoice-for-woocommerce' ); ?></span>
								</div>
								<div class="_winvoice-toggle-container" tooltip="" flow="right">
									<input type="hidden" name="wpifw_packingslip_product_height_show" value="0">
									<input type="checkbox" class="_winvoice-custom-control-input"
									       id="wpifw_packingslip_product_height_show"
									       name="wpifw_packingslip_product_height_show"
									       value="1" <?php checked( get_option( 'wpifw_packingslip_product_height_show' ), $current, true ); ?>
									>
									<label class="_winvoice-custom-control-label"
									       title="Display Product height into packing slip"
									       for="wpifw_packingslip_product_height_show"></label>
								</div>
							</div>
						</div>
						<div class="_winvoice-form-group" style="display: none;">
							<div class="_winvoice-custom-control _winvoice-custom-switch">
								<div class="_winvoice-toggle-label">
									<span class="_winvoice-checkbox-label"><?php esc_html_e( 'Display Product Weight', 'webappick-pdf-invoice-for-woocommerce' ); ?></span>
								</div>
								<div class="_winvoice-toggle-container" tooltip="" flow="right">
									<input type="hidden" name="wpifw_packingslip_product_weight_show" value="0">
									<input type="checkbox" class="_winvoice-custom-control-input"
									       id="wpifw_packingslip_product_weight_show"
									       name="wpifw_packingslip_product_weight_show"
									       value="1" <?php checked( get_option( 'wpifw_packingslip_product_weight_show' ), $current, true ); ?>
									>
									<label class="_winvoice-custom-control-label"
									       title="Display Product short weight into packing slip"
									       for="wpifw_packingslip_product_weight_show"></label>
								</div>
							</div>
						</div>
						<div class="_winvoice-card-footer _winvoice-save-changes-selector">
							<input type="submit" style="float:right;"
							       name="wpifw_submit_packingslip_section"
							       value="<?php esc_html_e( 'Save Changes', 'webappick-pdf-invoice-for-woocommerce' ); ?>"
							       class="_winvoice-btn _winvoice-btn-primary"/>
						</div>

					</form>

				</div>
			</div>
		</div>
		<div class="_winvoice-col-sm-4 _winvoice-col-12">

			<div class="_winvoice-card">
				<div class="_winvoice-card-header">
					<h4><?php esc_html_e( 'PACKINGSLIP BACKGROUND', 'webappick-pdf-invoice-for-woocommerce' ); ?></h4>
				</div>
				<div class="_winvoice-card-body">

					<?php
					if ( get_option( 'wpifw_packingslip_background_attachment_image_id' ) != false && ! empty( get_option( 'wpifw_packingslip_background_attachment_image_id' ) ) ) {
						$url = wp_get_attachment_url( get_option( 'wpifw_packingslip_background_attachment_image_id' ) );
						?>
						<img class="_winvoice-packingslip-background-preview"
						     id='wpifw_packingslip-background-preview' src='<?php echo esc_url( $url ); ?>'>
						<?php
					} else {
						?>
						<img class="_winvoice-packingslip-background-preview"
						     id='wpifw_packingslip-background-preview' src=''>
						<?php
					}
					?>

				</div>
			</div>
            <!-- Packing Slip Sidebar -->
                <!--    Banner Section start    -->
				<?php include(plugin_dir_path(__FILE__) . 'lifetime-banner-widget.php'); ?>
                <!-- End Banner section -->
		</div>
	</div>
</li>
