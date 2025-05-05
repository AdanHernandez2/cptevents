<?php
// Registrar Custom Post Type 'eventos'
add_action('init', function () {
    $labels = array(
        'name' => __('Eventos Agenda', 'eventos-agenda'),
        'singular_name' => __('Evento', 'eventos-agenda'),
        'menu_name' => __('Eventos Agenda', 'eventos-agenda'),
        'name_admin_bar' => __('Evento', 'eventos-agenda'),
        'add_new' => __('Añadir nuevo', 'eventos-agenda'),
        'add_new_item' => __('Añadir nuevo Evento', 'eventos-agenda'),
        'new_item' => __('Nuevo Evento', 'eventos-agenda'),
        'edit_item' => __('Editar Evento', 'eventos-agenda'),
        'view_item' => __('Ver Evento', 'eventos-agenda'),
        'all_items' => __('Todos los Eventos', 'eventos-agenda'),
        'search_items' => __('Buscar Eventos', 'eventos-agenda'),
        'not_found' => __('No se encontraron eventos.', 'eventos-agenda'),
        'not_found_in_trash' => __('No hay eventos en la papelera.', 'eventos-agenda')
    );

    $args = array(
        'labels' => $labels,
        'public' => true, // Debe ser true para que el archive funcione
        'publicly_queryable' => true, // Necesario para el archive
        'exclude_from_search' => false, // Para que aparezca en búsquedas
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'eventos'), // Slug más simple
        'capability_type' => 'post',
        'has_archive' => true, // Asegurar que el archive está activado
        'hierarchical' => false,
        'menu_position' => 5,
        'menu_icon' => 'dashicons-calendar-alt',
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
        'show_in_rest' => true,
    );

    register_post_type('eventos', $args);
});

// Registrar taxonomía para temporadas
add_action('init', function () {
    $labels = array(
        'name' => __('Temporadas', 'eventos-agenda'),
        'singular_name' => __('Temporada', 'eventos-agenda'),
        'search_items' => __('Buscar Temporadas', 'eventos-agenda'),
        'all_items' => __('Todas las Temporadas', 'eventos-agenda'),
        'edit_item' => __('Editar Temporada', 'eventos-agenda'),
        'update_item' => __('Actualizar Temporada', 'eventos-agenda'),
        'add_new_item' => __('Añadir nueva Temporada', 'eventos-agenda'),
        'new_item_name' => __('Nombre de la nueva Temporada', 'eventos-agenda'),
        'menu_name' => __('Temporadas', 'eventos-agenda'),
    );

    $args = array(
        'hierarchical' => true,
        'labels' => $labels,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'temporada'),
    );

    register_taxonomy('temporada', array('eventos'), $args);
});

// Registrar taxonomía para categorías
add_action('init', function () {
    $labels = array(
        'name' => __('Categorías', 'eventos-agenda'),
        'singular_name' => __('Categoría', 'eventos-agenda'),
        'search_items' => __('Buscar Categorías', 'eventos-agenda'),
        'all_items' => __('Todas las Categorías', 'eventos-agenda'),
        'edit_item' => __('Editar Categoría', 'eventos-agenda'),
        'update_item' => __('Actualizar Categoría', 'eventos-agenda'),
        'add_new_item' => __('Añadir nueva Categoría', 'eventos-agenda'),
        'new_item_name' => __('Nombre de la nueva Categoría', 'eventos-agenda'),
        'menu_name' => __('Categorías', 'eventos-agenda'),
    );

    $args = array(
        'hierarchical' => true,
        'labels' => $labels,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'categoria-evento'),
    );

    register_taxonomy('categoria_evento', array('eventos'), $args);
});
