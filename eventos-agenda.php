<?php

/**
 * Plugin Name: Eventos Agenda
 * Description: Plugin para gestionar eventos con información detallada y enlaces de compra
 * Version: 1.0.5
 * Author: Progresi
 * Text Domain: eventos-agenda
 */

defined('ABSPATH') || exit;

// Definir constantes del plugin
define('EVENTOS_AGENDA_VERSION', '1.0.0');
define('EVENTOS_AGENDA_PATH', plugin_dir_path(__FILE__));
define('EVENTOS_AGENDA_URL', plugin_dir_url(__FILE__));
define('EVENTOS_AGENDA_BASENAME', plugin_basename(__FILE__));

// Cargar Composer autoload si existe
if (file_exists(EVENTOS_AGENDA_PATH . 'vendor/autoload.php')) {
    require_once EVENTOS_AGENDA_PATH . 'vendor/autoload.php';
}

// Inicializar Carbon Fields
add_action('after_setup_theme', function () {
    \Carbon_Fields\Carbon_Fields::boot();
});

// Cargar archivos del plugin
$includes = [
    'includes/cpt-eventos.php',    // CPT y taxonomías
    'includes/carbon-fields.php',  // Campos personalizados
    'includes/templates.php',      // Manejo de plantillas
    'includes/shortcodes.php',     // Shortcodes
];

foreach ($includes as $file) {
    if (file_exists(EVENTOS_AGENDA_PATH . $file)) {
        require_once EVENTOS_AGENDA_PATH . $file;
    }
}

// Registrar estilos y scripts
add_action('wp_enqueue_scripts', 'eventos_agenda_assets');
function eventos_agenda_assets()
{
    // CSS
    wp_enqueue_style(
        'eventos-agenda-css',
        EVENTOS_AGENDA_URL . 'assets/css/estilo.css',
        [],
        EVENTOS_AGENDA_VERSION
    );

    // JS
    wp_enqueue_script(
        'eventos-agenda-js',
        EVENTOS_AGENDA_URL . 'assets/js/script.js',
        ['jquery'],
        EVENTOS_AGENDA_VERSION,
        true
    );
}

// Manejo del editor para el CPT eventos
add_action('admin_init', 'eventos_agenda_editor_config');
function eventos_agenda_editor_config()
{
    // Desactivar Gutenberg/Block Editor
    remove_post_type_support('eventos', 'editor');
    remove_post_type_support('eventos', 'gutenberg');
    remove_post_type_support('eventos', 'block-editor');

    // Reactivar editor clásico si es necesario
    add_post_type_support('eventos', 'editor');

    // Forzar el editor clásico en la interfaz de administración
    if (is_admin()) {
        add_filter('use_block_editor_for_post_type', function ($use_block_editor, $post_type) {
            return 'eventos' === $post_type ? false : $use_block_editor;
        }, 10, 2);
    }
}
