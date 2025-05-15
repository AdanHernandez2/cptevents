<?php

/**
 * Archive Template for Eventos - Ordenado por fecha
 */
get_header();

// Obtener todos los eventos
$args = array(
    'post_type' => 'eventos',
    'posts_per_page' => -1,
    'orderby' => 'meta_value',
    'order' => 'ASC',
);

$eventos_query = new WP_Query($args);

// Función para convertir fecha a timestamp
function convertir_fecha_a_ordenable($mes, $dia = '01', $año = null)
{
    if (!$año) $año = date('Y'); // Usar año actual si no se especifica

    $meses = [
        'Enero' => '01',
        'Febrero' => '02',
        'Marzo' => '03',
        'Abril' => '04',
        'Mayo' => '05',
        'Junio' => '06',
        'Julio' => '07',
        'Agosto' => '08',
        'Septiembre' => '09',
        'Octubre' => '10',
        'Noviembre' => '11',
        'Diciembre' => '12'
    ];

    $mes_numero = $meses[$mes] ?? '01';
    return strtotime("$año-$mes_numero-$dia");
}

// Recolectar y ordenar eventos
$eventos_ordenados = array();

if ($eventos_query->have_posts()) {
    while ($eventos_query->have_posts()) {
        $eventos_query->the_post();

        $autor = carbon_get_post_meta(get_the_ID(), 'evento_autor');
        $temporada_texto = carbon_get_post_meta(get_the_ID(), 'evento_temporada_texto');
        $fechas = carbon_get_post_meta(get_the_ID(), 'evento_fechas');
        $categorias = get_the_terms(get_the_ID(), 'categoria_evento');
        $categoria_nombre = !empty($categorias) ? $categorias[0]->name : '';

        // Determinar fecha para ordenación
        $fecha_orden = PHP_INT_MAX; // Valor alto por defecto (sin fecha)

        // Prioridad 1: Usar la primera fecha específica si existe
        if (!empty($fechas) && !empty($fechas[0]['mes'])) {
            $dia = !empty($fechas[0]['dia']) ? $fechas[0]['dia'] : '01';
            $fecha_orden = convertir_fecha_a_ordenable($fechas[0]['mes'], $dia);
        }
        // Prioridad 2: Intentar extraer fecha del texto de temporada
        elseif ($temporada_texto) {
            // Intenta extraer mes(es) y año del texto (ej: "Octubre, Noviembre, 2025")
            if (
                preg_match_all('/(Enero|Febrero|Marzo|Abril|Mayo|Junio|Julio|Agosto|Septiembre|Octubre|Noviembre|Diciembre)/i', $temporada_texto, $mes_matches) &&
                preg_match('/(\d{4})/', $temporada_texto, $ano_match)
            ) {

                // Tomamos el último mes mencionado (para casos como "Octubre, Noviembre")
                $ultimo_mes = end($mes_matches[1]);
                $ano = $ano_match[1];

                $fecha_orden = convertir_fecha_a_ordenable($ultimo_mes, '01', $ano);
            }
            // Formato alternativo (ej: "Marzo de 2026")
            elseif (preg_match('/(Enero|Febrero|Marzo|Abril|Mayo|Junio|Julio|Agosto|Septiembre|Octubre|Noviembre|Diciembre)[\s\-]*(?:de|del?)?[\s\-]*(\d{4})/i', $temporada_texto, $matches)) {
                $fecha_orden = convertir_fecha_a_ordenable($matches[1], '01', $matches[2]);
            }
        }

        $eventos_ordenados[] = array(
            'fecha_orden' => $fecha_orden,
            'post_id' => get_the_ID(),
            'autor' => $autor,
            'temporada_texto' => $temporada_texto,
            'fechas' => $fechas,
            'categoria_nombre' => $categoria_nombre
        );
    }
    wp_reset_postdata();

    // Ordenar eventos por fecha (más cercanos primero)
    usort($eventos_ordenados, function ($a, $b) {
        return $a['fecha_orden'] - $b['fecha_orden'];
    });
}
?>

<!-- El resto del HTML permanece exactamente igual -->
<div class="container-eventos-agenda">
    <?php if (!empty($eventos_ordenados)) : ?>
        <div class="eventos-grid">
            <?php foreach ($eventos_ordenados as $evento) :
                setup_postdata($GLOBALS['post'] = get_post($evento['post_id'])); ?>

                <div class="evento-card">
                    <div class="evento-header">
                        <?php if ($evento['categoria_nombre']) : ?>
                            <div class="evento-categoria"><?php echo esc_html($evento['categoria_nombre']); ?></div>
                        <?php endif; ?>

                        <h2 class="evento-titulo"><?php the_title(); ?></h2>
                        <div class="evento-autor"><?php echo esc_html($evento['autor']); ?></div>

                        <?php if ($evento['temporada_texto']) : ?>
                            <div class="evento-temporada"><?php echo esc_html($evento['temporada_texto']); ?></div>
                        <?php endif; ?>

                        <?php if (has_post_thumbnail()) : ?>
                            <div class="evento-imagen">
                                <?php the_post_thumbnail('medium_large'); ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <?php if (!empty($evento['fechas'])) : ?>
                        <div class="evento-fechas">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Día</th>
                                        <th>Mes</th>
                                        <th>Hora</th>
                                        <th>Ciudad</th>
                                        <th>Teatro</th>
                                        <th>Taquilla</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($evento['fechas'] as $fecha) : ?>
                                        <tr>
                                            <td><?php echo esc_html($fecha['dia']); ?></td>
                                            <td><?php echo esc_html($fecha['mes']); ?></td>
                                            <td><?php echo esc_html($fecha['dia_semana']); ?></td>
                                            <td><?php echo esc_html($fecha['hora']); ?></td>
                                            <td><?php echo esc_html($fecha['ciudad']); ?></td>
                                            <td><?php echo esc_html($fecha['teatro']); ?></td>
                                            <?php if (!empty($fecha['logo_taquilla'])): ?>
                                                <td>
                                                    <img src="<?php echo esc_url($fecha['logo_taquilla']); ?>" alt="Logo Taquilla">
                                                </td>
                                            <?php endif; ?>
                                            <td>
                                                <?php if ($fecha['enlace_taquilla']) : ?>
                                                    <a href="<?php echo esc_url($fecha['enlace_taquilla']); ?>" target="_blank">Comprar Entrada</a>
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
                wp_reset_postdata();
            endforeach; ?>
        </div>

        <?php the_posts_pagination(); ?>
    <?php else : ?>
        <p>No hay eventos programados.</p>
    <?php endif; ?>
</div>

<?php
get_footer();
