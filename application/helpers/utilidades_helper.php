<?php

function capitalizar_arreglo($data_cruda, $campos_a_capitalizar)
{

    $data_lista = $data_cruda;

    foreach ($data_cruda as $nombre_campo => $valor_campo) {

        if (in_array($nombre_campo, array_values($campos_a_capitalizar))) {

            $data_lista[$nombre_campo] = strtoupper($valor_campo);
        }
    }

    return $data_lista;
}

function obtener_mes($index)
{
    $index -= 1;

    $meses = array(
        'enero',
        'febrero',
        'marzo',
        'abril',
        'mayo',
        'junio',
        'julio',
        'agosto',
        'septiembre',
        'octubre',
        'noviembre',
        'diciembre'
    );

    return $meses[$index];
}
