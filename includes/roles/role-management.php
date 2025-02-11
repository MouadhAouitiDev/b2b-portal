<?php 
// /includes/roles/role-management.php
function create_b2b_roles() {
    add_role( 'b2b_small', 'B2B Petit Entreprise', array( 'read' => true, 'edit_posts' => true  ) );
    add_role( 'b2b_premium', 'B2B Grande Entreprise', array( 'read' => true, 'edit_posts' => true ) );
}
add_action('init', 'create_b2b_roles');
