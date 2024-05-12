<?php
/**
 * Banner Section
 */

$license_key = 'WebAppick_' . md5( 'webappick-pdf-invoice-for-woocommerce-pro' ) . '_manage_license';
$license_data = get_option( $license_key );
$life_time_product_ids = array( 63682, 63683, 63684 );

if ( ! in_array( $license_data['product_id'], $life_time_product_ids ) ) :
	?>

    <div class="_winvoice-banner-container">
        <div class="_winvoice-banner-logo">
            <a class="wapk-_winvoice-banner-logo" href="<?php echo esc_url( 'https://webappick.com/plugin/woocommerce-pdf-invoice-packing-slips#lifetime-license/?utm_source=customer_site&utm_medium=free_vs_pro&utm_campaign=woo_invoice_free' ); ?>" target="_blank"><img src="<?php echo esc_url( $woo_invoice_banner_logo_dir ); ?>" alt="Woo Invoice"></a>
        </div>
        <div class="_winvoice-banner-title">
            <h2>Buy Once and Enjoy Challan Pro Forever!</h2>
            <ul>
				<?php
				$benefits = array(
					'Lifetime Updates of Challan Pro',
					'Lifetime Dedicated Support',
					'Save a Ton of Money',
					'No Renewal Required',
					'Enjoy All Pro Features',
					'30-Days Money-back Guarantee',
				);

				foreach ( $benefits as $benefit ) :
					?>
                    <li>
                        <span>
                            <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 20 20" aria-hidden="true" class="ctx-flex-shrink-0 ctx-w-4 ctx-h-4 ctx-text-blue-600 dark:text-blue-500" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                        </span>
						<?php echo esc_html( $benefit ); ?>
                    </li>
				<?php endforeach; ?>
            </ul>
        </div>
        <button class="_winvoice-banner-btn" onclick="window.open('https://webappick.com/plugin/woocommerce-pdf-invoice-packing-slips#lifetime-license/?utm_source=customer_site&utm_medium=free_vs_pro&utm_campaign=woo_invoice_free', '_blank'); return false;">
            Update to Lifetime Now
            <svg width="20" height="20" fill="#fff" viewBox="0 0 24 24" version="1.2" baseProfile="tiny" xmlns="http://www.w3.org/2000/svg">
                <path d="M10.396 18.433 17 12l-6.604-6.433A2 2 0 0 0 7 7v10a2 2 0 0 0 3.396 1.433z" />
            </svg>
        </button>
    </div>

<?php
endif;
