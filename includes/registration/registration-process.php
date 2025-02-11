<?php
if (!defined('ABSPATH')) {
    exit;
}

// Ajouter un formulaire d'inscription B2B avec un shortcode
function b2b_registration_form() {
    ob_start(); ?>
    
    <form id="b2b-registration-form" method="post" class="container mt-4">
        <div class="mb-3">
            <label for="b2b_username" class="form-label">Nom d'utilisateur</label>
            <input type="text" name="b2b_username" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="b2b_email" class="form-label">E-mail</label>
            <input type="email" name="b2b_email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="b2b_company" class="form-label">Nom de l'entreprise</label>
            <input type="text" name="b2b_company" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="b2b_role" class="form-label">Type d'entreprise</label>
            <select name="b2b_role" class="form-select" required>
                <option value="b2b_small">Petite entreprise</option>
                <option value="b2b_premium">Grande entreprise</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="b2b_password" class="form-label">Mot de passe</label>
            <input type="password" name="b2b_password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="b2b_password_confirm" class="form-label">Confirmez le mot de passe</label>
            <input type="password" name="b2b_password_confirm" class="form-control" required>
        </div>
        <button type="submit" name="b2b_register_submit" class="btn btn-primary">S'inscrire</button>
    </form>

    <?php
    return ob_get_clean();
}
add_shortcode('b2b_registration', 'b2b_registration_form');

// Gérer la soumission du formulaire
function b2b_handle_registration() {
    if (isset($_POST['b2b_register_submit'])) {
        $username  = sanitize_text_field($_POST['b2b_username']);
        $email     = sanitize_email($_POST['b2b_email']);
        $company   = sanitize_text_field($_POST['b2b_company']);
        $password  = $_POST['b2b_password'];
        $confirm   = $_POST['b2b_password_confirm'];
        $role      = sanitize_text_field($_POST['b2b_role']);

        if (username_exists($username) || email_exists($email)) {
            wp_die('Cet utilisateur ou email existe déjà.');
        }

        if ($password !== $confirm) {
            wp_die('Les mots de passe ne correspondent pas.');
        }

        $user_id = wp_insert_user(array(
            'user_login' => $username,
            'user_email' => $email,
            'user_pass'  => $password,
            'role'       => 'pending_b2b',
        ));

        if (!is_wp_error($user_id)) {
            update_user_meta($user_id, 'company_name', $company);
            update_user_meta($user_id, 'b2b_role', $role);

            $admin_email = get_option('admin_email');
            wp_mail($admin_email, 'Nouvelle inscription B2B en attente', "Un nouvel utilisateur B2B ($username) a demandé l'accès.");

            wp_die('Votre inscription est en attente de validation par un administrateur.');
        } else {
            wp_die('Erreur lors de l’inscription.');
        }
    }
}
add_action('init', 'b2b_handle_registration');

// Création du rôle "pending_b2b"
function b2b_create_pending_role() {
    add_role('pending_b2b', 'B2B (En attente)', array('read' => true));
}
register_activation_hook(__FILE__, 'b2b_create_pending_role');

// Ajouter un bouton d’approbation
function b2b_approve_user($user_id) {
    if (!current_user_can('manage_options')) {
        return;
    }
    $user = get_userdata($user_id);
    if ($user && in_array('pending_b2b', (array) $user->roles)) {
        $b2b_role = get_user_meta($user_id, 'b2b_role', true);
        wp_update_user(array('ID' => $user_id, 'role' => $b2b_role));
        wp_mail($user->user_email, 'Votre compte B2B est activé', 'Vous pouvez maintenant vous connecter.');
        delete_user_meta($user_id, 'b2b_role');
    }
}

// Ajouter un lien d’approbation
function b2b_modify_user_table($actions, $user_object) {
    if (in_array('pending_b2b', (array) $user_object->roles)) {
        $approve_link = admin_url('users.php?action=approve_b2b_user&user_id=' . $user_object->ID);
        $actions['approve_b2b'] = '<a href="' . esc_url($approve_link) . '">Approuver</a>';
    }
    return $actions;
}
add_filter('user_row_actions', 'b2b_modify_user_table', 10, 2);

// Traiter l’approbation
function b2b_process_approval() {
    if (isset($_GET['action']) && $_GET['action'] == 'approve_b2b_user' && isset($_GET['user_id'])) {
        $user_id = intval($_GET['user_id']);
        b2b_approve_user($user_id);
        wp_redirect(admin_url('users.php'));
        exit;
    }
}
add_action('admin_init', 'b2b_process_approval');
