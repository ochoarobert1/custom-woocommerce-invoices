<?php
/*
Plugin Name: Custom Woocommerce Invoices
Plugin URI: http://www.robertochoa.com.ve/
Author: Robert Ochoa
Author URI: http://www.robertochoa.com.ve/
Description: Plugin para enviar y controlar documentos como invoices y facturas para cada pedido en Woocommerce.
Version: 1.0
License: GNU General Public License v2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain: cwoo_invoices
Domain Path: /lang
*/

if ( !defined( 'ABSPATH' ) ) {
    die( 'Direct access is forbidden.' );
}

/* --------------------------------------------------------------
/* ENQUEUE SCRIPTS AND STYLES
-------------------------------------------------------------- */
/* ADMIN SIDE REGISTRATION */
add_action('admin_enqueue_scripts', 'cwoo_invoices_admin_scripts', 10);

function cwoo_invoices_admin_scripts() {
    wp_enqueue_style('cwoo_invoices_admin_css', plugins_url('css/custom-woocommerce-invoices-admin.css', __FILE__), '1.0.0');
}


/* CLIENT SIDE REGISTRATION */
add_action('wp_enqueue_scripts', 'cwoo_invoices_client_scripts', 10);

function cwoo_invoices_client_scripts() {
    wp_enqueue_style('cwoo_invoices_client_css', plugins_url('css/custom-woocommerce-invoices-client.css', __FILE__), '1.0.0');
}


/* --------------------------------------------------------------
/* METABOX REGISTRATION
-------------------------------------------------------------- */
include(plugin_dir_path(__FILE__) . 'inc/custom-woocommerce-invoices-metabox.php');