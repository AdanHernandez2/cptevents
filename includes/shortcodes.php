<?php
// Shortcode para mostrar eventos
add_shortcode('eventos_agenda', 'eventos_agenda_shortcode');
function eventos_agenda_shortcode($atts)
{
    $atts = shortcode_atts(array(
        'cantidad' => -1,
        'categoria' => '',
        'temporada' => ''
    ), $atts);

    $args = array(
        'post_type' => 'eventos',
        'posts_per_page' => $atts['cantidad'],
        'orderby' => 'title',
        'order' => 'ASC'
    );

    // Filtrar por categoría si se especifica
    if (!empty($atts['categoria'])) {
        $args['tax_query'][] = array(
            'taxonomy' => 'categoria_evento',
            'field' => 'slug',
            'terms' => $atts['categoria']
        );
    }

    // Filtrar por temporada si se especifica
    if (!empty($atts['temporada'])) {
        $args['tax_query'][] = array(
            'taxonomy' => 'temporada',
            'field' => 'slug',
            'terms' => $atts['temporada']
        );
    }

    $eventos = new WP_Query($args);

    ob_start();

    if ($eventos->have_posts()) {
        echo '<div class="eventos-grid-shortcode">';

        while ($eventos->have_posts()) {
            $eventos->the_post();
            $autor = carbon_get_post_meta(get_the_ID(), 'evento_autor');
            $temporada_texto = carbon_get_post_meta(get_the_ID(), 'evento_temporada_texto');
            $fechas = carbon_get_post_meta(get_the_ID(), 'evento_fechas');
            $categorias = get_the_terms(get_the_ID(), 'categoria_evento');
            $categoria_nombre = !empty($categorias) ? $categorias[0]->name : '';
?>
            <div class="evento-card">
                <div class="evento-header">
                    <?php if ($categoria_nombre) : ?>
                        <div class="evento-categoria"><?php echo esc_html($categoria_nombre); ?></div>
                    <?php endif; ?>

                    <h3 class="evento-titulo"><?php the_title(); ?></h3>
                    <div class="evento-autor"><?php echo esc_html($autor); ?></div>

                    <?php if ($temporada_texto) : ?>
                        <div class="evento-temporada"><?php echo esc_html($temporada_texto); ?></div>
                    <?php endif; ?>

                    <?php if (has_post_thumbnail()) : ?>
                        <div class="evento-imagen">
                            <?php the_post_thumbnail('medium'); ?>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if (!empty($fechas)) : ?>
                    <div class="evento-fechas">
                        <table>
                            <thead>
                                <tr>
                                    <th>Día</th>
                                    <th>Mes</th>
                                    <th>Hora</th>
                                    <th>Ciudad</th>
                                    <th>Taquilla</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($fechas as $fecha) : ?>
                                    <tr>
                                        <td><?php echo esc_html($fecha['dia']); ?></td>
                                        <td><?php echo esc_html($fecha['mes']); ?></td>
                                        <td><?php echo esc_html($fecha['hora']); ?></td>
                                        <td><?php echo esc_html($fecha['ciudad']); ?></td>
                                        <td>
                                            <?php if ($fecha['enlace_taquilla']) : ?>
                                                <a href="<?php echo esc_url($fecha['enlace_taquilla']); ?>" target="_blank">Comprar</a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
<?php
        }

        echo '</div>';
        wp_reset_postdata();
    } else {
        echo '<p>No hay eventos programados.</p>';
    }

    return ob_get_clean();
}
