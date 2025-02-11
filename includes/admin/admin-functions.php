<?php
// /includes/admin/admin-functions.php
function b2b_admin_menu() {
    add_menu_page( 'B2B Portal', 'B2B Portal', 'manage_options', 'b2b-portal', 'b2b_admin_dashboard', 'dashicons-businessperson', 56 );
}
add_action( 'admin_menu', 'b2b_admin_menu' );

function b2b_admin_dashboard() {
    ?>
    <div class="wrap">
        <h1 class="wp-heading-inline">Tableau de bord B2B</h1>

        <div class="b2b-dashboard">
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Nom du produit</th>
                        <th>Prix</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Récupérer les produits WooCommerce
                    $args = array(
                        'post_type'      => 'product',
                        'posts_per_page' => 10,
                    );
                    $products = new WP_Query($args);

                    if ($products->have_posts()) :
                        while ($products->have_posts()) : $products->the_post();
                            $product = wc_get_product(get_the_ID());
                            $price = $product->get_price();
                            $image = get_the_post_thumbnail(get_the_ID(), 'thumbnail');
                            ?>
                            <tr>
                                <td><?php echo get_the_ID(); ?></td>
                                <td><?php echo $image; ?></td>
                                <td><?php the_title(); ?></td>
                                <td><?php echo wc_price($price); ?></td>
                                <td>
                                    <a href="<?php echo esc_url(get_permalink()); ?>" class="button action">Commander</a>
                                </td>
                            </tr>
                            <?php
                        endwhile;
                        wp_reset_postdata();
                    else :
                        echo '<tr><td colspan="5">Aucun produit disponible.</td></tr>';
                    endif;
                    ?>
                </tbody>
            </table>
        </div>

        <style>
            .b2b-dashboard table {
                margin-top: 20px;
            }
            .b2b-dashboard th, .b2b-dashboard td {
                text-align: center;
            }
        </style>
    </div>
    <?php
}

