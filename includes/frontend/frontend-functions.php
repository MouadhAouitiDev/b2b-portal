<?php 
// /includes/frontend/frontend-functions.php
function b2b_display_products() {
    // Récupérer et afficher les produits B2B
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => -1
    );
    $products = new WP_Query( $args );
    if ( $products->have_posts() ) {
        echo '<table class="b2b-products">';
        while ( $products->have_posts() ) {
            $products->the_post();
            echo '<tr>';
            echo '<td>' . get_the_ID() . '</td>';
            echo '<td>' . get_the_title() . '</td>';
            echo '<td>' . get_the_post_thumbnail() . '</td>';
            echo '<td>' . get_the_price() . '</td>';
            echo '<td><button class="add-to-cart">Ajouter au panier</button></td>';
            echo '</tr>';
        }
        echo '</table>';
    }
}
add_shortcode('b2b_product_display', 'b2b_display_products');
