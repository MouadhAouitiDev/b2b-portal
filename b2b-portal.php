<?php
/*
Plugin Name: B2B Portal
Description: Un plugin pour créer un portail client B2B personnalisé pour WooCommerce.
Version: 1.0
Author: Intilaq digital
*/

// Empêche l'accès direct au fichier
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Définir le chemin absolu du plugin
define( 'B2B_PORTAL_DIR', plugin_dir_path( __FILE__ ) );

// Inclure les fichiers principaux
require_once B2B_PORTAL_DIR . 'includes/admin/admin-functions.php';
require_once B2B_PORTAL_DIR . 'includes/frontend/frontend-functions.php';
require_once B2B_PORTAL_DIR . 'includes/roles/role-management.php';
require_once B2B_PORTAL_DIR . 'includes/pricing/price-management.php';
require_once B2B_PORTAL_DIR . 'includes/registration/registration-process.php';
require_once B2B_PORTAL_DIR . 'includes/ajax/ajax-functions.php';
