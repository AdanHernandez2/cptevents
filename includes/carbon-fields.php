<?php

use Carbon_Fields\Container;
use Carbon_Fields\Field;

add_action('carbon_fields_register_fields', function () {
    // Campos para el CPT Eventos
    Container::make('post_meta', __('Información del Evento', 'eventos-agenda'))
        ->where('post_type', '=', 'eventos')
        ->add_fields(array(
            Field::make('text', 'evento_autor', __('Autor', 'eventos-agenda'))
                ->set_required(true),

            Field::make('text', 'evento_temporada_texto', __('Temporada (texto)', 'eventos-agenda'))
                ->set_help_text('Ej: "Agenda de presentaciones - Noviembre 2025"'),

            Field::make('complex', 'evento_fechas', __('Fechas del Evento', 'eventos-agenda'))
                ->add_fields(array(
                    Field::make('text', 'dia', __('Día', 'eventos-agenda'))
                        ->set_attribute('type', 'number')
                        ->set_attribute('min', '1')
                        ->set_attribute('max', '31')
                        ->set_width(10),

                    Field::make('select', 'mes', __('Mes', 'eventos-agenda'))
                        ->set_options(array(
                            'Enero' => 'Enero',
                            'Febrero' => 'Febrero',
                            'Marzo' => 'Marzo',
                            'Abril' => 'Abril',
                            'Mayo' => 'Mayo',
                            'Junio' => 'Junio',
                            'Julio' => 'Julio',
                            'Agosto' => 'Agosto',
                            'Septiembre' => 'Septiembre',
                            'Octubre' => 'Octubre',
                            'Noviembre' => 'Noviembre',
                            'Diciembre' => 'Diciembre',
                        ))
                        ->set_width(30),

                    Field::make('select', 'dia_semana', __('Día de la semana', 'eventos-agenda'))
                        ->set_options(array(
                            'Lunes' => 'Lunes',
                            'Martes' => 'Martes',
                            'Miércoles' => 'Miércoles',
                            'Jueves' => 'Jueves',
                            'Viernes' => 'Viernes',
                            'Sábado' => 'Sábado',
                            'Domingo' => 'Domingo',
                        ))
                        ->set_width(30),

                    Field::make('text', 'hora', __('Hora', 'eventos-agenda'))
                        ->set_attribute('placeholder', 'HH:MM')
                        ->set_width(30),

                    Field::make('text', 'ciudad', __('Ciudad', 'eventos-agenda'))
                        ->set_width(50),

                    Field::make('text', 'teatro', __('Teatro', 'eventos-agenda'))
                        ->set_width(50),

                    Field::make('image', 'logo_taquilla', __('Logo Taquilla', 'eventos-agenda'))
                        ->set_value_type('url'),

                    Field::make('text', 'enlace_taquilla', __('Enlace Taquilla', 'eventos-agenda'))
                        ->set_attribute('type', 'url'),
                ))
                ->set_header_template('
                    <% if (dia && mes) { %>
                        <%- dia %> de <%- mes %>
                    <% } else { %>
                        Nueva fecha
                    <% } %>
                '),
        ));
});
