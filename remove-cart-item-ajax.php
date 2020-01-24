<?php

// Remove product in the cart using ajax
function warp_ajax_product_remove()
{

	// Get mini cart
    ob_start();

    foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item)
    {
        if($cart_item['product_id'] == $_POST['product_id'] && $cart_item_key == $_POST['cart_item_key'] )
        {
            WC()->cart->remove_cart_item($cart_item_key);
        }
    }

    WC()->cart->calculate_totals();
    WC()->cart->maybe_set_cart_cookies();

    woocommerce_mini_cart();

    $mini_cart = ob_get_clean();

	ob_start();
	$cart_count = WC()->cart->get_cart_contents_count();

	if( $cart_count > 0 ) {
		echo '<span class="count">'. $cart_count .'</span>';
	}

	$mini_cart_item = ob_get_clean();

	/// Fragments and mini cart are returned
    $data = array(
        'fragments' => apply_filters( 'woocommerce_add_to_cart_fragments', array(
                '.widget_shopping_cart_content' => '<div class="widget_shopping_cart_content">'. $mini_cart .'</div>',
				'.ff_cart_icon > .count' => $mini_cart_item
            )
        ),
        'cart_hash' => apply_filters( 'woocommerce_add_to_cart_hash', WC()->cart->get_cart_for_session() ? md5( json_encode( WC()->cart->get_cart_for_session() ) ) : '', WC()->cart->get_cart_for_session() )
    );

    wp_send_json( $data );

    die();
}

add_action( 'wp_ajax_product_remove', 'warp_ajax_product_remove' );
add_action( 'wp_ajax_nopriv_product_remove', 'warp_ajax_product_remove' );
