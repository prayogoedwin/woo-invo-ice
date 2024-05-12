
<li class="woo-invoice-localization-li">
	<form action="" method="post">
		<?php wp_nonce_field( 'settings_form_nonce' ); ?>
		<div class="_winvoice-row _winvoice-localization-invoice-block">
			<div class="_winvoice-col-8">
				<div class="_winvoice-card _winvoice-mr-0">
					<div class="_winvoice-card-header">
						<div class="_winvoice-card-header-title">
							<h3><?php esc_html_e( 'Invoice Template', 'webappick-pdf-invoice-for-woocommerce' ); ?></h3>
						</div>
					</div>
					<div class="_winvoice-card-body">

						<div class="_winvoice-form-group">
							<label class="_winvoice-fixed-label" for="invoice">Invoice</label>
							<input type="text" class="_winvoice-form-control _winvoice-fixed-input"
							       id="invoice" name="wpifw_INVOICE_TITLE"
							       value="<?php echo ! empty( get_option( 'wpifw_INVOICE_TITLE' ) ) ? esc_attr( get_option( 'wpifw_INVOICE_TITLE' ) ) : 'Invoice'; ?>">
						</div>

						<div class="_winvoice-form-group">
							<label class="_winvoice-fixed-label" for="vat_id">Vat ID</label>
							<input type="text" class="_winvoice-form-control _winvoice-fixed-input"
							       id="vat_id" name="wpifw_VAT_ID"
							       value="<?php echo ! empty( get_option( 'wpifw_VAT_ID' ) ) ? esc_attr( get_option( 'wpifw_VAT_ID' ) ) : 'VAT ID'; ?>">
						</div>

						<div class="_winvoice-form-group">
							<label class="_winvoice-fixed-label" for="ssn">SSN</label>
							<input type="text" class="_winvoice-form-control _winvoice-fixed-input" id="ssn"
							       name="wpifw_SSN"
							       value="<?php echo ! empty( get_option( 'wpifw_SSN' ) ) ? esc_attr( get_option( 'wpifw_SSN' ) ) : 'SSN'; ?>">
						</div>
						<div class="_winvoice-form-group">
							<label class="_winvoice-fixed-label" for="payment_method">Payment Method</label>
							<input type="text" class="_winvoice-form-control _winvoice-fixed-input"
							       id="payment_method" name="wpifw_payment_method_text"
							       value="<?php echo ! empty( get_option( 'wpifw_payment_method_text' ) ) ? esc_attr( get_option( 'wpifw_payment_method_text' ) ) : 'Payment Method'; ?>">
						</div>

						<div class="_winvoice-form-group">
							<label class="_winvoice-fixed-label" for="Invoice-number">Invoice Number</label>
							<input type="text" class="_winvoice-form-control _winvoice-fixed-input"
							       id="Invoice-number" name="wpifw_INVOICE_NUMBER_TEXT"
							       value="<?php echo ! empty( get_option( 'wpifw_INVOICE_NUMBER_TEXT' ) ) ? esc_attr( get_option( 'wpifw_INVOICE_NUMBER_TEXT' ) ) : 'Invoice Number'; ?>">
						</div>

						<div class="_winvoice-form-group">
							<label class="_winvoice-fixed-label" for="Invoice-date">Invoice Date</label>
							<input type="text" class="_winvoice-form-control _winvoice-fixed-input"
							       id="Invoice-date" name="wpifw_INVOICE_DATE_TEXT"
							       value="<?php echo ! empty( get_option( 'wpifw_INVOICE_DATE_TEXT' ) ) ? esc_attr( get_option( 'wpifw_INVOICE_DATE_TEXT' ) ) : 'Invoice Date'; ?>">
						</div>

						<div class="_winvoice-form-group">
							<label class="_winvoice-fixed-label" for="order_number">Order Number</label>
							<input type="text" class="_winvoice-form-control _winvoice-fixed-input"
							       id="order_number" name="wpifw_ORDER_NUMBER_TEXT"
							       value="<?php echo ! empty( get_option( 'wpifw_ORDER_NUMBER_TEXT' ) ) ? esc_attr( get_option( 'wpifw_ORDER_NUMBER_TEXT' ) ) : 'Order Number'; ?>">
						</div>

						<div class="_winvoice-form-group">
							<label class="_winvoice-fixed-label" for="order_date">Order Date</label>
							<input type="text" class="_winvoice-form-control _winvoice-fixed-input"
							       id="order_date" name="wpifw_ORDER_DATE_TEXT"
							       value="<?php echo ! empty( get_option( 'wpifw_ORDER_DATE_TEXT' ) ) ? esc_attr( get_option( 'wpifw_ORDER_DATE_TEXT' ) ) : 'Order Date'; ?>">
						</div>



						<div class="_winvoice-form-group">
							<label class="_winvoice-fixed-label" for="product">Product</label>
							<input type="text" class="_winvoice-form-control _winvoice-fixed-input"
							       id="product" name="wpifw_PRODUCT"
							       value="<?php echo ! empty( get_option( 'wpifw_PRODUCT' ) ) ? esc_attr( get_option( 'wpifw_PRODUCT' ) ) : 'Product'; ?>">
						</div>

						<div class="_winvoice-form-group">
							<label class="_winvoice-fixed-label" for="price">Price</label>
							<input type="text" class="_winvoice-form-control _winvoice-fixed-input"
							       id="price" name="wpifw_PRICE"
							       value="<?php echo ! empty( get_option( 'wpifw_PRICE' ) ) ? esc_attr( get_option( 'wpifw_PRICE' ) ) : 'Price'; ?>">
						</div>

						<div class="_winvoice-form-group">
							<label class="_winvoice-fixed-label" for="quantity">Quantity</label>
							<input type="text" class="_winvoice-form-control _winvoice-fixed-input"
							       id="quantity" name="wpifw_QUANTITY"
							       value="<?php echo ! empty( get_option( 'wpifw_QUANTITY' ) ) ? esc_attr( get_option( 'wpifw_QUANTITY' ) ) : 'Quantity'; ?>">
						</div>

						<div class="_winvoice-form-group">
							<label class="_winvoice-fixed-label" for="total">Total</label>
							<input type="text" class="_winvoice-form-control _winvoice-fixed-input"
							       id="total" name="wpifw_ROW_TOTAL"
							       value="<?php echo ! empty( get_option( 'wpifw_ROW_TOTAL' ) ) ? esc_attr( get_option( 'wpifw_ROW_TOTAL' ) ) : 'Total'; ?>">
						</div>

						<div class="_winvoice-form-group">
							<label class="_winvoice-fixed-label" for="su-total">Sub Total</label>
							<input type="text" class="_winvoice-form-control _winvoice-fixed-input"
							       id="su-total" name="wpifw_SUBTOTAL_TEXT"
							       value="<?php echo ! empty( get_option( 'wpifw_SUBTOTAL_TEXT' ) ) ? esc_attr( get_option( 'wpifw_SUBTOTAL_TEXT' ) ) : 'Sub Total'; ?>">
						</div>

						<div class="_winvoice-form-group">
							<label class="_winvoice-fixed-label" for="Tax1">Tax</label>
							<input type="text" class="_winvoice-form-control _winvoice-fixed-input"
							       id="Tax1"
							       name="wpifw_TAX_TEXT"
							       value="<?php echo ! empty( get_option( 'wpifw_TAX_TEXT' ) ) ? esc_attr( get_option( 'wpifw_TAX_TEXT' ) ) : 'Tax'; ?>">
						</div>

						<div class="_winvoice-form-group">
							<label class="_winvoice-fixed-label" for="Tax2">Tax(%)</label>
							<input type="text" class="_winvoice-form-control _winvoice-fixed-input"
							       id="Tax2"
							       name="wpifw_TAX_PERCENTAGE_TEXT"
							       value="<?php echo ! empty( get_option( 'wpifw_TAX_PERCENTAGE_TEXT' ) ) ? esc_attr( get_option( 'wpifw_TAX_PERCENTAGE_TEXT' ) ) : 'Tax(%)'; ?>">
						</div>

						<div class="_winvoice-form-group">
							<label class="_winvoice-fixed-label" for="discount">Discount</label>
							<input type="text" class="_winvoice-form-control _winvoice-fixed-input"
							       id="discount" name="wpifw_DISCOUNT_TEXT"
							       value="<?php echo ! empty( get_option( 'wpifw_DISCOUNT_TEXT' ) ) ? esc_attr( get_option( 'wpifw_DISCOUNT_TEXT' ) ) : 'Discount'; ?>">
						</div>

						<div class="_winvoice-form-group">
							<label class="_winvoice-fixed-label" for="shipping">Shipping</label>
							<input type="text" class="_winvoice-form-control _winvoice-fixed-input"
							       id="shipping" name="wpifw_SHIPPING_TEXT"
							       value="<?php echo ! empty( get_option( 'wpifw_SHIPPING_TEXT' ) ) ? esc_attr( get_option( 'wpifw_SHIPPING_TEXT' ) ) : 'Shipping'; ?>">
						</div>

						<div class="_winvoice-form-group">
							<label class="_winvoice-fixed-label" for="refunded">Refunded</label>
							<input type="text" class="_winvoice-form-control _winvoice-fixed-input"
							       id="refunded" name="wpifw_REFUNDED_TEXT"
							       value="<?php echo ! empty( get_option( 'wpifw_REFUNDED_TEXT' ) ) ? esc_attr( get_option( 'wpifw_REFUNDED_TEXT' ) ) : 'Refunded'; ?>">
						</div>

						<div class="_winvoice-form-group">
							<label class="_winvoice-fixed-label" for="grand-total-without-tax">Order Total
								Without Tax</label>
							<input type="text" class="_winvoice-form-control _winvoice-fixed-input"
							       id="grand-total-without-tax" name="wpifw_GRAND_TOTAL_WITHOUT_TAX_TEXT"
							       value="<?php echo ! empty( get_option( 'wpifw_GRAND_TOTAL_WITHOUT_TAX_TEXT' ) ) ? esc_attr( get_option( 'wpifw_GRAND_TOTAL_WITHOUT_TAX_TEXT' ) ) : 'Grand Total Without Tax'; ?>">
						</div>

						<div class="_winvoice-form-group">
							<label class="_winvoice-fixed-label" for="grand-total-without-discount">Order
								Total Without Discount</label>
							<input type="text" class="_winvoice-form-control _winvoice-fixed-input"
							       id="grand-total-without-discount"
							       name="wpifw_GRAND_TOTAL_WITHOUT_DISCOUNT_TEXT"
							       value="<?php echo ! empty( get_option( 'wpifw_GRAND_TOTAL_WITHOUT_DISCOUNT_TEXT' ) ) ? esc_attr( get_option( 'wpifw_GRAND_TOTAL_WITHOUT_DISCOUNT_TEXT' ) ) : 'Grand Total Without Discount'; ?>">
						</div>

						<div class="_winvoice-form-group">
							<label class="_winvoice-fixed-label" for="grand-total-tax">Order Total</label>
							<input type="text" class="_winvoice-form-control _winvoice-fixed-input"
							       id="grand-total-tax" name="wpifw_GRAND_TOTAL_TEXT"
							       value="<?php echo ! empty( get_option( 'wpifw_GRAND_TOTAL_TEXT' ) ) ? esc_attr( get_option( 'wpifw_GRAND_TOTAL_TEXT' ) ) : 'Grand Total'; ?>">
						</div>

						<div class="_winvoice-form-group">
							<label class="_winvoice-fixed-label" for="grand-total-tax">Net Total</label>
							<input type="text" class="_winvoice-form-control _winvoice-fixed-input"
							       id="grand-total-tax" name="wpifw_NET_TOTAL_TEXT"
							       value="<?php echo ! empty( get_option( 'wpifw_NET_TOTAL_TEXT' ) ) ? esc_attr( get_option( 'wpifw_NET_TOTAL_TEXT' ) ) : 'Grand Total'; ?>">
						</div>
						<div class="_winvoice-form-group">
							<label class="_winvoice-fixed-label" for="grand-total-tax">Order Note</label>
							<input type="text" class="_winvoice-form-control _winvoice-fixed-input"
							       id="grand-total-tax" name="wpifw_ORDER_NOTE_TEXT"
							       value="<?php echo ! empty( get_option( 'wpifw_ORDER_NOTE_TEXT' ) ) ? esc_attr( get_option( 'wpifw_ORDER_NOTE_TEXT' ) ) : 'Order Note: '; ?>">
						</div>
						<div class="_winvoice-form-group">
							<label class="_winvoice-fixed-label" for="grand-total-tax">Authorize Signature</label>
							<input type="text" class="_winvoice-form-control _winvoice-fixed-input"
							       id="grand-total-tax" name="wpifw_signature_text"
							       value="<?php echo ! empty( get_option( 'wpifw_signature_text' ) ) ? esc_attr( get_option( 'wpifw_signature_text' ) ) : 'Authorize Signature'; ?>">
						</div>
						<div class="_winvoice-form-group">
							<label class="_winvoice-fixed-label _winvoice-download-invoice"
							       for="grand-total-tax">Download Invoice</label>
							<input type="text" class="_winvoice-form-control _winvoice-fixed-input"
							       id="grand-total-tax" name="wpifw_DOWNLOAD_INVOICE_TEXT"
							       value="<?php echo ! empty( get_option( 'wpifw_DOWNLOAD_INVOICE_TEXT' ) ) ? esc_attr( get_option( 'wpifw_DOWNLOAD_INVOICE_TEXT' ) ) : 'Download Invoice'; ?>">
						</div>

						<div class="_winvoice-card-footer _winvoice-save-changes-selector">
							<input type="submit" style="float:right;"
							       name="wpifw_submit_invoice_localization"
							       value="<?php esc_html_e( 'Save Changes', 'webappick-pdf-invoice-for-woocommerce' ); ?>"
							       class="_winvoice-btn _winvoice-btn-primary"/>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
	<form action="" method="post">
		<?php wp_nonce_field( 'settings_form_nonce' ); ?>
		<div class="_winvoice-row">
			<div class="_winvoice-col-8">
				<div class="_winvoice-card _winvoice-mr-0">
					<div class="_winvoice-card-header">
						<div class="_winvoice-card-header-title">
							<h3><?php esc_html_e( 'Packing Slip Template', 'webappick-pdf-invoice-for-woocommerce' ); ?></h3>
						</div>
					</div>
					<div class="_winvoice-card-body">

						<div class="_winvoice-form-group">
							<label class="_winvoice-fixed-label" for="packing_slip">Packing Slip</label>
							<input type="text" class="_winvoice-form-control _winvoice-fixed-input"
							       id="packing_slip" name="wpifw_PACKING_SLIP_TEXT"
							       value="<?php echo ! empty( get_option( 'wpifw_PACKING_SLIP_TEXT' ) ) ? esc_attr( get_option( 'wpifw_PACKING_SLIP_TEXT' ) ) : 'Packing Slip'; ?>">
						</div>

						<div class="_winvoice-form-group">
							<label class="_winvoice-fixed-label" for="Invoice-number_slip">Order
								Number</label>
							<input type="text" class="_winvoice-form-control _winvoice-fixed-input"
							       id="Invoice-number_slip" name="wpifw_PACKING_SLIP_ORDER_NUMBER_TEXT"
							       value="<?php echo ! empty( get_option( 'wpifw_PACKING_SLIP_ORDER_NUMBER_TEXT' ) ) ? esc_attr( get_option( 'wpifw_PACKING_SLIP_ORDER_NUMBER_TEXT' ) ) : 'Order Number'; ?>">
						</div>

						<div class="_winvoice-form-group">
							<label class="_winvoice-fixed-label" for="Invoice-date">Order Date</label>
							<input type="text" class="_winvoice-form-control _winvoice-fixed-input"
							       id="Invoice-date" name="wpifw_PACKING_SLIP_ORDER_DATE_TEXT"
							       value="<?php echo ! empty( get_option( 'wpifw_PACKING_SLIP_ORDER_DATE_TEXT' ) ) ? esc_attr( get_option( 'wpifw_PACKING_SLIP_ORDER_DATE_TEXT' ) ) : 'Order Date'; ?>">
						</div>

						<div class="_winvoice-form-group">
							<label class="_winvoice-fixed-label" for="shipping_method">Shipping
								method</label>
							<input type="text" class="_winvoice-form-control _winvoice-fixed-input"
							       id="shipping_method" name="wpifw_PACKING_SLIP_ORDER_METHOD_TEXT"
							       value="<?php echo ! empty( get_option( 'wpifw_PACKING_SLIP_ORDER_METHOD_TEXT' ) ) ? esc_attr( get_option( 'wpifw_PACKING_SLIP_ORDER_METHOD_TEXT' ) ) : 'Shipping method'; ?>">
						</div>

						<div class="_winvoice-form-group">
							<label class="_winvoice-fixed-label" for="product">Product</label>
							<input type="text" class="_winvoice-form-control _winvoice-fixed-input"
							       id="product" name="wpifw_PACKING_SLIP_PRODUCT_TEXT"
							       value="<?php echo ! empty( get_option( 'wpifw_PACKING_SLIP_PRODUCT_TEXT' ) ) ? esc_attr( get_option( 'wpifw_PACKING_SLIP_PRODUCT_TEXT' ) ) : 'Product'; ?>">
						</div>

						<div class="_winvoice-form-group">
							<label class="_winvoice-fixed-label" for="width">Dimension</label>
							<input type="text" class="_winvoice-form-control _winvoice-fixed-input"
							       id="width" name="wpifw_PACKING_SLIP_DIMENSION_TEXT"
							       value="<?php echo ! empty( get_option( 'wpifw_PACKING_SLIP_DIMENSION_TEXT' ) ) ? esc_attr( get_option( 'wpifw_PACKING_SLIP_DIMENSION_TEXT' ) ) : 'Dimension  '; ?>">
						</div>

						<div class="_winvoice-form-group" style="display: none;">
							<label class="_winvoice-fixed-label" for="width">Width</label>
							<input type="text" class="_winvoice-form-control _winvoice-fixed-input"
							       id="width" name="wpifw_PACKING_SLIP_WIDTH_TEXT"
							       value="<?php echo ! empty( get_option( 'wpifw_PACKING_SLIP_WIDTH_TEXT' ) ) ? esc_attr( get_option( 'wpifw_PACKING_SLIP_WIDTH_TEXT' ) ) : 'Width'; ?>">
						</div>

						<div class="_winvoice-form-group" style="display: none;">
							<label class="_winvoice-fixed-label" for="height">Height</label>
							<input type="text" class="_winvoice-form-control _winvoice-fixed-input"
							       id="height" name="wpifw_PACKING_SLIP_HEIGHT_TEXT"
							       value="<?php echo ! empty( get_option( 'wpifw_PACKING_SLIP_HEIGHT_TEXT' ) ) ? esc_attr( get_option( 'wpifw_PACKING_SLIP_HEIGHT_TEXT' ) ) : 'Height'; ?>">
						</div>

						<div class="_winvoice-form-group" style="display: none;">
							<label class="_winvoice-fixed-label" for="weight">Weight</label>
							<input type="text" class="_winvoice-form-control _winvoice-fixed-input"
							       id="weight" name="wpifw_PACKING_SLIP_WEIGHT_TEXT"
							       value="<?php echo ! empty( get_option( 'wpifw_PACKING_SLIP_WEIGHT_TEXT' ) ) ? esc_attr( get_option( 'wpifw_PACKING_SLIP_WEIGHT_TEXT' ) ) : 'Weight'; ?>">
						</div>

						<div class="_winvoice-form-group">
							<label class="_winvoice-fixed-label" for="quantity">Quantity</label>
							<input type="text" class="_winvoice-form-control _winvoice-fixed-input"
							       id="quantity" name="wpifw_PACKING_SLIP_QUANTITY_TEXT"
							       value="<?php echo ! empty( get_option( 'wpifw_PACKING_SLIP_QUANTITY_TEXT' ) ) ? esc_attr( get_option( 'wpifw_PACKING_SLIP_QUANTITY_TEXT' ) ) : 'Quantity'; ?>">
						</div>

						<div class="_winvoice-form-group">
							<label class="_winvoice-fixed-label" for="invoice">Shipping Label</label>
							<input type="text" class="_winvoice-form-control _winvoice-fixed-input"
							       id="invoice" name="wpifw_SHIPPING_LABEL_TEXT"
							       value="<?php echo ! empty( get_option( 'wpifw_SHIPPING_LABEL_TEXT' ) ) ? esc_attr( get_option( 'wpifw_SHIPPING_LABEL_TEXT' ) ) : 'Shipping Label'; ?>">
						</div>

						<div class="_winvoice-card-footer _winvoice-save-changes-selector">
							<input type="submit" style="float:right;"
							       name="wpifw_submit_packingslip_localization"
							       value="<?php esc_html_e( 'Save Changes', 'webappick-pdf-invoice-for-woocommerce' ); ?>"
							       class="_winvoice-btn _winvoice-btn-primary"/>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
</li>

