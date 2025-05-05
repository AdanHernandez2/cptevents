<?php
add_filter('archive_template', function ($template) {
    if (is_post_type_archive('eventos')) {
        $new_template = EVENTOS_AGENDA_PATH . 'includes/templates/archive-eventos.php';
        if (file_exists($new_template)) {
            return $new_template;
        }
    }
    return $template;
});

add_action('template_redirect', function () {
    if (is_singular('eventos')) {
        wp_redirect(get_post_type_archive_link('eventos'), 301);
        exit;
    }
});
