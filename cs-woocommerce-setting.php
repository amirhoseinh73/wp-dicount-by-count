<?php
//merchant: 5fbcf9ef18f9344448fe61ff
/**
 * Summary.
 * write validate code in order detail after pay
 * send factor to crm24 after pay
 */

add_action('woocommerce_payment_complete', 'custom_update_order_meta', 20, 1 );
function custom_update_order_meta( $order_id ) {
     $order = wc_get_order( $order_id );

	if ( ! $order ) {
		return new WP_Error( 'invalid_order_id', __( 'Invalid order ID.', 'woocommerce' ), array( 'status' => 400 ) );
	}

     global $wpdb;
     $table = $wpdb->prefix . 'vira_aria_verif_code';

     $order      = wc_get_order( $order_id );
     $items      = $order->get_items();
     $customer   = $order->get_user();

     $products = array ();
     $sel_verification_codes = array();
     foreach ( $items as $product ) {
          $product_name           = $product->get_data()['name'];
          $product_original_price = $product->get_data()['subtotal'];
          $product_price          = $product->get_data()['total'];
          $product_id             = $product->get_data()['product_id'];
          $qty                    = intval( $product->get_data()['quantity'] );

          switch ( intval( $product_id ) ) :
               default:
               case 6264:
                    $grade = 1;
                    break;
               case 6324:
                    $grade = 2;
                    break;
               case 6325:
                    $grade = 3;
                    break;
               case 6326:
                    $grade = 4;
                    break;
               case 6327:
                    $grade = 5;
                    break;
               case 6328:
                    $grade = 6;
                    break;
          endswitch;
          // for ( $i = 0; $i < $qty; $i++ ):
          $get_codes = $wpdb->get_results("SELECT * FROM {$table} WHERE `status` = FALSE AND `grade` = {$grade}");
          if (isset($get_codes) && !empty($get_codes) && isset($get_codes[0]) && !empty($get_codes[0])) {
               //update
               $verification_code = $get_codes[0]->code;
               $ret_id = $get_codes[0]->id;
               $wpdb->update($table, array('status' => TRUE), array('id' => $ret_id));

               array_push(
                    $sel_verification_codes,
                    array (
                         "name" => $product_name,
                         "code" => $verification_code,
                         "id"   => $product_id
                    )
               );
          }

          array_push ($products, array(
               "product_name"           => $product_name,
               "product_original_price" => $product_original_price,
               "product_price"          => $product_price,
               "qty"                    => $qty,
          ));
          // endfor;

          if ( ! isset( $get_codes ) || count($get_codes) < 5 ) {
               sendSMS("09376885515", "کد های فعال سازی پایه {$grade} کم است.");
               sendSMS("09370802365", "کد های فعال سازی پایه {$grade} کم است.");
          }
     }

     $note = "";
     foreach ($sel_verification_codes as $code) {
          $note .= "<p class='alert alert-success'> {$code['name']} : {$code['code']} <p>";
          // $note .= "<p class='alert alert-danger'> " . json_encode( $code['id'] ) . " <p>";

          sendSMS($customer->data->user_email, "{$code['name']} : {$code['code']}");
     }

     $price          = $order->get_total();
     $original_price = $order->get_subtotal();

     $data_insert = array(
          "wc_order"       => $_GET["wc_order"],
          "trackId"        => $_GET["trackId"],
          "orderId"        => $_GET["orderId"],
          "success"        => $_GET["success"],
          "status"         => $_GET["status"],
          "price"          => $price,
          "original_price" => $original_price,
          "secure"         => $_GET["secure"],
     );
     $table = $wpdb->prefix . 'vira_aria_payment_tracks';
     $wpdb->insert($table, $data_insert);

     $post_fields_curl = array (
          "userdata"       => json_encode($customer, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
          "products"       => json_encode($products, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
          "price"          => $price,
          "original_price" => $original_price,
          "trackId"        => $_GET["trackId"],
          "orderId"        => $_GET["orderId"],
     );
     $post_fields_curl = http_build_query($post_fields_curl);
     $ch = curl_init();
     curl_setopt($ch, CURLOPT_URL, IbvVPBase() . '/api/crm24/aria/shop-complete');
     curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
     curl_setopt($ch, CURLOPT_POST, true);
     curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields_curl);
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
     curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
     curl_exec($ch);
     curl_close($ch);

	return $order->add_order_note( $note, 1, true );
}

/**
 * Summary.
 * redirect after payment to order details 
 */
add_action( 'woocommerce_thankyou', 'woocommerce_redirect_after_checkout');
function woocommerce_redirect_after_checkout( $order_id ){
 
    $order = wc_get_order( $order_id );
 
    $url = home_url() . "/my-account/view-order/" . $order_id;
 
    if ( ! $order->has_status( 'failed' ) ) {
 
        wp_safe_redirect( $url );
 
        exit;
 
    }
 
}

/** Summary.
 * send pre factor to crm24 after submit order 
 * before payment
 */
add_action( 'woocommerce_checkout_order_processed', 'woocommerce_before_checkout_crm' , 10, 1 );
function woocommerce_before_checkout_crm( $order_id ) {

     $order      = wc_get_order( $order_id );
     $items      = $order->get_items();
     $customer   = $order->get_user();

     $products = array ();
     foreach ( $items as $product ) {
          $product_name           = $product->get_data()['name'];
          $product_original_price = $product->get_data()['subtotal'];
          $product_price          = $product->get_data()['total'];
          $qty                    = intval( $product->get_data()['quantity'] );

          array_push ($products, array(
               "product_name"           => $product_name,
               "product_original_price" => $product_original_price,
               "product_price"          => $product_price,
               "qty"                    => $qty,
          ));
     }
     
     $price          = $order->get_total();
     $original_price = $order->get_subtotal();

     $post_fields_curl = array (
          "userdata"       => json_encode($customer, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
          "products"       => json_encode($products, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
          "price"          => $price,
          "original_price" => $original_price,
     );
     $post_fields_curl = http_build_query($post_fields_curl);
     $ch = curl_init();
     curl_setopt($ch, CURLOPT_URL, IbvVPBase() . '/api/crm24/aria/shop-pre-order');
     curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
     curl_setopt($ch, CURLOPT_POST, true);
     curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields_curl);
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
     curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
     curl_exec($ch);
     curl_close($ch);
}




// add_action( 'woocommerce_add_order_item_meta', 'add_order_item_meta' , 10, 2);
// function add_order_item_meta ( $item_id, $values ) {
     // global $wpdb;
     // $table = $wpdb->prefix . 'vira_aria_verif_code';
     // $get_codes = $wpdb->get_results("SELECT * FROM {$table} WHERE `status` = FALSE LIMIT 1");
     // if (isset($get_codes) && !empty($get_codes) && isset($get_codes[0]) && !empty($get_codes[0])) {
     //      //update
     //      $verification_code = $get_codes[0]->code;
     //      $ret_id = $get_codes[0]->id;
     //      wc_add_order_item_meta( $item_id, 'کد فعال سازی', $verification_code );
     //      $wpdb->update($table, array('status' => TRUE), array('id' => $ret_id));
     // }
     // wc_add_order_item_meta( $item_id, 'Name', $custom_data['customer_name'] );
     // wc_add_order_item_meta( $item_id, 'Message', $custom_data['customer_message'] );
// }

/**
 * Symmary.
 * validate for username be nat_code
 */

// add_action( 'validate_username', 'validate_username_vira' , 10 , 1);
// function validate_username_vira( $username ) {
//      $sanitized = sanitize_user( $username, true );
//      $valid     = ( $sanitized == $username && ! empty( $sanitized ) && check_nat_code_format( $username ));
  
//      return apply_filters( 'validate_username', $valid, $username );
// }

// define the validate_username callback 
// function filter_validate_username( $valid, $username ) { 
//      // make filter magic happen here... 
//      $valid     = ( $valid && check_nat_code_format( $username ));
//      return $valid; 
//  }; 
          
 // add the filter 
//  add_filter( 'validate_username', 'filter_validate_username', 10, 2 );



/**
 * Summary.
 * remove dashboard from woocommerce account page
 */

add_filter( 'woocommerce_account_menu_items', 'remove_my_account_dashboard' );
function remove_my_account_dashboard( $menu_links ){
	
	unset( $menu_links['dashboard'] );
	return $menu_links;
	
}

/**
 * Summary.
 * redirect dashboard woocommerce to orders
 */
add_action('template_redirect', 'redirect_to_orders_from_dashboard' );
function redirect_to_orders_from_dashboard(){

	if( is_account_page() && empty( WC()->query->get_current_endpoint() ) ){
		wp_safe_redirect( wc_get_account_endpoint_url( 'orders' ) );
		exit;
	}

}

/**
 * Summary.
 * change default order of woocommerce shop page to date
 */
add_filter('woocommerce_default_catalog_orderby', 'default_catalog_orderby');
function default_catalog_orderby( $sort_by ) {
	return 'date';
}

/**
 * Summary.
 * validate nat_code or username
 */

function check_nat_code_format($input) {
     if (!preg_match("/^\d{10}$/", $input)
         || $input == '0000000000'
         || $input == '1111111111'
         || $input == '2222222222'
         || $input == '3333333333'
         || $input == '4444444444'
         || $input == '5555555555'
         || $input == '6666666666'
         || $input == '7777777777'
         || $input == '8888888888'
         || $input == '9999999999') {
         return false;
     }
     $check = (int)$input[9];
     $sum = array_sum(array_map(function ($x) use ($input) {
             return ((int)$input[$x]) * (10 - $x);
         }, range(0, 8))) % 11;
     return ($sum < 2 && $check == $sum) || ($sum >= 2 && $check + $sum == 11);
}