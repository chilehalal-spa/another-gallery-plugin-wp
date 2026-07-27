<?php
/**
 * Plugin Name: Another Gallery Plugin
 * Description: Plugin modular de galerías con estructura assets/includes.
 * Version: 2.0
 * Author: Zumito
 * Text Domain: another-gallery-plugin
 */

defined( 'ABSPATH' ) || exit;

// Definimos la ruta base para facilitar los includes
define( 'AGP_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'AGP_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

require_once AGP_PLUGIN_PATH . 'includes/core/class-agp-loader.php';

/**
 * Inicializa el plugin.
 *
 * @return void
 */
function agp_init_plugin() {
    $loader = new AGP_Loader();
    $loader->run();
}

add_action( 'plugins_loaded', 'agp_init_plugin' );

/**
 * Registra el CPT y regenera las reglas de reescritura al activar el plugin,
 * para que la vista previa y el enlace permanente funcionen sin tener que
 * guardar los permalinks manualmente.
 *
 * @return void
 */
function agp_activate_plugin() {
    require_once AGP_PLUGIN_PATH . 'includes/core/class-agp-cpt.php';
    ( new AGP_CPT() )->register_post_type();

    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'agp_activate_plugin' );

/**
 * Limpia las reglas de reescritura al desactivar el plugin.
 *
 * @return void
 */
function agp_deactivate_plugin() {
    flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'agp_deactivate_plugin' );