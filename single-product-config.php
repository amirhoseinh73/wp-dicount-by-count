<?php

add_filter('woocommerce_product_get_price', 'AMH_NJ_adjustPrice', 99, 2);
add_filter('woocommerce_product_variation_get_price', 'AMH_NJ_adjustPrice', 99, 2);

function AMH_NJ_adjustPrice($price, $product)
{
    global $wpdb;

    $optionName = AMH_NJ_DBC_PREFIX . AMH_NJ_DBC_PLUGIN_NAME;
    $tableName = $wpdb->prefix . 'options';

    $resultQuery = $wpdb->get_results("SELECT * FROM $tableName WHERE option_name='$optionName'");

    $productId = (string)$product->get_data()["id"];

    if (!AMH_NJ_exists($resultQuery)) return $price;

    $resultQuery = $resultQuery[0];
    $result = json_decode($resultQuery->option_value);

    $queryRange_1 = intval($result->range_1);
    $queryPrice_1 = intval($result->price_1);

    $queryRange_2 = intval($result->range_2);
    $queryPrice_2 = intval($result->price_2);

    $queryRange_3 = intval($result->range_3);
    $queryPrice_3 = intval($result->price_3);

    $queryRange_4 = intval($result->range_4);
    $queryPrice_4 = intval($result->price_4);

    $queryProductId = $result->product_id;

    if ($productId !== $queryProductId) return $price;

    $productCount = AMH_NJ_getProductQuantityInCart($productId);

    if ($productCount <= 1) return $price;

    if (AMH_NJ_exists($queryRange_1) && AMH_NJ_exists($queryPrice_1) && $productCount === $queryRange_1) return $queryPrice_1;
    elseif (AMH_NJ_exists($queryRange_1) && AMH_NJ_exists($queryPrice_1) && !AMH_NJ_exists($queryRange_2) && $productCount >= $queryRange_1) return $queryPrice_1;

    if (AMH_NJ_exists($queryRange_2) && AMH_NJ_exists($queryPrice_2) && $productCount === $queryRange_2) return $queryPrice_2;
    elseif (AMH_NJ_exists($queryRange_2) && AMH_NJ_exists($queryPrice_2) && !AMH_NJ_exists($queryRange_3) && $productCount >= $queryRange_2) return $queryPrice_2;

    if (AMH_NJ_exists($queryRange_3) && AMH_NJ_exists($queryPrice_3) && $productCount === $queryRange_3) return $queryPrice_3;
    elseif (AMH_NJ_exists($queryRange_3) && AMH_NJ_exists($queryPrice_3) && !AMH_NJ_exists($queryRange_4) && $productCount >= $queryRange_3) return $queryPrice_3;

    if (AMH_NJ_exists($queryRange_4) && AMH_NJ_exists($queryPrice_4) && $productCount >= $queryRange_4) return $queryPrice_4;

    return $price;
}

function AMH_NJ_getProductQuantityInCart(string $productId)
{
    $quantity = 0;


    if (!WC()->cart) return $quantity;

    foreach (WC()->cart->get_cart() as $cartItem) {
        if (in_array($productId, array($cartItem['product_id'], $cartItem['variation_id']))) {
            $quantity =  intval($cartItem['quantity']);
            break;
        }
    }

    return $quantity;
}

add_action('woocommerce_before_add_to_cart_form', 'AMH_NJ_add_below_prod_gallery', 5);
function AMH_NJ_add_below_prod_gallery()
{
    global $product, $wpdb;

    $optionName = AMH_NJ_DBC_PREFIX . AMH_NJ_DBC_PLUGIN_NAME;
    $tableName = $wpdb->prefix . 'options';

    $resultQuery = $wpdb->get_results("SELECT * FROM $tableName WHERE option_name='$optionName'");

    $productId = (string)$product->get_data()["id"];
    // $productPrice = $product->get_sale_price();
    $productPrice = $product->get_data()["price"];

    if (!AMH_NJ_exists($resultQuery)) return;

    $resultQuery = $resultQuery[0];
    $result = json_decode($resultQuery->option_value);

    $queryRange_1 = $result->range_1;
    $queryPrice_1 = $result->price_1;

    $queryRange_2 = $result->range_2;
    $queryPrice_2 = $result->price_2;

    $queryRange_3 = $result->range_3;
    $queryPrice_3 = $result->price_3;

    $queryRange_4 = $result->range_4;
    $queryPrice_4 = $result->price_4;

    $queryProductId = $result->product_id;

    if ($productId !== $queryProductId) return; //|| $queryDate > date('Y-m-d H:i:s')

    $data = [
        (object)[
            "range" => 1,
            "price" => $productPrice,
        ],
    ];
    if (AMH_NJ_exists($queryRange_1) && AMH_NJ_exists($queryPrice_1)) $data[] = (object)[
        "range" => $queryRange_1,
        "price" => $queryPrice_1,
    ];

    if (AMH_NJ_exists($queryRange_2) && AMH_NJ_exists($queryPrice_2)) $data[] = (object)[
        "range" => $queryRange_2,
        "price" => $queryPrice_2,
    ];

    if (AMH_NJ_exists($queryRange_3) && AMH_NJ_exists($queryPrice_3)) $data[] = (object)[
        "range" => $queryRange_3,
        "price" => $queryPrice_3,
    ];

    if (AMH_NJ_exists($queryRange_4) && AMH_NJ_exists($queryPrice_4)) $data[] = (object)[
        "range" => $queryRange_4,
        "price" => $queryPrice_4,
    ];


    echo AMH_NJ_html($data);
}

function AMH_NJ_html(array $data)
{

    $html = "<section id='" . strtolower(AMH_NJ_DBC_PREFIX . AMH_NJ_DBC_PLUGIN_NAME) . "_html'>
    <div class='position-relative progress-parent'>
        <table class='table table-striped table-light table-hover'>
            <thead>
                <th>تعداد</th>
                <th>قیمت کالا</th>
            </thead>
            <tbody>";
    foreach ($data as $idx => $datum) {
        $numberText = "$datum->range عدد";

        if ($idx === count($data) - 1) $numberText .= " و بیشتر";

        $html .= "<tr>
                    <td>$numberText</td>
                    <td>$datum->price تومان</td>
                </tr>";
    }
    $html .= "</tbody>
        </table>
    </div>
    </section>";

    return $html;
}

add_filter('woocommerce_product_get_sale_price', 'AMH_NJ_custom_dynamic_sale_price', 10, 2);
add_filter('woocommerce_product_variation_get_sale_price', 'AMH_NJ_custom_dynamic_sale_price', 10, 2);
function AMH_NJ_custom_dynamic_sale_price($sale_price, $product)
{
    $sale_price = $product->get_price();
    return $sale_price;
}
