<?php
// /includes/pricing/price-management.php
function apply_b2b_discount($price, $product) {
    $user = wp_get_current_user();
    if (in_array('b2b_premium', (array) $user->roles)) {
        // Appliquer un prix premium
        $price *= 0.9; // Exemple de remise de 10%
    }
    return $price;
}
add_filter('woocommerce_product_get_price', 'apply_b2b_discount', 10, 2);
