<?php


$woo_invoice_order_meta_query = woo_invoice_order_meta_query();
?>
<li class="woo-invoice-shipping-label-li">
	<div class="_winvoice-row">
		<div class="_winvoice-col-8">
			<div class="_winvoice-card _winvoice-mr-0">
				<div class="_winvoice-card-header">
					<div class="_winvoice-card-header-title">
						<h3><?php esc_html_e( 'Shipping Label Print', 'webappick-pdf-invoice-for-woocommerce' ); ?></h3>
					</div>
				</div>
				<div class="_winvoice-card-body">

					<form action="" method="post" target="_blank">
						<?php wp_nonce_field( 'settings_form_nonce' ); ?>
						<div class="_winvoice-form-group">
							<label class="_winvoice-fixed-label" for="wpifw_shipping_lebel_num_col"> <?php esc_html_e( 'Number of Column Per Row', 'webappick-pdf-invoice-for-woocommerce' ); ?></label>
							<input type="number" class="_winvoice-form-control _winvoice-number-input _winvoice-fixed-input" id="wpifw_shipping_lebel_num_col" name="wpifw_shipping_lebel_num_col" placeholder="Ex:2" required>
						</div>
						<div class="_winvoice-form-group">
							<label class="_winvoice-fixed-label"
							       for="wpifw-shipping-lebel-num-row"> <?php esc_html_e( 'Number of Row', 'webappick-pdf-invoice-for-woocommerce' ); ?></label>
							<input type="number"
							       class="_winvoice-form-control _winvoice-number-input _winvoice-fixed-input"
							       id="wpifw-shipping-lebel-num-row" name="wpifw_shipping_lebel_num_row" placeholder="Ex:2" required>
						</div>

						<div class="_winvoice-form-group">
							<label class="_winvoice-fixed-label"
							       for="shipping-lebel-date-from"> <?php esc_html_e( 'Date From', 'webappick-pdf-invoice-for-woocommerce' ); ?></label>
							<input id="shipping-lebel-date-from"
							       class="_winvoice-form-control _winvoice-datepicker _winvoice-fixed-input"
							       name="wpifw_shipping_lebel_date_from" placeholder="Date From"
							       max="<?php echo esc_attr( gmdate( 'Y-m-d' ) ); ?>" autocomplete="off" required>
						</div>

						<div class="_winvoice-form-group">
							<label class="_winvoice-fixed-label"
							       for="shipping-lebel-date-to"> <?php esc_html_e( 'Date To', 'webappick-pdf-invoice-for-woocommerce' ); ?></label>
							<input id="shipping-lebel-date-to"
							       class="_winvoice-form-control _winvoice-datepicker _winvoice-fixed-input"
							       name="wpifw_shipping_lebel_date_to" placeholder="Date To"
							       max="<?php echo esc_attr( gmdate( 'Y-m-d' ) ); ?>" autocomplete="off" required>
						</div>
						<div class="_winvoice-form-group">
							<label class="_winvoice-fixed-label"
							       for="wpifw_shipping_lebel_font_size"><?php esc_html_e( 'Font Size', 'webappick-pdf-invoice-for-woocommerce' ); ?></label>
							<input type="number"
							       class="_winvoice-fixed-width _winvoice-form-control _winvoice-fixed-input"
							       id="wpifw_shipping_lebel_font_size"
							       name="wpifw_shipping_lebel_font_size"
							       value="<?php echo esc_attr( get_option( 'wpifw_shipping_lebel_font_size' ) ); ?>">
						</div>
						<div class="_winvoice-form-group">
							<?php
							$wpifw_shipping_lebel_paper = ( '' != get_option( 'wpifw_shipping_lebel_paper' )) ? get_option( 'wpifw_shipping_lebel_paper') : 'A4';
							?>
							<label class="wpifw-shipping-lebel-paper-size-label _winvoice-fixed-label"
							       for="wpifw-shipping-lebel-paper-size"> <?php esc_html_e( 'Paper Size', 'webappick-pdf-invoice-for-woocommerce' ); ?></label>
							<select id="wpifw-shipping-lebel-paper"
							        name="wpifw_shipping_lebel_paper"
							        class="_winvoice-select-control _winvoice-fixed-input">
								<option value="A3" <?php selected( $wpifw_shipping_lebel_paper, 'A3', true ); ?>>
									A3
								</option>
								<option value="A4" <?php selected( $wpifw_shipping_lebel_paper, 'A4', true ); ?>>
									A4
								</option>
								<option value="A5" <?php selected( $wpifw_shipping_lebel_paper, 'A5', true ); ?>>
									A5
								</option>
								<option value="Letter" <?php selected( $wpifw_shipping_lebel_paper, 'Letter', true ); ?>>
									Letter
								</option>
								<option value="custom" <?php selected( $wpifw_shipping_lebel_paper, 'custom', true ); ?>>
									<?php esc_html_e( 'Custom Paper Size', 'webappick-pdf-invoice-for-woocommerce' ); ?>
								</option>

							</select>
							<div class="wpifw_shipping_lebel_custom_paper_size"
							     style="overflow:hidden;display:none">
								<input style="float:left" type="text"
								       class="_winvoice-form-control"
								       id="_wpifw_shipping_lebel_custom_paper_wide"
								       name="wpifw_shipping_lebel_custom_paper_wide"
								       placeholder="wide(mm)">
								<span class="wpifw_invoice_custom_paper_times">X</span><input
									style="float:right" type="text"
									class="_winvoice-form-control"
									id="_wpifw_shipping_lebel_custom_paper_height"
									name="wpifw_shipping_lebel_custom_paper_height"
									placeholder="height(mm)">
							</div>
						</div>
						<div class="_winvoice-form-group">
							<label class="_winvoice-fixed-label"
							       for="wpifw_shipping_lebel_block_title_to"><?php esc_html_e( 'Block Title', 'webappick-pdf-invoice-for-woocommerce' ); ?></label>
							<input type="text"
							       class="_winvoice-fixed-width _winvoice-form-control _winvoice-fixed-input"
							       id="wpifw_shipping_lebel_block_title_to"
							       name="wpifw_shipping_lebel_block_title_to"
							       value="<?php echo esc_attr( get_option( 'wpifw_shipping_lebel_block_title_to' ) ); ?>">
						</div>
						<div class="_winvoice-form-group">
							<label class="_winvoice-buyer-label _winvoice-fixed-label"
							       for="shipping_lebel_buyer"><?php esc_html_e( 'Buyer Details Layout', 'webappick-pdf-invoice-for-woocommerce' ); ?></label>

							<div class="_winvoice-tinymce-textarea">
											<textarea style="height:150px;" class="_winvoice-form-control "
											          id="shipping_lebel_buyer" name="wpifw_shipping_lebel_buyer"
											          value=""
											          required><?php echo stripslashes( esc_textarea( get_option( 'wpifw_shipping_lebel_buyer' ) ) ); //phpcs:ignore ?></textarea>
								<br/>
								<table>
									<tbody>
									<tr>
										<td><code>{{billing_company}}</code></td>
										<td><code>{{billing_address_1}}</code></td>
									</tr>
									<tr>
										<td><code>{{billing_first_name}}</code></td>
										<td><code>{{billing_last_name}}</code></td>
									</tr>
									<tr>
										<td><code>{{billing_address_2}}</code></td>
										<td><code>{{billing_city}}</code></td>
									</tr>
									<tr>
										<td><code>{{billing_state}}</code></td>
										<td><code>{{billing_postcode}}</code></td>
									</tr>
									<tr>
										<td><code>{{billing_country}}</code></td>
										<td><code>{{billing_phone}}</code></td>
									</tr>
									<tr>
										<td><code>{{billing_email}}</code></td>
										<td></td>
									</tr>
									<tr>
										<td><code>{{shipping_first_name}}</code></td>
										<td><code>{{shipping_last_name}}</code></td>
									</tr>
									<tr>
										<td><code>{{shipping_company}}</code></td>
										<td><code>{{shipping_address_1}}</code></td>
									</tr>
									<tr>
										<td><code>{{shipping_address_2}}</code></td>
										<td><code>{{shipping_city}}</code></td>
									</tr>
									<tr>
										<td><code>{{shipping_state}}</code></td>
										<td><code>{{shipping_postcode}}</code></td>
									</tr>
									<tr>
										<td><code>{{shipping_country}}</code></td>
										<td><code>{{shipping_phone}}</code></td>
									</tr>
									<tr>
										<td><code>{{shipping_email}}</code></td>
										<td></td>
									</tr>
									</tbody>
								</table>
							</div>

						</div>
						<div class="_winvoice-form-group" tooltip="" flow="right">

							<label class="_winvoice-fixed-label"
							       for="wpifw_product_order_meta_show"> <?php esc_html_e( 'Display Meta', 'webappick-pdf-invoice-for-woocommerce' ); ?></label>

							<a href="#" class="_winvoice-add-shipping-label-meta">
								<div class="_winvoice-add-order-meta-btn">
									<span class="dashicons dashicons-plus-alt"></span>
									<?php esc_html_e( 'Add Order Meta', 'webappick-pdf-invoice-for-woocommerce' ); ?>
								</div>
							</a>

							<div class="_winvoice_shipping_label_meta"
							     style="float: none; padding-top: 10px;">
								<?php
								$custom_shipping_label_meta = get_option( 'wpifw_custom_shipping_label_meta' );
								if ( false == $custom_shipping_label_meta ) {
									$custom_shipping_label_meta = array();
								}
								if ( count( $custom_shipping_label_meta ) > 0 ) {
									foreach ( $custom_shipping_label_meta as $key => $value ) {
										?>
										<div class="_winvoice_shipping_label_meta_html">
											<input type="text"
											       placeholder="Meta Label"
											       class="_winvoice-form-control _winvoice-shipping-label-meta-label"
											       name="shipping_label_meta_label"
											       value="<?php echo esc_html( $value ); ?>">
											<select
												id="wpifw_product_order_meta_show"
												class="_winvoice-select-control _winvoice-shipping-label-meta"
												name="shipping_label_meta_name">
												<option value="" <?php selected( get_option( 'wpifw_product_order_meta_show' ), '', true ); ?>><?php esc_html_e( 'Select Order Meta', 'webappick-pdf-invoice-for-woocommerce' ); ?></option>
												<?php
												if ( count( $woo_invoice_order_meta_query ) ) {
													foreach ( $woo_invoice_order_meta_query as $meta ) {
														$selected = $key == $meta->meta_key ? 'selected' : '';
														echo '<option value=' . esc_attr( $meta->meta_key ) . ' ' . esc_attr( $selected ) . '>' . esc_html( $meta->meta_key ) . '</option>';
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
									<div style="display: none">
										<div class="_winvoice_shipping_label_meta_html">
											<input type="text"
											       placeholder="Meta Label"
											       name="shipping_label_meta_labels[]"
											       class="_winvoice-form-control _winvoice-shipping-label-meta-label">
											<select name="shipping_label_metas[]"
											        id="wpifw_product_order_meta_show"
											        class="_winvoice-select-control _winvoice-shipping-label-meta">
												<option value=""><?php esc_html_e( 'Select Order Meta', 'webappick-pdf-invoice-for-woocommerce' ); ?></option>
												<?php
												if ( count( $woo_invoice_order_meta_query ) ) {
													foreach ( $woo_invoice_order_meta_query as $meta ) {
														echo '<option value=' . esc_attr( $meta->meta_key ) . '>' . esc_html( $meta->meta_key ) . '</option>';
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
						</div>

						<div class="_winvoice-card-footer _winvoice-save-changes-selector">
							<!-- Fetch error message if not found table -->
							<?php if ( isset( $_GET['shipping_label_message'] ) ) { ?>
								<span style="margin:0; color:red; margin-top: -15px"> <?php echo esc_html( wp_unslash( $_GET['shipping_label_message'] ) ); //phpcs:ignore ?> </span>
							<?php } ?>

							<input type="submit" style="float:right;" name="wpifw_submit_shipping_lebel"
							       value="<?php esc_html_e( 'Download', 'webappick-pdf-invoice-for-woocommerce' ); ?>"
							       class="_winvoice-btn _winvoice-btn-primary"/>
						</div>
					</form>
				</div>
			</div>
		</div>
        <!-- Shipping Label Sidebar -->
        <div class="_winvoice-col-sm-4 _winvoice-col-12">
            <!--    Banner Section start    -->
			<?php include(plugin_dir_path(__FILE__) . 'lifetime-banner-widget.php'); ?>
            <!-- End Banner section -->
        </div>
	</div>
</li>
