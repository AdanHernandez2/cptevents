<?php

/**
 * Archive Template for Eventos
 */

get_header();
?>

<div class="container-eventos-agenda">
    <?php if (have_posts()) : ?>
        <div class="eventos-grid">
            <?php while (have_posts()) : the_post();
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

                        <h2 class="evento-titulo"><?php the_title(); ?></h2>
                        <div class="evento-autor"><?php echo esc_html($autor); ?></div>

                        <?php if ($temporada_texto) : ?>
                            <div class="evento-temporada"><?php echo esc_html($temporada_texto); ?></div>
                        <?php endif; ?>

                        <?php if (has_post_thumbnail()) : ?>
                            <div class="evento-imagen">
                                <?php the_post_thumbnail('medium_large'); ?>
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
                                        <th>Teatro</th>
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
            <?php endwhile; ?>
        </div>

        <?php the_posts_pagination(); ?>
    <?php else : ?>
        <p>No hay eventos programados.</p>
    <?php endif; ?>
</div>

<?php
get_footer();
