<?php 
// /includes/ajax/ajax-functions.php
function b2b_add_to_cart_ajax() {
    $product_id = $_POST['product_id'];
    WC()->cart->add_to_cart($product_id);
    echo 'Produit ajouté au panier';
    die();
}
add_action('wp_ajax_b2b_add_to_cart', 'b2b_add_to_cart_ajax');
add_action('wp_ajax_nopriv_b2b_add_to_cart', 'b2b_add_to_cart_ajax');
